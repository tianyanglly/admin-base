<?php


namespace AdminBase\Utility;

class HttpHelper
{
    //超时时间
    const TIMEOUT = 30;

    /**
     * 上传文件
     * @param $url
     * @param $filePath
     * @param $name;
     * @param $data
     * @param string $err
     * @return bool|string
     */
    public static function uploadFile($url, $filePath, &$err = '', $name = 'file', array $data = null)
    {
        $curl = curl_init();

        //post数据，使用@符号，curl就会认为是有文件上传
        $curlPost = array($name => new \CURLFile($filePath));
        if ($data) {
            $curlPost = array_merge($curlPost, $data);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true); //POST提交
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::TIMEOUT);
        $data = curl_exec($curl);
        if ($err = curl_error($curl)) {
            return false;
        }
        curl_close($curl);
        return $data;
    }

    /**
     * 发送get请求
     * @param $url
     * @param array $header
     * @param string $err
     * @return bool|string
     */
    public static function get($url, $header = [], &$err = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 超时设置,以秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, self::TIMEOUT);
        // 设置请求头
        if (!$header) {
            $header = [
                'Content-Type: application/json; charset=utf-8'
            ];
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $data = curl_exec($curl);
        if ($err = curl_error($curl)) {
            return false;
        }
        curl_close($curl);
        return $data;
    }

    /**
     * 发送post请求
     * @param $url
     * @param array $data
     * @param array $header
     * @param string $err
     * @return array|bool|string
     */
    public static function post($url, $data = [], $header = [], &$err = '')
    {
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 超时设置
        curl_setopt($curl, CURLOPT_TIMEOUT, self::TIMEOUT);
        // 设置请求头
        if (!$header) {
            $header = [
                'Content-Type: application/json; charset=utf-8'
            ];
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false );

        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $data = curl_exec($curl);
        if ($err = curl_error($curl)) {
            return false;
        }
        curl_close($curl);
        return $data;
    }

    /**
     * post raw方式
     * @param string $url
     * @param array $data
     * @param string $err
     * @return bool|string
     */
    public static function postJson(string $url, array $data, &$err='') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 超时设置
        curl_setopt($curl, CURLOPT_TIMEOUT, self::TIMEOUT);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8'
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false );
        $data = curl_exec($curl);
        if ($err = curl_error($curl)) {
            return false;
        }
        curl_close($curl);
        return $data;
    }
}