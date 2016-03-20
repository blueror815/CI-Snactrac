<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_include_path(
  get_include_path() .
  PATH_SEPARATOR .
  APPPATH .
  'libraries' .
  PATH_SEPARATOR .
  '/homepages/44/d538958209/htdocs/home/application/libraries'
);

class BaseController extends CI_Controller {
  
  /**
   * Default constructor
   */
  public function __construct()
  {
    parent::__construct();
    
    $this->load->library(
        "membership",
        array(
            'db' => $this->db,
            'email' => $this->email,
            'session' => $this->session
        )
    );
  }
  
  /**
   * Gets the logged in user
   * @return array
   */
  protected function getLoggedInUser(){
    $userId = $this->session->userdata('user_id');
    if(empty($userId)){
      return array(false, null);
    }
     
    list($ret, $user) = $this->membership->info($userId);
    if(empty($ret)){
      error_log("FATAL: Membership::info() failed: " . $user);
      return array(false, null);
    }
    
    return array(true, $user);    
  }
  
  /**
   * Returns true of this is a post request
   * @return boolean
   */
  protected function isPostRequest(){
    return ($_SERVER['REQUEST_METHOD'] === 'POST');
  }
}
