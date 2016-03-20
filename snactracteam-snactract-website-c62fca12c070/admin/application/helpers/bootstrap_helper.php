<?php

/**
 * create text input element
 * @param array $params
 * @return string
 */
function bootstrap_text($params){
  
	$value = set_value($params['name']);
	if(empty($_POST) and isset($params['value']) and $params['value'] != ''){
	  $value = $params['value'];
	}
	
	$hasError = form_error($params['name']) ? 'has-error' : ''; 

	$addon = '';
	if(isset($params['addon'])){
	  $addon = "<span class=\"input-group-addon\">"
	         . "  <input "
	         . "    type=\"{$params['addon']['type']}\" "
	         . "    name=\"{$params['addon']['name']}\" "
	         . "    value=\"{$params['addon']['value']}\" "
	         . "    {$params['addon']['checked']}>"
	         . "</span>";
	}
	
	$readOnly = '';
	if(!empty($params['readonly'])){
	  $readOnly = 'readonly';
	}
	
	return <<<EOF

		<div class="form-group clearfix {$hasError}">
		  <div class="col-lg-12">
		    <label class="sr-only" for="{$params['name']}">{$params['place_holder']}</label>
		    <div class="input-group col-lg-12">
		      {$addon}
  		    <input
  		      id="{$params['name']}"
  		    	type="{$params['type']}"
  		    	name="{$params['name']}"
  		    	value="{$value}" 
  		    	class="form-control"
  		    	placeholder="{$params['place_holder']}" {$readOnly}>
		    </div>
		  </div>
		</div>
	
EOF;
}


/**
 * file upload control
 * @param unknown $params
 * @return string
 */
function bootstrap_file($params){
  return <<<EOF
  
    <div class="form-group clearfix">
     <div class="col-lg-12">
      <label for="{$params['name']}">{$params['label']}</label>
      <input type="file" name="{$params['name']}">
      <p class="text-danger">{$params['error_info']}</p>
     </div>
    </div>

EOF;
}

/**
 * horizontal input with btn - like search or filter
 * @param unknown $prams
 * @return string
 */
function bootstrap_navbar_input($params){

  $value = set_value($params['name']);
  if(isset($params['value']) and $params['value'] != ''){
    $value = $params['value'];
  }
  
  return <<<EOF
  <div class="input-group">
    <input
      type="text"
      class="form-control"
      placeholder="{$params['place_holder']}"
      name="{$params['name']}"
      value="{$value}">
    <div class="input-group-btn">
      <button class="btn {$params['btn']}" type="submit"><i class="glyphicon {$params['glyphicon']}"></i></button>
    </div>
  </div>
EOF;
}

/**
 * create a textarea input element
 * @param unknown $params
 * @return string
 */
function bootstrap_textarea($params){
  $value = set_value($params['name']);
  if(empty($_POST) and isset($params['value']) and $params['value'] != ''){
    $value = $params['value'];
  }
  
  $hasError = form_error($params['name']) ? 'has-error' : '';
  
  return <<<EOF
  
		<div class="form-group clearfix {$hasError}">
		  <div class="col-lg-12">
		    <textarea
		    	name="{$params['name']}"
		    	class="form-control"
		    	placeholder="{$params['place_holder']}"
		    	rows="{$params['rows']}">{$value}</textarea>
		  </div>
		</div>
  
EOF;
  
}

/**
 * create a check box form element
 * @param array $params
 * @return string
 */
function bootstrap_checkbox($params){
  
  $checked = set_checkbox($params['name'], $params['value']);
  if(isset($params['checked']) and $params['checked'] == true){
    $checked = 'checked="checked"';
  }
  
	return <<<EOF
		<div class="remember">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="{$params['name']}" value="{$params['value']}" {$checked}> {$params['label']}
				</label>
		  </div>
    </div>
EOF;
}

/**
 * Create a drop down selection control
 */
function bootstrap_select($params){

  $value = ''; // TODO consider using set_select()
  if(empty($_POST) and isset($params['value']) and $params['value'] != ''){
    $value = $params['value'];
  }
  
  $ret = '<div class="form-group clearfix">'
       . '<div class="col-lg-12">'
       . "<select name=\"{$params['name']}\" class=\"form-control\">";
  foreach($params['options'] as $id=>$option){
    $selected = $value == $id ? 'selected' : '';
    $ret .= "<option {$selected} value={$id}>{$option}</option>\n";
  }
  $ret .= '</select></div></div>'.PHP_EOL;
  return $ret;
}

/**
 * @param string $alert
 * @return string
 */
function bootstrap_alert($alert){
  if(is_array($alert)){
    $msg = $alert['msg'];
    $class = $alert['class'];
  }
  else {
    $msg = $alert;
    $class = 'alert-warning';
  }
	return <<<EOF

		<div class="alert {$class} text-left">{$msg}</div>

EOF;
}

/**
 * return a <li> item for nav with active class on or off
 * @param string $title
 * @param string $uri
 */
function bootstrap_nav_item($title, $uri, $active){
  //$class = (uri_string() == $uri ? 'class="active"' : '');
  $class = strtolower($active) == strtolower($title)  ? 'class="active"' : '';
  $url = base_url($uri);
  return "<li {$class}><a href=\"{$url}\">{$title}</a></li>";
}
