<?php
/**
 * Implements functions like signin/signup/signout etc. 
 * @author Naveed Khan
 */
class Membership {
	
	/**
	 * Tables
	 */
	const TABLE_USERS = 'membership_users';
	const TABLE_PROFILE = 'membership_profiles';
	const TABLE_VERIFICATION_CODES = 'membership_verification_codes';
	
	/**
	 * salt used to hash the passwords
	 */
	const SERVER_SALT = '2a9QB9lxnd';
	
	/**
	 * Sources for user id
	 */
	const USER_SOURCE_UNKNOWN  =  0;   // unknown/invalid
	const USER_SOURCE_LOCAL    =  100; // created locally in our system
	const USER_SOURCE_FACEBOOK =  200; // facebook
	const USER_SOURCE_TWITTER  =  300; // twitter
	const USER_SOURCE_GOOGLE   =  400; // google
	
	/**
	 * User statuses
	 */
	const USER_STATUS_DISABLED   =  0;
	const USER_STATUS_ENABLED    =  100;
	const USER_STATUS_UNVERIFIED =  200;
	const USER_STATUS_LOCKED     =  300;
	const USER_STATUS_DELETED    =  400;
	
	/**
	 * User types - used in user profile and may be application dependent
	 */
	const USER_TYPE_UNKNOWN = 0;
	const USER_TYPE_SUPER_ADMIN = 100;
	const USER_TYPE_AREA_MANGER = 200;
	const USER_TYPE_REGULAR_USER = 300;
	
	/**
	 * Email verification constants
	 */
	const VERIFY_FROM_EMAIL = 'support@snactrac.com';
	const VERIFY_FROM_NAME = 'SnacTrac Support';
	
	const MIN_PSWD_LENGTH = 8;
	
	/**
	 * private helpers - normally passed in through controller
	 */
	private $db = null;
	private $mailer = null;
	private $session = null;
	
	/**
	 * Constructor
	 * @param array $params
	 */
	public function __construct($params){
		$this->db = $params['db'];
		$this->mailer = $params['email'];
		$this->session = $params['session'];
	}
	
	/**
	 * login a user and create a session
	 */
	public function login($email, $pswd){
		
		if(empty($email)){
			return array(false, 'Email is required.');
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array(false, 'Email address is invalid.');
		}
		
		if(empty($pswd) or strlen($pswd) < self::MIN_PSWD_LENGTH){
			return array(false, 'Invalid email or password.');
		}
		
		$params = array(
				'email' => $email
		);
		$query = $this->db->get_where(self::TABLE_USERS, $params,	1, 0);
		if($query->num_rows() < 1){
			return array(false, 'Invalid email or password.');
		}
		
		$row = array_shift($query->result());
		if(!password_verify($pswd . self::SERVER_SALT, $row->pswd)){
		  return array(false, 'Invalid email or password.');
		}
		
		if (password_needs_rehash($row->pswd, PASSWORD_BCRYPT)){
		  $row->pswd = password_hash($pswd . self::SERVER_SALT, PASSWORD_BCRYPT);
		  $this->db->update(self::TABLE_USERS, $row, array('id' => $row->id)); 
		}
		
		if($row->status == self::USER_STATUS_UNVERIFIED){
		  $msg = "You must verify your email before you can sign in. " .
		         "Use the link we sent to <b>" . $row->email . "</b>" .
		         "<br/>If you did not recieve the email we can always " .
		         "<a href=\"" . base_url("/user/resend/" . $row->id). "\">send another one</a>.";
		  return array(false, $msg);
		}
		
		if($row->status != self::USER_STATUS_ENABLED){
		  return array(false, 'Your account has been disabled, please contact support to resolve this issue.');
		}
		
		$this->session->set_userdata('user_id', $row->id);
		$this->session->set_userdata('user_name', $row->name);
		
		return array(true, 'Success');
	}
	
	/**
	 * logout a user and desrtoy the session
	 */
	function logout(){
		$this->session->sess_destroy();
	}
	
