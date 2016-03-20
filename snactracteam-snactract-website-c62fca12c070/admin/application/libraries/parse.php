<?php

/**
 * Parse push notification library wrapper
 * @author naveed
 *
 */
class Parse{
    
  const APP_ID = 'BGzJrrlFBGAAYh27qBcXfYUDnp5W7JX5hGWlIyYR';
  const MASTER_KEY = '4xx6vfK1XU9v5efoxzSxU02rzx24vhyUX7r4uHbS';
  const REST_KEY = '5RXKId4P4xGIWyuqpmwzCJrHgFy7XdcqsIgmbYFG';
  
  private $controller = null;
  
  
  /**
   * contructor - expects a controller in $params
   * @param array $params
   */
  public function __construct($params){
    $this->controller = $params['controller'];
    ParseClient::initialize(self::APP_ID, self::REST_KEY, self::MASTER_KEY);
  }
  
  /**
   * helper function to send a notification
   * @param string $deviceToken
   * @param string $msg
   */
  public function sendNotification($deviceToken, $msg){
    $query = ParseInstallation::query();
    $query->equalTo("deviceToken", $deviceToken);
    
    ParsePush::send(array(
      "where" => $query,
      "data" => array(
        "alert" => $msg
      )
    ));
  }
  
}