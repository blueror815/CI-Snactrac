<?php

/**
 * Define schedule related constants
 */
define('TABLE_TRUCK_SCHEDULES', 'truck_schedules');
define('TRUCK_SCHEDULE_STATUS_ENABLED', 100);
define('TRUCK_SCHEDULE_STATUS_DISABLED', 200);

/**
 * Helper function to get the list of schedule entries and associated location for a truck
 */
function schedule_get_truck_schedules($controller, $truckId, $scheduleId = null){
  $controller->db->select(
      TABLE_TRUCK_SCHEDULES.'.id as schedule_id, ' .
      'truck_id, ' . 
      'day_of_week, ' . 
      'start_time_hour, ' . 
      'start_time_min, ' . 
      'end_time_hour, ' . 
      'end_time_min, ' . 
      'location_id, ' . 
      'notes, ' . 
      'street, ' . 
      'street2, ' . 
      'city, ' . 
      'state, ' . 
      'zip, ' . 
      'country, ' . 
      'metro, ' . 
      'latitude, ' . 
      'longitude');
    $controller->db->from(TABLE_TRUCK_SCHEDULES);
	  $controller->db->join(
	      Location::TABLE_LOCATIONS,
	      'location_id = ' . Location::TABLE_LOCATIONS . '.id',
	      'left'
	  );
	  $controller->db->where(array('truck_id' => $truckId));
	  if(!empty($scheduleId)){
	    $controller->db->where(TABLE_TRUCK_SCHEDULES.'.id', $scheduleId);
	  }
	  $controller->db->order_by('day_of_week');
	  $query = $controller->db->get();
	  if($query->num_rows() < 1){
	    return array(false, 'No schedules defined for this truck.');
	  }
    return array(true, $query->result());
}

/**
 * Get a single schedule entry
 * @param BaseController $controller
 * @param int $truckId
 * @param int $scheduleId
 * @return array
 */
function schedule_get_truck_schedule($controller, $truckId, $scheduleId){
  list($ret, $schedules) = schedule_get_truck_schedules($controller, $truckId, $scheduleId);
  if($ret){
    return array(true, array_shift($schedules));
  }
  return array(false, $schedules);
}

/**
 * format the time into "hh:mm am/pm" format
 * @param int $hour
 * @param int $min
 * @return string
 */
function schedule_format_time($hour, $min){
  $ap = 'am';
  if($hour > 12){
    $ap = 'pm';
    $hour -= 12;
  }
  
  return sprintf("%02d:%02d%s", $hour, $min, $ap);
}

/**
 * Convert a schedule to the nearest date
 * @param Schedule $schedule
 */
function schedule_to_date($schedule){
  $diff = $schedule->day_of_week - date('N');
  $date = mktime(0,0,0, date('n'), date('j') + $diff, date('Y'));
  return date('l, j M Y', $date);
}
