<?php
global $SD_BACKEND_TOOL, $SD_TOOL_GS, $SD_TOOL_WS;
?>
<table class="form-table" role="presentation" width="100%">
	
	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Disable WooCommerce product details page tabs?</label>
		</th>
		<td>
			<label class="switch_checkbox_label">
				<input type="checkbox" name="sd_tool_disabled_woocommerce_product_page_tabs" data-on_text="Yes" data-off_text="No" id="sd_tool_disabled_woocommerce_product_page_tabs" <?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_tabs() ? 'checked' : ''; ?>>
				<span class="slider round"></span>
				<span class="on_off_text <?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_tabs() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_tabs() ? 'Yes' : 'No'; ?></span>
			</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Disable WooCommerce product details page upsell products?</label>
		</th>
		<td>
			<label class="switch_checkbox_label">
				<input type="checkbox" name="sd_tool_disabled_woocommerce_product_page_upsell_products" data-on_text="Yes" data-off_text="No" id="sd_tool_disabled_woocommerce_product_page_upsell_products" <?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_upsell_products() ? 'checked' : ''; ?>>
				<span class="slider round"></span>
				<span class="on_off_text <?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_upsell_products() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_upsell_products() ? 'Yes' : 'No'; ?></span>
			</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Disable WooCommerce product details page related products?</label>
		</th>
		<td>
			<label class="switch_checkbox_label oncheck_hide" data-targets="rpsh">
				<input type="checkbox" name="sd_tool_disabled_woocommerce_product_page_related_products" data-on_text="Yes" data-off_text="No" id="sd_tool_disabled_woocommerce_product_page_related_products" <?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_related_products() ? 'checked' : ''; ?>>
				<span class="slider round"></span>
				<span class="on_off_text <?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_related_products() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_related_products() ? 'Yes' : 'No'; ?></span>
			</label>
		</td>
	</tr>

	<tr valign="top" class="rpsh" <?php echo $SD_TOOL_WS->has_disabled_woocommerce_product_page_related_products() ? 'style="display:none"' : ''; ?>>
		<th scope="row" class="enable_disable">
			<label class="font_16" id="sd_tool_related_products_section_heading">Related products section heading</label>
		</th>
		<td>
			<input name="sd_tool_related_products_section_heading" type="text" id="sd_tool_related_products_section_heading" value="<?php echo get_sd_tool_option('sd_tool_related_products_section_heading'); ?>" placeholder="Related products" class="regular-text">
		</td>
	</tr>

</table>