	/**
	 * register a new user
	 * @param string $email
	 * @param string $pswd
	 * @param string $name
	 * @param string $type
	 * @return multitype:boolean string
	 */
	public function register($name, $email, $pswd, $type){
		
		if(empty($email)){
			return array(false, 'Email is required.');
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array(false, 'Email address is invalid.');
		}
		
		$query = $this->db->get_where(self::TABLE_USERS, array('email' => $email),	1, 0);
		if($query->num_rows() > 0){
			return array(false, 'Sorry, a user with this email already exists. Click <a href="'.base_url().'user/signin">here</a> to login.');
		}
		
		if(empty($pswd) or strlen($pswd) < 8){
			return array(false, 'Password must be atleast 8 characters long');
		}
						
		// Start a transaction since we are going to update multiple tables
		$this->db->trans_start();
		
		// Insert into the users table
		$hash = password_hash($pswd . self::SERVER_SALT, PASSWORD_BCRYPT);
		
		$this->db->insert(
			self::TABLE_USERS,
			array(
				'source' => self::USER_SOURCE_LOCAL,
				'email' => $email,
			  	'name' => $name,
				'pswd' => $hash,
			  	'phone' => null,
				'created_at' => time(),
				'status' => self::USER_STATUS_UNVERIFIED,
			  	'user_type' => $type
			)
		);
		
		if($this->db->affected_rows() < 1){
			return array(false, 'Failed to add the user into our system, please try again later');
		}
		$newUserId = $this->db->insert_id();
		
		// insert into the profiles table
		$this->db->insert(
				self::TABLE_PROFILE,
				array(
					'user_id' => $newUserId
				)
		);
		if($this->db->affected_rows() < 1){
			return array(false, 'Failed to add the user profile into our system, please try again later');
		}
		
		// insert into the verification code table
		$code = random_string('unique');
		$this->db->insert(
			self::TABLE_VERIFICATION_CODES,
			array(
				'user_id' => $newUserId,
				'code' => $code,
				'send_count' => 1
			)
		);
		if($this->db->affected_rows() < 1){
			return array(false, 'Failed to add the user into our verification system, please try again later');
		}
		
		// commit the transaction
		$this->db->trans_complete();
		
		// send verification email and report success
		$this->sendVerificationEmail($newUserId, $email, $code, $name);
		return array(true, 'You account has been created successfully, please confirm it by using the link sent to ' . $email);
	}
	
	/**
	 * update a user info - type cannot be changed
	 * @param int $id
	 * @param string $name
	 * @param string $email
	 * @param string $pswd
	 * @param string phone
	 */
	public function update($id, $name, $email, $pswd, $phone){
		
		if(empty($email)){
			return array(false, 'Email is required.');
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array(false, 'Email address is invalid.');
		}
		
		if(empty($pswd) or strlen($pswd) < 8){
		  return array(false, 'Password must be atleast 8 characters long.' . $pswd);
		}
				
		$query = $this->db->get_where(self::TABLE_USERS, array('id' => $id),	1, 0);
		if($query->num_rows() < 1){
			return array(false, 'Sorry, a user with this id does not exists. Click <a href="'.base_url().'user/signup">here</a> to register.');
		}
		$userInfo = array_shift($query->result());

		// Start a transaction since we are going to update multiple tables
		$this->db->trans_start();
		
		if($pswd != '********'){
		  $userInfo->pswd = password_hash($pswd . self::SERVER_SALT, PASSWORD_BCRYPT);
		}
		
		if($userInfo->email != $email){
		  $userInfo->status = self::USER_STATUS_UNVERIFIED;
		  $userInfo->email = $email;
		}
		
		$userInfo->name = $name;
		$userInfo->phone = $phone;
		
		$this->db->update(self::TABLE_USERS, $userInfo, array('id' => $userInfo->id));
		if($this->db->_error_number()){
		  $this->db->trans_rollback();
			return array(false, 'Failed to update the user into our system, please try again later');
		}

/*
		// update changes into the profiles table (nothing for now)
		$this->db->update(
				self::TABLE_PROFILE,
				array(
						'user_id' => $id
				)
		);
		if($this->db->affected_rows() < 1){
			return array(false, 'Failed to update the user profile into our system, please try again later');
		}
*/
		
		// email change requires re-verification
		if($userInfo->status == self::USER_STATUS_UNVERIFIED){
  		$code = random_string('unique');
  		$this->db->update(
  			self::TABLE_VERIFICATION_CODES,
  			array(
  				'user_id' => $id,
  				'code' => $code,
  				'send_count' => 1
  			)
  		);
  		
  		if($this->db->_error_number()){
        $this->db->trans_rollback();
  			return array(false, 'Failed to update the user into our verification system, please try again later');
  		}
  		
  		// send verification email
  		$this->sendVerificationEmail($userInfo->id, $email, $code, $name, false /*new*/);
		}
				
		// commit the transaction
		$this->db->trans_complete();
		
		// update the user name in session
		$this->session->set_userdata('user_name', $name);
		
		// report success
		return array(true, 'You account has been updated successfully');
	}
	
