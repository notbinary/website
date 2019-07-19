<?php

/**
 * Leadin Class
 */
class Leadin {

	/**
	 * Class constructor
	 */
	public function __construct() {
		global $pagenow;

		add_action( 'login_head', array( $this, 'add_page_analytics' ) );
		add_action( 'wp_head', array( $this, 'add_page_analytics' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_common_frontend_scripts' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'add_leadin_frontend_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_leadin_frontend_scripts' ) );

		if ( is_admin() ) {
			if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
				$li_wp_admin = new LeadinAdmin();
			}
		}
	}

	// =============================================
	// Scripts & Styles
	// =============================================
	/**
	 * Adds front end javascript + initializes ajax object
	 */
	public function add_leadin_frontend_scripts() {
		add_filter( 'script_loader_tag', array( $this, 'leadin_add_embed_script_attributes' ), 10, 2 );

		$embed_domain = constant( 'LEADIN_SCRIPT_LOADER_DOMAIN' );
		$portal_id    = get_option( 'leadin_portalId' );

		if ( empty( $portal_id ) ) {
			return;
		}

		$embed_url = "//$embed_domain/$portal_id.js?integration=WordPress";
		$embed_id  = 'leadin-scriptloader-js';

		if ( is_single() ) {
			$page_type = 'post';
		} elseif ( is_front_page() ) {
			$page_type = 'home';
		} elseif ( is_archive() ) {
			$page_type = 'archive';
		} elseif ( $this->leadin_is_login_or_register_page() ) {
			$page_type = 'login';
		} elseif ( is_page() ) {
			$page_type = 'page';
		} else {
			$page_type = 'other';
		}

		$leadin_wordpress_info = array(
			'userRole'            => ( is_user_logged_in() ) ? leadin_get_user_role() : 'visitor',
			'pageType'            => $page_type,
			'leadinPluginVersion' => LEADIN_PLUGIN_VERSION,
		);

		wp_register_script( $embed_id, $embed_url, array( 'jquery' ), LEADIN_PLUGIN_VERSION, true );
		wp_localize_script( $embed_id, 'leadin_wordpress', $leadin_wordpress_info );
		wp_enqueue_script( $embed_id );
	}

	/**
	 * Add leadin.css
	 */
	public function add_common_frontend_scripts() {
		wp_register_style( 'leadin-css', LEADIN_PATH . '/style/leadin.css', array(), LEADIN_PLUGIN_VERSION );
		wp_enqueue_style( 'leadin-css' );
	}

	/**
	 * Add tracking code
	 */
	public function add_page_analytics() {
		$portal_id = get_option( 'leadin_portalId' );
		if ( empty( $portal_id ) ) {
			echo '<!-- HubSpot WordPress Plugin v' . esc_html( LEADIN_PLUGIN_VERSION ) . ': embed JS disabled as a portalId has not yet been configured -->';
		} else {
			?>
			<!-- DO NOT COPY THIS SNIPPET! Start of Page Analytics Tracking for HubSpot WordPress plugin v<?php echo esc_html( LEADIN_PLUGIN_VERSION ); ?>-->
			<script type="text/javascript">
				var _hsq = _hsq || [];
				<?php
				// Pass along the correct content-type.
				if ( is_single() ) {
					echo '_hsq.push(["setContentType", "blog-post"]);' . "\n";
				} elseif ( is_archive() || is_search() ) {
					echo '_hsq.push(["setContentType", "listing-page"]);' . "\n";
				} else {
					echo '_hsq.push(["setContentType", "standard-page"]);' . "\n";
				}
				?>
			</script>
			<!-- DO NOT COPY THIS SNIPPET! End of Page Analytics Tracking for HubSpot WordPress plugin -->
			<?php
		}
	}

	/**
	 * Action script-loader-tag
	 *
	 * @param string $tag tag name.
	 * @param string $handle handle.
	 */
	public function leadin_add_embed_script_attributes( $tag, $handle ) {
		if ( 'leadin-scriptloader-js' === $handle ) {
			return str_replace( ' src', ' async defer src', $tag );
		} else {
			return $tag;
		}
	}

	/**
	 * Return true if the current page is login or register
	 */
	public static function leadin_is_login_or_register_page() {
		return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ), true );
	}
}

// =============================================
// Leadin Init
// =============================================
global $li_wp_admin;
