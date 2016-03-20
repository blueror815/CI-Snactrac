<?php 

define('TABLE_MENU', 'menu_items');
define('TABLE_MENU_KEYWORDS', 'menu_item_keywords');
define('MENU_ITEM_STATUS_ENABLED', 100);
define('MENU_ITEM_STATUS_DISABLED', 200);
define('MENU_IMAGE_BASE_PATH', 'images/menu/');

/**
 * get menu items for a given truck
 * @param BaseController $controller
 * @param int $truckId
 * @param string $filter
 * @param int limit 
 */
function menu_get_truck_menu($controller, $truckId, $filter = null, $limit = null){
  $controller->db->select('*');
  $controller->db->from(TABLE_MENU);
  $controller->db->where(array('truck_id' => $truckId));
  if(!empty($filter)){
    $controller->db->like('name', $filter);
  }
  if(!empty($limit)){
    $controller->db->limit($limit);
  }
  $query = $controller->db->get();
  $results = array();
  foreach($query->result() as $menuItem){
    $menuItem->keywords = menu_get_menu_item_keywords($controller, $menuItem);
    $results[] = $menuItem;
  }
  return array(true, $results);
}

/**
 * Get a menu item
 * @param BaseController $controller
 * @param int $menuItemId
 * @return multitype:boolean string |multitype:boolean mixed
 */
function menu_get_menu_item($controller, $menuItemId){
    $query = $controller->db->get_where(TABLE_MENU, array('id' => $menuItemId));
    if($query->num_rows() < 1){
      return array(false, 'Sorrry, could not find this menu item.');
    }
    
    $menuItem = array_shift($query->result());
    $menuItem->keywords = menu_get_menu_item_keywords($controller, $menuItem);
    return array(true, $menuItem);
}

/**
 * @param BaseController $controller
 * @param Truck $truck
 */
function menu_get_menu_item_keywords($controller, $menuItem){
  $controller->db->select('*');
  $controller->db->from(TABLE_MENU_KEYWORDS);
  $controller->db->where(array('menu_item_id' => $menuItem->id));
  $keywords = array();
  foreach($controller->db->get()->result() as $keyword){
    $keywords[] = $keyword->keyword;
  }
  return implode(', ', $keywords);  
}

/**
 * split the keywords into an array and insert into db
 * @param BaseController $controller
 * @param Truck $truck
 * @param string $keywords
 * @return boolean
 */
function menu_set_menu_item_keywords($controller, $menuItemId, $keywords){

  $controller->db->delete(TABLE_MENU_KEYWORDS, array('menu_item_id' => $menuItemId));
  
  if(!empty($keywords)){
    $data = array();
    foreach(preg_split("/[,\s+]/", $keywords, -1, PREG_SPLIT_NO_EMPTY) as $keyword){
      $data[] = array('menu_item_id' => $menuItemId, 'keyword' => $keyword);
    }
    $controller->db->insert_batch(TABLE_MENU_KEYWORDS, $data);
  }

  return true;
}

/**
 * delete a menu item
 * @param BaseController $controller
 * @param MenuItem $menuItem
 */
function menu_delete_menu_item($controller, $menuItem){
  debug_log($menuItem, __METHOD__);
  $controller->db->delete(TABLE_MENU_KEYWORDS, array('menu_item_id' => $menuItem->id));
  $controller->db->delete(TABLE_MENU, array('id' => $menuItem->id));
}