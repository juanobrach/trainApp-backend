<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/juanobrach
 * @since      1.0.0
 *
 * @package    Masfuerza
 * @subpackage Masfuerza/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Masfuerza
 * @subpackage Masfuerza/includes
 * @author     JuanObrach <juanobrach@gmail.com>
 */
class Masfuerza {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Masfuerza_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MASFUERZA_VERSION' ) ) {
			$this->version = MASFUERZA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'masfuerza';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Masfuerza_Loader. Orchestrates the hooks of the plugin.
	 * - Masfuerza_i18n. Defines internationalization functionality.
	 * - Masfuerza_Admin. Defines all hooks for the admin area.
	 * - Masfuerza_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		 /**
		 *  Composer dependencies
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/vendor/autoload.php';


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-masfuerza-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-masfuerza-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-masfuerza-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-masfuerza-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-masfuerza-posts.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-masfuerza-api.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Controllers/class-controller.php';
		
		/**
		 * INCLUDE ALL MODELS WITH THEIR CONTROLLERS
		 * */ 

		// Planification
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Planification/class-planification-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Planification/class-planification-api.php';

		// Dosing
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Dosing/class-dosing-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Dosing/class-dosing-api.php';

		// Heating
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Heating/class-heating-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Heating/class-heating-api.php';	

		// Exercise
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Exercise/class-exercise-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Exercise/class-exercise-api.php';
		
		// Auth
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Auth/class-auth-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Auth/class-auth-api.php';

		// Subscription
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Subscription/class-subscription-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Models/Subscription/class-subscription-api.php';


		// Helpers
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Helpers/index.php';


		$this->loader = new Masfuerza_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Masfuerza_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Masfuerza_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Masfuerza_Admin( $this->get_plugin_name(), $this->get_version() );
		$masFuerza_posts = new Masfuerza_Posts();
		$masFuerza_api   = new Masfuerza_Api();


		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'acf/save_post', $masFuerza_posts, 'custom_type_save_post', 20 );
		$this->loader->add_action( 'admin_head-post.php', $masFuerza_posts, 'check_for_notice' );
		$this->loader->add_action( 'rest_api_init', $masFuerza_api, 'init_api' );



		// When a trainner are on a form in order to crete a program, he can only select their own athletes users
		$this->loader->add_filter( 'acf/fields/user/query', $masFuerza_posts, 'get_own_trainer_athletes', 10, 3 );

		// When the user traineer save a new user, create a relationship between user adding a metadata to the new user.
		$this->loader->add_action('user_register', $masFuerza_posts, 'registration_save', 10, 1);

		// Filter JWT login
		$this->loader->add_filter('jwt_auth_token_before_dispatch', $masFuerza_api, 'filter_jwt_auth', 10, 1);

		// Enable the option show in rest
		add_filter( 'acf/rest_api/field_settings/show_in_rest', '__return_true' );

		// Enable the option edit in rest
		add_filter( 'acf/rest_api/field_settings/edit_in_rest', '__return_true' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Masfuerza_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Masfuerza_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
