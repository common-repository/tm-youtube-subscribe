<?php
/**
 * Module Name: Widget Factory
 * Description: Base widget class that simplifies creating of your own widgets.
 * Version: 1.1.0
 * Author: Cherry Team
 * Author URI: http://www.cherryframework.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package    Cherry_Framework
 * @subpackage Modules
 * @version    1.1.0
 * @author     Cherry Team <cherryframework@gmail.com>
 * @copyright  Copyright (c) 2012 - 2016, Cherry Team
 * @link       http://www.cherryframework.com/
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Cherry_Widget_Factory' ) ) {

	/**
	 * Widget factory module main class
	 */
	class Cherry_Widget_Factory {

		/**
		 * Module slug
		 *
		 * @var string
		 */
		public $module_slug = 'cherry-widget-factory';

		/**
		 * Module arguments
		 *
		 * @var array
		 */
		public $args = array();

		/**
		 * Core instance
		 *
		 * @var object
		 */
		public $core = null;

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the module
		 */
		function __construct( $core, $args ) {

			$this->core = $core;
			$this->args = $args;
			$this->include_abstract_widget();
			add_filter( 'cherry_widget_factory_core', array( $this, 'pass_core_to_widgets' ), 10, 2 );
			add_filter( 'cherry_core_js_ui_init_settings', array( $this, 'init_ui_js' ), 10 );
		}

		/**
		 * Init UI elements JS
		 *
		 * @since  1.0.0
		 * @param  array $settings UI elements init.
		 * @return array
		 */
		public function init_ui_js( $settings ) {

			global $current_screen;

			if ( $current_screen && 'widgets' == $current_screen->id ) {
				$settings['auto_init'] = true;
				$settings['targets']   = array( '#widgets-right' );
			}

			return $settings;
		}

		/**
		 * Pass current core instance into widget
		 *
		 * @param  mixed  $core current core object.
		 * @param  [type] $path abstract widget file path.
		 * @return mixed
		 */
		public function pass_core_to_widgets( $core, $path ) {

			$path         = str_replace( '\\', '/', $path );
			$current_core = str_replace( '\\', '/', $this->core->settings['extra_base_dir'] );

			if ( false !== strpos( $path, $current_core ) ) {
				return $this->core;
			}

			return $core;
		}

		/**
		 * Include abstract widget class
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function include_abstract_widget() {
			require_once( __DIR__ . '/inc/class-cherry-abstract-widget.php' );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $core, $args ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $core, $args );
			}

			return self::$instance;
		}
	}
}
