<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This class is used to bring your plugin to life. 
 * All the other registered classed bring features which are
 * controlled and managed by this class.
 * 
 * Within the add_hooks() function, you can register all of 
 * your WordPress related actions and filters as followed:
 * 
 * add_action( 'my_action_hook_to_call', array( $this, 'the_action_hook_callback', 10, 1 ) );
 * or
 * add_filter( 'my_filter_hook_to_call', array( $this, 'the_filter_hook_callback', 10, 1 ) );
 * or
 * add_shortcode( 'my_shortcode_tag', array( $this, 'the_shortcode_callback', 10 ) );
 * 
 * Once added, you can create the callback function, within this class, as followed: 
 * 
 * public function the_action_hook_callback( $some_variable ){}
 * or
 * public function the_filter_hook_callback( $some_variable ){}
 * or
 * public function the_shortcode_callback( $attributes = array(), $content = '' ){}
 * 
 * 
 * HELPER COMMENT END
 */

/**
 * Class Simple_Qr_Code_Generator_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		SIMPLEQRCO
 * @subpackage	Classes/Simple_Qr_Code_Generator_Run
 * @author		El Mehdi Mouhajer
 * @since		1.0.0
 */
class Simple_Qr_Code_Generator_Run{

	/**
	 * Our Simple_Qr_Code_Generator_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'plugin_action_links_' . SIMPLEQRCO_PLUGIN_BASE, array( $this, 'add_plugin_action_link' ), 20 );
		add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu_items' ), 100, 1 );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

    /**
     * Adds action links to the plugin list table
     *
     * @access	public
     * @since	1.0.0
     *
     * @param	array	$links An array of plugin action links.
     *
     * @return	array	An array of plugin action links.
     */
    public function add_plugin_action_link( $links ) {
        // Generate the URL to the settings page
        $settings_page_url = admin_url('admin.php?page=simple-qr-code-generator');

        // Update the custom link to point to the settings page
        $links['settings'] = sprintf( '<a href="%s" title="Settings" style="font-weight:700;">%s</a>', esc_url($settings_page_url), __( 'Settings', 'simple-qr-code-generator' ) );

        return $links;
    }

    /**
     * Add a new menu item to the WordPress topbar
     *
     * @access	public
     * @since	1.0.0
     *
     * @param	object $admin_bar The WP_Admin_Bar object
     *
     * @return	void
     */
    public function add_admin_bar_menu_items( $admin_bar ) {
        // Generate the URL to the settings page
        $settings_page_url = admin_url('admin.php?page=simple-qr-code-generator');

        $admin_bar->add_menu( array(
            'id'		=> 'simple-qr-code-generator-id',
            'title'		=> __( 'QR Code Settings', 'simple-qr-code-generator' ),
            'parent'	=> false,
            'href'		=> esc_url($settings_page_url),
            'group'		=> false,
            'meta'		=> array(
                'title'		=> __( 'QR Code Settings', 'simple-qr-code-generator' ),
                'class'		=> 'simple-qr-code-generator-class',
                'html'		=> false,
                'rel'		=> false,
                'onclick'	=> false,
                'tabindex'	=> false,
            ),
        ));
    }

}
