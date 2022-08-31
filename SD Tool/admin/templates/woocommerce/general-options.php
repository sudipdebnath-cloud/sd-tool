<?php
global $SD_BACKEND_TOOL, $SD_TOOL_GS, $SD_TOOL_WS;
?>
<table class="form-table" role="presentation" width="100%">
	
	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Disable WooCommerce sidebar?</label>
		</th>
		<td>
			<label class="switch_checkbox_label">
				<input type="checkbox" name="sd_tool_disabled_woocommerce_sidebar" data-on_text="Yes" data-off_text="No" id="sd_tool_disabled_woocommerce_sidebar" <?php echo $SD_TOOL_WS->has_disabled_woocommerce_sidebar() ? 'checked' : ''; ?>>
				<span class="slider round"></span>
				<span class="on_off_text <?php echo $SD_TOOL_WS->has_disabled_woocommerce_sidebar() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->has_disabled_woocommerce_sidebar() ? 'Yes' : 'No'; ?></span>
			</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Wrap WooCoommerce Main Content with a 'div' tag?</label>
		</th>
		<td>
			<label class="switch_checkbox_label oncheck_show" data-targets="wmcr">
				<input type="checkbox" name="sd_tool_wrap_woocommerce_main_content" data-on_text="Yes" data-off_text="No" id="sd_tool_wrap_woocommerce_main_content" <?php echo $SD_TOOL_WS->wrap_woocommerce_main_content() ? 'checked' : ''; ?>>
				<span class="slider round"></span>
				<span class="on_off_text <?php echo $SD_TOOL_WS->wrap_woocommerce_main_content() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->wrap_woocommerce_main_content() ? 'Yes' : 'No'; ?></span>
			</label>
		</td>
	</tr>

	<tr valign="top" class="wmcr" <?php echo !$SD_TOOL_WS->wrap_woocommerce_main_content() ? 'style="display:none"' : ''; ?>>
		<th scope="row" class="enable_disable">
			<label class="font_16">WooCoommerce Main Content wrapper class</label>
		</th>
		<td>
			<input type="text" name="sd_tool_woocommerce_main_content_wrapper_class" id="sd_tool_woocommerce_main_content_wrapper_class" class="regular-text" placeholder="Wrapper class" value="<?php echo get_sd_tool_option('sd_tool_woocommerce_main_content_wrapper_class'); ?>">
		</td>
	</tr>

</table>