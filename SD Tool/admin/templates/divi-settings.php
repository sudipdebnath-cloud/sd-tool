<?php
global $SD_BACKEND_TOOL, $SD_TOOL_GS;
?>
<div class="sd-tool-divi-settings">
	<h2>General Settings</h2>
	<div class="form-wrapper">
		<form method="post" id="sd-tool-divi-settings-form" action="" enctype="multipart/form-data">
			<?php wp_nonce_field( "sd-tool-divi-settings" ); ?>
			<table class="form-table" role="presentation">
				<tr valign="top">
					<th scope="row" class="enable_disable">
						<label class="font_16">Enable Collapsible Submenu</label>
					</th>
					<td>
						<label class="switch_checkbox_label">
							<input type="checkbox" name="sd_tool_divi_submenu" data-on_text="Enabled" data-off_text="Disabled" id="sd_tool_divi_submenu" <?php echo $SD_TOOL_GS->has_enabled_woocommerce_features() ? 'checked' : ''; ?>>
							<span class="slider round"></span>
							<span class="on_off_text <?php echo $SD_TOOL_GS->has_enabled_woocommerce_features() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_GS->has_enabled_woocommerce_features() ? 'Enabled' : 'Disabled'; ?></span>
						</label>
					</td>
				</tr>
			</table>
			<p class="submit">
				<button type="submit" name="submit" id="submit" class="button button-primary">Save Changes</button>
			</p>
		</form>
	</div>
</div>