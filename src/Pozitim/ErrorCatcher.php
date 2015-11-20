<?php

namespace Pozitim;

class ErrorCatcher
{
    protected $fatalCallback = null;
    protected $exceptionCallback = null;

    public function register()
    {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
        register_shutdown_function(array($this, 'fatalErrorHandler'));
    }

    /**
     * @param $onFatalCallback
     */
    public function setFatalCallback($onFatalCallback)
    {
        $this->fatalCallback = $onFatalCallback;
    }

    /**
     * @param $exceptionCallback
     */
    public function setExceptionCallback($exceptionCallback)
    {
        $this->exceptionCallback = $exceptionCallback;
    }

    /**
     * @param \Exception $exception
     */
    public function exceptionHandler(\Exception $exception)
    {
        $this->onExceptionCallback($exception);
    }

    /**
     * @param $errNo
     * @param $errStr
     * @param $errFile
     * @param $errLine
     * @throws \ErrorException
     */
    public function errorHandler($errNo, $errStr, $errFile, $errLine)
    {
        throw new \ErrorException($errStr, $errNo, 0, $errFile, $errLine);
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();

        if ($error !== null) {
            $errType = $error["type"];
            $errFile = $error["file"];
            $errLine = $error["line"];
            $errStr = $error["message"];

            $message = '[' . $errType . '] ' . $errStr . ' ' . $errFile . ':' . $errLine;
            $message = str_replace("\n", '', $message);
            $this->onFatalCallback($message, $errType, $errFile, $errLine, $errStr);
        }
    }

    /**
     * @param \Exception $exception
     */
    protected function onExceptionCallback(\Exception $exception)
    {
        if ($this->exceptionCallback != null) {
            call_user_func_array($this->exceptionCallback, [$exception]);
        }
    }

    /**
     * @param $message
     * @param $errType
     * @param $errFile
     * @param $errLine
     * @param $errStr
     */
    protected function onFatalCallback($message, $errType, $errFile, $errLine, $errStr)
    {
        if ($this->fatalCallback != null) {
            call_user_func_array($this->fatalCallback, [$message, $errType, $errFile, $errLine, $errStr]);
        }
    }
}
