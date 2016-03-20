<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class Schedule extends BaseController {
  
  /**
   * Default constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->library('location');
    $this->load->library('stcalendar');
    $this->load->helper('bootstrap_helper');
    $this->load->helper('business_helper');
    $this->load->helper('trucks_helper');
    $this->load->helper('menu_helper');
    $this->load->helper('schedule_helper');
  }
  
  /**
   * Index Page for this controller.
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function index($truckId, $id = null, $notify = null)
  {
    list($ret, $user) = $this->getLoggedInUser();
    if(empty($ret)){
      redirect('/user/signin', 'refresh');
      die();
    }
    
    if($user->user_type != Membership::USER_TYPE_AREA_MANGER){
      echo 'Sorry, only a truck owner is allowed to access this page.';
      die();
    }
    
    $alert = '';
    list($ret, $business) = business_get_user_business($this, $user);
    if(!$ret){
      $alert = 'Failed to get the business data. Please try again later.';
    }
    
    $schedule = null;
    list($ret, $truck) = trucks_get_truck($this, $truckId);
    if(!$ret){
      $alert = $truck;
    }
    else {
      if(!empty($id)){
        list($ret, $schedule) = schedule_get_truck_schedule($this, $truckId, $id);
        if(!$ret){
          $alert = $schedule;
          $schedule = null;
        }
      }
    }
    
    $this->form_validation->set_rules('day_of_week', 'Day of week', 'trim|xss_clean');
    $this->form_validation->set_rules('start_time', 'Start time', 'trim|required|xss_clean');
    $this->form_validation->set_rules('end_time', 'End time', 'trim|required|xss_clean');
    $this->form_validation->set_rules('street', 'Street', 'trim|required|xss_clean');
    $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
    $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
    $this->form_validation->set_rules('zip', 'Zip', 'trim|required|xss_clean');
    $this->form_validation->set_rules('notes', 'Notes', 'trim|xss_clean');
    
    if ($this->form_validation->run()){
      
      // verify correct value for schedule
      $day_of_week = strtolower($this->input->post('day_of_week'));
      list($start_time_hour, $start_time_min) = explode(':', $this->input->post('start_time'), 2);
      $start_time_hour = intval($start_time_hour);
      $start_time_min = intval($start_time_min);
      list($end_time_hour, $end_time_min) = explode(':', $this->input->post('end_time'), 2);
      $end_time_hour = intval($end_time_hour);
      $end_time_min = intval($end_time_min);
      
      $validSchedule = false;
      if(array_search($day_of_week, array_keys($this->stcalendar->getDaysOfWeek())) === false){
        $alert = 'Invalid day of the week';
      }
      elseif($start_time_hour < 0 or $start_time_hour > 23 or $start_time_min < 0 or $start_time_min > 59){
        $alert = 'Invalid start time use 24 hour format e.g. 17:25';
      }
      elseif($end_time_hour < 0 or $end_time_hour > 23 or $end_time_min < 0 or $end_time_min > 59){
        $alert = 'Invalid end time use 24 hour format e.g. 17:25';
      }
      else {
        // TODO check for over lapping schedules and other checks
        $validSchedule = true;
      }
      
      // verify valid address
      $latitude = false;
      $longitude = false;
      if($validSchedule){
        list($latitude, $longitude) = $this->location->geocodeByAddress(
            $this->input->post('street'),
            $this->input->post('city'),
            $this->input->post('state'),
            $this->input->post('zip'));
      }
      
      if($validSchedule){
        
        if(empty($latitude) or empty($longitude)){
          $alert  = "Sorry we were not able to locate this address.";
        }
        else {
          // Start a txn since we are going to update multiple tables
          $this->db->trans_start();
  
          // delete old location if any
          if(!empty($schedule->location_id)){
            $this->location->delete($this, $schedule->location_id);
          }
          
          // insert new location
          $locationId = $this->location->add(
              $this,
              $this->input->post('street'),
              $this->input->post('city'),
              $this->input->post('state'),
              $this->input->post('zip'),
              Location::COUNTRY_US,
              $latitude,
              $longitude);
          
          // new schedule
          if(empty($schedule)){
            // INSERT NEW SCHEDULE
            $this->db->insert(
    	        TABLE_TRUCK_SCHEDULES,
              array(
            	  'created_at' => time(),
                'truck_id' => $truckId,
                'day_of_week' => $day_of_week,
                'start_time_hour' => $start_time_hour,
                'start_time_min' => $start_time_min,
                'end_time_hour' => $end_time_hour,
                'end_time_min' => $end_time_min,
                'location_id' => $locationId,
                'notes' => $this->input->post('notes')
              )
            );
            if($this->db->_error_number()){
              $alert = 'Failed to add the schedule, please try again later.';
            }
            else {
              $id = $this->db->insert_id();
              $this->db->trans_complete();
              redirect("/schedule/index/{$truck->id}/{$id}/saved");
            }
          }
          else {
            $schedule->id = $schedule->schedule_id;
            $schedule->day_of_week = $day_of_week;
            $schedule->start_time_hour = $start_time_hour;
            $schedule->end_time_hour = $end_time_hour;
            $schedule->start_time_min = $start_time_min;
            $schedule->end_time_min = $end_time_min;
            $schedule->location_id = $locationId;
            $schedule->notes = $this->input->post('notes');
            foreach(array('schedule_id', 'street', 'street2', 'city', 'state', 'zip', 'country', 'metro', 'longitude', 'longitude', 'latitude') as $field){
              unset($schedule->$field);
            }
            $this->db->update(
                TABLE_TRUCK_SCHEDULES,
                $schedule,
                array('id' => $schedule->id)
            );
            if($this->db->_error_number()){
              $alert = 'Failed to update the schedule, please try again later.';
            }
            else {
              $this->db->trans_complete();
              $schedule->schedule_id = $schedule->id;
              redirect("/schedule/index/{$truck->id}/{$schedule->id}/saved");
            }
          }
        }
      }
    }
    
    // show notification if no alert is there to show
    if(empty($alert)){
      if(!empty($notify)){
        if($notify = 'saved'){
          $alert = 'Schedule saved successfully.';
        }
      }
    }
     
    $this->load->view('header', array('controller' => $this));
    $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'trucks'));
    $this->load->view(
        'schedule/schedule',
        array(
            'alert' => $alert,
            'business' => $business,
            'truck' => $truck,
            'schedule' => $schedule,
            'stcalendar' => $this->stcalendar
      )
    );
    $this->load->view('footer');
  }
  
  /**
   * Delete a truck schedule
   * @param int $truckId
   * @param int $scheduleId
   */
  public function delete($truckId, $scheduleId){
    list($ret, $user) = $this->getLoggedInUser();
    if(empty($ret)){
      redirect('/user/signin', 'refresh');
      die();
    }
    
    if($user->user_type != Membership::USER_TYPE_AREA_MANGER){
      echo 'Sorry, only a truck owner is allowed to access this page.';
      die();
    }
    
    $alert = '';
    list($ret, $business) = business_get_user_business($this, $user);
    if(!$ret){
      $alert = 'Failed to get the business data. Please try again later.';
    }
        
    $schedule = null;
    list($ret, $truck) = trucks_get_truck($this, $truckId);
    if(!$ret){
      $alert = $truck;
    }
    else {
      if(!empty($scheduleId)){
        list($ret, $schedule) = schedule_get_truck_schedule($this, $truckId, $scheduleId);
        if(!$ret){
          $alert = $schedule;
          $schedule = null;
        }
        else {
          $this->db->delete(TABLE_TRUCK_SCHEDULES, array('id' => $scheduleId, 'truck_id' => $truckId));
          if($this->db->_error_number()){
            $alert = 'Failed to delete the schedule, please try again later.';
          }
          else {
            redirect("/truck/schedule/{$truck->id}/deleted");
          }
        }
      }
    }
    
    $this->load->view('header', array('controller' => $this));
    $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'trucks'));
    $this->load->view(
        'schedule/schedule',
        array(
            'alert' => $alert,
            'business' => $business,
            'truck' => $truck,
            'schedule' => $schedule,
            'stcalendar' => $this->stcalendar
        )
    );
    $this->load->view('footer');
  }
  
  /**
   * Add, Remove, Update monitoring for a truck
   */
  public function track($truckId, $scheduleId){
    
    list($ret, $user) = $this->getLoggedInUser();
    if(empty($ret)){
      redirect('/user/signin', 'refresh');
      die();
    }
    
    $alert = '';
    $success = true;
    list($success, $schedule) = schedule_get_truck_schedule($this, $truckId, $scheduleId);
    if(!$success){
      $alert = 'Unable to load truck data, please try again later.';
    }
    else {
      $dayOfWeek = ucfirst($this->stcalendar->getDayOfWeekTitle($schedule->day_of_week));
      list($success, $truck) = trucks_get_truck($this, $truckId);
      if(!$success){
        $alert = $truck;
      }
      else {
        $this->load->library('monitor', array('controller' => $this));
        list($success, $monitor) = $this->monitor->get($truckId, $user->id);
        if(!$success){
          $success = true;
          $alert = ''; // its okay if a monitor does exist yet
          $monitor = null;
        }
      }
    }
    
    $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|email');
    $this->form_validation->set_rules('phone', 'Phone', 'trim|xss_clean');
    
    if ($success and $this->form_validation->run()){
//      debug_log($_POST);
      if($this->input->post('save') == 1){
        $monitorType = Monitor::MONITOR_TYPE_EMAIL;
        if($this->input->post('method') == 'phone'){
          $monitorType = Monitor::MONITOR_TYPE_SMS;
        }
        
        if(!empty($monitor)){
          $this->monitor->remove($monitor->id);
        }
        list($success, $monitorId) = $this->monitor->add($truckId, $user->id, $monitorType);
        if($success){
          list($success, $monitor) = $this->monitor->getById($monitorId);
          $alert="Success: We will notify you when this truck opens up on {$dayOfWeek}.";
        }
      }
      elseif($this->input->post('stop') == 1) {
        if(!empty($monitor)){
          $this->monitor->remove($monitor->id);
          redirect("schedule/track/{$truckId}/{$scheduleId}");
          die();
        }
      }
    }
        
    $this->load->view('header', array('controller' => $this));
    $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'tracks'));
    $this->load->view(
        'schedule/track',
        array(
            'alert' => $alert,
            'truck' => $truck,
            'schedule' => $schedule,
            'dayOfWeek' => $dayOfWeek,
            'monitor' => $monitor,
            'stcalendar' => $this->stcalendar
        )
    );
    $this->load->view('footer');
  }
  
  /**
   * broadcast notifications about truck schedules
   * could be through sms/email/twitter/facebook etc.
   * invoked by cron script with:
   * /usr/bin/php5.5-cli /kunden/homepages/44/d538958209/htdocs/home/index.php schedule notify
   */
  public function notify(){
    
    $this->load->library('monitor', array('controller' => $this));
    $this->load->helper('device_token_helper');
    $this->load->library("parse", array('controller' => $this));
    
    list($success, $notifications) = $this->monitor->getHourlyNotifications();
    if(!$success){
      return false;
    }
    
    foreach($notifications as $notification){
      echo("\n".json_encode($notification) . "\n");
      if($notification->start_time_min <= 5){
        $msg = "{$notification->name} is now open at {$notification->street}";
        $this->postNotificationMsg($notification, $msg);
      }
      elseif($notification->start_time_min <= 30){
        $msg = "{$notification->name} is about to open at {$notification->street}";
        $this->postNotificationMsg($notification, $msg);
      }
      elseif($notification->start_time_min < 60){
        $msg = "{$notification->name} will open at {$notification->start_time_hour}:{$notification->start_time_min} at {$notification->street}";
        $this->postNotificationMsg($notification, $msg);
      }
    }
  }
  
  /**
   * send a notification message to email, sms or social media
   * @param StdClass $notification
   * @param string $msg
   */
  private function postNotificationMsg($notification, $msg){
    echo "SENDING MESSAGE: {$msg}\n";
    
    switch($notification->monitor_type){
      
    	case Monitor::MONITOR_TYPE_EMAIL:
    	  echo "USING EMAIL: {$notification->email}\n";
    	  if(!empty($notification->email)){
            $this->load->library('email');
            $this->email->from('support@snactrac.com', 'SnacTrac');
            $this->email->to($notification->email);
            $this->email->subject("Heads up for {$notification->name}");
            $unsubscribe = base_url("schedule/track/{$notification->truck_id}/{$notification->id}");
            $this->email->message($msg . "\n\nTo unsubscribe from this notification:\n{$unsubscribe}\n--\nsupport@snactract.com\n");
    	    //echo $this->email->print_debugger();
    	    $this->email->send();
    	  }
          break;    	   

    	case Monitor::MONITOR_TYPE_SMS:
    	  echo "USING SMS: {$notification->phone}\n";
    	  if(!empty($notification->phone)){
      	     $this->load->library('twilio');
      	     $this->twilio->sms($notification->phone, "{$msg} -- contact support@snactrac.com to stop");
    	  }
    	  break;
    	  
    	case Monitor::MONITOR_TYPE_PUSH_NOTIFICATION:
    	    list($ret, $dt) = device_token_get_device_token($this, $notification->user_id);
    	    if($ret){
    	       echo "USING PUSH NOTIFICATION FOR DEVICE TOKEN: {$dt['token']}\n";
    	        $this->parse->sendNotification($dt['token'], $msg);
    	    }    	    
    	    break;
    	    

    }
  }
}