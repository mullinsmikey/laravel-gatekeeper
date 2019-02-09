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
        $this->cookieid = strtolower(config('app.name')).'_gk';
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
        $orig_hash = md5(serialize([$this->username, $this->password, config('app.key')]));

        $existing = array_get($_COOKIE, $this->cookieid, false);
        if (!empty($existing) && $existing !== false) {
            if ($existing === $orig_hash) {
                return $next($request);
            } else {
                return $this->showAuth();
            }
        } else {
            $username = empty($request->get('username')) ? '' : $request->get('username');
            $password = empty($request->get('password')) ? '' : $request->get('password');

            $input_hash = md5(serialize([$username, $password, config('app.key')]));
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
}
