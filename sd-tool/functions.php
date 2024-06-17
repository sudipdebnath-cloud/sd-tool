<?php

function get_sd_tool_option($option_name){
	return get_option($option_name) ? get_option($option_name) : null;
}

function update_sd_tool_option($option_name, $option_value){
	update_option($option_name, $option_value);
}

function sd_tool_get_discount_amount($applied_discount, $max_discount = ''){
	if(!$applied_discount){
		return;
	}
	if ($max_discount) {
		if(floatval($applied_discount) > floatval($max_discount)){
			return floatval($max_discount);
		}
	}
	return $applied_discount;
}

?>