	/**
	 * forgot pswd - sends email
	 * @param string $email
	 */
	public function forgot($email){
		
		if(empty($email)){
			return array(false, 'Email is required.');
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array(false, 'Email address is invalid.');
		}
		
		$query = $this->db->get_where(self::TABLE_USERS, array('email' => $email),	1, 0);
		if($query->num_rows() < 1){
			return array(false, 'Sorry, a user with this email does not exist. Use <a href="'.base_url().'user/signup">Sign up</a> to create your account.');
		}
		$user = array_shift($query->result());
		
		// update the users table
		$pswd = random_string('alpha', 8);
		$user->pswd = password_hash($pswd . self::SERVER_SALT, PASSWORD_BCRYPT);
		$this->db->update(self::TABLE_USERS, $user, array('id' => $user->id));
		if($this->db->affected_rows() < 1){
			return array(false, 'Failed to update the user into our system, please try again later');
		}
		
		// send new pswd email and report success
		$this->sendNewPswdEmail($user->id, $user->name, $email, $pswd);
		return array(true, 'Success! We\'ve sent new password to <b>' . $email . '</b>');
	}
	
	/**
	 * resend confirmation email 
	 * @param string $email
	 */
	public function resend($email){

		if(empty($email)){
			return array(false, 'Email is required.');
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array(false, 'Email address is invalid.');
		}
		
		$query = $this->db->get_where(self::TABLE_USERS, array('email' => $email),	1, 0);
		if($query->num_rows() < 1){
			return array(false, 'A user with this email does not exist.');
		}
		$user = array_shift($query->result());
		
		if($user->status != self::USER_STATUS_UNVERIFIED){
		  return array(false, 'Your email is already verified, please <a href="' . base_url('/user/signin') . '">Sign in</a>.');
		}
		
		$query = $this->db->get_where(self::TABLE_VERIFICATION_CODES, array('user_id' => $user->id), 1, 0);
		if($query->num_rows() < 1){
			return array(false, 'We are unable to send verification code at the moment. Please contact <a href="mailto:support@snactrac.com">support@snactrac.com</a>.');
		}
		
		$verification = array_shift($query->result());
		$verification->send_count++;
		$this->db->update(self::TABLE_VERIFICATION_CODES, $verification, array('id' => $verification->id));
		
		$this->sendVerificationEmail($user->id, $email, $verification->code, $user->name);
		return array(true, 'Success! Email with verification code has been re-sent to <b>' . $email . '</b>');
	}
	
