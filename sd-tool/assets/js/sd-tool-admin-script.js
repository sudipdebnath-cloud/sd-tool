(function($){
	"use strict";
	console.log("SD TOOL is working..");

	/*Accordian start*/
	if($('.accordion-list').length){
		$('.accordion-list > li > .content').hide();
	  	$('.accordion-list > li .title').click(function() {
	    	if ($(this).closest("li").hasClass("active")) {
	      		$(this).closest("li").removeClass("active").find(".content").slideUp();
	    	} else {
	      		$(".accordion-list > li.active .content").slideUp();
	      		$(".accordion-list > li.active").removeClass("active");
	      		$(this).closest("li").addClass("active").find(".content").slideDown();
	    	}
	    	return false;
	  	});
	}
	/*Accordian end*/

	var sd_ajax_uri = ajaxurl;

	function call_sd_ajax($data, $beforeSend, $success){
		$.ajax({
			url: sd_ajax_uri,
			type: 'post',
			dataType: 'json',
			processData: false,
    		contentType: false,
			cache: false,
			data: $data,
			beforeSend: $beforeSend,
			success: $success
		});
	}

	$("input[type=checkbox]").click(function(){
		if($(this).closest(".switch_checkbox_label").length){
			var on_text = $(this).attr("data-on_text");
			var off_text = $(this).attr("data-off_text");
			var $on_off_text = $(this).closest(".switch_checkbox_label").find(".on_off_text");
			if(on_text && off_text && $on_off_text.length){
				if($(this).is(":checked")){
					$on_off_text.text(on_text).removeClass("disabled").addClass("enabled");
				}else{
					$on_off_text.text(off_text).removeClass("enabled").addClass("disabled");
				}
			}
		}

		if($(this).closest("label").hasClass("oncheck_hide")){
			var _targets = $(this).closest("label").attr("data-targets");
			var _targets_arr = _targets.split(",");
			if(_targets_arr){
				for (var i = 0; i < _targets_arr.length; i++) {
					if($("."+_targets_arr[i]).length){
						if($(this).is(":checked")){
							$("."+_targets_arr[i]).css("display", "none");
						}else{
							$("."+_targets_arr[i]).css("display", "contents");
						}
					}
				}
			}
		}

		if($(this).closest("label").hasClass("oncheck_show")){
			var _targets = $(this).closest("label").attr("data-targets");
			var _targets_arr = _targets.split(",");
			if(_targets_arr){
				for (var i = 0; i < _targets_arr.length; i++) {
					if($("."+_targets_arr[i]).length){
						if($(this).is(":checked")){
							$("."+_targets_arr[i]).css("display", "contents");
						}else{
							$("."+_targets_arr[i]).css("display", "none");
						}
					}
				}
			}
		}

	});


	$(window).load(function(){
		$("input[type=checkbox]").each(function(){
			if($(this)[0].hasAttribute("checked")){
				$(this).prop("checked", true);
			}else{
				$(this).prop("checked", false);
			}
		});
	});


	$("#sd-tool-general-settings-form").submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		data.append('action', 'save_sd_tool_general_settings_data');
		var $submit_btn = $(this).find("[type=submit]");
		call_sd_ajax(data,
		function(){
			$submit_btn.attr("disabled", "disabled");
		},
		function(Res){
			if(Res.updated){
				location.reload();
			}
		});
	});

	$("#sd-tool-woocommerce-settings-form").submit(function(e){
		e.preventDefault();
		var $this = $(this);
		var _submit = true;
		$this.find("input").each(function(){
			if($(this).prop('required') && $.trim($(this).val()) == ''){
				_submit = false;
			}
		});
		var data = new FormData(this);
		data.append('action', 'save_sd_tool_woocommerce_settings_data');
		var $submit_btn = $this.find("[type=submit]");
		if(_submit == true){
			call_sd_ajax(data,
			function(){
				$submit_btn.attr("disabled", "disabled");
			},
			function(Res){
				if(Res.updated){
					$("#ajax_response_message").text("Updated").addClass("success ml-14");
					$submit_btn.removeAttr("disabled");
					setTimeout(function(){
						$("#ajax_response_message").removeClass("success ml-14").text("");
					},2000);
				}
			});
		}
	});

	$("#add_new_global_discount_rule").click(function(){
		var global_discount_tr = `<tr>
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
									<td colspan="2"><div class="d-flex"><input type="text" name="global_discount_rule[cart_table_text][]" class="cart_table_text"><button type="button" class="button button-danger" onclick="removeTheDiscountTr(this)">-</button></div></td>
								</tr>`;
		if($("#global_discount_rules_table tbody").find("tr").length == 1 && $("#global_discount_rules_table tbody").find("tr").eq(0).hasClass("blank_row")){
			$("#global_discount_rules_table tbody").html(global_discount_tr);
		}else{
			$("#global_discount_rules_table tbody").append(global_discount_tr);
		}
	});

	$("#add_new_product_discount_rule").click(function(){
		var product_discount_tr = `<tr>
									<td><input type="number" name="product_discount_rule[min_qty][]" class="min_qty required" min="1" required></td>
									<td><input type="number" name="product_discount_rule[max_qty][]" class="max_qty" min="1"></td>
									<td>
										<div class="d-flex">
											<input type="number" name="product_discount_rule[discount][]" class="discount" min="1" step="0.01">
											<select name="product_discount_rule[discount_type][]" class="discount_type">
												<option value="percentage">%</option>
												<option value="flat">Flat</option>
											</select>
										</div>
									</td>
									<td colspan="2"><div class="d-flex"><input type="text" name="product_discount_rule[cart_table_text][]" class="cart_table_text"><button type="button" class="button button-danger" onclick="removeTheDiscountTr(this)">-</button></div></td>
								</tr>`;
		if($("#product_discount_rules_table tbody").find("tr").length == 1 && $("#product_discount_rules_table tbody").find("tr").eq(0).hasClass("blank_row")){
			$("#product_discount_rules_table tbody").html(product_discount_tr);
		}else{
			$("#product_discount_rules_table tbody").append(product_discount_tr);
		}
	});

	// On check uncheck global discount
	$("#sd_tool_enable_global_discount").click(function(){
		if($(this).is(":checked")){
			if($("#global_discount_rules_table").find(".min_qty").length){
				$("#global_discount_rules_table").find(".min_qty").each(function(){
					$(this).attr("required", "required");
				});
			}
		}else{
			if($("#global_discount_rules_table").find(".min_qty").length){
				$("#global_discount_rules_table").find(".min_qty").each(function(){
					$(this).removeAttr("required");
				});
			}
		}
	});

	// On check uncheck product discount
	$("#sd_tool_enable_product_custom_discount").click(function(){
		if($(this).is(":checked")){
			if($("#product_discount_rules_table").find(".min_qty").length){
				$("#product_discount_rules_table").find(".min_qty").each(function(){
					$(this).attr("required", "required");
				});
			}
		}else{
			if($("#product_discount_rules_table").find(".min_qty").length){
				$("#product_discount_rules_table").find(".min_qty").each(function(){
					$(this).removeAttr("required");
				});
			}
		}
	});

	$("#create_shortcode_form").submit(function(e){
		e.preventDefault();
		var $this = $(this);
		var _submit = true;
		var data = new FormData(this);
		data.append('action', 'generate_sd_tool_shortcode');
		var $submit_btn = $this.find("[type=submit]");
		if(_submit == true){
			call_sd_ajax(data,
			function(){
				$submit_btn.attr("disabled", "disabled");
			},
			function(Res){
				if(Res.data){
					$("#show_generated_shortcode").html(Res.data);
					if($this.closest(".sd_modal").find(".sd_modal_close").length){
						$this.find("[type=submit]").removeAttr("disabled");
						$this.closest(".sd_modal").find(".sd_modal_close").trigger("click");

						$(".gen_shortcode_heading, .gen_shortcode_copy").css("display", "block");

					}
				}
			});
		}
	});

	$(".sd_modal_open").click(function(){
		var target_modal = $(this).attr("target-modal");
		if(target_modal && typeof target_modal !== undefined){
			if($("#"+target_modal)){
				$("#"+target_modal).css("display", "block");
			}
		}
	});

	$(".sd_modal_close").click(function(){
		$(this).closest(".sd_modal").css("display", "none");
	});

})(jQuery);

if(typeof jQuery !== undefined){
	var $ = jQuery.noConflict();	
}
function removeTheDiscountTr(e){
	var closest_tbody = $(e).closest("td").closest("tr").closest("tbody");
	$(e).closest("td").closest("tr").remove();
	if(closest_tbody.find("tr").length == 0){
		var blank_tr = `<tr class="blank_row">
						<td colspan="5" align="center">No rules added. Please click on the (+) button to add a new rule.</td>
					</tr>`;
		closest_tbody.html(blank_tr);
	}
}

function copyToClipboard(text) {
  	var input = document.body.appendChild(document.createElement("input"));
  	input.value = text;
  	input.select();
  	document.execCommand('copy');
  	input.parentNode.removeChild(input);
}

function copyShortcode(e){
	var $elem_selector = "#show_generated_shortcode";
	var myText = $($elem_selector).text();
	if(!$(e).hasClass("copied")){
		copyToClipboard(myText);
		$(e).addClass("copied");
		$(e).text('Copied');
		setTimeout(function(){
			$(e).removeClass("copied");
			$(e).text('Copy');
		},2000);
	}
}