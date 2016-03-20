<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class Business extends BaseController {
  
  /**
   * Default constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->helper('bootstrap_helper');
    $this->load->helper('format_helper');
    $this->load->helper('business_helper');
    $this->load->helper('trucks_helper');
  }

	/**
	 * Index Page for this controller.
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

	  	$this->load->view('header', array('controller' => $this, 'business' => true, 'active' => 'snacker'));
		$this->load->view('banner-snack');
	  	$this->load->view('business/visibility');
	  	$this->load->view('business/schedule');
		$this->load->view('order');
		$this->load->view('business/social_media');
		$this->load->view('footer');
	}
	
	
	/**
	 * maps to /business/dashboard
	 */
	public function dashboard()
	{
	  	list($ret, $user) = $this->getLoggedInUser();
	  	if(empty($ret)){
		    error_log("FATAL: Membership::info() failed: " . $user);
		    redirect('/user/signin', 'refresh');
		    die();
	  	}

	  	if($user->user_type != Membership::USER_TYPE_AREA_MANGER){
		    echo 'Sorry: only a truck owner is allowed to access this page.';
		    die();
	  	}
	
	  	$alert = '';
	  	list($ret, $business) = business_get_user_business($this, $user);
	  	if(!$ret){
	    	$alert = 'Failed to get the business data. Please try again later.';
	  	}

	  	$styles = array('plugins/morris/morris.css', 'plugins/jvectormap/jquery-jvectormap-1.2.2.css', 'plugins/datepicker/datepicker3.css', 'plugins/daterangepicker/daterangepicker-bs3.css');
	  	$scripts = array('plugins/morris/morris.js', 'plugins/sparkline/jquery.sparkline.min.js', 'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js', 'plugins/jvectormap/jquery-jvectormap-world-mill-en.js', 'plugins/knob/jquery.knob.js','js/admin/dashboard.js');
	   
	  	$this->load->view('admin_panel/header', array('controller' => $this, 'user' => $user, 'styles' => $styles, 'scripts' => $scripts));
	  	$this->load->view('admin_panel/sidebar', array('controller' => $this, 'user' => $user, 'active' => 'dashboard'));
	  	$this->load->view('business/dashboard', array('alert' => $alert, 'business' => $business));
	  	$this->load->view('admin_panel/footer');
	}
	
	/**
	 * handles /business/business page
	 */
	public function profile(){
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
	  list($ret, $business) = business_get_user_business($this, $user);
	  if(!$ret){
	    $alert = 'Failed to get the business data. Please try again later.';
	  }
	
	  if(!empty($business) and $business->status == BUSINESS_STATUS_DISABLED){
	    $alert = 'Your business is not enabled in our system, please contact us to resolve this.';
	  }
	
	  $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
	  $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
	  $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
	  $this->form_validation->set_rules('website', 'Website', 'trim|xss_clean');
	  $this->form_validation->set_rules('twitter', 'Twitter', 'trim|xss_clean');
	  $this->form_validation->set_rules('facebook', 'Facebook', 'trim|xss_clean');
	  $this->form_validation->set_rules('yelp', 'Yelp', 'trim|xss_clean');
	  $this->form_validation->set_rules('desc', 'Description/Info', 'trim|xss_clean');
	
	  if ($this->form_validation->run()){
	    $business->name = $this->input->post('name');
	    $business->phone = $this->input->post('phone');
	    $business->email = $this->input->post('email');
	    $business->website = $this->input->post('website');
	    $business->twitter = $this->input->post('twitter');
	    $business->facebook = $this->input->post('facebook');
	    $business->yelp = $this->input->post('yelp');
	    $business->description = $this->input->post('desc');
	
	    $this->db->update(TABLE_BUSINESS, $business, array('id' => $business->id));
	    if($this->db->_error_number()){
	      $alert = 'Failed to update the business info, please try again later.';
	    }
	  }
      
      $styles = array('');
      $scripts = array('plugins/select2/select2.full.min.js', 'plugins/input-mask/jquery.inputmask.js', 'js/admin/custom.js');

      $this->load->view('admin_panel/header', array('controller' => $this, 'user' => $user, 'scripts' => $scripts));
	  $this->load->view('admin_panel/sidebar', array('controller' => $this, 'user' => $user, 'active' => 'business'));
	  $this->load->view('business/profile', array('alert' => $alert, 'business' => $business));
	  $this->load->view('admin_panel/footer');
	}
	
	/**
	 * Shows list of trucks via /business/trucks
	 */
	public function trucks(){
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
	  list($ret, $business) = business_get_user_business($this, $user);
	  if(!$ret){
	    $alert = 'Failed to get the business data. Please try again later.';
	  }
	  
	  if(!empty($business) and $business->status == BUSINESS_STATUS_DISABLED){
	    $alert = 'Your business is not enabled in our system, please contact support to resolve this.';
	  }
	  
	  $this->form_validation->set_rules('filter', 'Filter', 'trim|xss_clean');
	  
	  $filter = null;
	  if ($this->form_validation->run()){
	    $filter = $this->input->post('filter');
	  }
	  
	  $trucks = trucks_get_business_trucks($this, $business->id, $filter);
	  if(empty($trucks)){
	    $trucks = array();
	    if(empty($filter)){	
	      $alert = 'You do not have any trucks yet.';	      
	    }
	    else {
	      $alert = 'No trucks match this search criteria.';
	    }
	  }

	  $styles = array('plugins/datatables/dataTables.bootstrap.css');
	  $scripts = array('plugins/select2/select2.full.min.js', 'plugins/input-mask/jquery.inputmask.js', 'js/admin/custom.js','plugins/datatables/jquery.dataTables.js', 'plugins/datatables/dataTables.bootstrap.js');
	   
	  $this->load->view('admin_panel/header', array('controller' => $this, 'user' => $user, 'styles' => $styles, 'scripts' => $scripts));
	  $this->load->view('admin_panel/sidebar', array('controller' => $this, 'user' => $user, 'active' => 'truck'));
	  $this->load->view('business/trucks', array('alert' => $alert, 'business' => $business, 'trucks' => $trucks));
	  $this->load->view('admin_panel/footer');
	}
	
	/**
	 * Payment handling - /business/payment
	 */
	public function payment(){
	  // echo '<h1>Payments coming soon</h1>';
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

	  $this->load->view('admin_panel/header', array('controller' => $this, 'user' => $user));
	  $this->load->view('admin_panel/sidebar', array('controller' => $this, 'user' => $user, 'active' => 'payment'));
	  $this->load->view('business/payment', array('alert' => 'Will coming soon', 'business' => '$business', 'payment' => ''));
	  $this->load->view('admin_panel/footer');
	}
	
}
