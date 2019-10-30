<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Send friend Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerSend_Friend extends RedshopController
{
	/**
	 * Method for send mail
	 *
	 * @return void
	 * @throws Exception
	 *
	 * @since  1.0.0
	 */
	public function sendmail()
	{
		$input       = JFactory::getApplication()->input;
		$yourName    = $input->getString('your_name', '');
		$friendName  = $input->getString('friends_name', '');
		$friendEmail = $input->getString('friends_email', '');
		$productId   = $input->getInt('pid', 0);

		/** @var RedshopModelSend_Friend $model */
		$model = $this->getModel('send_friend');

		$model->sendProductMailToFriend($yourName, $friendName, $productId, $friendEmail);

		JFactory::getApplication()->close();
	}
}
