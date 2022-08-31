<?php

/**
 * 
 */
class SD_TOOL_SHORTCODES
{
	
	function __construct()
	{
		$this->initiate_shortcodes();
		$this->initiate_filters();
		$this->initiate_actions();
	}

	public function initiate_shortcodes(){
		add_shortcode( 'sd_site_title', array($this, 'sd_site_title_func') );
		add_shortcode( 'sd_year', array($this, 'sd_year_func') );
		add_shortcode( 'sd_post_title', array($this, 'sd_post_title_func') );
		add_shortcode( 'sd_featured_image', array($this, 'sd_featured_image_func') );
		add_shortcode( 'sd_posts_list', array($this, 'sd_posts_list_func') );
	}

	public function initiate_filters(){
		add_filter('sd_posts_list_thumbnail_link_outside_wrapper', array($this, 'sd_posts_list_thumbnail_link_outside_wrapper_func'), 10, 2);
		add_filter('sd_posts_list_title', array($this, 'sd_posts_list_title_func'), 10, 2);
		add_filter('sd_posts_list_excerpt', array($this, 'sd_posts_list_excerpt_func'), 10, 2);
	}

	public function initiate_actions(){
		add_action('wp_ajax_generate_sd_tool_shortcode', array($this, 'generate_sd_tool_shortcode_callback'));
	}

	public function sd_posts_list_thumbnail_link_outside_wrapper_func($link_tag, $post_type){
		return $link_tag;
	}

	public function sd_posts_list_title_func($post_title, $post_type){
		return $post_title;
	}

	public function sd_posts_list_excerpt_func($post_excerpt, $post_type){
		return $post_excerpt;
	}

	public function sd_site_title_func(){
		return get_bloginfo('name');
	}

	public function sd_year_func(){
		ob_start();
		echo date('Y');
		return ob_get_clean();
	}

	public function sd_post_title_func(){
		ob_start();
		$post_id = get_queried_object_id();
		if(get_the_title($post_id)){
			echo get_the_title($post_id);
		}
		return ob_get_clean();
	}

	public function sd_featured_image_func(){
		ob_start();
		$post_id = get_queried_object_id();
		if(has_post_thumbnail($post_id)){
			echo get_the_post_thumbnail($post_id, 'full');
		}
		return ob_get_clean();
	}

	private function sd_create_shortcode_elem_start($elem, $default_class = ''){
		$item_wrapper_str = '<';
		$item_wrapper = $elem ? $elem : $tag.'|class:'.$default_class;
	    if($item_wrapper){
	    	$item_wrapper_arr = explode("|", $item_wrapper);
	    	if($item_wrapper_arr){
	    		$count = 1;
	    		foreach ($item_wrapper_arr as $key => $value) {
	    			if($count == 1){
	    				$item_wrapper_str .= $value.' ';
	    			}else{
	    				$value_arr = $value ? explode(":", $value) : null;
	    				$_key = isset($value_arr[0]) ? $value_arr[0] : '';
	    				$_value = isset($value_arr[1]) ? $value_arr[1] : '';
	    				if($_key && $_value){
	    					if($_key == 'class' && $default_class){
    							$_value = $_value.' '.$default_class;
    						}
	    					$item_wrapper_str .= $_key.'="'.$_value.'" ';
	    				}
	    			}
	    		$count++;}
	    	}
	    }
	    return trim($item_wrapper_str).'>';
	}

	private function sd_create_shortcode_elem_end($elem){
		$item_wrapper_str = '</';
		$tag = 'div';
		$item_wrapper = $elem ? $elem : $tag.'|class:'.$default_class;
	    if($item_wrapper){
	    	$item_wrapper_arr = explode("|", $item_wrapper);
	    	$tag = $item_wrapper_arr[0];
	    	$item_wrapper_str .= $tag;
	    }
	    return trim($item_wrapper_str).'>';
	}

