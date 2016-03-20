  <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('base.php');

class Menu extends BaseController {
  
  /**
   * Default constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->helper('bootstrap_helper');
    $this->load->helper('business_helper');
    $this->load->helper('trucks_helper');
    $this->load->helper('menu_helper');
  }
  
  /**
   * Index Page for this controller.
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function index($truckId, $menuItemId = null, $saved = null)
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
    
    list($ret, $truck) = trucks_get_truck($this, $truckId);
    if(!$ret){
      echo $truck;
      die();
    }
    
    $menuItem = null;
    $alert = '';
    if(!empty($menuItemId)){
      list($ret, $menuItem) = menu_get_menu_item($this, $menuItemId);
      if(!$ret){
        $alert = $menuItem;
      }
    }
    
    $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
    $this->form_validation->set_rules('price', 'price', 'trim|required|xss_clean');
    $this->form_validation->set_rules('desc', 'Description/Info', 'trim|xss_clean');
    $this->form_validation->set_rules('keywords', 'Keywords', 'trim|xss_clean');
    
    if ($this->form_validation->run()){
      // add new menu item
      if(empty($menuItem)){
        $this->db->insert(
            TABLE_MENU,
            array(
                'created_at' => time(),
                'status' => MENU_ITEM_STATUS_ENABLED,
                'truck_id' => $truck->id,
                'name' => $this->input->post('name'),
                'price' => $this->input->post('price') * 100,
                'description' => $this->input->post('desc')
            )
        );
        
        if($this->db->_error_number()){
          $alert = 'Failed to add the menu item, please try again later.';
        }
        else {
          $menuItemId = $this->db->insert_id();
          list($ret, $menuItem) = menu_get_menu_item($this, $menuItemId);
          if($ret){
            menu_set_menu_item_keywords($this, $menuItemId, $this->input->post('keywords'));
            list($success, $ret) = $this->uploadImage($menuItemId);
            if($success){
              redirect("/menu/index/{$truck->id}/{$menuItemId}/saved");
            }
            else {
              $alert = $ret;
            }
          }
        }
      }
      // update existing menu
      else {
        $menuItem->name = $this->input->post('name');
        $menuItem->price = $this->input->post('price') * 100;
        $menuItem->description = $this->input->post('desc');
        unset($menuItem->keywords);
        $this->db->update(TABLE_MENU, $menuItem, array('id' => $menuItem->id));
        if($this->db->_error_number()){
          $alert = 'Failed to update the menu item, please try again later.';
        }
        else {
          menu_set_menu_item_keywords($this, $menuItem->id, $this->input->post('keywords'));
          list($success, $ret) = $this->uploadImage($menuItem->id);
          if($success){
            redirect("/menu/index/{$truck->id}/{$menuItem->id}/saved");
          }
          else {
            $alert = $ret;
          }
                    
        }
      }
    }
    
    if(empty($alert) and !empty($saved)){
      $alert = array('msg' => 'Menu Item saved successfully', 'class' => 'alert-success fade-away');
    }
    
    $this->load->view('header', array('controller' => $this));
    $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'trucks'));
    $this->load->view('menu/menu', array('alert' => $alert, 'truck' => $truck, 'menuItem' => $menuItem));
    $this->load->view('footer');
  }
  
  /**
   * grab the uploaded image and store it
   * @param int $menuId
   * @return array
   */
  private function uploadImage($menuId){

    $this->load->library('upload', array(
        'upload_path' => MENU_IMAGE_BASE_PATH,
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
        return array(false, 'This file is not an image');
      }

      // delete any existing image
      foreach(array('png', 'jpg') as $ext){
        $oldFile = "{$data['file_path']}{$menuId}.{$ext}";
        if(file_exists($oldFile)){
          unlink($oldFile);
        }
      }
      
      // resize and copy
      $this->load->library('image_lib', array(
          'source_image' => $data['full_path'],
          'maintain_ratio' => true,
          'width' => 320,
          'height' => 240,
          'new_image' => "{$menuId}{$data['file_ext']}"
      ));
      if(!$this->image_lib->resize()){
        return array(false, $this->image_lib->display_errors('', ''));
      }
      
      // delete original uploaded image
      unlink($data['full_path']);

      return array(true, $data);
    }
  }
  
  /**
   * delete a menu item and go back to menu list
   * @param unknown $truckId
   * @param unknown $menuItemId
   */
  public function delete($truckId, $menuItemId){
    list($ret, $user) = $this->getLoggedInUser();
    if(empty($ret)){
      redirect('/user/signin', 'refresh');
      die();
    }
     
    if($user->user_type != Membership::USER_TYPE_AREA_MANGER){
      echo 'Sorry, only a truck owner is allowed to access this page.';
      die();
    }
    
    list($ret, $truck) = trucks_get_truck($this, $truckId);
    if(!$ret){
      echo $truck;
      die();
    }
    
    $alert = '';
    $menuItem = null;
    list($ret, $menuItem) = menu_get_menu_item($this, $menuItemId);
    if($ret){
      menu_delete_menu_item($this, $menuItem);
      redirect("/truck/menu/{$truck->id}/deleted");
    }
    
    $this->load->view('header', array('controller' => $this));
    $this->load->view('sidenav', array('controller' => $this, 'user' => $user, 'active' => 'trucks'));
    $this->load->view('menu/menu', array('alert' => $alert, 'truck' => $truck, 'menuItem' => $menuItem));
    $this->load->view('footer');
  }
}