	/**
	 * confirm code sent in email
	 * @param string $code
	 */
	public function confirm($userId, $code){
	  
	  $query = $this->db->get_where(self::TABLE_USERS, array('id' => $userId),	1, 0);
	  if($query->num_rows() < 1){
	    return array(false, 'Invalid verification attempt.');
	  }
	  $user = array_shift($query->result());
	  
	  if($user->status != self::USER_STATUS_UNVERIFIED){
	    return array(false, 'Your email is already verified, please <a href="' . base_url('/user/signin') . '">Sign in</a>.');
	  }
	  
		$params = array(
			'user_id' => $userId,
			'code' => $code
		);
		$query = $this->db->get_where(self::TABLE_VERIFICATION_CODES, $params, 1, 0);
		if($query->num_rows() < 1){
			return array(false, 'We are unable to confirm verification code at the moment. Please contact <a href="mailto:support@snactrac.com">support@snactrac.com</a>.');
		}
		
		$user->status = self::USER_STATUS_ENABLED;
		$this->db->update(self::TABLE_USERS, $user, array('id' => $userId));
		
		return array(true, 'Success! Thankyou for verifying your email with us. You may <a href="' . base_url('/user/signin') . '">Sign In</a> to access your account.');
	}
	
	/**
	 * Helper function to send verifiction email
	 * @param unknown $userId
	 * @param unknown $email
	 * @param unknown $code
	 * @param unknown $name
	 */
	private function sendVerificationEmail($userId, $email, $code, $name, $new = true){
	  $action = $new ? ' creating an ' : ' updating your ';
		// send email to user
		$msg = "Dear " . $name . "\n\n"
				 . "Thank you for" . $action . "account at snactrac.com.\n\n"
				 . "Please confirm your email " . $email . " using the link below: \n\n"
				 . base_url('/user/confirm/'. $userId . "/" . $code) . "\n\n"
				 . "--\nThanks\nSnacTrac Support Team\nsupport@snactrac.com\n";
		$this->mailer->from(self::VERIFY_FROM_EMAIL, self::VERIFY_FROM_NAME);
		$this->mailer->to($email);
		$this->mailer->subject('Please verify your email for snactrac.com');
		$this->mailer->message($msg);
		
		if(ENVIRONMENT == 'development'){
		  // $this->mailer->send();
		  echo $this->mailer->print_debugger();
		}
		else {
		  $this->mailer->send();		  
		}
	}
	
	/**
	 * Helper function to send email when pswd changes
	 * @param int $userId
	 * @param string $email
	 * @param string $code
	 * @param string $name
	 */
	private function sendNewPswdEmail($userId, $name, $email, $pswd){
		// send email to user
		$msg = "Dear " . $name . "\n\n"
				 . "You password for snactrac.com has been updated.\n\n"
				 . "Your new password is: " . $pswd . "\n\n"
				 . "--\nThanks\nSnacTrac Support Team\nsupport@snactrac.com\n";
		$this->mailer->from(self::VERIFY_FROM_EMAIL, self::VERIFY_FROM_NAME);
		$this->mailer->to($email);
		$this->mailer->subject('New password for snactrac.com');
		$this->mailer->message($msg);
		
		if(ENVIRONMENT == 'development'){
		  echo $this->mailer->print_debugger();
		}
		else {
		  $this->mailer->send();		  
		}
	}
	
	/**
	 * Get user info helper
	 * @param int $userId
	 */
	public function info($userId){
	  $query = $this->db->get_where(self::TABLE_USERS, array('id' => $userId),	1, 0);
	  if($query->num_rows() < 1){
	    return array(false, 'FATAL: user with id ' . $userId . ' does not exist.');
	  }
	  
    return array(true, array_shift($query->result()));
	}
	
	/**
	 * Delete a player by renaming its email so login will not work
	 * but any exiting user data including foriegn keys are preserved
	 * @param unknown $userId
	 */
	public function delete($userId){
	  list($ret, $userInfo) = $this->info($userId);
	  if(!$ret){
	    return array(false, 'Failed to find user with this id');
	  }
	  
	  $userInfo->email = "D_" . time() . "_" . str_replace('@', '#', $userInfo->email);
	  $userInfo->status = self::USER_STATUS_DELETED;
	  $this->db->update(self::TABLE_USERS, $userInfo, array('id' => $userId));
	  
	  if($this->db->_error_number()){
	    return array(false, 'Failed to delete the user, please contact support');
	  }
	  
	  return array(true, 'User has been deleted successfully.');
	}
}
