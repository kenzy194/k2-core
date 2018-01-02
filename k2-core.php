<?php
/**
 *
 * Plugin Name: K2 Core
 * Plugin URI: http://k2kingmans.com
 * Description: This plugin is Framework of Wordpress, developed by K2ProTeam
 * Version: 1.0.0
 * Author: K2 Team
 * Author URI: http://k2kingmans.com
 * Copyright 2018 by K2Team. All rights reserved.
 * Text Domain: k2-core
 */
define('K2_NAME', 'k2-core');
define('K2_DIR', plugin_dir_path(__FILE__));
define('K2_URL', plugin_dir_url(__FILE__));
define('K2_LIBRARIES', K2_DIR . "libraries" . DIRECTORY_SEPARATOR);
define('K2_LANGUAGES', K2_DIR . "languages" . DIRECTORY_SEPARATOR);
define('K2_TEMPLATES', K2_DIR . "templates" . DIRECTORY_SEPARATOR);
define('K2_INCLUDES', K2_DIR . "includes" . DIRECTORY_SEPARATOR);

define('K2_CSS', K2_URL . "assets/css/");
define('K2_JS', K2_URL . "assets/js/");
define('K2_IMAGES', K2_URL . "assets/images/");
define('K2_TEXT_DOMAIN', 'k2-core');
/**
 * Require functions on plugin
 */
require_once K2_INCLUDES . "redux-framework/redux-framework.php";
require_once K2_INCLUDES . "core-functions.php";

/**
 * K2Core Class
 *
 */
class K2Core
{
    /**
     * Core singleton class
     *
     * @var self - pattern realization
     * @access private
     */
    public static $instance;

    /**
     * Store plugin paths
     *
     * @since 1.0
     * @access private
     * @var array
     */


    public $plugin_url;

    public $plugin_dir;

    public $file;

    private $paths = array();

    public $post_metabox = null;

    protected $post_format_metabox = null;

