<?php
/**
 * Created by PhpStorm.
 * User: Li Ning
 * Date: 2018/5/16
 * Time: 10:01
 */

namespace app\lib\exception;

use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    public function render(\Exception $exception)
    {
        if ($exception instanceof BaseException) {
            $this->code = $exception->code;
            $this->msg = $exception->msg;
            $this->errorCode = $exception->errorCode;
        } else {
            if (config('app_debug')){
                return parent::render($exception);
            }else{
                $this->code = 500;
                $this->msg = '服务器内部错误!';
                $this->errorCode = '999';
                $this->recordErrorLog($exception);
            }
        }

        $request = Request::instance();
        $result = [
            'errorCode' => $this->errorCode,
            'msg' => $this->msg,
            'request_url' => $request->url()
        ];
        return json($result);
    }

    /**
     * @param $exception
     */
    public function recordErrorLog(\Exception $exception)
    {
        Log::init([
            'type' => 'File',
            'path' => RUNTIME_PATH . 'log' . DS
        ]);
        Log::record($exception->getMessage(), 'error');
    }
}