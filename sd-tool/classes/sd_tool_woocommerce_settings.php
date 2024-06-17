<?php
/**
 * 
 */
class SD_TOOL_WOOCOMMERCE_SETTINGS
{
	
	function __construct()
	{	
		global $SD_TOOL_GS;
		if($SD_TOOL_GS === null && class_exists( 'SD_TOOL_GENERAL_SETTINGS' )){
			$SD_TOOL_GS = new SD_TOOL_GENERAL_SETTINGS();
		}
		if($SD_TOOL_GS->has_enabled_woocommerce_features()){
			$this->sd_tool_trigger_woocommerce_actions();
		}
	}

	public function sd_tool_trigger_woocommerce_actions(){
		global $SD_TOOL_GS;
		if($SD_TOOL_GS->has_enabled_woocommerce_features()){

			add_action( 'wp_ajax_save_sd_tool_woocommerce_settings_data', array($this, 'save_sd_tool_woocommerce_settings_data') );

			if($this->has_disabled_woocommerce_sidebar()){
				remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
			}

			if($this->has_disabled_woocommerce_product_page_tabs()){
				add_filter( 'woocommerce_product_tabs', '__return_empty_array', 98 );
			}

			if($this->has_disabled_woocommerce_product_page_upsell_products()){
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			}

			if($this->has_disabled_woocommerce_product_page_related_products()){
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
				add_filter('woocommerce_product_related_posts_query', '__return_empty_array', 100);
			}

			add_filter( 'woocommerce_product_related_products_heading', array($this, 'modify_product_related_products_heading') );

			if($this->wrap_woocommerce_main_content()){
				add_action( 'woocommerce_before_main_content', array($this, 'woocommerce_before_main_content_func') );
				add_action( 'woocommerce_after_main_content', array($this, 'woocommerce_after_main_content_func') );
			}

			add_filter( 'post_class', array($this, 'products_loop_li_class'), 10, 3 );

			if($this->wrap_woocommerce_products_loop_image()){
				add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'woocommerce_before_shop_loop_item_title_func9'), 9 );
				add_action( 'woocommerce_before_shop_loop_item_title', array($this, 'woocommerce_before_shop_loop_item_title_func11'), 11 );
			}

			add_action( 'update_sd_tool_options', array($this, 'update_sd_tool_options_func'), 10, 1 );

			add_filter( 'woocommerce_cart_product_subtotal', array($this, 'sd_tool_filter_woocommerce_cart_item_subtotal'), 10, 4 );
			add_action( 'woocommerce_cart_calculate_fees' , array($this, 'sd_tool_calculate_discounts') );
			add_filter( 'sd_tool_cart_item_discount_note', array($this, 'sd_tool_cart_item_discount_note_func'), 10, 1 );
			add_filter( 'sd_tool_cart_total_discount_label', array($this, 'sd_tool_cart_total_discount_label_func'), 10, 1 );

			add_action('load_settings_templates', array($this, 'render_settings_templates'), 10, 1);

