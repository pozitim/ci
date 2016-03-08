<?php

namespace Pozitim\CI\Notification\Sender;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Pozitim\CI\Suite;

class HipChatSender extends SenderAbstract
{
    const API_URL = 'https://api.hipchat.com/v2';

    /**
     * @var array
     */
    protected $hipChatConfigs = array();

    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * @param Suite $suite
     */
    public function sendJobCompletedNotification(Suite $suite)
    {
        $this->setHipChatConfigs($suite);
        $this->post($suite);
    }

    /**
     * @param Suite $suite
     */
    protected function setHipChatConfigs(Suite $suite)
    {
        $this->hipChatConfigs['access_token'] = '';
        $this->hipChatConfigs['room_name'] = '';
        if (isset($suite->getNotificationSettings()['hipchat'])) {
            $this->hipChatConfigs = $suite->getNotificationSettings()['hipchat'];
            if (isset($this->hipChatConfigs['room_name']) == false
                && isset($this->getNotificationTypeData()->default_room)) {
                $this->hipChatConfigs['room_name'] = $this->getNotificationTypeData()->default_room;
            }
        }
        if (isset($this->getNotificationTypeData()->access_token)) {
            $this->hipChatConfigs['access_token'] = $this->getNotificationTypeData()->access_token;
        }
    }

    /**
     * @param Suite $suite
     */
    protected function post(Suite $suite)
    {
        $url = HipChatSender::API_URL . '/room/' . $this->hipChatConfigs['room_name'] . '/notification';
        $options = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->hipChatConfigs['access_token']
            ),
            'body' => json_encode($this->prepareBody($suite))
        );
        try {
            $response = $this->getGuzzleClient()->request('POST', $url, $options);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
        }
        if ($response->getStatusCode() != 204) {
            $this->getLogger()->error(
                'HipChat Response',
                array(
                    'body' => json_decode($response->getBody()->__toString()),
                    'statusCode' => $response->getStatusCode(),
                    'headers' => $response->getHeaders()
                )
            );
        }
    }

    /**
     * @param Suite $suite
     * @return \stdClass
     */
    protected function prepareBody(Suite $suite)
    {
        $jobUrl = $this->getConfig()->host_url . '/raw-job-viewer?id=' . $suite->getJobEntity()->id;
        $body = new \stdClass();
        $body->notify = true;
        $body->message = $suite->getName() . ' completed!';
        if ($suite->getJobEntity()->exit_code == 0) {
            $body->color = 'green';
        } else {
            $body->color = 'red';
        }
        $body->card = new \stdClass();
        $body->card->style = 'application';
        $body->card->title = 'Build #' . $suite->getProject()->getBuildEntity()->id;
        $body->card->id = $suite->getJobEntity()->id;
        $body->card->url = $jobUrl;
        $body->card->description = new \stdClass();
        $body->card->description->format = 'html';
        $body->card->description->value = $suite->getName() . ' completed in ' . $suite->getJobEntity()->getDuration()
            . ' seconds. <a href="' . $jobUrl . '">Click for details.</a>';
        return $body;
    }

    /**
     * @return Client
     */
    protected function getGuzzleClient()
    {
        if ($this->guzzleClient == null) {
            $this->guzzleClient = new Client();
        }
        return $this->guzzleClient;
    }
}
