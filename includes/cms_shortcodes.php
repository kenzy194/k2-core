<?php
/* Add extra type on visual composer */
require_once K2_INCLUDES.'types/cms_template.php';
require_once K2_INCLUDES.'types/cms_template_img.php';
require_once K2_INCLUDES.'types/img.php';
/* Get List Shortcodes From Folder*/
require_once CMS_DIR . '/shortcodes/cms_base.php';

//Start adding shortcode
function cms_add_more_shortcode(){
	$shortcodes = array(
	'cms_carousel',
	'cms_grid',
	);
	$shortcodes = apply_filters('cms-shorcode-list', $shortcodes);
	foreach($shortcodes as $shortcode){
		require_once CMS_DIR . '/shortcodes/'.$shortcode.'.php';
	}
}
add_action('vc_after_init', 'cms_add_more_shortcode' , 10);