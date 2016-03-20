<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class Search extends BaseController {

  /**
   * Default constructor 
   */
  public function __construct()
  {
    parent::__construct();
    
    $this->load->helper('form');
    $this->load->helper('schedule');
    $this->load->helper('trucks');
    $this->load->helper('business');
    $this->load->helper('menu');
    $this->load->helper('assets');
    $this->load->library('form_validation');
    $this->load->library('location');
    $this->load->library('session');
    $this->load->library('yelp');
  }

  /**
   * Index Page for this controller.
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function index()
  {
    // check if this is a post request - redirect to home if not
    if($this->isPostRequest() == false){
      redirect("/welcome/index");
      return;
    }
    
    // check if location was passed in - if not use IP to geo locate - fail if we cannot
    $latitude = $this->input->post("latitude");
    $longitude = $this->input->post("longitude");
    if(empty($latitude) or empty($longitude)){
      list($latitude, $longitude) = $this->location->geocodeByIp($this->input->ip_address());
      if(empty($latitude) or empty($longitude)){
        redirect("/welcome/index/err/loc");
        return;
      }
    }
    else {
      //debug_log("GOT LOCATION: {$latitude},{$longitude}");
    }
    
    // find metro area
    $metro = $this->location->findMetroArea($latitude, $longitude);
    if($this->location->checkMetro($metro) == false){
      redirect("welcome/index/err/metro");
      return;
    }
    
    // tracking all searches
    $query = htmlspecialchars($this->input->post('search'));
    log_message('info', "SEARCHED FOR [{$query}] @({$latitude},{$longitude})");
    
    // search for trucks matching the query
    $alert = '';
    $results = $this->searchTrucksByLocation($latitude, $longitude, $metro, $this->input->post('search'), $query);
    if(empty($results)){
      redirect("welcome/index/err/nope");
      return;
    }
    $this->session->set_userdata('search_query', $query);
    $this->session->set_userdata('search_results', $results);
    $this->session->set_userdata('center', array('latitude' => $latitude, 'longitude' => $longitude));
    
    // load up the views
    $this->load->view(
      'header',
      array(
        'controller' => $this,
        'scripts' => array('search/js/results'),
        'center' => array('latitude' => $latitude, 'longitude' => $longitude)
      )
    );
    $this->load->view(
      'search/nav',
      array(
        'controller' => $this,
        'results' => $results,
        'query' => $query
      )
    );
    $this->load->view(
      'search/results',
      array(
          'alert' => $alert,
          'results' => $results,
          'query' => $query,
          'center' => array('latitude' => $latitude, 'longitude' => $longitude)
      )
    );
    $this->load->view('footer');
  }
  
  
  /**
   * returns the java script
   * @param string $script
   */
  public function js($script){
    
    if(empty($script)){
      return;
    }
    
    $this->output->set_content_type('application/javascript');
    
    if($script == 'results'){
      $this->load->view(
        'search/results.js.php',
        array(
         'controller' => $this,
      	 'center' => $this->session->userdata('center'),
         'zoom' => 14,
         'results' => $this->session->userdata('search_results')
        )
      );
    }
    elseif($script == 'minimap'){
      $schedule = $this->session->userdata('truck_schedule');
      $truck = $this->session->userdata('truck');
      $this->load->view(
        'search/minimap.js.php',
        array(
          'controller' => $this,
        	'center' => array('latitude' => $schedule->latitude, 'longitude' => $schedule->longitude),
          'zoom' => 14,
          'schedule' => $schedule,
          'truck' => $truck
        )
      );
    }
    elseif($script = 'slick'){
      $this->load->view(
          'truck/slick.js.php',
          array()
      );
    }
  }
  
  /**
   * shows details of a business in a search context
   * @param int $truckId
   * @param int $scheduleId
   */
  public function details($truckId, $scheduleId){
    
    // if we dont have search results redirect to home page
    $results = $this->session->userdata('search_results');
    if(empty($results)){
      //debug_log("could not get results from session data, redirecting to home");
      redirect("/welcome/index");
    }
    
    $query = $this->session->userdata('search_query');
    if(empty($query)){
      // its okay - query might be empty
      //debug_log("could not get query from session data, redirecting to home");
      //redirect("/welcome/index");
    }
    
    $alert = '';
    list($success, $truck) = trucks_get_truck($this, $truckId);
    if(!$success){
      $alert = $truck;
    }
    else {
      list($success, $schedule) = schedule_get_truck_schedule($this, $truckId, $scheduleId);
      if(!$success){
        $alert = $schedule;
      }
      else {
        list($success, $business) = business_get_business($this, $truck->business_id);
        if(!$success){
          $alert = $business;
        }
      }
    }

    if($success){
      // add some quick data
      $truck->location_summary = $this->location->summary($schedule);
      $truck->keywords = trucks_get_truck_keywords($this, $truckId);
      $truck->image = trucks_get_truck_image($this, $truckId);
      
      list($success, $menuItems) = menu_get_truck_menu($this, $truck->id, null);
      if(!$success){
        $menuItems = array();
      }
      
      if(!empty($business->yelp)){
        $truck->buzz['yelp'] = $this->yelp->get_business($business->yelp);
      }
      
      $this->session->set_userdata('truck_schedule', $schedule);
      $this->session->set_userdata('truck', $truck);
    }
    
    // load up the views
    $this->load->view(
      'header',
      array(
        'controller' => $this,
        'scripts' => array('search/js/minimap', 'search/js/slick'),
      )
    );
    $this->load->view(
      'search/nav',
      array(
        'controller' => $this,
        'results' => $results,
        'query' => $query,
        'selected' => $scheduleId
      )
    );
    $this->load->view(
      'search/details',
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
   * search the db for open trucks
   */
  private function searchTrucksByLocation($latitude, $longitude, $metro, $query){
  
    // *** BIG TODO ***
    // if query is passed look for truck ids base on query first
    // then use in stmt to get only the trucks that match
    // if no trucks match then get everything
    // for now just get all trucks.
    $this->db->select(
        TABLE_TRUCK_SCHEDULES.'.id as schedule_id,
        truck_id,'.
        TABLE_TRUCKS.'.created_at,
        name,
        start_time_hour,
        start_time_min,
        end_time_hour,
        end_time_min,
        location_id,
        notes,
        street,
        street2,
        city,
        state,
        zip,
        country,
        metro,
        latitude,
        longitude'
    );
    $this->db->from(TABLE_TRUCK_SCHEDULES);
    $this->db->join(
        TABLE_TRUCKS,
        TABLE_TRUCKS . '.id = ' . TABLE_TRUCK_SCHEDULES . '.truck_id'
    );
    $this->db->join(
        Location::TABLE_LOCATIONS,
        TABLE_TRUCK_SCHEDULES . '.location_id = ' . Location::TABLE_LOCATIONS . '.id'
    );
    $hour = intval(date('G'));
    $min = intval(date('i'));
    $day = intval(date('N'));
    $this->db->where(
        array(
            'metro' => $metro,
            'day_of_week' => $day,
            'start_time_hour <=' => $hour,
            'end_time_hour >=' => $hour,
        )
    );
    if(!empty($query)){
      //TODO IMPLEMENT QUERY FILTERING
    }
    $query = $this->db->get();
    debug_log_sql($this->db->last_query());
  
    $results = array();
    foreach($query->result() as $truck){
      // menu
      /*list($ret, $menu) = menu_get_truck_menu($this, $truck->truck_id);
       if($ret){
      $truck->menu = $menu;
      }*/
  
      //distance
      $truck->distance = $this->location->distance(
          array('latitude' => $latitude, 'longitude' => $longitude),
          array('latitude' => $truck->latitude, 'longitude' => $truck->longitude)
      ) + mt_rand(1,10);
  
      // other quick access data
      $truck->location_summary = $this->location->summary($truck);
      $truck->keywords = trucks_get_truck_keywords($this, $truck->truck_id);
      $truck->image = trucks_get_truck_image($this, $truck->truck_id);
  
      $results[] = $truck;
    }
  
    //debug_log($results, __METHOD__);
    return $results;
  }
  
}