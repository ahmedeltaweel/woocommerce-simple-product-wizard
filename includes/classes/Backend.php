<?php namespace Wordpress\Woocommerce\Add_Product_Wizard;

/**
 * Backend logic
 *
 * @package Wordpress\Woocommerce\Add_Product_Wizard
 */
class Backend extends Component
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init()
	{
		parent::init();

		// adding wizard sub menu item.
		add_action( 'admin_menu', [ &$this, 'add_new_wizard_sub_menu' ], 1000 );

		// adding styles and scripts to admin area.
		add_action( 'admin_enqueue_scripts', [ &$this, 'enqueue_styles_and_scripts' ], 100 );
	}

	/**
	 * loading styles and scripts for admin page.
	 *
	 * @return void
	 */
	public function enqueue_styles_and_scripts()
	{
		$load_path = WWAPW_URI . ( Helpers::is_script_debugging() ? 'assets/src/' : 'assets/dist/' );

		// wizard styles.
		wp_register_style( 'WWAPW-wizard-style', $load_path . 'css/jquery.steps.css', [], wwapw_version() );

		// jquery ui theme style.
		wp_register_style( 'WWAPW-jquery-ui-style-theme', '//code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css', [], wwapw_version() );

		// select 2 styles
		wp_register_style( 'WWAPW-select2-style', WWAPW_URI . 'assets/select2/select2.min.css', [], wwapw_version() );

		// wizard custom style.
		wp_register_style( 'WWAPW-custom-wizard-style', $load_path . 'css/wizard.css', [
			'WWAPW-wizard-style',
			'WWAPW-jquery-ui-style-theme',
			'WWAPW-select2-style',
		], wwapw_version() );

		// wizard script.
		wp_register_script( 'WWAPW-jquery-steps', $load_path . 'js/jquery.steps.js', [ 'jquery' ], wwapw_version(), true );

		// media script for wizard.
		wp_register_script( 'WWAPW-media-script', $load_path . 'js/media-script.js', [ 'jquery' ], wwapw_version(), true );

		// select 2 script.
		wp_register_script( 'WWAPW-select2-script', WWAPW_URI . 'assets/select2/select2.full.min.js', [ 'jquery' ], wwapw_version(), true );

		// main file.
		wp_register_script( 'WWAPW-custom-wizard-script', $load_path . 'js/wizard.js', [
			'jquery-ui-datepicker',
			'WWAPW-select2-script',
			'WWAPW-media-script',
			'WWAPW-jquery-steps',
		], wwapw_version(), true );

		// localizing script.
		wp_localize_script( 'WWAPW-custom-wizard-script', 'wwapw_ajax_object', [
			'wwapw_ajax_url' => admin_url( 'admin-ajax.php' ),
		] );

		// de-register woocommerce select2 on wizard page.
		if ( class_exists( 'woocommerce' ) && get_current_screen()->id === 'product_page_' . WWAPW_WIZARD_PAGE_NAME )
		{
			// remove select 2.
			wp_dequeue_style( 'select2' );
			wp_deregister_style( 'select2' );

			wp_dequeue_script( 'select2' );
			wp_deregister_script( 'select2' );
		}

	}

	/**
	 * adding sub menu item for product wizard.
	 *
	 * @return void
	 */
	public function add_new_wizard_sub_menu()
	{
		// getting vendor role.
		$role = Helpers::get_vendor_role();

		// check if admin or vendor.
		if ( current_user_can( 'manage_options' ) || in_array( $role, wp_get_current_user()->roles ) )
		{
			// adding sub-menu page.
			add_submenu_page(
				'edit.php?post_type=product',
				__('Add Product' ,WWAPW_DOMAIN ),
				__('Add Product Wizard' , WWAPW_DOMAIN ),
				'publish_products',
				WWAPW_WIZARD_PAGE_NAME,
				[ &$this, 'populate_add_product_wizard' ]
			);
		}
	}

	/**
	 * content of sub menu item.
	 *
	 * @return void
	 */
	public function populate_add_product_wizard()
	{
		// enqueue scripts and styles.
		wp_enqueue_media();
		wp_enqueue_script( 'WWAPW-custom-wizard-script' );
		wp_enqueue_style( 'WWAPW-custom-wizard-style' );

		//getting all categories.
		$categories = get_terms( [
			'taxonomy'     => 'product_cat',
			'hierarchical' => 1,
			'hide_empty'   => false,
		] );

		if ( empty( $categories ) )
		{
			// empty categories.
			$class   = 'notice notice-error';
			$message = __( 'No Categories found please add some.', WWAPW_DOMAIN );
			$url     = admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' );
			printf( '<div class="%s"><p>%s <a href=%s>%s</a></p></div>', $class, $message, $url, __( 'Add Category', WWAPW_DOMAIN ) );
			die();

		}
		// load view and sending data..
		wwapw_view( 'add-product-wizard' );
	}
}
