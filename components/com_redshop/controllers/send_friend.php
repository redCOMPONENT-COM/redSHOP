<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

/**
 * send_friendController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class send_friendController extends RedshopCoreController
{
    /**
     * sendmail function
     *
     * @access public
     * @return void
     */
    public function sendmail()
    {
        $post      = $this->input->getArray($_POST);
        $your_name = $post['your_name'];
        $name      = $post['friends_name'];
        $pid       = $post['pid'];
        $email     = $post['friends_email'];

        $model = $this->getModel('send_friend');

        $model->sendProductMailToFriend($your_name, $name, $pid, $email);
    }
}
