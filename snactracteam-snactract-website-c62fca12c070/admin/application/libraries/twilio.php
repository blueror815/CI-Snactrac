<?php 
/**
 * See https://github.com/twilio/twilio-php#quickstart 
 */

require('twilio/Services/Twilio.php');

class Twilio {

  const FROM_NUMBER = '6502295564';
  const SID = "AC9a2917a295dd2d9f5f2986758f46db39";
  const TOKEN = '420665f1c4416171196e29705372fc95';
  
  /**
   * send sms to a number
   * @param string $to
   * @param string $msg
   */
  public function sms($to, $msg){
    
    if(empty($to) or empty($msg)){
      return false;
    }
    
    $client = new Services_Twilio(self::SID, self::TOKEN);
    $message = $client->account->messages->sendMessage(self::FROM_NUMBER, $to, $msg);
    log_message('info', __METHOD__ . "(): to: {$to} msg: {$msg} sid: {$message->sid}");
    
    return $message->sid;
  }
}