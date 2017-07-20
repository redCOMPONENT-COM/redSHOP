<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * send friend Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerSend_friend extends RedshopController
{
	/**
	 * sendmail function
	 *
	 * @access public
	 * @return void
	 */
	public function sendmail()
	{
		$post = JRequest::get('post');
		$your_name = $post['your_name'];
		$name = $post['friends_name'];
		$pid = $post['pid'];
		$email = $post['friends_email'];

		$model = $this->getModel('send_friend');

		$model->sendProductMailToFriend($your_name, $name, $pid, $email);
	}
}
