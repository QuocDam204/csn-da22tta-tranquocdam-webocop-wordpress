<?php
/**
 * About page class
 *
 * @package Xmag
 * @since 1.3.3
 */

if ( ! class_exists( 'Xmag_About_Page' ) ) {

	/**
	 * Singleton class used for generating the about page of the theme.
	 */
	class Xmag_About_Page {

		// Define the version of the class.
		private $version = '1.0.0';

		// Used for loading the texts and setup the actions inside the page.
		private $config;

		// The current theme object.
		private $theme;

		// Get the theme name using wp_get_theme.
		private $theme_name;

		// Get the theme slug.
		private $theme_slug;

		// Get the theme version.
		private $theme_version;

		// Get the pro theme name.
		private $theme_pro_name;

		// Get the pro theme slug.
		private $theme_pro_slug;

		// Define the menu item name for the page.
		private $menu_name;

		// Define the page title name.
		private $page_name;

		// Define the page tabs.
		private $tabs;

		// Define the html notification content displayed upon activation.
		private $notification;

		// UTM link.
		private $utm;

		// The single instance of About Page
		private static $instance;

		// The Main About Page instance. We make sure that only one instance of About Page exists in the memory at one time.
		public static function init( $config ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Xmag_About_Page ) ) {
				self::$instance = new Xmag_About_Page;
				if ( ! empty( $config ) && is_array( $config ) ) {
					self::$instance->config = $config;
					self::$instance->setup_config();
					self::$instance->setup_actions();
				}
			}
		}

		/**
		 * Setup the class props based on the config array.
		 */
		public function setup_config() {
			$theme = wp_get_theme();
			if ( is_child_theme() ) {
				$this->theme_name = $theme->parent()->get( 'Name' );
				$this->theme_slug = $theme->parent()->get_template();
			} else {
				$this->theme_name = $theme->get( 'Name' );
				$this->theme_slug = $theme->get_template();
			}
			$this->theme_version  = $theme->get( 'Version' );
			$this->menu_name      = sprintf( __( 'About %s', 'xmag' ), $this->theme_name);
			$this->page_name      = sprintf( __( 'About %s', 'xmag' ), $this->theme_name);
			$this->theme_pro_name = isset( $this->config['theme_pro_name'] ) ? $this->config['theme_pro_name'] : ' Pro';
			$this->theme_pro_slug = isset( $this->config['theme_pro_slug'] ) ? $this->config['theme_pro_slug'] : '-pro';
			$this->tabs           = isset( $this->config['tabs'] ) ? $this->config['tabs'] : array();
			$this->notification   = isset( $this->config['notification'] ) ? $this->config['notification'] : '';
			$this->utm            = isset( $this->config['utm'] ) ? $this->config['utm'] : '';
		}

		/**
		 * Setup the actions used for this page.
		 */
		public function setup_actions() {
			add_action( 'admin_menu', array( $this, 'about_page_register' ) );
			/* Activation notice */
			add_action( 'load-themes.php', array( $this, 'activation_admin_notice' ) );
			/* Enqueue script and style for about page */
			add_action( 'admin_enqueue_scripts', array( $this, 'style_and_scripts' ) );
		}

		/**
		 * Register the menu page under Appearance menu.
		 */
		function about_page_register() {
			if ( ! empty( $this->menu_name ) && ! empty( $this->page_name ) ) {
				$title =  $this->page_name . '<span class="badge-action-count" style="padding: 0 7px; display: inline-block; background-color: #d63638; color: #fff; font-size: 11px; line-height: 17px; font-weight: 400; margin: 1px 0 0 2px; vertical-align: top; -webkit-border-radius: 10px; border-radius: 10px; z-index: 26; margin-top: 0; margin-left: 5px;"> i </span>' ;

				add_theme_page( $this->menu_name, $title, 'activate_plugins', $this->theme_slug . '-welcome', array(
					$this,
					'about_page_render',
				) );
			}
		}

		/**
		 * Adds an admin notice upon successful activation.
		 */
		public function activation_admin_notice() {
			global $pagenow;
			if ( is_admin() && ( 'themes.php' == $pagenow ) && isset( $_GET['activated'] ) ) {
				add_action( 'admin_notices', array( $this, 'about_page_admin_notice' ), 99 );
			}
		}

		/**
		 * Display an Admin notice linking to the about page.
		 */
		public function about_page_admin_notice() {
			if ( ! empty( $this->notification ) ) {
				echo '<div class="updated notice is-dismissible">';
				echo wp_kses_post( $this->notification );
				echo '</div>';
			}
		}

		/**
		 * Render the main content page.
		 */
		public function about_page_render() {
			echo '<div class="wrap about-wrap dl-about-wrap">';
			echo '<div class="dl-about-header">';

			echo '<div class="dl-about-header-top">';
			if ( ! empty( $this->config['welcome_title'] ) ) {
				$welcome_title = $this->config['welcome_title'];
			}
			if ( ! empty( $this->config['welcome_content'] ) ) {
				$welcome_content = $this->config['welcome_content'];
			}

			/* Display About Title */
			if ( ! empty( $welcome_title ) ) {
				echo '<h1>' . esc_html( $welcome_title ) . '</h1>';
			}

			/* Display About Text */
			echo '<div class="about-text">';
			if ( ! empty( $welcome_content ) ) {
				echo '<p>' . wp_kses_post( $welcome_content ) . '</p>';
			}
			echo '</div>';

			echo '</div><!-- /.dl-about-header-top -->';

			/* Display tabs */
			if ( ! empty( $this->tabs ) ) {
				$active_tab = isset( $_GET['tab']) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'getting_started';
				echo '<nav class="nav-tab-wrapper wp-clearfix">';
				foreach ( $this->tabs as $tab_key => $tab_name ) {
					echo '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-welcome' ) ) . '&tab=' . esc_attr($tab_key) . '" class="nav-tab ' . ( $active_tab == $tab_key ? 'nav-tab-active' : '' ) . '" role="tab" data-toggle="tab">';
					echo esc_html( $tab_name );
					echo '</a>';
				}
				echo '</nav>';
			}

			echo '</div><!-- /.dl-about-header -->';

			echo '<div class="dl-about-content">';

			/* Display content for current tab */
			if ( ! empty( $this->tabs ) ) {
				if ( method_exists( $this, $active_tab ) ) {
					$this->$active_tab();
				}
			}

			echo '</div><!-- /.dl-about-content -->';

			echo '</div><!-- /.wrap.about-wrap -->';
		}

		/**
		 * Getting started tab
		 */
		public function getting_started() {
			echo '<div class="dl-about-section dl-has-2-columns">';

			echo '<div class="dl-about-column">';
			echo '<figure class="dl-thumb"><a href="' . esc_url( 'https://www.designlabthemes.com/' . $this->theme_pro_slug . '-wordpress-theme/' . $this->utm ) . '" target="_blank"><img src="' . esc_url( get_template_directory_uri() . '/inc/about-page/images/screenshot.jpg' ) . '"/></a></figure>';
			echo '</div><!-- /.dl-about-column -->';

			echo '<div class="dl-about-column">';
			echo '<h3>' . wp_kses_post( sprintf( __( 'Upgrade to %s', 'xmag' ), $this->theme_pro_name ) ) . '</h3>';
			echo '<p>' . wp_kses_post( sprintf( __( 'If you &hearts; %1$s, you’ll love all the extra features %2$s come with.', 'xmag' ), $this->theme_name, $this->theme_pro_name ) ) . '</p>';
			echo '<ul class="dl-feature-list">';
			echo '<li><span class="dashicons dashicons-yes-alt"></span>' . esc_html( 'Additional Theme Features', 'xmag' ) . '</li>';
			echo '<li><span class="dashicons dashicons-yes-alt"></span>' . esc_html( 'Magazine Template', 'xmag' ) . '</li>';
			echo '<li><span class="dashicons dashicons-yes-alt"></span>' . esc_html( 'Premium Support', 'xmag' ) . '</li>';
			echo '</ul>';
			echo '<p><a href="' . esc_url( 'https://www.designlabthemes.com/' . $this->theme_pro_slug . '-wordpress-theme/' . $this->utm ) . '" target="_blank" class="button button-primary button-hero">' . esc_html( sprintf( __( 'Get %s now', 'xmag' ), $this->theme_pro_name ) ) . '</a></p>';
			echo '</div><!-- /.dl-about-column -->';

			echo '</div><!-- /.dl-about-section -->';

			echo '<div class="dl-about-section dl-has-1-column">';

			echo '<div class="dl-about-column">';

			echo '<div class="dl-icon-text">';
			echo '<div class="dl-icon">';
			echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48" aria-hidden="true" focusable="false"><path d="M7 13.8h6v-1.5H7v1.5zM18 16V4c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2zM5.5 16V4c0-.3.2-.5.5-.5h10c.3 0 .5.2.5.5v12c0 .3-.2.5-.5.5H6c-.3 0-.5-.2-.5-.5zM7 10.5h8V9H7v1.5zm0-3.3h8V5.8H7v1.4zM20.2 6v13c0 .7-.6 1.2-1.2 1.2H8v1.5h11c1.5 0 2.7-1.2 2.7-2.8V6h-1.5z"></path></svg>';
			echo '</div>';
			echo '<div class="dl-text">';
			echo '<h3>' . esc_html( 'Read Full Documentation', 'xmag' ) . '</h3>';
			echo '<p class="about">' . esc_html( 'Need any help to setup and configure the theme? Please check our full documentation for detailed information on how to use it.', 'xmag' ) . '</p><p><a target="_blank" href="' . esc_url( 'https://www.designlabthemes.com/documentation/' . $this->theme_slug . '-documentation/' ) . '" target="_blank" class="button">' . esc_html( 'Read Documentation', 'xmag' ) . '</a></p>';
			echo '</div>';
			echo '</div>';

			echo '<div class="dl-icon-text">';
			echo '<div class="dl-icon">';
			echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="48" height="48" aria-hidden="true" focusable="false"><path d="M12 4c-4.4 0-8 3.6-8 8v.1c0 4.1 3.2 7.5 7.2 7.9h.8c4.4 0 8-3.6 8-8s-3.6-8-8-8zm0 15V5c3.9 0 7 3.1 7 7s-3.1 7-7 7z"></path></svg>';
			echo '</div>';
			echo '<div class="dl-text">';
			echo '<h3>' . esc_html( 'Customize your site', 'xmag' ) . '</h3>';
			echo '<p>' . esc_html( 'Using the WordPress Customizer you can easily customize every aspect of the theme.', 'xmag' ) . '</p><p><a href="' . esc_url( admin_url( 'customize.php' ) ) . '" class="button">' . esc_html( 'Start Customize', 'floral-lite' ) . '</a></p>';
			echo '</div>';
			echo '</div>';

			echo '<div class="dl-icon-text">';
			echo '<div class="dl-icon">';
			echo '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="48" height="48" aria-hidden="true" focusable="false"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.68822 16.625L5.5 17.8145L5.5 5.5L18.5 5.5L18.5 16.625L6.68822 16.625ZM7.31 18.125L19 18.125C19.5523 18.125 20 17.6773 20 17.125L20 5C20 4.44772 19.5523 4 19 4H5C4.44772 4 4 4.44772 4 5V19.5247C4 19.8173 4.16123 20.086 4.41935 20.2237C4.72711 20.3878 5.10601 20.3313 5.35252 20.0845L7.31 18.125ZM16 9.99997H8V8.49997H16V9.99997ZM8 14H13V12.5H8V14Z"></path></svg>';
			echo '</div>';
			echo '<div class="dl-text">';
			echo '<h3>' . esc_html( 'Rate us', 'xmag' ) . '</h3>';
			echo '<p>' . wp_kses_post( sprintf( __( 'Please rate us <a target="_blank" href="https://wordpress.org/support/theme/%1$s/reviews/?filter=5" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a target="_blank" href="https://wordpress.org/support/theme/%1$s/reviews/?filter=5" target="_blank">WordPress.org</a> to help us spread the word. Thank you from Design Lab Themes!', 'xmag' ), $this->theme_slug ) ) . '</p>';
			echo '</div>';
			echo '</div>';

			echo '</div><!-- /.dl-about-column -->';

			echo '</div><!-- /.dl-about-section -->';
		}

		/**
		 * Free vs PRO tab
		 */
		public function free_pro() {
			echo '<div class="dl-about-section">';

			echo '<div class="dl-free-pro-cta">';
			echo '<div class="dl-free-pro-box">';
			echo '<p>' . wp_kses_post( sprintf( __( 'Need more customizations and flexibility? Try %s', 'xmag' ), $this->theme_pro_name ) ) . '</p>';
			echo '<p><a href="' . esc_url( "https://www.designlabthemes.com/$this->theme_pro_slug-wordpress-theme/$this->utm" ) . '" target="_blank" class="button button-primary button-hero">' . esc_html( sprintf( __( 'Get %s now', 'xmag' ), $this->theme_pro_name ) ) . '</a></p>';
			echo '</div>';
			echo '</div>';

			echo '<table class="dl-free-pro-table">';

			echo '<thead>';
			echo '<tr>';
			echo '<th></th>';
			echo '<th>' . esc_html( 'Free', 'xmag' ) . '</th>';
			echo '<th>' . esc_html( 'PRO', 'xmag' ) . '</th>';
			echo '</tr>';
			echo '</thead>';

			echo '<tbody>';

			echo '<tr>';
			echo '<td><h3>' . esc_html( 'Basic Theme Customization', 'xmag' ) . '</h3><p>' . esc_html( 'Pick an accent color, upload your logo, and easily customize your website', 'xmag' ) . '</p></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td><h3>' . esc_html( 'Gutenberg-First', 'xmag' ) . '</h3><p>' . esc_html( sprintf( __( '%s is optimized for the block editor with nice styling for blocks and combinations.', 'xmag' ), $this->theme_name ) ) . '</p></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td><h3>' . esc_html( 'Fast loading', 'xmag' ) . '</h3><p>' . esc_html( sprintf( __( 'With %s your website loads fast and runs smoothly.', 'xmag' ), $this->theme_name ) ) . '</p></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td><h3>' . esc_html( 'SEO Ready & AMP Support', 'xmag' ) . '</h3><p>' . esc_html( 'Each page is search-engine-optimized (SEO) and fully AMP compatible (Official AMP plugin required)', 'xmag' ) . '</p></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td><h3>' . esc_html( 'Priority Support', 'xmag' ) . '</h3><p>' . esc_html( 'You will benefit of our full support for any issues you have with the theme.', 'xmag' ) . '</p></td>';
			echo '<td><span class="dashicons dashicons-no"></span></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td><h3>' . esc_html( 'Magazine Template', 'xmag' ) . '</h3><p>' . esc_html( 'Professionally designed templates to create your Magazine website in no time!', 'xmag' ) . '</p></td>';
			echo '<td><span class="dashicons dashicons-no"></span></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td><h3>' . esc_html( 'Advanced Theme Customization', 'xmag' ) . '</h3><p>' . esc_html( 'Make your website unique with multiple Layout Options, Header Options, Post Slider, and more!', 'xmag' ) . '</p></td>';
			echo '<td><span class="dashicons dashicons-no"></span></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<tr>';
			echo '<td><h3>' . esc_html( 'Colors and Typography', 'xmag' ) . '</h3><p>' . esc_html( 'Easily adjust theme elements\' color, font-family, and font styles.', 'xmag' ) . '</p></td>';
			echo '<td><span class="dashicons dashicons-no"></span></td>';
			echo '<td><span class="dashicons dashicons-yes"></span></td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td></td>';
			echo '<td colspan="2"><a href="' . esc_url( "https://www.designlabthemes.com/$this->theme_pro_slug-wordpress-theme/$this->utm" ) . '" target="_blank" class="button button-primary button-hero">' . esc_html( sprintf( __( 'Get %s now', 'xmag' ), $this->theme_pro_name ) ) . '</a></td>';
			echo '</tr>';

			echo '</tbody>';

			echo '</table><!-- /.dl-free-pro-table -->';

			echo '</div><!-- /.dl-about-section -->';
		}

		/**
		 * Support tab
		 */
		public function support() {

		}

		/**
		 * Load CSS and scripts for the About Page.
		 */
		public function style_and_scripts( $hook_suffix ) {

			if ( 'appearance_page_' . $this->theme_slug . '-welcome' == $hook_suffix ) {
				wp_enqueue_style( $this->theme_slug . '-about-page-css', get_template_directory_uri() . '/inc/about-page/css/about_page_style.css', array(), '1.0.2' );
			}

		}

	}
}
