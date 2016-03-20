<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class User extends BaseController {

  /**
   * Default constructor 
   */
  public function __construct()
  {
    parent::__construct();
    
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->helper('bootstrap_helper');
  }

  /**
   * maps to /user/signup 
   */
  public function signup($business = '')
  {
    $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[confirm_password]|xss_clean');
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
    
    // check box rule handling issue refer to below url for details
    // https://github.com/EllisLab/CodeIgniter/issues/949
    $this->form_validation->set_rules('truck_owner', 'truck owner', '');
    
    $msg = null;
    if ($this->form_validation->run()){

      $truckOwner = $this->input->post('truck_owner');
      list($success, $msg) = $this->membership->register(
        $this->input->post('name'),
        $this->input->post('email'),
        $this->input->post('password'),
        empty($truckOwner) ? Membership::USER_TYPE_REGULAR_USER : Membership::USER_TYPE_AREA_MANGER
      );
      
      if($success){
        //redirect('/welcome/index', 'refresh');
      }
    }
    else {
      $msg = validation_errors();
    }
    
    $this->load->view('header', array('controller' => $this, 'active' => 'signup'));
    $this->load->view('user/signup', array('alert' => $msg, 'business' => $business));
    $this->load->view('footer');
  }
  
  /**
   * maps to /user/signin
   */
  public function signin()
  {
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
    
    $msg = null;
    if ($this->form_validation->run()){
      
      list($success, $msg) = $this->membership->login(
        $this->input->post('email'),
        $this->input->post('password')
      );

      if($success){
        
        $userId = $this->session->userdata('user_id');
        if(!empty($userId)){
          list($success, $user) = $this->membership->info($userId);
          if($success){
            if($user->user_type == Membership::USER_TYPE_SUPER_ADMIN){
              redirect('/super/dashboard', 'refresh');
            }
            elseif($user->user_type == Membership::USER_TYPE_AREA_MANGER){
              redirect('/business/dashboard', 'refresh');
            }
            else {
              redirect('/welcome/index', 'refresh');
            }
          }
        }
      }
    }
    else {
      $msg = validation_errors();
    }

    $this->load->view('header', array('controller' => $this, 'active' => 'signin'));
    $this->load->view('user/signin', array('alert' => $msg));
    $this->load->view('footer');
  }
  
  /**
   * handles /user/signout
   */
  public function signout(){
    
    $this->membership->logout();
    redirect('/welcome/index', 'refresh');
    
    //$this->load->view('header', array('controller' => $this));
    //$this->load->view('user/signout', array('alert' => array('msg' => 'Success! You are now signed out of snactrac.com.', 'class' => 'alert-success')));
    //$this->load->view('footer');
  }
  
  /**
   * send verification email
   * @param string $id
   */
  public function resend($id = null){
    
    $email = '';
    if(!empty($id)){
      $query = $this->db->get_where(Membership::TABLE_USERS, array('id' => $id),	1, 0);
		  if($query->num_rows() > 0){
			  $user = array_shift($query->result());
			  if(!empty($user->email)){
			    $email = $user->email;
			  }
		  }
    }
    
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    
    $msg = null;
    if ($this->form_validation->run()){
    
      list($success, $msg) = $this->membership->resend($this->input->post('email'));
      if($success){
        $msg = array('msg' => $msg, 'class' => 'alert-success');
      }
    }
    else {
      $msg = validation_errors();
    }
        
    $this->load->view('header', array('controller' => $this, 'active' => 'signin'));
    $this->load->view('user/resend', array('email' => $email, 'alert' => $msg));
    $this->load->view('footer');    
    
  }
  
  /**
   * forgot password handler
   */
  public function forgot(){
    
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    
    $msg = null;
    if ($this->form_validation->run()){
    
      list($success, $msg) = $this->membership->forgot($this->input->post('email'));
      if($success){
        $msg = array('msg' => $msg, 'class' => 'alert-success');
      }
    }
    else {
      $msg = validation_errors();
    }
    
    $this->load->view('header', array('controller' => $this, 'active' => 'signin'));
    $this->load->view('user/forgot', array('alert' => $msg));
    $this->load->view('footer');
  }
  
  /**
   * handles /user/confirm/id/code
   * @param int $userId
   * @param string $code
   */
  public function confirm($userId, $code){
    
    $msg = '';
    if(empty($userId) or empty($code)){
      $msg = 'Something isn\'t right, We will need to send you another email. Contact support@snactrac.com if the problem persists.';
    }
    else {
      list($success, $msg) = $this->membership->confirm(intval($userId), $code);
      if($success){
        $msg = array('msg' => $msg, 'class' => 'alert-success');
      }
    }
    
    $this->load->view('header', array('controller' => $this, 'active' => 'signin'));
    $this->load->view('user/confirm', array('alert' => $msg));
    $this->load->view('footer');
  }
  
  /**
   * handles /user/dashboard for all users
   */
  public function dashboard(){
    $userId = $this->session->userdata('user_id');
    if(empty($userId)){
      redirect('/user/signin', 'refresh');
    }
    
    list($ret, $userInfo) = $this->membership->info($userId);

    if(empty($ret)){
      error_log("FATAL: Membership::info() failed: " . $userInfo);
      redirect('/user/signin', 'refresh');
    }
    
    $this->load->view('header', array('controller' => $this, 'active' => 'vender', 'scripts' => 'welcome/js/location'));
    // $this->load->view('sidenav', array('controller' => $this, 'user' => $userInfo, 'active' => 'welcome'));
    // $this->load->view('user/dashboard', array('alert' => ''));
    $this->load->view('banner', array('alert' => ''));
    $this->load->view('discover');
    $this->load->view('track');
    //$this->load->view('projects');
    //$this->load->view('pricing');
    //$this->load->view('actioncall');
    // $this->load->view('index');
    // $this->load->view('aboutus');
    $this->load->view('order');
    $this->load->view('footer');
    // $this->load->view('footer');
  }
  
  /**
   * handles /user/profile for all users
   * @param string $submit
   */
  public function profile($submit = null){
    
    /**
     * Check for basic things
     */
    $userId = $this->session->userdata('user_id');
    if(empty($userId)){
      redirect('/user/signin', 'refresh');
    }
    
    $alert = null;
    list($ret, $userInfo) = $this->membership->info($userId);
    if(empty($ret)){
      $alert = 'Failed to load your information. Please try again later.';
    }
    
    /**
     * Process form subission if needed
     */
    if($ret and !empty($submit)){
      if($submit == 'basic'){
        list($ret, $alert) = $this->processBasicProfileUpdate($userInfo);
        if($ret){
          list($ret, $userInfo) = $this->membership->info($userInfo->id);
          if(empty($ret)){
            $alert = 'Failed to load your information. Please try again later.';
          }
        }
      }
      else if($submit == 'settings'){
        list($ret, $alert) = $this->processSettingsUpdate($userInfo);
      }
      else if ($submit == 'delete'){
        list($ret, $alert) = $this->processAccountDelete($userInfo);
      }
    }
    
    /**
     * Load the views
     */
    // $this->load->view('header', array('controller' => $this));
    // $this->load->view('sidenav', array('controller' => $this, 'user' => $userInfo, 'active' => 'profile'));
    // $this->load->view('user/profile', array('userInfo' => $userInfo, 'alert' => $alert, 'mode' => $submit));
    // $this->load->view('footer');

    $scripts = array('plugins/select2/select2.full.min.js', 'plugins/input-mask/jquery.inputmask.js', 'js/admin/custom.js');

    $this->load->view('admin_panel/header', array('controller' => $this, 'user' => $userInfo, 'scripts' => $scripts));
    if($submit == 'basic') {
      $this->load->view('admin_panel/sidebar', array('controller' => $this, 'user' => $userInfo, 'active' => 'profile'));
      $this->load->view('user/profile', array('userInfo' => $userInfo, 'alert' => $alert, 'mode' => $submit));  
    } else if ($submit == 'settings') {
      $this->load->view('admin_panel/sidebar', array('controller' => $this, 'user' => $userInfo, 'active' => 'settings'));
      $this->load->view('user/settings', array('userInfo' => $userInfo, 'alert' => $alert, 'mode' => $submit));
    }
    $this->load->view('admin_panel/footer');
  }
  
  /**
   * Process the basic info form submission
   */
  private function processBasicProfileUpdate($userInfo){
    $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[confirm_password]|xss_clean');
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
    $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
    
    if (!$this->form_validation->run()){
      return array(false, validation_errors());
    }
    
    $msg = null;
    list($success, $msg) = $this->membership->update(
        $userInfo->id,
        $this->input->post('name'),
        $this->input->post('email'),
        $this->input->post('password'),
        $this->input->post('phone')
    );
    
    if($success){
      return array(true, array('msg' => $msg, 'class' => 'alert-success'));
    }
    
    return array(false, $msg);
  }
  
  /**
   * Process the Settings form submission
   */
  private function processSettingsUpdate($userInfo){
    
  }
  
  /**
   * Process the delete account form submission
   */
  private function processAccountDelete($userInfo){
    $confirmDelete = $this->input->post('account_delete');
    if(empty($confirmDelete)){
      return array(false, 'Please confirm delete of your account by selecting the check box');
    }
    
    list($ret, $alert) = $this->membership->delete($userInfo->id);
    if(!$ret){
      return array(false, $alert);
    }
    
    $userId = $this->session->userdata('user_id');
    if(!empty($userId) and $userId == $userInfo->id){
      $this->session->sess_destroy();
    }
    
    return array(true, 'Account has been deleted successfully');
  }
}

