<?php
defined( 'ABSPATH' ) || exit;
/**
 * Plugin Name: WP Unload Users
 * Plugin URI: https://3xweb.site
 * Description: Terminates users sessions after they've closed this website in a configurable time interval in Settings > Unload users
 * Author URI: https://3xweb.site/
 * Version: 1.0.0
 * Requires at least: 4.4
 * Tested up to: 6.0.2
 * Text Domain: unload-users
 * Domain Path: /languages
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function _3x_Unload_Init() {

    static $plugin;

    if ( ! isset( $plugin ) ) {

        class _3x_Unload_Users {

            /**
             * The *Singleton* instance of this class
             *
             * @var Singleton
             */
            private static $instance;

            /**
             * Returns the *Singleton* instance of this class.
             *
             * @return Singleton The *Singleton* instance.
             */
            public static function get_instance() {
                if ( null === self::$instance ) {
                    self::$instance = new self();
                }
                return self::$instance;
            }

            /**
             * Private clone method to prevent cloning of the instance of the
             * *Singleton* instance.
             *
             * @return void
             */
            public function __clone() {}

            /**
             * Private unserialize method to prevent unserializing of the *Singleton*
             * instance.
             *
             * @return void
             */
            public function __wakeup() {}

            /**
             * Protected constructor to prevent creating a new instance of the
             * *Singleton* via the `new` operator from outside of this class.
             */
            public function __construct() {
                add_action( 'admin_init', [ $this, 'install' ] );
                 if ( is_admin() ) {
                        $this->admin_includes();
                    }
                    $this->includes();

            }
            /**
             * Handles upgrade routines.
             *
             * @since 3.1.0
             * @version 3.1.0
             */
            public function install() {
                if ( ! is_plugin_active( plugin_basename( __FILE__ ) ) ) {
                    return;
                }

            }
            private function includes() {
              include_once dirname( __FILE__ ) . '/includes/options.php';
              include_once dirname( __FILE__ ) . '/includes/ajax.php';
            }
            /**
             * Admin includes.
             */
            private function admin_includes() {
                
                include_once dirname( __FILE__ ) . '/includes/options.php';
                include_once dirname( __FILE__ ) . '/includes/ajax.php';

            }

        }

        $plugin = _3x_Unload_Users::get_instance();

    }

    return $plugin;
}

add_action( 'plugins_loaded', '_unload_servico_init' );

function _unload_servico_init() {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    load_plugin_textdomain( 'unload_users', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

    //Load public scripts
    function Unload_loadPublicScripts($hook) {
              
        wp_enqueue_script( 
            'public_js', 
            plugins_url( '/js/public.js', __FILE__ ), 
            ['jquery'] 
        );

        // allows ajax on frontend
        wp_localize_script( 
            'public_js', 
            'ajax_prop',
            [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ]
        );

     
    }
    add_action('wp_enqueue_scripts', 'Unload_loadPublicScripts');

    _3x_Unload_Init();
}