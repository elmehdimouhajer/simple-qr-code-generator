<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Simple_Qr_Code_Generator_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Simple_Qr_Code_Generator' ) ) :

	/**
	 * Main Simple_Qr_Code_Generator Class.
	 *
	 * @package		SIMPLEQRCO
	 * @subpackage	Classes/Simple_Qr_Code_Generator
	 * @since		1.0.0
	 * @author		El Mehdi Mouhajer
	 */
	final class Simple_Qr_Code_Generator {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Simple_Qr_Code_Generator
		 */
		private static $instance;

		/**
		 * SIMPLEQRCO helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Simple_Qr_Code_Generator_Helpers
		 */
		public $helpers;

		/**
		 * SIMPLEQRCO settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Simple_Qr_Code_Generator_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'simple-qr-code-generator' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'simple-qr-code-generator' ), '1.0.0' );
		}

		/**
		 * Main Simple_Qr_Code_Generator Instance.
		 *
		 * Insures that only one instance of Simple_Qr_Code_Generator exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Simple_Qr_Code_Generator	The one true Simple_Qr_Code_Generator
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Simple_Qr_Code_Generator ) ) {
				self::$instance					= new Simple_Qr_Code_Generator;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Simple_Qr_Code_Generator_Helpers();
				self::$instance->settings		= new Simple_Qr_Code_Generator_Settings();

				//Fire the plugin logic
				new Simple_Qr_Code_Generator_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'SIMPLEQRCO/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once SIMPLEQRCO_PLUGIN_DIR . 'core/includes/classes/class-simple-qr-code-generator-helpers.php';
			require_once SIMPLEQRCO_PLUGIN_DIR . 'core/includes/classes/class-simple-qr-code-generator-settings.php';

			require_once SIMPLEQRCO_PLUGIN_DIR . 'core/includes/classes/class-simple-qr-code-generator-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'simple-qr-code-generator', FALSE, dirname( plugin_basename( SIMPLEQRCO_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.