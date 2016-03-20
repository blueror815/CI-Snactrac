<?php 

define('TABLE_TRUCKS', 'trucks');
define('TABLE_TRUCK_KEYWORDS', 'truck_keywords');
define('TRUCK_STATUS_ENABLED', 100);
define('TRUCK_STATUS_DISABLED', 200);
define('TRUCK_TYPE_TRUCK', 1);
define('TRUCK_TYPE_TROLLY', 2);
define('TRUCK_TYPE_BOOTH', 3);
define('TRUCK_IMAGE_BASE_PATH', 'images/trucks/');

/**
 * Get list of trucks for a business
 * @param int $businessId
 * @param string $filter
 */
function trucks_get_business_trucks($controller, $businessId, $filter = null){
	  $controller->db->select('*');
	  $controller->db->from(TABLE_TRUCKS);
	  $controller->db->where(array('business_id' => $businessId));
	  if(!empty($filter)){
	    $controller->db->like('name', $filter);
	  }
	  $query = $controller->db->get();
	  $results = array();
	  foreach($query->result() as $truck){
	    $truck->keywords = trucks_get_truck_keywords($controller, $truck->id);
	    $results[] = $truck;
	  }
	  return $results;
}

/**
 * Load truck data from db
 * @param int $truckId
 * @return array
 */
function trucks_get_truck($controller, $truckId){
  $truck = null;
  if(!empty($truckId)){
    $query = $controller->db->get_where(TABLE_TRUCKS, array('id' => $truckId));
    if($query->num_rows() < 1){
      return array(false, "Failed to load truck data for truck with id {$truckId}, please try again later."); 
    }
    
    $truck = array_shift($query->result());
    $truck->keywords = trucks_get_truck_keywords($controller, $truck->id);
    return array(true, $truck);
  }
}

/**
 * @param BaseController $controller
 * @param int $truckId
 */
function trucks_get_truck_keywords($controller, $truckId){
  $controller->db->select('*');
  $controller->db->from(TABLE_TRUCK_KEYWORDS);
  $controller->db->where(array('truck_id' => $truckId));
  $keywords = array();
  foreach($controller->db->get()->result() as $keyword){
    $keywords[] = $keyword->keyword;
  }
  return implode(', ', $keywords);  
}

/**
 * split the keywords into array and insert into db
 * @param BaseController $controller
 * @param int $truckId
 * @param string $keywords
 * @return boolean
 */
function trucks_set_truck_keywords($controller, $truckId, $keywords){
  
  // delete old keywords
  $controller->db->delete(TABLE_TRUCK_KEYWORDS, array('truck_id' => $truckId));
  
  // insert new ones
  if(!empty($keywords)) {
    $data = array();
    foreach(preg_split("/[,\s+]/", $keywords, -1, PREG_SPLIT_NO_EMPTY) as $keyword){
      $data[] = array('truck_id' => $truckId, 'keyword' => strtolower($keyword));
    }
    $controller->db->insert_batch(TABLE_TRUCK_KEYWORDS, $data);
  }
  return true;
}

/**
 * delete a truck and associated keywords and menu items
 * @param Base Controller $controller
 * @param truck id $truckId
 */
function trucks_delete_truck($controller, $truckId){
  list($ret, $menuItems) = menu_get_truck_menu($controller, $truckId);
  if($ret){
    foreach($menuItems as $menuItem){
      menu_delete_menu_item($controller, $menuItem);
    }
  }
  $controller->db->delete(TABLE_TRUCK_KEYWORDS, array('truck_id' => $trcukId));
  $controller->db->delete(TABLE_TRUCK_SCHEDULES, array('truck_id' => $truckId));
  $controller->db->delete(TABLE_TRUCKS, array('id' => $truckId));
  return array(true, '');
}

/**
 * Get the image path of the truck
 * @param BaseController $controller
 * @param int $truckId
 * @return string
 */
function trucks_get_truck_image($controller, $truckId){

  foreach(array('png', 'jpg') as $ext){
    $fileName = TRUCK_IMAGE_BASE_PATH . "{$truckId}.{$ext}";
    if(file_exists($fileName)){
      return base_url($fileName);
    }
  }
  return assets_url('images/truck-' . (($truckId % 7) + 1) . '.jpg');
}