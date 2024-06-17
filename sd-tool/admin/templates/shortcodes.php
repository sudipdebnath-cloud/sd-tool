<?php

?>
<div class="sd-tool-shortcodes">
	<h2>Shortcodes</h2>

	<ul class="accordion-list">
		<li>
			<h2 class="title">Default shortcodes</h2>
			<div class="content">
				<table class="form-table" role="presentation" width="100%">
					<tbody>
						<tr>
							<td>For site title</td>
							<td><strong>[sd_site_title]</strong></td>
						</tr>
						<tr>
							<td>For current year</td>
							<td><strong>[sd_year]</strong></td>
						</tr>
						<tr>
							<td>For current page/post title</td>
							<td><strong>[sd_post_title]</strong></td>
						</tr>
						<tr>
							<td>For current post/page featured image</td>
							<td><strong>[sd_featured_image]</strong></td>
						</tr>
					</tbody>
				</table>
			</div>
		</li>
	</ul>

	<table class="form-table" role="presentation" width="100%">
		<thead>
			<tr>
				<td align="right"><button type="button" class="button btn sd_modal_open" target-modal="create-shortcode-popup">Generate Shortcode</button></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<h3 style="display: none;" class="gen_shortcode_heading">Generated Shortcode</h3>
					<p id="show_generated_shortcode"></p>
					<button type="button" class="button btn primary-button gen_shortcode_copy" style="display:none;margin: 0 auto;margin-top: 20px;" onclick="copyShortcode(this);">Copy</button>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="sd_modal" id="create-shortcode-popup">
  	<!-- Modal content -->
  	<div class="sd_modal_content">
    	<div class="sd_modal_header">
      		<span class="sd_modal_close">&times;</span>
      		<h2>New Shortcode</h2>
    	</div>
    	<div class="sd_modal_body">
      		<form class="form create_shortcode_form" id="create_shortcode_form">
      			<?php wp_nonce_field( "sd-tool-shortcode-settings" ); ?>
      			<table>
      				<tr>
      					<td width="50%">
      						<div class="sd_form_field_wrapper">
								<label for="shortcode_type" class="font_16">Shortcode Type</label>
								<select class="sd_form_field sd_form_field_select d-block" name="shortcode_type" id="shortcode_type">
									<option value="posts_list">Posts List</option>
									<option value="categories_list">Categories List</option>
								</select>
							</div>
      					</td>
      					<td width="50%">
      						<div class="sd_form_field_wrapper">
								<label for="post_type" class="font_16">Post Type</label>
								<select class="sd_form_field sd_form_field_select d-block" name="post_type" id="post_type">
									<?php
									$args = array(
										'public' => true,
									);
									$post_types = get_post_types( $args, 'objects' );
									if($post_types) {
									 	foreach ($post_types as $post_type) {
									 	?>
										<option value="<?php echo $post_type->name; ?>"><?php echo $post_type->label; ?></option>
										<?php 
										} 
									} ?>
								</select>
							</div>
      					</td>
      				</tr>
      				<tr>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="each_item_wrapper" class="font_16">Item Wrapper</label>
								<select class="sd_form_field sd_form_field_select d-block" name="item_wrapper" id="each_item_wrapper">
									<option value="div">Div</option>
									<option value="section">Section</option>
									<option value="li">Li</option>
								</select>
							</div>
      					</td>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="item_wrapper_class" class="font_16">Item Wrapper Class</label>
								<input type="text" class="d-block" name="item_wrapper_class" placeholder="Item wrapper class" id="item_wrapper_class">
							</div>
      					</td>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="item_wrapper_id" class="font_16">Item Wrapper ID</label>
								<input type="text" class="d-block" name="item_wrapper_id" placeholder="Item wrapper id" id="item_wrapper_id">
							</div>
      					</td>
      				</tr>
      				<tr>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
								<label><input type="checkbox" name="has_thumbnail" checked>Post Thumbnail</label>
							</div>
      					</td>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="thumbnail_wrapper" class="font_16">Thumbnail Wrapper</label>
								<select class="sd_form_field sd_form_field_select d-block" name="thumbnail_wrapper" id="thumbnail_wrapper">
									<option value="div">Div</option>
									<option value="span">Span</option>
								</select>
							</div>
      					</td>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="Thumbnail LInk" class="font_16">Thumbnail Link</label>
								<label class="d-block"><input type="radio" name="thumbnail_link" value="link_thumbmail" checked>Link only thumbnail</label>
								<label class="d-block"><input type="radio" name="thumbnail_link" value="link_thumbmail_wrapper">Link thumbnail wrapper</label>
								<label class="d-block"><input type="radio" name="thumbnail_link" value="none">None</label>
							</div>
      					</td>
      				</tr>
      				<tr>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
								<label><input type="checkbox" name="has_title" checked>Post Title</label>
							</div>
      					</td>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="title_wrapper" class="font_16">Title Wrapper</label>
								<select class="sd_form_field sd_form_field_select d-block" name="title_wrapper" id="title_wrapper">
									<option value="h1">H1</option>
									<option value="h2">H2</option>
									<option value="h3">H3</option>
									<option value="h4" selected>H4</option>
									<option value="h5">H5</option>
									<option value="h6">H6</option>
									<option value="p">P</option>
									<option value="span">Span</option>
								</select>
							</div>
      					</td>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="Title LInk" class="font_16">Title Link</label>
								<label class="d-block"><input type="radio" name="title_link" value="link_title" checked>Link only title</label>
								<label class="d-block"><input type="radio" name="title_link" value="link_title_wrapper">Link title wrapper</label>
								<label class="d-block"><input type="radio" name="title_link" value="none">None</label>
							</div>
      					</td>
      				</tr>
      				<tr>
      					<td width="50%">
      						<div class="sd_form_field_wrapper">
								<label><input type="checkbox" name="has_excerpt" checked>Post Excerpt</label>
							</div>
      					</td>
      					<td width="50%">
      						<div class="sd_form_field_wrapper">
      							<label for="excerpt_wrapper" class="font_16">Excerpt Wrapper</label>
								<select class="sd_form_field sd_form_field_select d-block" name="excerpt_wrapper" id="excerpt_wrapper">
									<option value="div">Div</option>
								</select>
							</div>
      					</td>
      				</tr>
      				<tr>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
								<label><input type="checkbox" name="has_readmore" checked>Read more button</label>
							</div>
      					</td>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="readmore_btn_text" class="font_16">Button text</label>
								<input type="text" class="d-block" name="readmore_btn_text" placeholder="Button text" id="readmore_btn_text">
							</div>
      					</td>
      					<td width="33.33%">
      						<div class="sd_form_field_wrapper">
      							<label for="readmore_btn_class" class="font_16">Button class</label>
								<input type="text" class="d-block" name="readmore_btn_class" placeholder="Button class" id="readmore_btn_class">
							</div>
      					</td>
      				</tr>
      			</table>
      			<div class="text-center">
      				<button type="submit" class="button btn" id="create_shortcode_form_submit_btn">Generate</button>
      			</div>
      		</form>
    	</div>
  	</div>
</div>