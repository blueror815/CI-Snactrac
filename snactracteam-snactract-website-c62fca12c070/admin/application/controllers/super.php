<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class Super extends BaseController {
  
  /**
   * Default constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('form_helper');
    $this->load->helper('bootstrap_helper');
    $this->load->library('location');
  }

	/**
	 * Index Page for this controller.
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	  die();
	}
	
	/**
	 * Dashboard welcome page for super admin
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function dashboard()
	{
	  $userId = $this->session->userdata('user_id');
	  if(empty($userId)){
	    redirect('/user/signin', 'refresh');
	  }
	  
	  list($ret, $userInfo) = $this->membership->info($userId);
	  if(empty($ret)){
	    error_log("FATAL: Membership::info() failed: " . $userInfo);
	    redirect('/user/signin', 'refresh');
	  }
	  
	  $this->load->view('header', array('controller' => $this));
	  $this->load->view('sidenav', array('controller' => $this, 'user' => $userInfo, 'active' => 'dashboard'));
	  $this->load->view('super/dashboard', array('alert' => ''));
	  $this->load->view('footer');
	  
	}
	
	/**
	 * users mgmt page for super adimin
	 */
	public function users(){
	  
	  $userId = $this->session->userdata('user_id');
	  if(empty($userId)){
	    redirect('/user/signin', 'refresh');
	  }
	   
	  list($ret, $userInfo) = $this->membership->info($userId);
	  if(empty($ret)){
	    error_log("FATAL: Membership::info() failed: " . $userInfo);
	    redirect('/user/signin', 'refresh');
	  }
	  
	  $alert = '';
	  list($ret, $results) = $this->getUsers(Membership::USER_TYPE_REGULAR_USER, $this->input->post('filter'));
	  if(empty($ret)){
	    $alert = 'Failed to load the user data';
	  }
	  
	  $users = array();
	  foreach($results as $row){
	    $metro = $this->location->getMetroInfo($row->metro);
	    $users[$row->user_id] = array(
	    	'name' => $row->name,
	      'email' => $row->email,
	      'type' => $row->user_type,
	      'metro' => $metro['title']
	    );
	  }
	  
	  $this->load->view('header', array('controller' => $this));
	  $this->load->view('sidenav', array('controller' => $this, 'user' => $userInfo, 'active' => 'users'));
	  $this->load->view('super/users', array('users' => $users, 'alert' => $alert));
	  $this->load->view('footer');
	}
	
	/**
	 * trucks mgmt page for super adimin
	 */
	public function trucks(){
	  $userId = $this->session->userdata('user_id');
	  if(empty($userId)){
	    redirect('/user/signin', 'refresh');
	  }
	   
	  list($ret, $userInfo) = $this->membership->info($userId);
	  if(empty($ret)){
	    error_log("FATAL: Membership::info() failed: " . $userInfo);
	    redirect('/user/signin', 'refresh');
	  }
	  
	  $alert = '';
	  list($ret, $results) = $this->getUsers(Membership::USER_TYPE_AREA_MANGER, $this->input->post('filter'));
	  if(empty($ret)){
	    $alert = 'Failed to load the user data';
	  }
	  
	  $users = array();
	  foreach($results as $row){
	    $metro = $this->location->getMetroInfo($row->metro);
	    $users[$row->user_id] = array(
	    	'name' => $row->name,
	      'email' => $row->email,
	      'type' => $row->user_type,
	      'metro' => $metro['title']
	    );
	  }
	  
	  $this->load->view('header', array('controller' => $this));
	  $this->load->view('sidenav', array('controller' => $this, 'user' => $userInfo, 'active' => 'trucks'));
	  $this->load->view('super/trucks', array('users' => $users, 'alert' => $alert));
	  $this->load->view('footer');
	}
	
	/**
	 * Helper function to get users of a particular type with filter
	 * @param string $filter
	 */
	private function getUsers($type, $filter = null, $start = 0, $skip = 0){

	  $this->db->select(Membership::TABLE_USERS . '.id as user_id, name, email, user_type, metro');
	  $this->db->from(Membership::TABLE_USERS);
	  $this->db->join(
	      Location::TABLE_USER_LOCATIONS,
	      Membership::TABLE_USERS . '.id = ' . Location::TABLE_USER_LOCATIONS.'.user_id',
	      'left'
	  );
	  $this->db->join(
	      Location::TABLE_LOCATIONS,
	      Location::TABLE_USER_LOCATIONS . '.location_id = ' . Location::TABLE_LOCATIONS . '.id',
	      'left'
	  );
	  $this->db->where(array('user_type' => $type));
	  if(!empty($filter)){
	    $this->db->like('name', $filter);
	  }
	  $query = $this->db->get();
    $ret = $query->result();
	  return array(true, $ret);
	}
}