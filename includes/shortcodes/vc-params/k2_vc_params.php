<?php
/**
 * @team: FsFlex Team
 * @since: 1.0.0
 * @author: KP
 */
if (!defined('ABSPATH')) {
    exit();
}
if (!class_exists('k2_vc_params')) {
    class k2_vc_params
    {
        public function __construct()
        {
            add_action('init', array($this, 'k2_vc_add_shortcode_param'), 10);
        }

        function k2_vc_add_shortcode_param()
        {
            if (function_exists('vc_add_shortcode_param')) {
                vc_add_shortcode_param('k2_images_param', array($this, 'k2_images_param'));
            }
        }

        function k2_images_param($settings, $value)
        {
            $layout = $this->fs_get_template_file__('params/k2-images-param', array('settings' => $settings, 'value' => $value));
            return $layout;
        }
    }

    new k2_vc_params();
}