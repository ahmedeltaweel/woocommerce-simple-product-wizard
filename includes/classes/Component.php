<?php namespace Wordpress\Woocommerce\Add_Product_Wizard;

/**
 * Base Component
 *
 * @package Wordpress\Woocommerce\Add_Product_Wizard
 */
class Component extends Singular
{
	/**
	 * Plugin Main Component
	 *
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init()
	{
		// vars
		$this->plugin = Plugin::get_instance();
	}
}
