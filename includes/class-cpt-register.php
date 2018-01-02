<?php
/**
 * Custom post types register.
 * @since   1.0
 * @author  KP
 *
 */

class EFramework_CPT_Register
{
    /**
     * Core singleton class
     *
     * @var self - pattern realization
     * @access private
     */
    private static $_instance;

    /**
     * Store supported post types in an array
     * @var array
     * @access private
     */
    private $post_types = array();

    /**
     * Constructor
     *
     * @access private
     */
    private function __construct()
    {
        add_action('init', array($this, 'init'), 0);
    }

    /**
     * init hook - 0
     */
    function init()
    {
        $this->post_types = apply_filters('cmssuperheroes_extra_post_types', array(
            'portfolio'   => false
        ));

        if (isset($this->post_types['portfolio']) && $this->post_types['portfolio']) {
            $this->type_portfolio_register();
            flush_rewrite_rules();
        }
    }

    /**
     * Registers portfolio post type, this function should be called in an init hook function.
     * @uses $wp_post_types Inserts new post type object into the list
     *
     * @access protected
     */
    protected function type_portfolio_register()
    {

        $portfolio_slug = function_exists('abtheme_get_opt') ? abtheme_get_opt('portfolio_slug','portfolio') : 'portfolio';
        $args = apply_filters('cmssuperheroes_portfolio_post_type_args', array(
            'labels'              => array(
                'name'                  => __('Portfolio', K2_TEXT_DOMAIN),
                'singular_name'         => __('Portfolio Entry', K2_TEXT_DOMAIN),
                'add_new'               => _x('Add New', 'add new on admin panel', K2_TEXT_DOMAIN),
                'add_new_item'          => __('Add New Portfolio Entry', K2_TEXT_DOMAIN),
                'edit_item'             => __('Edit Portfolio Entry', K2_TEXT_DOMAIN),
                'new_item'              => __('New Portfolio Entry', K2_TEXT_DOMAIN),
                'view_item'             => __('View Portfolio Entry', K2_TEXT_DOMAIN),
                'view_items'            => __('View Portfolio Entries', K2_TEXT_DOMAIN),
                'search_items'          => __('Search Portfolio Entries', K2_TEXT_DOMAIN),
                'not_found'             => __('No Portfolio Entries Found', K2_TEXT_DOMAIN),
                'not_found_in_trash'    => __('No Portfolio Entries Found in Trash', K2_TEXT_DOMAIN),
                'parent_item_colon'     => __('Parent Portfolio Entry:', K2_TEXT_DOMAIN),
                'all_items'             => __('All Entries', K2_TEXT_DOMAIN),
                'archives'              => __('Portfolio Archives', K2_TEXT_DOMAIN),
                'attributes'            => __('Portfolio Entry Attributes', K2_TEXT_DOMAIN),
                'insert_into_item'      => __('Insert into Portfolio Entry', K2_TEXT_DOMAIN),
                'uploaded_to_this_item' => __('Uploaded to this Portfolio Entry', K2_TEXT_DOMAIN),
                'menu_name'             => __('Portfolio', K2_TEXT_DOMAIN),
                'filter_items_list'     => __('Filter portfolio list', K2_TEXT_DOMAIN),
                'items_list_navigation' => __('Portfolio list navigation', K2_TEXT_DOMAIN),
                'items_list'            => __('Portfolio list', K2_TEXT_DOMAIN),
                'name_admin_bar'        => _x('Portfolio', 'add new on admin bar', K2_TEXT_DOMAIN)
            ),
            'hierarchical'        => false,
            'description'         => '',
            'taxonomies'          => array('portfolio_category'),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => null,
            'menu_icon'           => 'dashicons-feedback',
            'show_in_nav_menus'   => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => array(
                'slug'       => $portfolio_slug,
                'with_front' => false,
                'pages'      => true
            ),
            'capability_type'     => 'post',
            'supports'            => array(
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'revisions'
            )
        ));
        register_post_type('cms-portfolio', $args);
    }

    /**
     * Get instance of the class
     *
     * @access public
     * @return object this
     */
    public static function get_instance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}