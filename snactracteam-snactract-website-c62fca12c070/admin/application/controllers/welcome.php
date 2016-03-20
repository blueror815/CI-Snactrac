<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class Welcome extends BaseController {

  /**
   * Default constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->helper('bootstrap_helper');
    $this->load->library('session');
  }
    
	/**
	 * Index Page for this controller.
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
  public function index($action = null, $reason = null)
	{
	  	$alert = '';
	  	$query = '';
	  	if(!empty($action)){
	  	  	if($action == 'err'){
		  	    switch($reason){
		  	    	case 'loc':
		  	    	  $alert = 'Sorry, we were not able to locate you.';
		  	    	  break;
		  	    	  
		  	    	case 'metro':
		  	    	  $alert = 'Sorry, your location is not supported in our system.';
		  	    	  break;
		  	    	  
		  	    	case 'nope':
		  	    	  $alert = 'Sorry, no trucks are open at your location at this time.';
		  	    	  break;
		  	    	
		       	  default:
		  	    	  $alert = '';
		  	    	  break;
		  	    }
	  	  	}
	  	  	elseif($action == 'search'){
		  	    $query = $reason;
	  	  	}
	  	}	

		$this->load->view('header', array('controller' => $this, 'scripts' => 'welcome/js/location', 'active' => 'vender'));
		$this->load->view('banner', array('alert' => $alert, 'query' => $query));
		$this->load->view('discover');
		$this->load->view('track');
		//$this->load->view('projects');
		//$this->load->view('pricing');
		//$this->load->view('actioncall');
		// $this->load->view('index');
		// $this->load->view('aboutus');
		$this->load->view('order');
		$this->load->view('footer');
	  // $this->load->view('welcome/home_user');
	}
	
	/**
	 * Loads javascript
	 * @param string $script
	 */
	public function js($script){
	  
	  if(empty($script)){
	    die();
	  }
	  
	  $this->output->set_content_type('application/javascript');
	  
	  if($script == 'location'){
	  
	    $this->load->view(
	        'welcome/location.js.php',
	        array(
	            'controller' => $this,
	        )
	    );
	  }
	}
	
}

