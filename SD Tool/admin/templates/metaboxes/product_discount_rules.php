<?php
global $SD_BACKEND_TOOL, $SD_TOOL_GS, $SD_TOOL_WS, $post;
$product_id = $post->ID;
wp_nonce_field( 'sd_tool_discount_rules_nonce', 'sd_tool_discount_rules_nonce' );
?>
<div class="sd-tool-product-discount-rules">

	<table class="form-table" role="presentation" width="100%">
		<tr valign="top">
			<th scope="row" class="enable_disable">
				<label class="font_16">Disable global discount rules?</label>
			</th>
			<td>
				<label class="switch_checkbox_label">
					<input type="checkbox" name="sd_tool_disable_product_global_discount" data-on_text="Yes" data-off_text="No" id="sd_tool_disable_product_global_discount" <?php echo $SD_TOOL_WS->disable_product_global_discount($product_id) ? 'checked' : ''; ?>>
					<span class="slider round"></span>
					<span class="on_off_text <?php echo $SD_TOOL_WS->disable_product_global_discount($product_id) ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->disable_product_global_discount($product_id) ? 'Yes' : 'No'; ?></span>
				</label>
				<p class="description">Individual product discount rules will always get the priority over the Global discount rules.</p>
			</td>
		</tr>
		<?php if($SD_TOOL_WS->enabled_product_wise_discount()) { ?>
		<tr valign="top">
			<th scope="row" class="enable_disable">
				<label class="font_16">Custom discount rules on the basis of cart items?</label>
			</th>
			<td>
				<label class="switch_checkbox_label oncheck_show" data-targets="pcdr">
					<input type="checkbox" name="sd_tool_enable_product_custom_discount" data-on_text="Enabled" data-off_text="Disabled" id="sd_tool_enable_product_custom_discount" <?php echo $SD_TOOL_WS->enable_product_custom_discount($product_id) ? 'checked' : ''; ?>>
					<span class="slider round"></span>
					<span class="on_off_text <?php echo $SD_TOOL_WS->enable_product_custom_discount($product_id) ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->enable_product_custom_discount($product_id) ? 'Enabled' : 'Disabled'; ?></span>
				</label>
			</td>
		</tr>
		<?php } ?>
	</table>

	<?php if($SD_TOOL_WS->enabled_product_wise_discount()) { ?>
	<div class="product_quantity_wise_rules pcdr" <?php echo !$SD_TOOL_WS->enable_product_custom_discount($product_id) ? 'style="display:none;"' : ''; ?>>
		<table width="100%" class="product_discount_rules_table discount_rules_table" id="product_discount_rules_table">
			<thead>
				<tr>
					<th colspan="5" align="left">Quantity wise (For each cart item)</th>
				</tr>
				<tr>
					<th align="left">Min.</th>
					<th align="left">Max.</th>
					<th align="left">Discount</th>
					<th align="left">Cart table text</th>
					<th align="left"></th>
				</tr>
			</thead>
			<tbody>
				<?php if($SD_TOOL_WS->get_product_discount_rules($product_id)) {
					foreach ($SD_TOOL_WS->get_product_discount_rules($product_id) as $pdr) {
					?>
					<tr>
						<td><input type="number" name="product_discount_rule[min_qty][]" class="min_qty" min="1" value="<?php echo $pdr['min_qty']; ?>" required></td>
						<td><input type="number" name="product_discount_rule[max_qty][]" class="max_qty" min="1" value="<?php echo $pdr['max_qty']; ?>"></td>
						<td>
							<div class="d-flex">
								<input type="number" name="product_discount_rule[discount][]" class="discount" min="1" step="0.01" value="<?php echo $pdr['discount']; ?>">
								<select name="product_discount_rule[discount_type][]" class="discount_type">
									<option value="percentage" <?php echo $pdr['discount_type'] == 'percentage' ? 'selected' : ''; ?>>%</option>
									<option value="flat" <?php echo $pdr['discount_type'] == 'flat' ? 'selected' : ''; ?>>Flat</option>
								</select>
							</div>
						</td>
						<td colspan="2">
							<div class="d-flex">
								<input type="text" name="product_discount_rule[cart_table_text][]" class="cart_table_text" value="<?php echo $pdr['cart_table_text']; ?>">
								<button type="button" class="button button-danger" onclick="removeTheDiscountTr(this)">-</button>
							</div>
						</td>
					</tr>
					<?php
					}
				}else{
					?>
					<tr class="blank_row">
						<td colspan="5" align="center">No rules added. Please click on the (+) button to add a new rule.</td>
					</tr>
					<?php
				} ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5" align="right"><button type="button" class="button button-primary" id="add_new_product_discount_rule">+</button></td>
				</tr>
			</tfoot>
		</table>
		<p class="description"><strong>Note:</strong> Leave the "Max" field blank if you want to give discount above all the products of respective "Min" value.<br>Use the shortcode <i><strong>%discount_value%</strong></i> into the "Cart table text" input box to show the calculated discont value</p>

		<table width="100%">
			<tr valign="top">
				<th scope="row" align="left">
					<label class="font_16">Set maximum discount amount</label>
				</th>
				<td>
					<input type="number" name="sd_tool_maximum_discount_amount" step="0.5" min="1" value="<?php echo $SD_TOOL_WS->get_maximum_discount_amount($product_id); ?>">
				</td>
			</tr>
		</table>

	</div>
	<?php } ?>

</div>