<?php
vc_map(array(
    "name" => 'K2 Map',
    "base" => "k2_map",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('K2 Shortcodes', "book-junky"),
    "params" => array(
		array(
            "type" => "loop",
            "heading" => esc_html__("Source", "book-junky"),
            "param_name" => "btn_radius",
            "description" => "Enter: ...px",
            "group" => esc_html__("Button Settings", "book-junky"),
        ),
    )
));

class WPBakeryShortCode_k2_map extends K2ShortCode
{

    protected function content($atts, $content = null)
    {
        extract(shortcode_atts(array(), $atts));
        add_action('wp_head', self::add_scripts_to_head());
        return parent::content($atts, $content);
    }

    protected function add_scripts_to_head()
    {
        echo '<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDviganm7862Pgo16Pex7zK7gBEF4NuH4A" type="text/javascript"></script>';
        echo '<script src="'.k2core()->plugin_url.'/assets/js/richmarker-compiled.js" type="text/javascript"></script>';
        echo '<script src="'.k2core()->plugin_url.'/assets/js/k2-map.js" type="text/javascript"></script>';
    }
}