			$this->initiate_meta_boxes();

		}
	}

	public function render_settings_templates($custom_templates){
		$templates = array(
			'General Options' => SD_TOOL_DIR.'admin/templates/woocommerce/general-options',
			'Product Details Page' => SD_TOOL_DIR.'admin/templates/woocommerce/manage-product-details-page',
			'Products List' => SD_TOOL_DIR.'admin/templates/woocommerce/manage-products-list',
			'Discount Rules' => SD_TOOL_DIR.'admin/templates/woocommerce/manage-discounts',
		);
		$my_templates = $templates;
		if($custom_templates && is_array($custom_templates)){
			$my_templates = array_merge($templates, $custom_templates);
		}
		if($my_templates){
			echo '<ul class="accordion-list">';
			foreach ($my_templates as $template_title => $template_path) {
				echo '<li><h2 class="title">'.$template_title.'</h2><div class="content">';
				if(file_exists($template_path.'.php')){
					require_once $template_path.'.php';
				}else{
					echo 'File missing: '.$template_path.'.php';
				}
				echo '</div></li>';
			}
			echo '</ul>';
		}
	}

	public function initiate_meta_boxes(){
		if($this->enabled_global_discount() || $this->enabled_product_wise_discount()){
			add_action( 'add_meta_boxes', array( $this, 'adding_product_discount_meta_boxes'), 10, 2 );
			add_action( 'save_post', array($this, 'save_sd_tool_product_discount_meta_box_data') );
		}
	}

	public function adding_product_discount_meta_boxes(){
		add_meta_box( 
	        'sd-tool-product-discount-rules',
	        __( 'Discount Rules' ),
	        array($this, 'render_sd_tool_product_discount_rules'),
	        'product',
	        'normal',
	        'default'
	    );
	}

	public function render_sd_tool_product_discount_rules(){
		global $SD_BACKEND_TOOL;
		$SD_BACKEND_TOOL->render_admin_template('metaboxes/product_discount_rules');
	}

	public function save_sd_tool_product_discount_meta_box_data($post_id){
		// Check if our nonce is set.
	    if ( ! isset( $_POST['sd_tool_discount_rules_nonce'] ) ) {
	        return;
	    }
	    // Verify that the nonce is valid.
	    if ( ! wp_verify_nonce( $_POST['sd_tool_discount_rules_nonce'], 'sd_tool_discount_rules_nonce' ) ) {
	        return;
	    }
	    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return;
	    }
	    // Check the user's permissions.
	    if ( isset( $_POST['post_type'] ) && 'product' == $_POST['post_type'] ) {

	        if ( ! current_user_can( 'edit_page', $post_id ) ) {
	            return;
	        }

	    }
	    else {

	        if ( ! current_user_can( 'edit_post', $post_id ) ) {
	            return;
	        }
	    }

	    /* OK, it's safe for us to save the data now. */

	    $sd_tool_disable_product_global_discount = isset($_POST['sd_tool_disable_product_global_discount']) ? 1 : 0;
		$sd_tool_enable_product_custom_discount = isset($_POST['sd_tool_enable_product_custom_discount']) ? 1 : 0;

		$sd_tool_maximum_discount_amount = isset($_POST['sd_tool_maximum_discount_amount']) ? floatval($_POST['sd_tool_maximum_discount_amount']) : ''; 

		$product_discount_rule = isset($_POST['product_discount_rule']) ? $_POST['product_discount_rule'] : null;
		$product_discount_rules_arr = array();
		if($product_discount_rule){
			if(isset($product_discount_rule['min_qty'])){
				for ($i=0; $i < count($product_discount_rule['min_qty']); $i++) { 
					$product_discount_rules_arr[] = array(
						'min_qty' => $product_discount_rule['min_qty'][$i] ? intval($product_discount_rule['min_qty'][$i]) : '',
						'max_qty' => $product_discount_rule['max_qty'][$i] ? intval($product_discount_rule['max_qty'][$i]) : '',
						'discount' => $product_discount_rule['discount'][$i] ? floatval($product_discount_rule['discount'][$i]) : '',
						'discount_type' => $product_discount_rule['discount_type'][$i],
						'cart_table_text' => wp_strip_all_tags($product_discount_rule['cart_table_text'][$i]),
					);
				}
			} 
		}
		$product_discount_rules_str = $product_discount_rules_arr ? serialize($product_discount_rules_arr) : null;

	    // Update the meta field in the database.
	    update_post_meta( $post_id, 'sd_tool_disable_product_global_discount', $sd_tool_disable_product_global_discount );
	    update_post_meta( $post_id, 'sd_tool_enable_product_custom_discount', $sd_tool_enable_product_custom_discount );
	    update_post_meta( $post_id, 'sd_tool_product_discount_rules', $product_discount_rules_str );
	    update_post_meta( $post_id, 'sd_tool_maximum_discount_amount', $sd_tool_maximum_discount_amount );
	}

	public function has_disabled_woocommerce_sidebar(){
		$disabled_woocommerce_sidebar = get_sd_tool_option('sd_tool_disabled_woocommerce_sidebar');
		if($disabled_woocommerce_sidebar && intval($disabled_woocommerce_sidebar) == 1){
			return true;
		}
	}

	public function has_disabled_woocommerce_product_page_tabs(){
		$disabled_woocommerce_product_page_tabs = get_sd_tool_option('sd_tool_disabled_woocommerce_product_page_tabs');
		if($disabled_woocommerce_product_page_tabs && intval($disabled_woocommerce_product_page_tabs) == 1){
			return true;
		}
	}

	public function has_disabled_woocommerce_product_page_upsell_products(){
		$disabled_woocommerce_product_page_upsell_products = get_sd_tool_option('sd_tool_disabled_woocommerce_product_page_upsell_products');
		if($disabled_woocommerce_product_page_upsell_products && intval($disabled_woocommerce_product_page_upsell_products) == 1){
			return true;
		}
	}

	public function has_disabled_woocommerce_product_page_related_products(){
		$disabled_woocommerce_product_page_related_products = get_sd_tool_option('sd_tool_disabled_woocommerce_product_page_related_products');
		if($disabled_woocommerce_product_page_related_products && intval($disabled_woocommerce_product_page_related_products) == 1){
			return true;
		}
	}

	public function wrap_woocommerce_main_content(){
		$sd_tool_wrap_woocommerce_main_content = get_sd_tool_option('sd_tool_wrap_woocommerce_main_content');
		if($sd_tool_wrap_woocommerce_main_content && intval($sd_tool_wrap_woocommerce_main_content) == 1){
			return true;
		}
	}

	public function wrap_woocommerce_products_loop_image(){
		$sd_tool_wrap_woocommerce_products_loop_image = get_sd_tool_option('sd_tool_wrap_woocommerce_products_loop_image');
		if($sd_tool_wrap_woocommerce_products_loop_image && intval($sd_tool_wrap_woocommerce_products_loop_image) == 1){
			return true;
		}
	}

	public function modify_product_related_products_heading(){
		$related_products_section_heading = get_sd_tool_option('sd_tool_related_products_section_heading');
		return $related_products_section_heading ? $related_products_section_heading : 'Related products';
	}

	public function woocommerce_before_main_content_func(){
		$sd_tool_woocommerce_main_content_wrapper_class = get_sd_tool_option('sd_tool_woocommerce_main_content_wrapper_class');
		echo '<div class="'.$sd_tool_woocommerce_main_content_wrapper_class.'">';
	}

	public function woocommerce_after_main_content_func(){
		echo '</div>';
	}

	public function products_loop_li_class($classes, $class, $product_id){
		$sd_tool_woocommerce_products_loop_item_custom_class = get_sd_tool_option('sd_tool_woocommerce_products_loop_item_custom_class');
		$clss = $sd_tool_woocommerce_products_loop_item_custom_class ? explode(' ', $sd_tool_woocommerce_products_loop_item_custom_class) : array();
		$classes = array_merge($clss, $classes);
		return $classes;
	}

	public function woocommerce_before_shop_loop_item_title_func9(){
		$sd_tool_woocommerce_products_loop_wrapper_class = get_sd_tool_option('sd_tool_woocommerce_products_loop_wrapper_class');
		echo '<div class="'.$sd_tool_woocommerce_products_loop_wrapper_class.'">';
	}

	public function woocommerce_before_shop_loop_item_title_func11(){
		echo '</div>';
	}

	public function update_sd_tool_options_func($data){
		if($data){
			foreach ($data as $key => $value) {
				update_sd_tool_option($key, $value);
			}
		}
	}

	public function save_sd_tool_woocommerce_settings_data(){
		$nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'sd-tool-woocommerce-settings' ) ) {
		    print_r("Invalid request!");
		}else{
			$sd_tool_disabled_woocommerce_sidebar = isset($_POST['sd_tool_disabled_woocommerce_sidebar']) ? 1 : 0;

			$sd_tool_disabled_woocommerce_product_page_tabs = isset($_POST['sd_tool_disabled_woocommerce_product_page_tabs']) ? 1 : 0;

			$sd_tool_disabled_woocommerce_product_page_upsell_products = isset($_POST['sd_tool_disabled_woocommerce_product_page_upsell_products']) ? 1 : 0;

			$sd_tool_disabled_woocommerce_product_page_related_products = isset($_POST['sd_tool_disabled_woocommerce_product_page_related_products']) ? 1 : 0;

			$sd_tool_related_products_section_heading = isset($_POST['sd_tool_related_products_section_heading']) ? $_POST['sd_tool_related_products_section_heading'] : '';

			$sd_tool_wrap_woocommerce_main_content = isset($_POST['sd_tool_wrap_woocommerce_main_content']) ? 1 : 0;

			$sd_tool_woocommerce_main_content_wrapper_class = isset($_POST['sd_tool_woocommerce_main_content_wrapper_class']) ? $_POST['sd_tool_woocommerce_main_content_wrapper_class'] : '';

			$sd_tool_woocommerce_products_loop_item_custom_class = isset($_POST['sd_tool_woocommerce_products_loop_item_custom_class']) ? $_POST['sd_tool_woocommerce_products_loop_item_custom_class'] : '';

			$sd_tool_wrap_woocommerce_products_loop_image = isset($_POST['sd_tool_wrap_woocommerce_products_loop_image']) ? 1 : 0;

			$sd_tool_woocommerce_products_loop_wrapper_class = isset($_POST['sd_tool_woocommerce_products_loop_wrapper_class']) ? $_POST['sd_tool_woocommerce_products_loop_wrapper_class'] : '';

			$sd_tool_enable_global_discount = isset($_POST['sd_tool_enable_global_discount']) ? $_POST['sd_tool_enable_global_discount'] : '';

			$sd_tool_enable_global_discount_conditions = isset($_POST['sd_tool_enable_global_discount_conditions']) ? $_POST['sd_tool_enable_global_discount_conditions'] : '';

			$sd_tool_order_total_exluding_shippng_taxes_for_global_discount = isset($_POST['sd_tool_order_total_exluding_shippng_taxes_for_global_discount']) ? floatval($_POST['sd_tool_order_total_exluding_shippng_taxes_for_global_discount']) : '';

			$sd_tool_maximum_discount_amount = isset($_POST['sd_tool_maximum_discount_amount']) ? floatval($_POST['sd_tool_maximum_discount_amount']) : '';

			$sd_tool_enable_category_wise_discount = isset($_POST['sd_tool_enable_category_wise_discount']) ? $_POST['sd_tool_enable_category_wise_discount'] : '';

			$sd_tool_enable_product_wise_discount = isset($_POST['sd_tool_enable_product_wise_discount']) ? $_POST['sd_tool_enable_product_wise_discount'] : '';


			$global_discount_rule = isset($_POST['global_discount_rule']) ? $_POST['global_discount_rule'] : null;
			$global_discount_rules_arr = array();
			if($global_discount_rule){
				if(isset($global_discount_rule['min_qty'])){
					for ($i=0; $i < count($global_discount_rule['min_qty']); $i++) { 
						$global_discount_rules_arr[] = array(
							'min_qty' => $global_discount_rule['min_qty'][$i] ? intval($global_discount_rule['min_qty'][$i]) : '',
							'max_qty' => $global_discount_rule['max_qty'][$i] ? intval($global_discount_rule['max_qty'][$i]) : '',
							'discount' => $global_discount_rule['discount'][$i] ? floatval($global_discount_rule['discount'][$i]) : '',
							'discount_type' => $global_discount_rule['discount_type'][$i],
							'cart_table_text' => wp_strip_all_tags($global_discount_rule['cart_table_text'][$i]),
						);
					}
				} 
			}
			$global_discount_rules_str = $global_discount_rules_arr ? serialize($global_discount_rules_arr) : null;

			$post_data = array(
				'sd_tool_disabled_woocommerce_sidebar' => $sd_tool_disabled_woocommerce_sidebar,
				'sd_tool_disabled_woocommerce_product_page_tabs' => $sd_tool_disabled_woocommerce_product_page_tabs,
				'sd_tool_disabled_woocommerce_product_page_upsell_products' => $sd_tool_disabled_woocommerce_product_page_upsell_products,
				'sd_tool_disabled_woocommerce_product_page_related_products' => $sd_tool_disabled_woocommerce_product_page_related_products,
				'sd_tool_related_products_section_heading' => $sd_tool_related_products_section_heading,
				'sd_tool_wrap_woocommerce_main_content' => $sd_tool_wrap_woocommerce_main_content,
				'sd_tool_woocommerce_main_content_wrapper_class' => $sd_tool_woocommerce_main_content_wrapper_class,
				'sd_tool_woocommerce_products_loop_item_custom_class' => $sd_tool_woocommerce_products_loop_item_custom_class,
				'sd_tool_wrap_woocommerce_products_loop_image' => $sd_tool_wrap_woocommerce_products_loop_image,
				'sd_tool_woocommerce_products_loop_wrapper_class' => $sd_tool_woocommerce_products_loop_wrapper_class,
				'sd_tool_enable_global_discount' => $sd_tool_enable_global_discount,
				'sd_tool_enable_global_discount_conditions' => $sd_tool_enable_global_discount_conditions,
				'sd_tool_order_total_exluding_shippng_taxes_for_global_discount' => $sd_tool_order_total_exluding_shippng_taxes_for_global_discount,
				'sd_tool_maximum_discount_amount' => $sd_tool_maximum_discount_amount,
				'sd_tool_enable_category_wise_discount' => $sd_tool_enable_category_wise_discount,
				'sd_tool_enable_product_wise_discount' => $sd_tool_enable_product_wise_discount,
				'sd_tool_global_discount_rules_quantity_wise' => $global_discount_rules_str
			);

			/*echo '<pre>';
			print_r($post_data);
			echo '</pre>'; die();*/

			do_action('update_sd_tool_options', $post_data);

			$response = array(
				'updated' => true
			);
			wp_send_json($response);
		}
		wp_die();
	}

	public function enabled_global_discount(){
		$sd_tool_enable_global_discount = get_sd_tool_option('sd_tool_enable_global_discount');
		if($sd_tool_enable_global_discount){
			return true;
		}
	}

	public function enabled_global_discount_conditions(){
		$sd_tool_enable_global_discount_conditions = get_sd_tool_option('sd_tool_enable_global_discount_conditions');
		if($sd_tool_enable_global_discount_conditions){
			return true;
		}
	}

	public function get_maximum_discount_amount($product_id=''){
		if($product_id){
			$max_discount_amt = get_post_meta($product_id, 'sd_tool_maximum_discount_amount', true) ? floatval(get_post_meta($product_id, 'sd_tool_maximum_discount_amount', true)) : '';
			return $max_discount_amt;
		}
		return get_sd_tool_option('sd_tool_maximum_discount_amount');
	}

	public function enabled_category_wise_discount(){
		$sd_tool_enable_category_wise_discount = get_sd_tool_option('sd_tool_enable_category_wise_discount');
		if($sd_tool_enable_category_wise_discount){
			return true;
		}
	}

	public function enabled_product_wise_discount(){
		$sd_tool_enable_product_wise_discount = get_sd_tool_option('sd_tool_enable_product_wise_discount');
		if($sd_tool_enable_product_wise_discount){
			return true;
		}
	}

	public function get_global_discount_rules_quantity_wise(){
		if($gdrqw = get_sd_tool_option('sd_tool_global_discount_rules_quantity_wise')){
			return unserialize($gdrqw);
		}
	}

	public function enable_product_custom_discount($product_id){
		$sd_tool_enable_product_custom_discount = get_post_meta($product_id, 'sd_tool_enable_product_custom_discount', true);
		if($sd_tool_enable_product_custom_discount && intval($sd_tool_enable_product_custom_discount) == 1){
			return true;
		}
	}

	public function get_product_discount_rules($product_id){
		if($sd_tool_product_discount_rules = get_post_meta($product_id, 'sd_tool_product_discount_rules', true)){
			if($sd_tool_product_discount_rules && $this->enable_product_custom_discount($product_id) && $this->enabled_product_wise_discount()){
				return unserialize($sd_tool_product_discount_rules);
			}
		}
	}

	public function sd_tool_cart_item_discount_note_func($cart_item_discount_note){
		return $cart_item_discount_note;
	}

	public function sd_tool_cart_total_discount_label_func($cart_total_discount_label){
		return $cart_total_discount_label;
	}

	public function disable_product_global_discount($product_id){
		$sd_tool_disable_product_global_discount = get_post_meta($product_id, 'sd_tool_disable_product_global_discount', true);
		if($sd_tool_disable_product_global_discount && intval($sd_tool_disable_product_global_discount) == 1){
			return true;
		}
	}

	public function disable_product_category_wise_discount($product_id){
		$sd_tool_disable_product_category_wise_discount = get_post_meta($product_id, 'sd_tool_disable_product_category_wise_discount', true);
		if($sd_tool_disable_product_category_wise_discount && intval($sd_tool_disable_product_category_wise_discount) == 1){
			return true;
		}
	}

	public function match_discount_rules_conditions(){
		global $woocommerce;
		$return = true;
		if($this->enabled_global_discount_conditions()){
			$otestfgd = get_sd_tool_option('sd_tool_order_total_exluding_shippng_taxes_for_global_discount');
			if(floatval($otestfgd) > 0){
				$otestfgd_val = floatval($otestfgd);
				$cart_items_total = WC()->cart->get_cart_contents_total();
				if($cart_items_total < $otestfgd_val){
					$return = false;
				}
			}
		}
		return $return;
	}

	public function sd_tool_filter_woocommerce_cart_item_subtotal($product_subtotal, $product, $quantity, $cart){
		$subtotal_string = trim(wp_strip_all_tags($product_subtotal));
		$currency_symbol = get_woocommerce_currency_symbol();
		$subtotal_value = str_replace([',', $currency_symbol], '', $subtotal_string);
		$product_id = $product->is_type( 'variable' ) ? $product->parent_id : $product->get_id();

		$enable_global_discount = $this->enabled_global_discount();
		$enabled_category_wise_discount = $this->enabled_category_wise_discount();
		$enabled_product_wise_discount = $this->enabled_product_wise_discount();

		if($this->disable_product_global_discount($product_id)){
			$enable_global_discount = false;
		}
		if($this->disable_product_category_wise_discount($product_id)){
			$enabled_category_wise_discount = false;
		}

		$product_subtotal_modified = $product_subtotal;
		$discount_rules = null;
		if($enable_global_discount){
			$discount_rules = $this->get_global_discount_rules_quantity_wise();
		}
		if($this->get_product_discount_rules($product_id)){
			$discount_rules = $this->get_product_discount_rules($product_id);
		}
		if($discount_rules && $this->match_discount_rules_conditions()){

			$discount_value = 0;
			$cart_table_text = apply_filters( 'sd_tool_cart_item_discount_note', 'Discount applied!' );
			$total_price = floatval($subtotal_value);
			foreach ($discount_rules as $rule) {
				$min_qty = intval($rule['min_qty']);
				$max_qty = $rule['max_qty'] ? intval($rule['max_qty']) : '';
				$discount = floatval($rule['discount']);
				$discount_type = $rule['discount_type'];
				$cart_table_text = $rule['cart_table_text'] ? $rule['cart_table_text'] : $cart_table_text;
				if($max_qty){
					if($quantity >= $min_qty && $quantity <= $max_qty){
						switch ($discount_type) {
							case 'percentage':
								$discount_value = $total_price * ($discount/100);
								break;
							
							case 'flat':
								$discount_value = $discount;
								break;
						}
					}
				}else{
					if($quantity >= $min_qty){
						switch ($discount_type) {
							case 'percentage':
								$discount_value = $total_price * ($discount/100);
								break;
							
							case 'flat':
								$discount_value = $discount;
								break;
						}
					}
				}
			}
			if($discount_value && floatval($discount_value) > 0){
				$get_maximum_discount_amount = $this->get_maximum_discount_amount($product_id) ? $this->get_maximum_discount_amount($product_id) : $this->get_maximum_discount_amount();
				$discount_value = sd_tool_get_discount_amount($discount_value, floatval($get_maximum_discount_amount));
				$new_price = $total_price - $discount_value;
				$product_subtotal_modified = wc_price($new_price);
				$product_subtotal_modified .= '<p class="info cart_discount_label">'.str_replace('%discount_value%', '<span>'.wc_price($discount_value).'</span>', $cart_table_text).'</p>';
			}
		}
		echo $product_subtotal_modified;
	}

	public function sd_tool_calculate_discounts($cart_object){
		global $woocommerce;
    	if($cart_object->get_cart()){
    		$total_discount = 0;
    		$cart_subtotal = WC()->cart->get_cart_contents_total();
			foreach ($cart_object->get_cart() as $hashkey => $cart_item) {
				$product_id = $cart_item['product_id'];
				$quantity = $cart_item['quantity'];

				$enable_global_discount = $this->enabled_global_discount();
				$enabled_category_wise_discount = $this->enabled_category_wise_discount();
				$enabled_product_wise_discount = $this->enabled_product_wise_discount();

				if($this->disable_product_global_discount($product_id)){
					$enable_global_discount = false;
				}
				if($this->disable_product_category_wise_discount($product_id)){
					$enabled_category_wise_discount = false;
				}

				$discount_rules = null;
				if($enable_global_discount){
					$discount_rules = $this->get_global_discount_rules_quantity_wise();
				}
				if($this->get_product_discount_rules($product_id)){
					$discount_rules = $this->get_product_discount_rules($product_id);
				}
				if($discount_rules && $this->match_discount_rules_conditions()){
					$discount_value = 0;
					$total_price = floatval($cart_item['line_total']);
					foreach ($discount_rules as $rule) {
						$min_qty = intval($rule['min_qty']);
						$max_qty = $rule['max_qty'] ? intval($rule['max_qty']) : '';
						$discount = floatval($rule['discount']);
						$discount_type = $rule['discount_type'];
						if($max_qty){
							if($quantity >= $min_qty && $quantity <= $max_qty){

								switch ($discount_type) {
									case 'percentage':
										$discount_value = $total_price * ($discount/100);
										break;
									
									case 'flat':
										$discount_value = $discount;
										break;
								}
							}
						}else{
							if($quantity >= $min_qty){
								switch ($discount_type) {
									case 'percentage':
										$discount_value = $total_price * ($discount/100);
										break;
									
									case 'flat':
										$discount_value = $discount;
										break;
								}
							}
						}
					}
					if($discount_value && floatval($discount_value) > 0){
						$get_maximum_discount_amount = $this->get_maximum_discount_amount($product_id) ? $this->get_maximum_discount_amount($product_id) : $this->get_maximum_discount_amount();

						$discount_value = sd_tool_get_discount_amount($discount_value, floatval($get_maximum_discount_amount));
						$total_discount = $total_discount + $discount_value;
					}
				}
			}
		}
    	
    	if(floatval($total_discount) > 0){
    		$cart_total_discount_label = apply_filters( 'sd_tool_cart_total_discount_label', 'Total Discount' );
    		/*if($this->get_maximum_discount_amount() && $total_discount > floatval($this->get_maximum_discount_amount())){
    			$total_discount = floatval($this->get_maximum_discount_amount());
    		}*/
	    	$discount_fee = '-'.$total_discount;
	    	$woocommerce->cart->add_fee( __($cart_total_discount_label, 'woocommerce'), $discount_fee );
	    }
	}

}

if(class_exists( 'SD_TOOL_WOOCOMMERCE_SETTINGS' )){
	$SD_TOOL_WS = new SD_TOOL_WOOCOMMERCE_SETTINGS();
}

?>