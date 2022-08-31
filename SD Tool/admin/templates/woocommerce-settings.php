<?php
global $SD_BACKEND_TOOL, $SD_TOOL_GS, $SD_TOOL_WS;
if($SD_TOOL_GS->has_enabled_woocommerce_features()){
?>
<div class="sd-tool-woocommerce-settings">
	<h2>WooCommerce Settings</h2>
	<div class="form-wrapper">
		<form method="post" id="sd-tool-woocommerce-settings-form" action="" enctype="multipart/form-data">
			<?php wp_nonce_field( "sd-tool-woocommerce-settings" ); ?>
			<?php do_action('load_settings_templates'); ?>
			<p class="submit">
				<button type="submit" name="submit" id="submit" class="button button-primary">Save Changes</button><span id="ajax_response_message" class="sd_tool_response_message"></span>
			</p>
		</form>
	</div>
</div>
<?php
}
?>