<?php

// 不转义中文字符的json编码
function json($data)
{
    return json_encode($data, JSON_UNESCAPED_UNICODE);
}

// 返回成功信息
function success($msg, $data = [])
{
    $r_data = ['msg' => $msg, 'code' => 1];
    if ($data) {
        $r_data['data'] = $data;
    }
    return $r_data;
}

// 返回失败信息
function fail($msg)
{
    return ['msg' => $msg, 'code' => -1];
}

// 是否为开发环境
function is_dev()
{
    return $GLOBALS['debug'];
}

// 是否为开发者ip
function is_dev_ip()
{
    return in_array($_SERVER['REMOTE_ADDR'], $_ENV['_config']['dev_ips']);
}

// 获取当前站点url
function site_url()
{
    return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
}

// 生成UUID
function uuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// 获取随机数
function random_str($len, $chars = '')
{
    if (!trim($chars)) {
        $chars = 'abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789';
    }
    for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
        $str .= $chars[mt_rand(0, $lc)];
    }
    return $str;
}