	public function sd_posts_list_func($atts){
		ob_start();
		extract(shortcode_atts(array(
			'post_type' => '',
			'limit' => 12,
	        'item_wrapper' => 'div',
	        'has_thumbnail' => 'true',
	        'thumbnail_size' => 'post-thumbnail',
	        'thumbnail_linkable' => 'true',
	        'thumbnail_link_outside_wrapper' => 'false',
	        'thumbnail_wrapper' => '',
	        'has_title' => 'true',
	        'title_linkable' => 'false',
	        'title_link_outside_wrapper' => 'false',
	        'title_wrapper' => 'h2',
	        'has_excerpt' => 'true',
	        'excerpt_wrapper' => '',
	        'has_readmore' => 'true',
	        'readmore_class' => 'sd_read_more',
	        'readmore_text' => 'Read more',
	    ), $atts, 'sd_posts_list'));
	    
	    if($post_type){
	    	$posts = get_posts(
	    		array(
	    			'post_type' => $post_type,
	    			'numberposts' => $limit,
	    		)
	    	);
	    }

	    $html = '';
	    if($posts){
	    	foreach ($posts as $post) {
	    		$html .= $this->sd_create_shortcode_elem_start($item_wrapper, 'sd_item');

	    		//	Thambnail
	    		if($has_thumbnail == 'true'){
	    			if($thumbnail_linkable = 'true' && $thumbnail_link_outside_wrapper == 'true'){
	    				$html .= apply_filters( 'sd_posts_list_thumbnail_link_outside_wrapper', '<a href="'.get_permalink($post->ID).'">', $post_type );
	    			}
	    			if($thumbnail_wrapper){
	    				$html .= $this->sd_create_shortcode_elem_start($thumbnail_wrapper);
	    			}
	    			if($thumbnail_linkable = 'true' && $thumbnail_link_outside_wrapper == 'false'){
	    				$html .= '<a href="'.get_permalink($post->ID).'">';
	    			}
			    	$html .= get_the_post_thumbnail($post->ID, $thumbnail_size);
			    	if($thumbnail_linkable = 'true' && $thumbnail_link_outside_wrapper == 'false'){
	    				$html .= '</a>';
	    			}
			    	if($thumbnail_wrapper){
	    				$html .= $this->sd_create_shortcode_elem_end($thumbnail_wrapper);
	    			}
	    			if($thumbnail_linkable = 'true' && $thumbnail_link_outside_wrapper == 'true'){
	    				$html .= '</a>';
	    			}
	    		}

	    		//	Title
	    		if($has_title == 'true'){

	    			if($title_linkable == 'true'){
	    				if($title_link_outside_wrapper == 'true'){
		    				$html .= '<a href="'.get_permalink($post->ID).'">';
		    			}
		    			if($title_wrapper){
		    				$html .= $this->sd_create_shortcode_elem_start($title_wrapper);
		    			}
		    			if($title_link_outside_wrapper == 'false'){
		    				$html .= '<a href="'.get_permalink($post->ID).'">';
		    			}
				    	$html .= apply_filters( 'sd_posts_list_title', get_the_title($post->ID), $post_type );
				    	if($title_link_outside_wrapper == 'false'){
		    				$html .= '</a>';
		    			}
				    	if($title_wrapper){
		    				$html .= $this->sd_create_shortcode_elem_end($title_wrapper);
		    			}
		    			if($title_link_outside_wrapper == 'true'){
		    				$html .= '</a>';
		    			}
	    			}else{
		    			if($title_wrapper){
		    				$html .= $this->sd_create_shortcode_elem_start($title_wrapper);
		    			}
				    	$html .= apply_filters( 'sd_posts_list_title', get_the_title($post->ID), $post_type );
				    	if($title_wrapper){
		    				$html .= $this->sd_create_shortcode_elem_end($title_wrapper);
		    			}
	    			}
	   
	    		}

	    		//	Excerpt
	    		if($has_excerpt == 'true'){
	    			if($excerpt_wrapper){
	    				$html .= $this->sd_create_shortcode_elem_start($excerpt_wrapper);
	    			}
	    			$html .= apply_filters('sd_posts_list_excerpt', wpautop(get_the_excerpt($post->ID)), $post_type);
	    			if($excerpt_wrapper){
	    				$html .= $this->sd_create_shortcode_elem_end($excerpt_wrapper);
	    			}
	    		}

	    		//	Read more
	    		if($has_readmore == 'true'){
	    			$html .= '<a href="'.get_permalink($post->ID).'" class="'.$readmore_class.'">'.$readmore_text.'</a>';
	    		}

			    $html .= $this->sd_create_shortcode_elem_end($item_wrapper);		
	    	}
	    }
	    echo $html;
	    return ob_get_clean();
	}