    protected $taxonomy_meta = null;

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new K2Core();
            self::$instance->setup_globals();
            self::$instance->includes();
        }
        return self::$instance;
    }

    private function setup_globals()
    {
        $this->file = __FILE__;
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
        $url = untrailingslashit(plugin_dir_url(__FILE__));
        $this->plugin_dir = plugin_dir_path($this->file);
        $this->plugin_url = plugin_dir_url($this->file);
        $this->set_paths(array(
            'APP_DIR' => $dir,
            'APP_URL' => $url
        ));

        /**
         * Init function, which is run on site init and plugin loaded
         */
        add_action('init', array($this, 'k2Init'));
        add_action('plugins_loaded', array($this, 'k2_plugin_loaded'));
        add_filter('style_loader_tag', array($this, 'k2ValidateStylesheet'));
        register_activation_hook(__FILE__, array($this, 'k2_activation_hook'));

        if (!class_exists('ReduxFramework')) {
            add_action('admin_notices', array($this, 'redux_framework_notice'));
        } else {
            // Late at 30 to be sure that other extensions available via same hook.
            // Eg: Load extensions at 29 or lower.
            add_action("redux/extensions/before", array($this, 'redux_extensions'), 30);
        }
        if (!class_exists('EFramework_enqueue_scripts')) {
            require_once $this->path('APP_DIR', 'includes/class-enqueue-scripts.php');
        }

        if (!class_exists('EFramework_CPT_Register')) {
            require_once K2_INCLUDES . 'class-cpt-register.php';
            EFramework_CPT_Register::get_instance();
        }

        if (!class_exists('EFramework_CTax_Register')) {
            require_once K2_INCLUDES . 'class-ctax-register.php';
            EFramework_CTax_Register::get_instance();
        }

        if (!class_exists('EFramework_MegaMenu_Register')) {
            require_once K2_INCLUDES . 'mega-menu/class-megamenu.php';
            EFramework_MegaMenu_Register::get_instance();
        }


        if (!class_exists('EFramework_menu_handle')) {
            require_once K2_INCLUDES . 'class-menu-hanlde.php';
        }

        /**
         * Enqueue Scripts on plugin
         */
        add_action('wp_enqueue_scripts', array($this, 'K2_register_style'));
        add_action('wp_enqueue_scripts', array($this, 'k2_register_script'));
        add_action('admin_enqueue_scripts', array($this, 'k2_admin_script'));

        /**
         * Visual Composer action
         */
        add_action('vc_before_init', array($this, 'k2_short_code_base'));

        /**
         * widget text apply shortcode
         */
        add_filter('widget_text', 'do_shortcode');
    }

    private function includes()
    {

    }

    function k2_plugin_loaded()
    {
        global $wp_filesystem;
        // Localization
        load_plugin_textdomain(K2_NAME, false, K2_LANGUAGES);

        /* Add WP_Filesystem. */
        if (!class_exists('WP_Filesystem')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem();
        }
    }

    function k2Init()
    {
        if (apply_filters('k2_scssc_on', false)) {
            // scss compiler library
            if (!class_exists('scssc')) {
                require_once K2_LIBRARIES . 'scss.inc.php';
            }
        }
    }

    function k2_short_code_base()
    {
        require_once K2_INCLUDES . 'shortcodes/K2ShortCode.php';
        k2_require_folder('shortcodes/elements', K2_INCLUDES);
    }

    /**
     * Function register stylesheet on plugin
     */
    function K2_register_style()
    {
        wp_enqueue_style('cms-plugin-stylesheet', K2_CSS . 'cms-style.css');
    }

    /**
     * replace rel on stylesheet (Fix validator link style tag attribute)
     */
    function k2ValidateStylesheet($src)
    {
        if (strstr($src, 'widget_search_modal-css') || strstr($src, 'owl-carousel-css') || strstr($src, 'vc_google_fonts')) {
            return str_replace('rel', 'property="stylesheet" rel', $src);
        } else {
            return $src;
        }
    }

    /**
     * Function register script on plugin
     */
    function k2_register_script()
    {
        wp_register_script('modernizr', K2_JS . 'modernizr.min.js', array('jquery'));
        wp_register_script('waypoints', K2_JS . 'waypoints.min.js', array('jquery'));
        wp_register_script('imagesloaded', K2_JS . 'jquery.imagesloaded.js', array('jquery'));
        wp_register_script('jquery-shuffle', K2_JS . 'jquery.shuffle.js', array('jquery', 'modernizr', 'imagesloaded'));
        if (file_exists(get_template_directory() . '/assets/js/jquery.shuffle.cms.js')) {
            wp_register_script('cms-jquery-shuffle', get_template_directory_uri() . '/assets/js/jquery.shuffle.cms.js', array('jquery-shuffle'));
        } else {
            wp_register_script('cms-jquery-shuffle', K2_JS . 'jquery.shuffle.cms.js', array('jquery-shuffle'));
        }
    }

    /**
     * Function register admin on plugin
     */
    function k2_admin_script()
    {
        wp_enqueue_style('admin-style', K2_CSS . 'admin.css', array(), '1.0.0');
        wp_enqueue_style('font-awesome', K2_CSS . 'font-awesome.min.css', array(), 'all');
    }

    /**
     * Setter for paths
     *
     * @since  1.0
     * @access protected
     *
     * @param array $paths
     */
    protected function set_paths($paths = array())
    {
        $this->paths = $paths;
    }

    /**
     * Gets absolute path for file/directory in filesystem.
     *
     * @since  1.0
     * @access public
     *
     * @param string $name - name of path path
     * @param string $file - file name or directory inside path
     *
     * @return string
     */
    function path($name, $file = '')
    {
        return $this->paths[$name] . (strlen($file) > 0 ? '/' . preg_replace('/^\//', '', $file) : '');
    }

    /**
     * Get url for asset files
     *
     * @since  1.0
     * @access public
     *
     * @param  string $file - filename
     * @return string
     */
    function get_url($file = '')
    {
        return esc_url($this->path('APP_URL', $file));
    }

    /**
     * Get template file full path
     * @param  string $file
     * @param  string $default
     * @return string
     */
    function get_template($file, $default)
    {
        $path = locate_template($file);
        if ($path) {
            return $path;
        }
        return $default;
    }

    function is_min()
    {
        $dev_mode = defined('WP_DEBUG') && WP_DEBUG;
        if ($dev_mode) {
            return '';
        } else {
            return '.min';
        }
    }


    /**
     * Redux Framework notices
     *
     * @since 1.0
     * @access public
     */
    function redux_framework_notice()
    {
        $plugin_name = '<strong>' . esc_html__("Cmssupperheroes", K2_TEXT_DOMAIN) . '</strong>';
        $redux_name = '<strong>' . esc_html__("Redux Framework", K2_TEXT_DOMAIN) . '</strong>';

        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p>';
        printf(
            esc_html__('%1$s require %2$s installed and activated. Please active %3$s plugin', K2_TEXT_DOMAIN),
            $plugin_name,
            $redux_name,
            $redux_name
        );
        echo '</p>';
        printf('<button type="button" class="notice-dismiss"><span class="screen-reader-text">%s</span></button>', esc_html__('Dismiss this notice.', K2_TEXT_DOMAIN));
        echo '</div>';
    }


    /**
     * Action handle when active plugin
     *
     * Check Redux framework active
     */
    function k2_activation_hook()
    {
        if (is_admin()) {
            if (!class_exists('ReduxFrameworkPlugin')) {
                deactivate_plugins(plugin_basename(__FILE__));

                $plugin_name = '<strong>' . esc_html__("K2 Core", K2_TEXT_DOMAIN) . '</strong>';
                $redux_name = '<strong>' . esc_html__("Redux Framework", K2_TEXT_DOMAIN) . '</strong>';
                ob_start();

                printf(
                    esc_html__('%1$s requires %2$s installed and activated. Currently it is either not installed or installed but not activated. Please follow these steps to get %1$s up and working:', K2_TEXT_DOMAIN),
                    $plugin_name,
                    $redux_name
                );

                printf(
                    "<br/><br/>1. " . esc_html__('Go to %1$s to check if %2$s is installed. If it is, activate it then activate %3$s.', K2_TEXT_DOMAIN),
                    sprintf('<strong><a href="%1$s">%2$s</a></strong>', esc_url(admin_url('plugins.php')), esc_html__('Plugins/Installed Plugins', K2_TEXT_DOMAIN)),
                    $redux_name,
                    $plugin_name
                );

                printf(
                    "<br/><br/>2. " . esc_html__('If %1$s is not installed, go to %2$s, search for %1$s, install and activate %1$s, then activate %3$s.', K2_TEXT_DOMAIN),
                    $redux_name,
                    sprintf('<strong><a href="%1$s">%2$s</a></strong>', esc_url(admin_url('plugin-install.php?s=Redux+Framework&tab=search&type=term')), esc_html__('Plugins/Add New')),
                    $plugin_name
                );

                $message = ob_get_clean();

                wp_die($message, esc_html__('Plugin Activation Error', K2_TEXT_DOMAIN), array('back_link' => true));
            }
        }
    }


    /**
     * Load our ReduxFramework extensions
     *
     * @since 1.0
     * @param  ReduxFramework $redux
     */
    function redux_extensions($redux)
    {
        if (!class_exists('K2_Redux_Extensions')) {
            require_once $this->path('APP_DIR', 'includes/class-redux-extensions.php');
        }
        if (!class_exists('K2_Post_Metabox')) {
            require_once $this->path('APP_DIR', 'includes/class-post-metabox.php');

            if (empty($this->post_metabox)) {
                $this->post_metabox = new K2_Post_Metabox($redux);
            }
        }

        if (!class_exists('K2_Taxonomy_Meta')) {
            require_once $this->path('APP_DIR', 'includes/class-taxonomy-meta.php');

            if (empty($this->taxonomy_meta)) {
                $this->taxonomy_meta = new K2_Taxonomy_Meta($redux);
            }
        }
    }
}


/**
 * Get instance of CmssuperheroesCore
 *
 * @since  1.0
 * @return K2Core instance
 */
function k2core()
{
    return K2Core::instance();
}

$GLOBALS['k2core'] = k2core();

?>