<?php

/**
 * Define business constants
 */
define('TABLE_BUSINESS', 'business');
define('BUSINESS_STATUS_ENABLED', 100);
define('BUSINESS_STATUS_DISABLED', 200);


/**
 * Helper function to get the business associated with
 * current user.
 */
function business_get_user_business($controller, $user, $autoInsert = true){

  if($user->user_type != Membership::USER_TYPE_AREA_MANGER){
    return array(false, 'Sorry: only a truck owner is allowed to access this page.');
  }

  $query = $controller->db->get_where(TABLE_BUSINESS, array('owner_id' => $user->id));
  if($query->num_rows() < 1 and $autoInsert){
    $controller->db->insert(
        TABLE_BUSINESS,
        array(
            'created_at' => time(),
            'status' => BUSINESS_STATUS_DISABLED,
            'owner_id' => $user->id,
            'name' => '',
            'phone' => '',
            'email' => $user->email,
            'website' => null,
            'twitter' => null,
            'facebook' => null,
            'yelp' => null,
            'description' => null
        )
    );
    if($controller->db->affected_rows() < 1){
      return array(false, "Unexpected error, please contact support. 0xF00001");
    }
    $businessId = $controller->db->insert_id();
    $query = $controller->db->get_where(TABLE_BUSINESS, array('id' => $businessId));
    if($query->num_rows() < 1){
      return array(false, "Unexpected error, please contact support. 0xF00002");
    }
  }

  return array(true, array_shift($query->result()));
}

/**
 * get a business from id
 * @param BaseController $controller
 * @param int $businessId
 * @return mixed
 */
function business_get_business($controller, $businessId){
  
  $query = $controller->db->get_where(TABLE_BUSINESS, array('id' => $businessId));
  if($query->num_rows() < 1){
    return array(false, "Unexpected error getting business data, please contact support. 0xF00002");
  }

  return array(true, array_shift($query->result()));
}
