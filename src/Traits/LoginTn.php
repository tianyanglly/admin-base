<?php


namespace AdminBase\Traits;


use AdminBase\Utility\TnCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait LoginTn
{
    //重复标识，第二次登录验证以后需要验证码验证
    private $sessionKey = 'repeat_login';

    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        $repeat = session()->has($this->sessionKey);
        return view($this->loginView, ['repeat' => $repeat]);
    }

    public function postLogin(Request $request)
    {
        if($request->session()->has($this->sessionKey) && !$request->session()->has(TnCode::SESSION_VERIFY)) {
            return back()->withInput()->withErrors([
                'verify' => '请验证',
            ]);
        }
        session()->put($this->sessionKey, 1);
        session()->forget(TnCode::SESSION_VERIFY);
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