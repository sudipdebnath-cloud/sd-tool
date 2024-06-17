<?php
global $SD_BACKEND_TOOL, $SD_TOOL_GS, $SD_TOOL_WS;
?>
<table class="form-table" role="presentation" width="100%">
	
	<tr valign="top" class="wmcr" <?php echo !$SD_TOOL_WS->wrap_woocommerce_main_content() ? 'style="display:none"' : ''; ?>>
		<th scope="row" class="enable_disable">
			<label class="font_16">WooCoommerce products loop item "li" add custom class</label>
		</th>
		<td>
			<input type="text" name="sd_tool_woocommerce_products_loop_item_custom_class" id="sd_tool_woocommerce_products_loop_item_custom_class" class="regular-text" placeholder="Loop li class" value="<?php echo get_sd_tool_option('sd_tool_woocommerce_products_loop_item_custom_class'); ?>">
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Wrap WooCoommerce products loop image with a "div" tag?</label>
		</th>
		<td>
			<label class="switch_checkbox_label oncheck_show" data-targets="wplir">
				<input type="checkbox" name="sd_tool_wrap_woocommerce_products_loop_image" data-on_text="Yes" data-off_text="No" id="sd_tool_wrap_woocommerce_products_loop_image" <?php echo $SD_TOOL_WS->wrap_woocommerce_products_loop_image() ? 'checked' : ''; ?>>
				<span class="slider round"></span>
				<span class="on_off_text <?php echo $SD_TOOL_WS->wrap_woocommerce_products_loop_image() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->wrap_woocommerce_products_loop_image() ? 'Yes' : 'No'; ?></span>
			</label>
		</td>
	</tr>

	<tr valign="top" class="wplir" <?php echo !$SD_TOOL_WS->wrap_woocommerce_products_loop_image() ? 'style="display:none"' : ''; ?>>
		<th scope="row" class="enable_disable">
			<label class="font_16">WooCoommerce products loop image wrapper class</label>
		</th>
		<td>
			<input type="text" name="sd_tool_woocommerce_products_loop_wrapper_class" id="sd_tool_woocommerce_products_loop_wrapper_class" class="regular-text" placeholder="Wrapper class" value="<?php echo get_sd_tool_option('sd_tool_woocommerce_products_loop_wrapper_class'); ?>">
		</td>
	</tr>

</table>