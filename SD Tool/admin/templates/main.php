<?php
global $SD_BACKEND_TOOL, $SD_TOOL_GS;
//Get the active tab from the $_GET param
$default_tab = null;
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
<div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      	<a href="?page=sd-tool" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">General Settings</a>
      	<a href="?page=sd-tool&tab=page-settings" class="nav-tab <?php if($tab==='page-settings'):?>nav-tab-active<?php endif; ?>">Page Settings</a>
      	<a href="?page=sd-tool&tab=post-settings" class="nav-tab <?php if($tab==='post-settings'):?>nav-tab-active<?php endif; ?>">Post Settings</a>
      	<?php if($SD_TOOL_GS->has_enabled_woocommerce_features()) { ?>
      	<a href="?page=sd-tool&tab=woocommerce-settings" class="nav-tab <?php if($tab==='woocommerce-settings'):?>nav-tab-active<?php endif; ?>">WooCommerce Settings</a>
      	<?php } ?>
        <a href="?page=sd-tool&tab=shortcodes" class="nav-tab <?php if($tab==='shortcodes'):?>nav-tab-active<?php endif; ?>">Shortcodes</a>
        <?php //if(class_exists('ET_Theme_Builder_Request')) { ?>
        <a href="?page=sd-tool&tab=divi-settings" class="nav-tab <?php if($tab==='divi_settings'):?>nav-tab-active<?php endif; ?>">DIVI Settings</a>
        <?php //} ?> 
    </nav>
    <div class="tab-content">
    	<?php
    	$template = $tab ? $tab : 'general-settings';
    	$SD_BACKEND_TOOL->render_admin_template($template);
    	?>
    </div>
</div>