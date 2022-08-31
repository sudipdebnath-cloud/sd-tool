<?php
global $SD_BACKEND_TOOL, $SD_TOOL_GS, $SD_TOOL_WS;
?>
<table class="form-table" role="presentation" width="100%">
	
	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Enable global discount?</label>
		</th>
		<td>
			<label class="switch_checkbox_label oncheck_show" data-targets="egd">
				<input type="checkbox" name="sd_tool_enable_global_discount" data-on_text="Yes" data-off_text="No" id="sd_tool_enable_global_discount" <?php echo $SD_TOOL_WS->enabled_global_discount() ? 'checked' : ''; ?>>
				<span class="slider round"></span>
				<span class="on_off_text <?php echo $SD_TOOL_WS->enabled_global_discount() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->enabled_global_discount() ? 'Yes' : 'No'; ?></span>
			</label>
		</td>
	</tr>

	<tr valign="top" class="egd" <?php echo !$SD_TOOL_WS->enabled_global_discount() ? 'style="display:none"' : ''; ?>>
		<th scope="row" class="enable_disable">
			<label class="font_16">Global discount rules on the basis of cart items</label>
		</th>
		<td>
			<table width="100%" class="global_discount_rules_table discount_rules_table" id="global_discount_rules_table">
				<thead>
					<tr>
						<th colspan="5">Quantity wise (For each cart item)</th>
					</tr>
					<tr>
						<th>Min.</th>
						<th>Max.</th>
						<th>Discount</th>
						<th>Cart table text</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if($SD_TOOL_WS->get_global_discount_rules_quantity_wise()) { 
						$count = 1;
						foreach ($SD_TOOL_WS->get_global_discount_rules_quantity_wise() as $grqw) {
						?>
						<tr>
							<td><input type="number" name="global_discount_rule[min_qty][]" class="min_qty required" min="1" value="<?php echo $grqw['min_qty'] ? $grqw['min_qty'] : 1; ?>" required></td>
							<td><input type="number" name="global_discount_rule[max_qty][]" class="max_qty" min="1" value="<?php echo $grqw['max_qty']; ?>"></td>
							<td>
								<div class="d-flex">
									<input type="number" name="global_discount_rule[discount][]" class="discount" min="1" value="<?php echo $grqw['discount']; ?>">
									<select name="global_discount_rule[discount_type][]" class="discount_type">
										<option value="percentage" <?php echo $grqw['discount_type'] == 'percentage' ? 'selected' : ''; ?>>%</option>
										<option value="flat" <?php echo $grqw['discount_type'] == 'flat' ? 'selected' : ''; ?>>Flat</option>
									</select>
								</div>
							</td>
							<td colspan="2">
								<?php if($count > 1) { ?>
								<div class="d-flex">
									<input type="text" name="global_discount_rule[cart_table_text][]" class="cart_table_text" value="<?php echo $grqw['cart_table_text']; ?>">
									<button type="button" class="button button-danger" onclick="removeTheDiscountTr(this)">-</button>
								</div>
								<?php }else{ ?>
								<input type="text" name="global_discount_rule[cart_table_text][]" class="cart_table_text" value="<?php echo $grqw['cart_table_text']; ?>">
								<?php } ?>
							</td>
						</tr>
						<?php
						$count++;}
					}else{ ?>
					<tr>
						<td><input type="number" name="global_discount_rule[min_qty][]" class="min_qty required" min="1" required></td>
						<td><input type="number" name="global_discount_rule[max_qty][]" class="max_qty" min="1"></td>
						<td>
							<div class="d-flex">
								<input type="number" name="global_discount_rule[discount][]" class="discount" min="1" step="0.01">
								<select name="global_discount_rule[discount_type][]" class="discount_type">
									<option value="percentage">%</option>
									<option value="flat">Flat</option>
								</select>
							</div>
						</td>
						<td colspan="2"><input type="text" name="global_discount_rule[cart_table_text][]" class="cart_table_text"></td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" align="right"><button type="button" class="button button-primary" id="add_new_global_discount_rule">+</button></td>
					</tr>
				</tfoot>
			</table>
			<p class="description"><strong>Note:</strong> Leave the "Max" field blank if you want to give discount above all the products of respective "Min" value.<br>Use the shortcode <i><strong>%discount_value%</strong></i> into the "Cart table text" input box to show the calculated discont value</p>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Enable product wise discount?</label>
			<p class="field_descriptino font_12">This option will allow you to manage discounts individually for each product</p>
		</th>
		<td>
			<label class="switch_checkbox_label">
				<input type="checkbox" name="sd_tool_enable_product_wise_discount" data-on_text="Yes" data-off_text="No" id="sd_tool_enable_product_wise_discount" <?php echo $SD_TOOL_WS->enabled_product_wise_discount() ? 'checked' : ''; ?>>
				<span class="slider round"></span>
				<span class="on_off_text <?php echo $SD_TOOL_WS->enabled_product_wise_discount() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->enabled_product_wise_discount() ? 'Yes' : 'No'; ?></span>
			</label>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" class="enable_disable">
			<label class="font_16">Set maximum discount amount per item</label>
		</th>
		<td>
			<input type="number" name="sd_tool_maximum_discount_amount" step="0.5" min="1" value="<?php echo $SD_TOOL_WS->get_maximum_discount_amount(); ?>">
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label class="font_16">Discount Conditions</label>
		</th>
		<td>
			<table width="100%">
				<thead>
					<tr>
						<th>Apply Conditions</th>
						<td>
							<label class="switch_checkbox_label oncheck_show" data-targets="egd_ac">
								<input type="checkbox" name="sd_tool_enable_global_discount_conditions" data-on_text="Yes" data-off_text="No" id="sd_tool_enable_global_discount_conditions" <?php echo $SD_TOOL_WS->enabled_global_discount_conditions() ? 'checked' : ''; ?>>
								<span class="slider round"></span>
								<span class="on_off_text <?php echo $SD_TOOL_WS->enabled_global_discount_conditions() ? 'enabled' : 'disabled'; ?>"><?php echo $SD_TOOL_WS->enabled_global_discount_conditions() ? 'Yes' : 'No'; ?></span>
							</label>
						</td>
					</tr>
				</thead>
				<tbody class="egd_ac" <?php echo !$SD_TOOL_WS->enabled_global_discount_conditions() ? 'style="display:none"' : ''; ?>>
					<tr>
						<td>If the minimum Order Total value is <strong>(excluding shipping & taxes)</strong></td>
						<td><input type="number" step="0.5" min="1" name="sd_tool_order_total_exluding_shippng_taxes_for_global_discount" value="<?php echo get_sd_tool_option('sd_tool_order_total_exluding_shippng_taxes_for_global_discount'); ?>"></td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>

</table>