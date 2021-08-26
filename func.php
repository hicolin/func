<?php

// 计划：区分常用和不常用

// 目录常量，根据框架调整
const ROOT = __DIR__;
const LOG = ROOT . '/log/';
const WWW = ROOT . '/www/';
const UPLOAD = WWW . '/upload/';

// 记录日志
function _log($path, $data)
{
    $log_path = LOG . $path;
    if (!is_dir($log_path)) {
        mkdir($log_path, 0755, true);
    }
    $file = $log_path . '/' . date('Y-m-d') . ".txt";
    $time = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = $_SERVER['REQUEST_URI'];
    $data = "{$time}\t{$ip}\t{$url}\t{$data}\r\n";
    if (filesize($data < 1024 * 1024 * 5)) { // 5M
        file_put_contents($file, $data, FILE_APPEND);
    } else {
        file_put_contents($file, $data);
    }
}

// 不转义中文字符的json编码
function json($data)
{
    return json_encode($data, JSON_UNESCAPED_UNICODE);
}

// 返回成功信息
function success($msg, $data = [])
{
    $r_data = ['code' => 1, 'msg' => $msg];
    if ($data) {
        $r_data['data'] = $data;
    }
    return $r_data;
}

// 返回失败信息
function fail($msg)
{
    return ['code' => 1, 'msg' => $msg];
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

// 生成订单号
function gen_order_number($prefix = '')
{
    return $prefix . date('YmdHis') . mt_rand(1000, 9999);
}

// 判断当前时间是否在某个时间段中
function in_range_time($start_time, $end_time)
{
    $now = time();
    return $now >= strtotime($start_time) && $now <= strtotime($end_time);
}

// 设置超时时间和内存限制
function ignore_timeout()
{
    @ignore_user_abort(true);
    @ini_set("max_execution_time", 48 * 60 * 60);
    @set_time_limit(48 * 60 * 60);//set_time_limit(0)  2day
    @ini_set('memory_limit', '4000M');//4G;
}

// 终止并完成http请求；客户端终止等待完成请求
// 后续代码可以继续运行；例如日志、统计等代码；后续输出将不再生效
function http_close()
{
    ignore_timeout();
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    } else {
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_start();
        echo str_pad('', 1024 * 5);
        ob_end_flush();
        flush();
    }
}

// CSV文件导出
function csv_export($data, $name = '')
{
    $csvFileName = $name ? $name . '.csv' : date('YmdHis') . mt_rand(1000, 9999) . '.csv';
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
    header('Expires: Mon,26 Jul 1997 05:00:00 GMT');
    header('Content-Transfer-Encoding: binary');
    echo implode("\r\n", $data);
    exit;
}

// 文件下载
function file_download($file_dir, $file_name)
{
    if (!file_exists($file_dir . $file_name)) {
        header('HTTP/1.1 404 NOT FOUND');
    } else {
        $file = fopen($file_dir . $file_name, "rb");
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: " . filesize($file_dir . $file_name));
        Header("Content-Disposition: attachment; filename=" . $file_name);
        echo fread($file, filesize($file_dir . $file_name));
        fclose($file);
        exit;
    }
}
