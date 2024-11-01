<?php
/*
Plugin Name: Widget Plugoo
Plugin URI: http://www.dimgoto.com/open-source/wordpress/plugins/widget-plugoo
Description: Chat with users connected to your website. you must have an account Plugoo
Version: 1.0.0
Author: Dimitri GOY
Author URI: http://www.dimgoto.com/
*/

class WidgetPlugoo {

	private $_widgetname = null;
	private $_widgetnamelower = null;
	private $_file = null;
	
	public function __construct() {
		
		$this->_widgetname = get_class($this);
		$this->_widgetnamelower  = strtolower($this->_widgetname);
		$this->_file = __FILE__;
		
		register_activation_hook($this->_file, array($this, 'activate'));
		register_deactivation_hook($this->_file, array($this, 'deactivate'));
		register_uninstall_hook($this->_file, array($this, 'uninstall'));
		
		register_sidebar_widget($this->_widgetname, array($this, 'widget'));
    	register_widget_control($this->_widgetname, array($this, 'control'));
	}
	
	public function activate() {
		
		$option = array(
			'title'	=> '',
			'embed'	=> ''
		);
		add_option($this->_widgetnamelower, $option);
	}
	
	public function deactivate() {
	}
	
	public function uninstall() {
		
		delete_option($this->_widgetnamelower);
	}
	
	public function widget() {
		
		$option = get_option($this->_widgetnamelower);

		if ($option 
		&& array_key_exists('embed', $option)
		&& isset($option['embed'])) {
		
			if (array_key_exists('title', $option) 
			&& isset($option['title'])) {
				$title = $option['title'];
			}
			$embed = $option['embed'];

			$html = "";
			$html .= $args['before_widget'] . "\n";
			if (isset($title)) {
				$html .= $args['before_title'] . $title . $args['after_title']. "\n";
			}
			$html .= "<ul>\n";
			$html .= "<li id=\"" . $this->_widgetnamelower . "-" . $args['widget_id'] . "\">" . html_entity_decode(stripslashes($embed)) . "</li>\n";
			$html .= "</ul>\n";
			$html .= $args['after_widget'] . "\n";
			
			echo $html;
		}
	}
	
	public function control() {
		
		$option = get_option($this->_widgetnamelower);
		$data = $option;

		if (isset($_POST[$this->_widgetnamelower . '_title'])) {
    		$data['title'] = attribute_escape($_POST[$this->_widgetnamelower . '_title']);
  		} else {
			$date['title'] = '';
		}

   		if (isset($_POST[$this->_widgetnamelower . '_embed'])) {
    		$data['embed'] = attribute_escape($_POST[$this->_widgetnamelower . '_embed']);
  		} else {
  			$data['embed'] = '';
  		}
		
  		if (isset($data)
  		&& !empty($data)) {
  			update_option($this->_widgetnamelower, $data);
  		}
		
		$html = "";
		
		$html .= "<p><label>" . __('Title') . ": </label>";
		$html .= "<input name=\"" . $this->_widgetnamelower . "_title\" type=\"text\" value=\"" . $data['title'] . "\"/></p>";

  		$html .= "<p><label>" . __('Plugoo Code') . ": </label>";
		$html .= "<input name=\"" . $this->_widgetnamelower . "_embed\" type=\"text\" value=\"" . $data['embed'] . "\"/><br/>";
  		$html .= "<small>(" . __('Votre code Plugoo', $this->_widgetname) . ": ";
		$html .= "<a href=\"http://www.plugoo.com\" target=\"_blank\">" . __('Plugoo', $this->_widgetname) . "</a>)</small></p>";
			
		echo $html;
	}
}
new WidgetPlugoo();
?>