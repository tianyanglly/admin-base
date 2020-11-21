<?php

return [
    //二次登陆验证
    'google2fa_email' => env('GOOGLE_2FA_EMAIL', 'google2fa@pragmarx.com'),
    'google2fa_qr_width' => 200,
    'google2fa_force' => env('GOOGLE_2FA_FORCE', false),    //是否强制开启

    //缓存时间 默认五分钟
    'cache_expire' => env('CACHE_EXPIRE', 5),

    //aes秘钥
    'aes_key' => env('AES_KEY', ''),

    //jwt秘钥
    'jwt_key' => env('JWT_KEY', ''),

    //忽略过滤request参数
    'request_except_params' => [],

    //开启格式化字符串整型
    'request_format_int' => false
];