<?php

/**
 * Prints to error_log
 * @param mixed $subject
 * @param string $title
 */
function debug_log($subject, $title = ''){
  if(!empty($title)){
    $title = "{$title}: ";
  }
  if(is_array($subject) or is_object($subject) or is_bool($subject)){
    error_log($title . json_encode($subject));
  }
  else {
    error_log($title . $subject);
  }
}

/**
 * Logs to the error log and also application log using log_message()
 * @param string $sql
 */
function debug_log_sql($sql){
  $sql = str_replace(array("\r\n", "\n", "\r"), ' ', $sql);
  log_message("info", "SQL: {$sql}");
  //error_log("SQL: {$sql}");
}