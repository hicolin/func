<?php

namespace app\common;

class LogService
{
    /**
     * 写入自定义日志
     * @param string $content 日志内容
     * @param string $fileName 日志文件名（不含扩展名）
     * @param string $dirName 日志目录名
     */
    public static function write(string $content, string $fileName = 'custom', string $dirName = 'custom')
    {
        $logPath = app()->getRuntimePath() . "log/{$dirName}/";

        // 检查目录是否存在，不存在则创建
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755, true);
        }

        // 日志文件名（格式：文件名_日期.log）
        $logFile = $logPath . "{$fileName}_" . date('Ymd') . '.log';

        // 日志内容（添加时间戳和换行）
        $logContent = '[' . date('Y-m-d H:i:s') . '] ' . $content . PHP_EOL;

        // 写入文件（追加模式）
        file_put_contents($logFile, $logContent, FILE_APPEND);
    }
}
