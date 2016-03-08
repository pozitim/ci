<?php

namespace Pozitim\CI\Web\V1;

use Monolog\Logger;
use OU\DI;
use Pozitim\ErrorCatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
        /**
         * @var ErrorCatcher $errorCatcher
         */
        $errorCatcher = $di->get('error_catcher');
        $errorCatcher->register();
        $errorCatcher->setExceptionCallback(array($this, 'handleException'));
        $errorCatcher->setFatalCallback(array($this, 'handleFatalError'));
    }

    /**
     * @param $message
     */
    public function handleFatalError($message)
    {
        $this->handleException(new \Exception($message));
    }

    /**
     * @param \Exception $exception
     */
    public function handleException(\Exception $exception)
    {
        $this->getLogger()->error($exception);
        $this->sendHtml('Internal Server Error', 500);
    }

    /**
     * @param $url
     * @param int $code
     * @param array $headers
     * @return RedirectResponse
     */
    protected function redirect($url, $code = 302, $headers = array())
    {
        return new RedirectResponse($url, $code, $headers);
    }

    /**
     * @param $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function sendJson($content, $status = 200, $headers = array())
    {
        $this->getHttpResponse()->setContent(json_encode($content))
            ->setStatusCode($status);
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $this->setHeaders($headers);
        $this->getHttpResponse()->send();
    }

    /**
     * @param $content
     * @param int $status
     * @param array $headers
     */
    protected function sendPlainText($content, $status = 200, $headers = array())
    {
        $this->getHttpResponse()->setContent($content)
            ->setStatusCode($status, '');
        $headers['Content-Type'] = 'text/plain; charset=utf-8';
        $this->setHeaders($headers);
        $this->getHttpResponse()->send();
    }

    /**
     * @param $content
     * @param int $status
     * @param array $headers
     */
    protected function sendHtml($content, $status = 200, $headers = array())
    {
        $this->getHttpResponse()->setContent($content)
            ->setStatusCode($status, '');
        $headers['Content-Type'] = 'text/html; charset=utf-8';
        $this->setHeaders($headers);
        $this->getHttpResponse()->send();
    }

    /**
     * @param array $headers
     * @throws \Exception
     */
    protected function setHeaders($headers = array())
    {
        $this->getHttpResponse()->headers->set('X-App-Request-Id', $this->getDi()->get('config')->req_id);
        foreach ($headers as $name => $value) {
            $this->getHttpResponse()->headers->set($name, $value);
        }
    }

    /**
     * @return Request
     * @throws \Exception
     */
    protected function getHttpRequest()
    {
        return $this->getDi()->get('http_request');
    }

    /**
     * @return Response
     * @throws \Exception
     */
    protected function getHttpResponse()
    {
        return $this->getDi()->get('http_response');
    }

    /**
     * @return Logger
     * @throws \Exception
     */
    protected function getLogger()
    {
        return $this->getDi()->get('logger_helper')->getLogger();
    }

    /**
     * @return DI
     */
    public function getDi()
    {
        return $this->di;
    }
}
