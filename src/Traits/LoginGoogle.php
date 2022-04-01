<?php


namespace AdminBase\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * google验证码验证
 * Trait LoginGoogle
 * @package AdminBase\Traits
 */
trait LoginGoogle
{
    //重复标识，第二次登录验证以后需要验证码验证
    private $sessionKey = 'repeat_login';

    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }
        return view($this->loginView, ['repeat' => session()->has($this->sessionKey), 'key' => config('base.recaptcha_site')]);
    }

    public function postLogin(Request $request)
    {
        if (env('APP_ENV') == 'local') {
            return parent::postLogin($request);
        }
        if($request->session()->has($this->sessionKey)) {
            $recaptcha = new \ReCaptcha\ReCaptcha(config('base.recaptcha_secret'));
            $resp = $recaptcha->setExpectedHostname($request->header('host'))
                ->setScoreThreshold(config('base.recaptcha_score') ?: 0.8)
                ->verify($request->input('g-token'), $_SERVER['REMOTE_ADDR']);
            if ($resp->isSuccess()) {
                return parent::postLogin($request);
            } else {
                return back()->withInput()->withErrors([
                    $this->username() => $resp->getErrorCodes(),
                ]);
            }
        }
        session()->put($this->sessionKey, 1);
        return parent::postLogin($request);
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function loginValidator(array $data)
    {
        return Validator::make($data, [
            $this->username()   => 'required|regex:/\w{4,20}/|exists:admin_users,username,enabled,1',
            'password'          => 'required|regex:/^(\w*(?=\w*\d)(?=\w*[A-Za-z])\w*){8,18}$/',
        ], [$this->username().'.*' => '用户名不匹配', 'password.*' => '密码不匹配']);
    }
}