<?php

/**
 * 
 */
class SD_FRONTEND_TOOL
{
	
	function __construct()
	{
		add_action( 'wp_enqueue_scripts', array($this, 'sd_add_public_styles'));
		add_action( 'wp_enqueue_scripts', array($this, 'sd_add_public_scripts'));
	}

	public function sd_add_public_styles(){
		wp_enqueue_style( 'sd-tool-public-style', SD_TOOL_URL.'assets/css/sd-tool-public-style.css', array(), SD_TOOL_VERSION );
	}

	public function sd_add_public_scripts(){
		wp_enqueue_script( 'sd-tool-public-script', SD_TOOL_URL.'assets/js/sd-tool-public-script.js', array(), SD_TOOL_VERSION, true );
	}
}

?>