<?php
/**
 * Plugin Name: TM YouTube Subscribe
 * Plugin URI: https://wordpress.org/plugins/tm-youtube-subscribe/
 * Description: This simple plugin allows to display YouTube subscribe widget on the website. Flexible customization options allow you to install it in no time flat.
 * Version: 1.0.1
 * Author: Jetimpex
 * Author URI:  https://jetimpex.com/wordpress/
 * Text Domain: youtube-subscribe
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If class 'TM_Youtube_Subscribe' not exists.
if ( ! class_exists( 'TM_Youtube_Subscribe' ) ) {

	/**
	 * Class add all hooks.
	 */
	class TM_Youtube_Subscribe {
		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $core = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Load the installer core.
			add_action( 'after_setup_theme', require( trailingslashit( dirname( __FILE__ ) ) . 'cherry-framework/setup.php' ), 0 );

			// Init the core.
			add_action( 'after_setup_theme', array( $this, 'get_core' ), 1 );
			add_action( 'after_setup_theme', array( 'Cherry_Core', 'load_all_modules' ), 2 );

			// Internationalize the text strings used.
			add_action( 'plugins_loaded', array( $this, 'lang' ), 5 );

			// Load the functions files.
			add_action( 'widgets_init', array( $this, 'subscribe_widget' ), 4 );
		}

		/**
		 * Add text domain to WP.
		 *
		 * @since 1.0.0
		 */
		function lang() {
			load_plugin_textdomain( 'youtube-subscribe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Loads the core functions. These files are needed before loading anything else in the
		 * theme because they have required functions for use.
		 *
		 * @since  1.1.0
		 */
		public function get_core() {
			global $chery_core_version;

			if ( null !== $this->core ) {
				return $this->core;
			}

			if ( 0 < sizeof( $chery_core_version ) ) {
				$core_paths = array_values( $chery_core_version );

				require_once( $core_paths[0] );
			} else {
				die( 'Class Cherry_Core not found' );
			}

			$this->core = new Cherry_Core( array(
				'base_dir' => plugin_dir_path( __FILE__ ) . 'cherry-framework',
				'base_url' => plugin_dir_url( __FILE__ ) . 'cherry-framework',
				'modules'  => array(
					'cherry-js-core' => array(
						'autoload' => true,
					),
					'cherry-ui-elements' => array(
						'autoload' => true,
						'args'     => array(
							'ui_elements' => array(
								'text',
							),
						),
					),
					'cherry-widget-factory' => array(
						'autoload' => true,
					),
				),
			));

			return $this->core;
		}
		/**
		 * Include and add all foles.
		 *
		 * @since  1.0.0
		 *
		 */
		function subscribe_widget() {

			require_once 'class-youtube-subscribe-helper.php';
			require_once 'class-youtube-subscribe-widget.php';
			register_widget( 'Youtube_Subscribe_Widget' );

			if ( apply_filters( 'youtube_subscribe_styles', true ) ) {
				wp_enqueue_style( 'youtube-widget-style',  plugin_dir_url( __FILE__ ) . 'assets/youtube-style.css' );
				wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'assets/font-awesome/css/font-awesome.min.css' );
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

	TM_Youtube_Subscribe::get_instance();
}
