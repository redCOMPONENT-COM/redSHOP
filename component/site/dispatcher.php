<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\Dispatcher;

/**
 * Dispatcher class for com_redshop
 *
 * @since  4.0.0
 */
class RedshopDispatcher extends Dispatcher
{
	/**
	 * The extension namespace
	 *
	 * @var    string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $namespace = 'Redcomponent\\Component\\Redshop';
}