	public function create_sd_posts_list_shortcode($data){
		$post_type = isset($data['post_type']) ? $data['post_type'] : '';
		$limit = isset($data['limit']) ? intval($data['limit']) : 12;
		$item_wrapper = isset($data['item_wrapper']) ? $data['item_wrapper'] : '';
		$item_wrapper_class = isset($data['item_wrapper_class']) ? $data['item_wrapper_class'] : '';
		$item_wrapper_custom_attr = isset($data['item_wrapper_custom_attr']) ? $data['item_wrapper_custom_attr'] : '';
		$item_wrapper_id = isset($data['item_wrapper_id']) ? $data['item_wrapper_id'] : '';
		$has_thumbnail = isset($data['has_thumbnail']) ? $data['has_thumbnail'] : 'false';
		$thumbnail_wrapper = isset($data['thumbnail_wrapper']) ? $data['thumbnail_wrapper'] : '';
		$thumbnail_wrapper_class = isset($data['thumbnail_wrapper_class']) ? $data['thumbnail_wrapper_class'] : '';
		$thumbnail_link = isset($data['thumbnail_link']) ? $data['thumbnail_link'] : '';
		$thumbnail_linkable = 'true';
		$thumbnail_link_outside_wrapper = 'false';
		if($thumbnail_link){
			switch ($thumbnail_link) {
				case 'none':
					$thumbnail_linkable = 'false';
					break;
				case 'link_thumbnail':
					$thumbnail_linkable = 'true';
					$thumbnail_link_outside_wrapper = 'false';
					break;
				case 'link_thumbnail_wrapper':
					$thumbnail_linkable = 'true';
					$thumbnail_link_outside_wrapper = 'true';
					break;
			}
		}

		$has_title = isset($data['has_title']) ? $data['has_title'] : 'false';
		$title_wrapper = isset($data['title_wrapper']) ? $data['title_wrapper'] : '';
		$title_link = isset($data['title_link']) ? $data['title_link'] : '';
		$title_linkable = 'true';
		$title_link_outside_wrapper = 'false';
		if($title_link){
			switch ($title_link) {
				case 'none':
					$title_linkable = 'false';
					break;
				case 'link_title':
					$title_linkable = 'true';
					$title_link_outside_wrapper = 'false';
					break;
				case 'link_title_wrapper':
					$title_linkable = 'true';
					$title_link_outside_wrapper = 'true';
					break;
			}
		}

		$has_excerpt = isset($data['has_excerpt']) ? $data['has_excerpt'] : 'false';
		$excerpt_wrapper = isset($data['excerpt_wrapper']) ? $data['excerpt_wrapper'] : '';
		$has_readmore = isset($data['has_readmore']) ? $data['has_readmore'] : 'false';
		$readmore_class = isset($data['readmore_class']) ? $data['readmore_class'] : '';
		$readmore_text = isset($data['readmore_text']) ? $data['readmore_text'] : '';

		$shortcode = '[sd_posts_list post_type="'.$post_type.'" limit="'.$limit.'" item_wrapper="'.$item_wrapper.'|class:'.$item_wrapper_class.'|id:'.$item_wrapper_id.'|'.$item_wrapper_custom_attr.'" has_thumbnail="'.$has_thumbnail.'" thumbnail_wrapper="'.$thumbnail_wrapper.'|class:'.$thumbnail_wrapper_class.'" thumbnail_linkable="'.$thumbnail_linkable.'" thumbnail_link_outside_wrapper="'.$thumbnail_link_outside_wrapper.'" has_title="'.$has_title.'" title_wrapper="'.$title_wrapper.'" title_linkable="'.$title_linkable.'" title_link_outside_wrapper="'.$title_link_outside_wrapper.'" has_excerpt="'.$has_excerpt.'" excerpt_wrapper="'.$excerpt_wrapper.'" has_readmore="'.$has_readmore.'" readmore_class="'.$readmore_class.'" readmore_text="'.$readmore_text.'"]';
		return $shortcode;
	}

	public function generate_sd_tool_shortcode_callback(){
		$nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
		$shortcode = '';
		if ( ! wp_verify_nonce( $nonce, 'sd-tool-shortcode-settings' ) ) {
		    print_r("Invalid request!");
		}else{
			$shortcode_type = isset($_POST['shortcode_type']) ? $_POST['shortcode_type'] : '';
			if($shortcode_type){
				switch ($shortcode_type) {
					case 'posts_list':
						$data = array(
							'post_type' => isset($_POST['post_type']) ? $_POST['post_type'] : '',
							'item_wrapper' => isset($_POST['item_wrapper']) ? $_POST['item_wrapper'] : '',
							'item_wrapper_class' => isset($_POST['item_wrapper_class']) ? $_POST['item_wrapper_class'] : '',
							'item_wrapper_id' => isset($_POST['item_wrapper_id']) ? $_POST['item_wrapper_id'] : '',
							'has_thumbnail' => isset($_POST['has_thumbnail']) ? 'true' : 'false',
							'thumbnail_wrapper' => isset($_POST['thumbnail_wrapper']) ? $_POST['thumbnail_wrapper'] : '',
							'thumbnail_link' => isset($_POST['thumbnail_link']) ? $_POST['thumbnail_link'] : '',
							'has_title' => isset($_POST['has_title']) ? 'true' : 'false',
							'title_wrapper' => isset($_POST['title_wrapper']) ? $_POST['title_wrapper'] : '',
							'title_link' => isset($_POST['title_link']) ? $_POST['title_link'] : '',
							'has_excerpt' => isset($_POST['has_excerpt']) ? 'true' : 'false',
							'excerpt_wrapper' => isset($_POST['excerpt_wrapper']) ? $_POST['excerpt_wrapper'] : '',
							'has_readmore' => isset($_POST['has_readmore']) ? 'true' : 'false',
							'readmore_text' => isset($_POST['readmore_btn_text']) ? $_POST['readmore_btn_text'] : '',
							'readmore_class' => isset($_POST['readmore_btn_class']) ? $_POST['readmore_btn_class'] : '',
						);
						$shortcode = $this->create_sd_posts_list_shortcode($data);
						break;
					
					default:
						// code...
						break;
				}
			}

			$response = array(
				'data' => $shortcode
			);
			wp_send_json($response);
		}
		wp_die();
	}
}

if(class_exists( 'SD_TOOL_SHORTCODES' )){
	$SD_TOOL_SC = new SD_TOOL_SHORTCODES();
}

?>