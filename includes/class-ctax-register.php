<?php
/**
 * Custom taxonomies register
 *
 * @package eFramework
 * @since   1.0
 */

class EFramework_CTax_Register
{
    /**
     * Core singleton class
     * 
     * @var self - pattern realization
     * @access private
     */
    private static $_instance;

    /**
     * Store supported taxonomies in an array
     * @var array
     * @access private
     */
    private $taxonomies = array();

    /**
     * Constructor
     *
     * @access private
     */
    function __construct()
    {
        add_action( 'init', array( $this, 'init' ), 0 );
    }

    /**
     * init hook - 0
     */
    function init()
    {
        $this->taxonomies = apply_filters( 'abtheme_extra_taxonomies', array(
            'cmsportfolio-category' => true,
            'team_member_position' => true
        ) );

        if ( isset( $this->taxonomies['cmsportfolio-category'] ) && $this->taxonomies['cmsportfolio-category'] )
        {
            $this->portfolio_category_register();
        }

        if ( isset( $this->taxonomies['team_member'] ) && $this->taxonomies['team_member'] )
        {
            $this->type_team_member_register();
        }
    }
    function portfolio_category_register(){


        $categories = array(
            'hierarchical' => true,
            'show_tagcloud' => true,
            'labels' => array(
                'name' => esc_html__('Categories', K2_TEXT_DOMAIN),
                'singular_name' => esc_html__('Category', K2_TEXT_DOMAIN),
                'edit_item' => esc_html__('Edit Category', K2_TEXT_DOMAIN),
                'update_item' => esc_html__('Update Category', K2_TEXT_DOMAIN),
                'add_new_item' => esc_html__('Add New Category', K2_TEXT_DOMAIN),
                'new_item_name' => esc_html__('New Type Category', K2_TEXT_DOMAIN),
                'all_items' => esc_html__('All Categories', K2_TEXT_DOMAIN),
                'search_items' => esc_html__('Search Category', K2_TEXT_DOMAIN),
                'parent_item' => esc_html__('Parent Category', K2_TEXT_DOMAIN),
                'parent_item_colon' => esc_html__('Parent Category' . ':', K2_TEXT_DOMAIN),
            ),
            'show_in_menu' => true
        );
        
        register_taxonomy('cmsportfolio-category', array('cms-portfolio'), $categories);
    }

    /**
     * Get instance of the class
     *
     * @access public
     * @return object this
     */
    public static function get_instance()
    {
        if ( ! ( self::$_instance instanceof self ) )
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}