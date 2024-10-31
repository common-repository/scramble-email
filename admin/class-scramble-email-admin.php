<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/Kuuak/scramble-email
 * @since      1.0.0
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
if ( !class_exists( 'Scramble_Email_Admin' ) ) {

	/**
	 * Class Scramble_Email_Admin
	 * @since	1.0.0
	 */
	class Scramble_Email_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since		1.0.0
		 * @access	private
		 * @var			string	$plugin_name	The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since		1.0.0
		 * @access	private
		 * @var			string	$version	The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since		1.0.0
		 * @param		string	$plugin_name	The name of this plugin.
		 * @param		string	$version			The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version = $version;

			$this->load_settings();
			$this->register_hooks();
		}

		/**
		 * Create instance of plugin's settings page functionnalities
		 *
		 * @since		1.2.0
		 */
		private function load_settings() {
			$settings = new Scramble_Email_Settings( $this->plugin_name, $this->version );
		}

		/**
		 * Register the actions and filters related to the admin area functionality.
		 *
		 * @since		1.2.0
		 */
		private function register_hooks() {

			// Do no load shortcode elements if the parse content is enabled
			if ( !(bool)get_option( 'scem_parse_content' ) ) {
				add_action( 'admin_init', array($this, 'register_mce_plugin') );
				add_action( 'admin_init', array($this, 'enqueue_editor_style') );
			}
			else {
				add_filter( 'the_content', array($this,'the_content') );
			}

			add_action( 'plugin_action_links_scramble-email/scramble-email.php', array( $this, 'settings_action_link'), 1 );
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since		1.0.0
		 */
		public function enqueue_styles() {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/scem-wpview.css', array(), $this->version, 'all' );
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since		1.0.0
		 */
		public function enqueue_editor_style() {
			global $editor_styles;

			$editor_styles[] = plugin_dir_url( __FILE__ ) .'css/scem-wpview.css';
		}

		/**
		 * Register the TinyMCE plugin
		 *
		 * @since		1.0.0
		 */
		public function register_mce_plugin() {

			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
				return false;
			}

			if ( 'true' == get_user_option('rich_editing') ) {
				add_filter( 'mce_buttons', array($this, 'mce_add_button') );
				add_filter( 'mce_external_plugins', array($this, 'mce_enqueue_js') );
			}
		}

		/**
		 * Add the custom button to the TinyMCE first toolbar
		 *
		 * @since		1.0.0
		 *
		 * @param 	array	$buttons	An array of tinyMCE buttons.
		 * @return	array
		 */
		public function mce_add_button( $buttons ) {
			array_push( $buttons, 'scem_mce_button' );
			return $buttons;
		}

		/**
		 * Enqueue the MCE plugin javascript
		 *
		 * @since		1.0.0
		 *
		 * @param		array	$plugins	An array of all plugins.
		 * @return	array
		 */
		public function mce_enqueue_js( $plugin_array ) {
			$plugin_array['scem_mce_plugin']		= plugin_dir_url( __FILE__ ) .'js/scem-mce-plugin.js';
			$plugin_array['scem_admin_wpview']	= plugin_dir_url( __FILE__ ) .'js/scem-wpview.js';
			return $plugin_array;
		}

		/**
		 * Add settings action link on plugin page
		 *
		 * @since		1.2.0
		 *
		 * @param		array	$links	An array of plugin action links
		 * @return	array					Array with extra setting link
		 */
		public function settings_action_link( $links ) {
			$action_links = array(
				'settings' => sprintf( '<a href="%s" title="%s">%s</a>',
					admin_url( 'options-general.php?page=scem' ),
					__( 'View Scramble Email Settings', 'scem' ),
					__( 'Settings', 'scem' )
				)
			);
			return array_merge( $action_links, $links );
		}

		/**
		 * Parse the WYSIWYG editor content to scramble mailto: links
		 *
		 * @since		1.2.0
		 *
		 * @param		string	$content
		 * @return	string
		 */
		public function the_content( $content ) {

			// match all links with `mailto`
			preg_match_all( '/<a([^>]*)href="mailto:([^@"]+@[^@"]+)"([^>]*)>([^<]*)<\/a>/', $content, $matches, PREG_SET_ORDER );

			foreach ($matches as $match) {
				$attrs = [];
				// match & parse the html attributes
				preg_match_all('/([^=]+)="([^"]+)"/', trim("{$match[1]} {$match[3]}"), $matches_attrs, PREG_SET_ORDER);
				foreach ($matches_attrs as $attr) {
					$attrs[$attr[1]] = $attr[2];
				}

				// Replace the links with the scramble email script tag
				$content = str_replace( $match[0], scramble_email($match[2], $match[4], $attrs), $content );
			}

			return $content;
		}
	}
}
