<?php
/**
 * @team: FsFlex Team
 * @since: 1.0.0
 * @author: KP
 */
if (!defined('ABSPATH')) {
    exit();
}
if (!class_exists('k2_test_params')) {
    class k2_test_params extends K2Framework
    {
        public function __construct()
        {
            parent::__construct();
            add_action('vc_before_init', array($this, 'your_name_integrateWithVC'));
        }

        function your_name_integrateWithVC()
        {
            vc_map(array(
                "name"     => __("Bar tag test", "my-text-domain"),
                "base"     => "bartag",
                "class"    => "",
                "category" => __("Content", "my-text-domain"),
                "params"   => array(
                    array(
                        "type"        => "k2_images_param",
                        "holder"      => "div",
                        "class"       => "",
                        "heading"     => __("K2 Images", "k2-core"),
                        "param_name"  => "foo",
                        "values"      => array(
                            'k' => 'haha',
                            'p' => 'hehe'
                        ),
                        'value'=>'k'
                    )
                )
            ));
        }
    }
}