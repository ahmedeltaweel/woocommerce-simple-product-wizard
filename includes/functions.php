<?php
/**
 * Created by Nabeel
 * Date: 2016-01-22
 * Time: 2:38 AM
 *
 * @package Wordpress\Woocommerce\Add_Product_Wizard
 */

use Wordpress\Woocommerce\Add_Product_Wizard\Plugin;

if ( !function_exists( 'add_product_wizard' ) ):
	/**
	 * Get plugin instance
	 *
	 * @return Plugin
	 */
	function add_product_wizard()
	{
		return Plugin::get_instance();
	}
endif;

if ( !function_exists( 'wwapw_view' ) ):
	/**
	 * Load view
	 *
	 * @param string  $view_name
	 * @param array   $args
	 * @param boolean $return
	 *
	 * @return void
	 */
	function wwapw_view( $view_name, $args = null, $return = false )
	{
		if ( $return )
		{
			// start buffer
			ob_start();
		}

		add_product_wizard()->load_view( $view_name, $args );

		if ( $return )
		{
			// get buffer flush
			return ob_get_clean();
		}
	}
endif;

if ( !function_exists( 'wwapw_version' ) ):
	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	function wwapw_version()
	{
		return add_product_wizard()->version;
	}
endif;