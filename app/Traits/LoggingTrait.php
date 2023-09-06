<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LoggingTrait
{
    /*
     * Create error in logging file
     *
     * @param array $channels
     *
     * @param Exception
     *
     * */
    public function createLogs(array $channels, \Exception $exception, $level)
    {
        Log::stack($channels)->$level(
            '[1] Information => '.$exception->getMessage()
            .PHP_EOL.'[2] File name => '.$exception->getFile()
            .PHP_EOL.'[3] line number=> '.$exception->getLine()
            .PHP_EOL.'[4] Code => '.$exception->getCode()
        );
    }
}
