<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class Truck extends BaseController {
  
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
    $this->load->library('yelp');
    $this->load->helper('bootstrap_helper');
    $this->load->helper('format_helper');
    $this->load->helper('business_helper');
    $this->load->helper('trucks_helper');
    $this->load->helper('menu_helper');
    $this->load->helper('schedule_helper');
  }
  
  /**
   * Index Page for this controller.
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function index($id = null, $saved = null)
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
    
    $truck = null;
    if(!empty($id)){
      list($ret, $truck) = trucks_get_truck($this, $id);
      if(!$ret){
        $alert = $truck;
      }
      elseif(!empty($saved)){
        $alert = array('msg' => 'Truck saved successfully', 'class' => 'alert-success fade-away');
      }
    }
    
    $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
    $this->form_validation->set_rules('calendar', 'Google Calendar URL', 'trim|xss_clean');
    $this->form_validation->set_rules('desc', 'Description/Info', 'trim|xss_clean');

    if ($this->form_validation->run()){
      // add new truck
      if(empty($truck)){
        $this->db->insert(
            TABLE_TRUCKS,
            array(
                'created_at' => time(),
                'status' => TRUCK_STATUS_ENABLED,
                'business_id' => $business->id,
                'name' => $this->input->post('name'),
                'calendar_url' => $this->input->post('calendar'),
                'description' => $this->input->post('desc'),
                'truck_type' => TRUCK_TYPE_TRUCK
            )
        );
        if($this->db->_error_number()){
          $alert = 'Failed to add the truck, please try again later.';
        }
        else {
          $truckId = $this->db->insert_id();
          trucks_set_truck_keywords($this, $truckId, $this->input->post('keywords'));
          list($success, $ret) = $this->uploadImage($truckId);
          if($success){
            // redirect("/truck/index/{$truckId}/saved");
            redirect("business/trucks");
          }
          else {
            $alert = $ret;
          }
        }
      }
      // update existing truck
      else {
        $truck->name = $this->input->post('name');
        $truck->calendar_url = $this->input->post('calendar');
        $truck->description = $this->input->post('desc');
        unset($truck->keywords);
        $this->db->update(TABLE_TRUCKS, $truck, array('id' => $truck->id));
        if($this->db->_error_number()){
          $alert = 'Failed to update the truck, please try again later.';
        }
        else {
          trucks_set_truck_keywords($this, $truck->id, $this->input->post('keywords'));
          list($success, $ret) = $this->uploadImage($truck->id);
          if($success){
            redirect("/truck/index/{$truck->id}/saved");
          }
          else {
            $alert = $ret;
          }
        }
      }
    }

    $styles = array('plugins/datatables/dataTables.bootstrap.css');
    $scripts = array('plugins/select2/select2.full.min.js', 'plugins/input-mask/jquery.inputmask.js', 'js/admin/custom.js','plugins/datatables/jquery.dataTables.js', 'plugins/datatables/dataTables.bootstrap.js');
     
    // $this->load->view('admin_panel/header', array('controller' => $this));
    // $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'truck'));
    // $this->load->view('truck/truck', array('alert' => $alert, 'business' => $business, 'truck' => $truck));
    // $this->load->view('footer');
    $this->load->view('admin_panel/header', array('controller' => $this, 'user' => $user, 'styles' => $styles, 'scripts' => $scripts));
    $this->load->view('admin_panel/sidebar', array('controller' => $this, 'user' => $user, 'active' => 'truck'));
    $this->load->view('truck/truck', array('alert' => $alert, 'business' => $business, 'truck' => $truck));
    $this->load->view('admin_panel/footer');
  }
  
  /**
   * grab the uploaded image and store it
   * @param int $truckId
   * @return array
   */
  private function uploadImage($truckId){
    
    $this->load->library('upload', array(
        'upload_path' => TRUCK_IMAGE_BASE_PATH,
        'allowed_types' => 'jpg|png',
        'max_size'	=> '2048',
        'max_width'  => '2000',
        'max_height'  => '2000',
    ));
    
    if (!$this->upload->do_upload('pic')) {
      $error = $this->upload->display_errors('','');
      if($error == 'You did not select a file to upload.'){
        return array(true, 'No file, no change');
      }
      return array(false, $error);
    }
    else {
      $data = $this->upload->data();
      if(empty($data['is_image'])){
        return array(false, 'The truck image file is not an image');
      }
      
      // delete old images
      foreach(array('png', 'jpg') as $ext){
        $oldFile = "{$data['file_path']}{$truckId}.{$ext}";
        if(file_exists($oldFile)){
          unlink($oldFile);
        }
      }
      
      // resize and copy new image
      $this->load->library('image_lib', array(
          'source_image' => $data['full_path'],
          'width' => 640,
          'height' => 480,
          'new_image' => "{$truckId}{$data['file_ext']}"
      ));
      if(!$this->image_lib->resize()){
        return array(false, $this->image_lib->display_errors('', ''));
      }
      
      // delete the original uploaded image 
      unlink($data['full_path']);
      
      // var_dump($data);
      return array(true, $data);
    }
  }
  
  /**
   * show the menu items for this truck - via /truck/menu 
   * @param int $truckId
   */
  public function menu($truckId, $notify = ''){
    list($ret, $user) = $this->getLoggedInUser();
    if(empty($ret)){
      error_log("FATAL: Membership::info() failed: " . $user);
      redirect('/user/signin', 'refresh');
      die();
    }
     
    if($user->user_type != Membership::USER_TYPE_AREA_MANGER){
      echo 'Sorry, only a truck owner is allowed to access this page.';
      die();
    }
     
    $alert = '';
    $business = null;
    $truck = null; 
    $menuItems = null;
    list($ret, $business) = business_get_user_business($this, $user);
    if(!$ret){
      $alert = 'Failed to get the business data. Please try again later.';
    }
    else {
      if(!empty($business) and $business->status == BUSINESS_STATUS_DISABLED){
        $alert = 'Your business is not enabled in our system, please contact support to resolve this.';
      }
      
      list($ret, $truck) = trucks_get_truck($this, $truckId);
      if(!$ret){
        $alert = 'Failed to load truck data, please try again later.';
      }
      else {
        $this->form_validation->set_rules('filter', 'Filter', 'trim|xss_clean');
         
        $filter = null;
        if ($this->form_validation->run()){
          $filter = $this->input->post('filter');
        }
        
        list($ret, $menuItems) = menu_get_truck_menu($this, $truck->id);
        if(!$ret){
          $alert = $menuItems;
        }
        else {
          if(empty($menuItems)){
            $trucks = array();
            if(empty($filter)){
              $alert = 'Your truck menu is empty';
            }
            else {
              $alert = 'No menu items match this search criteria.';
            }
          }
        }
      }
    }
    
    if(empty($alert)){
      if($notify == 'saved'){
        $alert = array('msg' => 'Menu item saved successfully', 'class' => 'alert-success fade-away');
      }
      elseif($notify == 'deleted'){
        $alert = array('msg' => 'Menu item deleted successfully', 'class' => 'alert-success fade-away');
      }
    }
    
    $this->load->view('header', array('controller' => $this));
    $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'trucks'));
    $this->load->view('truck/menu', array('alert' => $alert, 'business' => $business, 'truck' => $truck, 'menuItems' => $menuItems));
    $this->load->view('footer');
  }
  
  /**
   * Delete a truck - maps to /truck/delete/<id>
   * @param int $truckId
   */
  public function delete($truckId){
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
    else { 
      list($ret, $truck) = trucks_get_truck($this, $truckId);
      if(!$ret){
        $alert = $truck;
      }
      else {
        trucks_delete_truck($this, $truck->id);
        redirect("/business/trucks/deleted");
      }
    }
    
    $this->load->view('header', array('controller' => $this));
    $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'trucks'));
    $this->load->view('truck/truck', array('alert' => $alert, 'business' => $business, 'truck' => $truck));
    $this->load->view('footer');
  }
  
  /**
   * Manages truck's schedule - /truck/schedule
   * @param int $truckId
   */
  public function schedule($truckId, $notify = null){
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
    else {
      list($ret, $truck) = trucks_get_truck($this, $truckId);
      if(!$ret){
        $alert = $truck;
      }
      else {
        list($ret, $scheduleList) = schedule_get_truck_schedules($this, $truck->id);
        if(!$ret){
          $alert = $scheduleList;
        }
      }
    }
    
    if(empty($scheduleList) or !is_array($scheduleList)){
      $scheduleList = array();
    }
    
    if(!empty($notify)){
      if($notify == 'deleted'){
        $alert = 'Schedule deleted successfully.';
      }
    }
    
    $this->load->view('header', array('controller' => $this));
    $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'trucks'));
    $this->load->view('truck/schedule', array('alert' => $alert, 'business' => $business, 'truck' => $truck, 'schedules' => $scheduleList));
    $this->load->view('footer');
  }
  
  /**
   * returns the java script
   * @param string $script
   */
  public function js($mode, $truckId = null, $scheduleId = null){
    $this->output->set_content_type('application/javascript');
    
    if($mode == 'minimap'){
      
      list($success, $truck) = trucks_get_truck($this, $truckId);
      if(!$success){
        return;
      }
      
      $schedule = null;
      if(!empty($scheduleId)){
        list($success, $schedule) = schedule_get_truck_schedule($this, $truckId, $scheduleId);
        if(!$success){
          return;
        }
        $latitude = $schedule->latitude;
        $longitude = $schedule->longitude;
      }
      else {
        //if no schedule was passed we center the map to 1 market
        $latitude = 37.794251;
        $longitude = -122.394843;
      }
      
      $this->load->view(
        'truck/minimap.js.php',
        array(
          'controller' => $this,
        	'center' => array('latitude' => $latitude, 'longitude' => $longitude),
          'zoom' => 14,
          'schedule' => $schedule,
          'truck' => $truck
        )
      );
    }
    elseif($mode == 'slick'){
      $this->load->view(
          'truck/slick.js.php',
          array()
      );
    }
    elseif($mode == 'embed'){
      $this->load->view(
          'truck/embed.js.php',
          array('text' => $this->session->flashdata('embed-code'))
      );
    }
  }
  
  /**
   * views a truck - truck public page
   * @param int $truckId
   * @param int $scheduleId
   */
  public function view($truckId, $scheduleId = null){

    $alert = null;
    $business = null;
    $truck = null;
    $schedules = null;
    list($success, $ret) = trucks_get_truck($this, $truckId);
    if(!$success){
      $alert = $ret;
    }
    else {
      // var_dump($ret);
      $truck = $ret;
      list($success, $ret) = schedule_get_truck_schedules($this, $truckId);

      if(!$success){
        $alert = $ret;
      }
      else {
        $schedules = $ret;
        list($success, $ret) = business_get_business($this, $truck->business_id);
        if(!$success){
          $alert = $ret;
        }
        else {
          $business = $ret;
        }
      }
    }
    
    if($success){
      
      // if a schedule id is not passed we switch highlight today's day
      $schedule = null;
      if(count($schedules) > 0){
        $day = date('N');
        if(empty($scheduleId)){
          foreach($schedules as $schedule){
            if($schedule->day_of_week == $day){
              $scheduleId = $schedule->schedule_id;
              break;
            }
          }
          // did not find a schedule for today use the first available
          if(empty($schedule)){
            $schedule = $schedule[0];
            $scheduleId = $schedule->schedule_id;
          }
        }
        else {
          foreach($schedules as $schedule){
            if($schedule->schedule_id == $scheduleId){
              break;
            }
          }
        }
      }
      //debug_log($schedule, "FOUND SCHEDULE");
      
      // add some quick data
      $truck->keywords = trucks_get_truck_keywords($this, $truckId);
      $truck->image = trucks_get_truck_image($this, $truckId);
      
      list($success, $menuItems) = menu_get_truck_menu($this, $truck->id, null);
      if(!$success){
        $menuItems = array();
      }
    
      if(!empty($business->yelp)){
        $truck->buzz['yelp'] = $this->yelp->get_business($business->yelp);
      }
    }
    else {
      $schedules = array();
    }
    
    // load up the views
    $this->load->view(
      'header',
      array(
        'controller' => $this,
        'scripts' => array("truck/js/minimap/{$truckId}/{$scheduleId}", "truck/js/slick"),
      )
    );
    $this->load->view(
      'truck/nav',
      array(
        'controller' => $this,
        'schedules' => $schedules,
        'selected' => $schedule
      )
    );
    $this->load->view(
      'truck/view',
      array(
        'alert' => $alert,
        'truck' => $truck,
        'schedule' => $schedule,
        'business' => $business,
        'controller' => $this,
        'menuItems' => $menuItems
      )
    );
    $this->load->view('footer');
  }
  
  /**
   * shows embedable truck map and shedule
   * @param int $truckId
   * @param int $scheduleId
   */
  public function embed($truckId, $scheduleId = null){
  
    $alert = null;
    list($success, $ret) = trucks_get_truck($this, $truckId);
    if(!$success){
      $alert = $ret;
    }
    else {
      $truck = $ret;
      list($success, $ret) = schedule_get_truck_schedules($this, $truckId);
      if(!$success){
        $alert = $ret;
      }
      else {
        $schedules = $ret;
        list($success, $ret) = business_get_business($this, $truck->business_id);
        if(!$success){
          $alert = $ret;
        }
        else {
          $business = $ret;
        }
      }
    }
  
    if($success){
  
      // if a schedule id is not passed we highlight today's day
      $schedule = null;
      if(empty($scheduleId)){
        if(count($schedules) > 0){
          $day = date('N');
          foreach($schedules as $candidate){
            if($candidate->day_of_week == $day){
              $schedule = $candidate;
              break;
            }
          }
          // did not find a schedule for today use the first available
          if(empty($schedule)){
            $schedule = $schedules[0];
            $scheduleId = $schedule->schedule_id;
          }
        }
      }
      else {
        list($success, $schedule) = schedule_get_truck_schedule($this, $truckId, $scheduleId);
        if(!$success){
          $schedule = null;
          // in embed case we suppress the error and just show the map
          $success = true;
          debug_log("CRITICAL: could not get schedule for schedule with id {$scheduleId}");        }
      }
  
      // add some quick data
      $truck->keywords = trucks_get_truck_keywords($this, $truckId);
      $truck->image = trucks_get_truck_image($this, $truckId);
  
      list($success, $menuItems) = menu_get_truck_menu($this, $truck->id, null);
      if(!$success){
        $menuItems = array();
      }
    }
    else {
      $schedules = array();
    }
  
    // load up the views
    $this->load->view(
      'header',
      array(
          'controller' => $this,
          'no_header' => 'true',
          'scripts' => array("truck/js/minimap/{$truckId}/{$scheduleId}"),
      )
    );
    
    $this->load->view(
      'truck/embed',
      array(
          'alert' => $alert,
          'truck' => $truck,
          'schedule' => $schedule,
          'selected' => $schedule,
          'schedules' => $schedules,
          'business' => $business,
          'controller' => $this,
          'menuItems' => $menuItems
      )
    );
    $this->load->view(
      'footer',
      array(
    	 'no_footer' => true
      )
    );
  }
  
  
  /**
   * Displays a page that shows the iframe embed code
   * @param unknown $truckId
   */
  public function embed_code($truckId){
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
    else {
      list($ret, $truck) = trucks_get_truck($this, $truckId);
      if(!$ret){
        $alert = $truck;
      }
    }
    
    $url = base_url("/truck/embed/{$truck->id}");
    $code = <<<EOF
<div>
    <iframe 
      id="snactrac"
      width="100%"
      height="100%"
      border="0px"
      src="{$url}">
    </iframe>
</div>
EOF;
    // TODO - Dec-18-2014 NEED TO FIX THIS $code for the copy to clipboard to work properly.
    // STOPPING WORK PER DISCUSSION WITH REIS TODAY
    // $this->session->set_flashdata('embed-code', $code);
    
    $this->load->view(
      'header',
      array(
        'controller' => $this,
        'scripts' => array("truck/js/embed/{$truckId}")
      )
    );
    
    $this->load->view(
      'sidenav',
       array(
         'controller' => $this,
         'user' => $user,
         'active' => 'trucks'
        )
    );
    $this->load->view(
      'truck/embed-code',
      array(
        'alert' => $alert,
        'business' => $business, 
        'truck' => $truck, 
        'code' => $code));
    
    $this->load->view('footer');
    
    
  }
  
}
