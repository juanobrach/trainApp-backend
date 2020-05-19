<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/juanobrach
 * @since      1.0.0
 *
 * @package    Masfuerza
 * @subpackage Masfuerza/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Masfuerza
 * @subpackage Masfuerza/admin
 * @author     JuanObrach <juanobrach@gmail.com>
 */
class Masfuerza_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Masfuerza_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Masfuerza_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/masfuerza-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Masfuerza_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Masfuerza_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/masfuerza-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_role_caps() {

    $role = get_role('trainer');               
    $role->add_cap( 'read_planification');
    $role->add_cap( 'edit_planification' );
    $role->add_cap( 'edit_planifications' );
    $role->add_cap( 'edit_published_planifications' );
    $role->add_cap( 'publish_planifications' );
    $role->add_cap( 'delete_planification' );
	}

}
