<?php
/**
 * @team: K2 Team
 * @since: 1.0.0
 * @author: KP
 */
if(!defined('ABSPATH')){
    exit();
}
if(!class_exists('K2ShortCode')){
    class K2ShortCode extends WPBakeryShortCode{

        protected function loadTemplate($atts, $content = null) {
            $output = '';
            $cms_template = isset($atts['cms_template']) ? $atts['cms_template'] : $this->shortcode.'.php';
            $files = $this->findShortcodeTemplates();
            if ($cms_template && isset($files[$cms_template])) {
                $this->setTemplate($files[$cms_template]->uri);
            } else {
                $this->findShortcodeTemplate();
            }
            if (!is_null($content))
                $content = apply_filters('vc_shortcode_content_filter', $content, $this->shortcode);
            if ($this->html_template) {
                ob_start();
                include ( $this->html_template );
                $output = ob_get_contents();
                ob_end_clean();
            } else {
                trigger_error(sprintf(__('Template file is missing for `%s` shortcode. Make sure you have `%s` file in your theme folder.', 'js_composer'), $this->shortcode, 'wp-content/themes/your_theme/vc_templates/' . $this->shortcode . '.php'));
            }
            wp_reset_postdata();
            wp_reset_query();
            return apply_filters('vc_shortcode_content_filter_after', $output, $this->shortcode);
        }

        /**
         *
         * @return Array(): array of all available templates
         */
        protected function findShortcodeTemplates() {
            $theme_dir = get_template_directory() . '/vc_templates';
            $child_dir = get_stylesheet_directory() . '/vc_templates';
            $reg = "/^({$this->shortcode}\.php|{$this->shortcode}--.*\.php)/";
            $files = K2FileScanFromDirectory($theme_dir, $reg);
            $files = array_merge(K2FileScanFromDirectory(k2core()->plugin_dir.'/includes/shortcodes/vc_templates', $reg), $files);
            $files = array_merge($files, K2FileScanFromDirectory($child_dir, $reg));
            return $files;
        }
    }
}