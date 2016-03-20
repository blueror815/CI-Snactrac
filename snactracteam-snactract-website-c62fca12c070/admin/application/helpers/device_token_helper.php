<?php

/**
 * Define device token related constants
 */
define('TABLE_DEVICE_TOKENS', 'device_tokens');
define('PLATFORM_IOS', 1001);
define('PLATFORM_ANDROID', 1002);


/**
 * Helper function to get the device token associated with a user id
 */
function device_token_get_device_token($controller, $userId){
    $controller->db->select(
      'token,' .
      'platform');
    $controller->db->from(TABLE_DEVICE_TOKENS);
    $controller->db->where(array('user_id' => $userId));
    $query = $controller->db->get();
	if($query->num_rows() < 1){
	    return array(false, 'No device token found for this user.');
	}
    return array(true, array_shift($query->result()));
}