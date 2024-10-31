<?php
/**
 * The Settings page of the plugin.
 *
 * @link       https://github.com/Kuuak/scramble-email
 * @since      1.2.0
 *
 * @package    Scramble_Email
 * @subpackage Scramble_Email/admin
 */

/* Prevent loading this file directly */
defined( 'ABSPATH' ) || exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Scramble_Email
 * @subpackage Scramble_Email/admin
 * @author     Kuuak
 */
if ( !class_exists( 'Scramble_Email_Settings' ) ) {

	/**
	 * Class Scramble_Email_Settings
	 * @since	1.0.0
	 */
	class Scramble_Email_Settings {

		/**
		 * The ID of this plugin.
		 *
		 * @since		1.2.0
		 * @access	private
		 * @var			string	$plugin_name	The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since		1.2.0
		 * @access	private
		 * @var			string	$version	The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since		1.2.0
		 * @param		string	$plugin_name	The name of this plugin.
		 * @param		string	$version			The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version = $version;

			$this->register_hooks();
		}

		/**
		 * Register the actions and filters specific for the settings page.
		 *
		 * @since		1.2.0
		 */
		private function register_hooks() {
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_menu', array( $this, 'register_settings_menu' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_files' ) );
		}

		/**
		 * Register setting, section and field.
		 *
		 * @since		1.2.0
		 */
		public function register_settings(){

			register_setting('scem', 'scem_parse_content');

			add_settings_section(
				'scem_parse_content_section',
				__( "Settings", 'scem' ),
				'',
				'scem'
			);

			add_settings_field(
				'scem_parse_field',
				'',
				array( $this, 'render_field' ),
				'scem',
				'scem_parse_content_section'
			);

		}

		/**
		 * Register the settings page in WP menu.
		 *
		 * @since		1.2.0
		 */
		public function register_settings_menu(){

			add_submenu_page(
				'options-general.php',
				__( "Scramble Email", 'scem' ),
				__( "Scramble Email", 'scem' ),
				'manage_options',
				'scem',
				array( $this, 'render_page' )
			);

		}

		/**
		 * Prints HTML.
		 *
		 * @since		1.2.0
		 */
		public function render_desc() {
			_e( "Define if the plugin should parse the content of the editor and scramble the email links", 'scem' );
		}
		public function render_field() {

			$parse = (bool)get_option( 'scem_parse_content' );
			?>
			<div class="scem-settings-wrapper">
				<div class="scem-settings-head">
					<p class="scem-label"><?php _e( "Pasre WYSIWYG and scramble automatically email links?", 'scem' ); ?></p>
					<div class="scem-switch">
						<input type="checkbox" class="scem-switch__input" id="scem_parse_field" value="1" name="scem_parse_content" <?php if ($parse) echo 'checked'; ?>>
						<label for="scem_parse_field" class="scem-switch__label">
							<span class="scem-switch__txt scem-switch__txt_yes">Yes</span>
							<span class="scem-switch__txt scem-switch__txt_no">No</span>
						</label>
					</div>
				</div>
			</div>
			<?php
		}
		public function render_page() {
			if (!current_user_can('manage_options')) {
				return;
			}

			?>
			<div class="wrap scem-wrap">
				<h1><?= esc_html(get_admin_page_title()); ?></h1>
				<form action="options.php" method="post">
					<?php
					settings_fields('scem'); // security fields for the registered setting "scem"
					do_settings_sections('scem'); // setting sections and their fields
					submit_button(); // save settings button
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Display a success notice if settings are successfully saved.
		 *
		 * @since		1.2.0
		 */
		public function settings_updated_notice() {

			$screen = get_current_screen();
			if ( 'settings_page_scem' !== $screen->base ) {
				return;
			}

			if ( ! (isset($_GET['settings-updated']) && $_GET['settings-updated']) ) {
				return;
			}

			printf( '<div class="notice notice-success is-dismissible">%s</div>', __( 'Settings have been saved!', 'scem' ) );
		}

		/**
		 * Enqueue style and javascript files
		 *
		 * @since	1.0.0
		 * @param	string	$hook_suffix	The current admin page.
		 */
		public function enqueue_files( $hook ) {

			if ( 'settings_page_scem' !== $hook ) {
				return;
			}

			wp_enqueue_style( 'scem', trailingslashit(plugin_dir_url(__FILE__)). 'css/scem-settings.css', false, $this->version );
		}
	}
}
