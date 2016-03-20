<?php

/**
 * Manages monitors for trucks
 * @author naveed
 *
 */
class Monitor {
  
  const TABLE_NAME = 'truck_monitors';
    
  const MONITOR_TYPE_EMAIL = 1001;
  const MONITOR_TYPE_SMS = 1002;
  const MONITOR_TYPE_TWITTER = 1003;
  const MONITOR_TYPE_FACEBOOK = 1004;
  const MONITOR_TYPE_PUSH_NOTIFICATION = 1005;
  
  private $controller = null;
  
  /**
   * contructors - accepts a controller in $params
   * @param array $params
   */
  public function __construct($params){
    $this->controller = $params['controller'];
  }
  
  /**
   * add a new alert
   * @param int $truckId
   * @param int $userId
   * @param int $monitorType
   * @return array
   */
  public function add($truckId, $userId, $monitorType){
    $this->controller->db->insert(
        self::TABLE_NAME,
        array(
            'created_at' => time(),
            'truck_id' => $truckId,
            'user_id' => $userId,
            'monitor_type' => $monitorType,
        )
    );
    
    if($this->controller->db->_error_number()){
      return array(false, 'Unable to perform this operation, please try again later');
    }
    else {
      return array(true, $this->controller->db->insert_id());
    }
  }
  
  /**
   * Remove a monitor
   * @param int $monitorId
   * @return multitype:boolean string
   */
  public function remove($monitorId){
    $this->controller->db->delete(self::TABLE_NAME, array('id' => $monitorId));
    return array(true, 'success');
  }
  
  /**
   * 
   * @param unknown $monitorId
   */
  public function update($monitorId){
    
  }
  
  /**
   * get all the monitors for this truck
   * @param int $truckId
   * @param int $userId
   * @return array
   */
  public function get($truckId, $userId){
    
    $this->controller->db->select('*');
    $this->controller->db->from(self::TABLE_NAME);
    $where = array('truck_id' => $truckId);
    if(!empty($userId)){
      $where['user_id'] = $userId;
    }
    $this->controller->db->where($where);
    $query = $this->controller->db->get();
    debug_log_sql($this->controller->db->last_query());
    if(empty($query)){
      return array(false, 'An unexpected error occurred while trying to fetch the data. Please try again later.');
    }
    
    $results = $query->result();
    if(count($results) > 0){
      return array(true, $results[0]);
    }
    return array(false, 'No data was found.');
  }
  
  /**
   * get a monitor by its id
   * @param int $monitorId
   * @return array
   */
  public function getById($monitorId){
    $this->controller->db->select('*');
    $this->controller->db->from(self::TABLE_NAME);
    $where = array('id' => $monitorId);
    $this->controller->db->where($where);
    $query = $this->controller->db->get();
    debug_log_sql($this->controller->db->last_query());
    if(empty($query)){
      return array(false, 'An unexpected error occurred while trying to fetch the data. Please try again later.');
    }
    $results = $query->result();
    if(count($results) > 0){
      return array(true, $results[0]);
    }
    return array(false, 'An unexpected error occurred while trying to fetch the data. Please try again later.');
  }
  
  /**
   * Runs every hour to get the monitors for the upcoming hour
   * @return array
   */
  public function getHourlyNotifications(){

    $this->controller->db->select(
       's.id,
        s.truck_id,
        s.day_of_week,
        s.start_time_hour,
        s.start_time_min,
        s.location_id,
        m.user_id,
        m.monitor_type,
        l.street,
        l.state,
        t.name,
        u.name as user_name,
        u.phone,
        u.email,
        d.token'
    );
    $this->controller->db->from(TABLE_TRUCK_SCHEDULES . ' s');
    $this->controller->db->join(TABLE_TRUCKS . ' t', 's.truck_id = t.id');
    $this->controller->db->join(self::TABLE_NAME . ' m', 'm.truck_id = t.id');
    $this->controller->db->join(Location::TABLE_LOCATIONS . ' l', 's.location_id = l.id');
    $this->controller->db->join(Membership::TABLE_USERS . ' u', 'm.user_id = u.id');
    $this->controller->db->join(DeviceTokens::TABLE_DEVICE_TOKENS. ' u', 'm.user_id = u.id');
    
    $this->controller->db->where(
    	array(
    	 'day_of_week' => date('N'),
    	 'start_time_hour' => date('G')
      )
    );
    $this->controller->db->order_by('start_time_min');
    
    $query = $this->controller->db->get();
    debug_log_sql($this->controller->db->last_query());
    if(empty($query)){
      return array(false, 'An unexpected error occurred while trying to fetch the data. Please try again later.');
    }
    
    if($query->num_rows() < 1){
      echo "DID NOT GET ANYTHING TO NOTIFY\n";
      echo "SQL:" . $this->controller->db->last_query() . "\n";
      return array(true, array());
    }
    
    return array(true, $query->result());
  }
}