<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * shipping_ratecontroller
 *
 * @package     RedSHOP
 * @subpackage  Controller
 * @since       1.0
 */
class shipping_ratecontroller extends JController
{
	/**
	 * cancel
	 */
	public function cancel()
	{
		$post = JRequest::get('post');
		$this->setRedirect('index.php?option=' . $post['option'] . '&view=shipping_detail&task=edit&cid[]=' . $post['id']);
	}
}
