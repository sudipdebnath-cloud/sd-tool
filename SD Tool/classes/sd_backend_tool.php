<?php
/**
 * 
 */
class SD_BACKEND_TOOL
{
	
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'sd_add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array($this, 'sd_add_admin_styles') );
		add_action( 'admin_enqueue_scripts', array($this, 'sd_add_admin_scripts') );
	}

	public function sd_add_admin_styles(){
		wp_enqueue_style( 'sd-tool-material-design-iconic-font', 'https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css', array(), SD_TOOL_VERSION );
		wp_enqueue_style( 'sd-tool-admin-style', SD_TOOL_URL.'assets/css/sd-tool-admin-style.css', array(), SD_TOOL_VERSION );
	}

	public function sd_add_admin_scripts(){
		wp_enqueue_script( 'sd-tool-admin-script', SD_TOOL_URL.'assets/js/sd-tool-admin-script.js', array(), SD_TOOL_VERSION, true );
	}

	public function render_admin_template($file_name){
		if(file_exists(SD_TOOL_DIR.'admin/templates/'.$file_name.'.php')){
			require_once SD_TOOL_DIR.'admin/templates/'.$file_name.'.php';
		}
	}

	public function get_main_menu_image_url(){
		$image_file = 'main-menu-icon.png';
		if(file_exists(SD_TOOL_DIR.'assets/images/'.$image_file)){
			return SD_TOOL_URL.'assets/images/'.$image_file;
		}
	}

	public function sd_add_admin_menu(){
		add_menu_page('SD Tool', 'SD Tool', 'manage_options', 'sd-tool', array($this, 'sd_tool_main_page'), $this->get_main_menu_image_url(), 75);
	}

	public function sd_tool_main_page(){
		if ( ! current_user_can( 'manage_options' ) ) {
	    	return;
	  	}
		$this->render_admin_template('main');
	}

}

if(class_exists( 'SD_BACKEND_TOOL' )){
	$SD_BACKEND_TOOL = new SD_BACKEND_TOOL();
}

?>