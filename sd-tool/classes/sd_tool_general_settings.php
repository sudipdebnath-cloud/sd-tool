<?php

/**
 * 
 */
class SD_TOOL_GENERAL_SETTINGS
{
	
	function __construct()
	{
		add_action( 'wp_ajax_save_sd_tool_general_settings_data', array($this, 'save_sd_tool_general_settings_data') );
	}

	public function is_woocommerce_active(){
		if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
			return true;
		}
	}

	public function has_enabled_woocommerce_features(){
		$woocommerce_features = get_sd_tool_option('sd_tool_woocommerce_features');
		if($woocommerce_features && intval($woocommerce_features) == 1 && $this->is_woocommerce_active()){
			return true;
		}
	}

	public function save_sd_tool_general_settings_data(){
		$nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'sd-tool-general-settings' ) ) {
		    print_r("Invalid request!");
		}else{
			$sd_tool_woocommerce_features = isset($_POST['sd_tool_woocommerce_features']) ? 1 : 0;
			update_sd_tool_option('sd_tool_woocommerce_features', $sd_tool_woocommerce_features);
			$response = array(
				'updated' => true
			);
			wp_send_json($response);
		}
		wp_die();
	}

}

if(class_exists( 'SD_TOOL_GENERAL_SETTINGS' )){
	$SD_TOOL_GS = new SD_TOOL_GENERAL_SETTINGS();
}

?>