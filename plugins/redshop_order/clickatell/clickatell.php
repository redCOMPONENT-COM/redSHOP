<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * redSHOP Click A Tell plugin
 *
 * @since  1.0.0
 */
class PlgRedshop_OrderClickATell extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Event after order status saved.
	 *
	 * @param   stdClass  $data       Order data
	 * @param   string    $newStatus  Order status
	 *
	 * @return  void
	 */
	public function onAfterOrderStatusUpdate($data, $newStatus)
	{

	}
}
