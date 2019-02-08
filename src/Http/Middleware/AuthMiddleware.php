<?php

namespace Muffin\GateKeeper\Http\Middleware;

use Closure;

class AuthMiddleware
{
    /**
     * @var string
     */
    protected $authtime;

    /**
     * @var string
     */
    protected $cookieid;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * Create a new AuthMiddleware instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->username = empty(config('gatekeeper.username')) ? '' : config('gatekeeper.username');
        $this->password = empty(config('gatekeeper.password')) ? '' : config('gatekeeper.password');
        $this->cookieid = str_slug(config('name'), '_').'_gateid';
        $this->authtime = empty(config('gatekeeper.authtime')) ? '' : config('gatekeeper.authtime');
    }

    /**
     * Handle incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $this->pseudoConstruct();
        $orig_hash = $this->makeCredentials($this->username, $this->password);

        $existing = array_get($_COOKIE, $this->cookieid, false);
        if ($existing !== false) {
            if ($existing === $orig_hash) {
                return $next($request);
            } else {
                return $this->showAuth();
            }
        } else {
            $username = empty($request->get('username')) ? '' : $request->get('username');
            $password = empty($request->get('password')) ? '' : $request->get('password');

            $input_hash = $this->makeCredentials($username, $password);
            if ($input_hash === $orig_hash) {
                setcookie($this->cookieid, $input_hash, time() + $this->authtime, '/');
                return redirect($request->url());
            } else {
                return $this->showAuth();
            }
        }
    }

    /**
     * Handle logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        setcookie($this->cookieid, '', time() - $this->authtime, '/');
        return $this->showAuth();
    }

    /**
     * Redirect to login form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showAuth()
    {
        return response(view(config('gatekeeper.authview')), 403);
    }

    /**
     * Hash credentials.
     *
     * @param string $username
     * @param string $password
     * @return string
     */
    protected function makeCredentials($username, $password)
    {
        $key = serialize(array(
            'username' => $username, 'password' => $password
        ));
        return md5($key);
    }

    /**
     * Constructor
     * @return void
     */
    // protected function pseudoConstruct()
    // {
    // }
}
