<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');

class shipping_ratecontroller extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
	}

	function cancel()
	{
		$post = JRequest::get('post');
		$this->setRedirect('index.php?option=' . $post['option'] . '&view=shipping_detail&task=edit&cid[]=' . $post['id']);
	}

	function display()
	{

		parent::display();
	}
}


