<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Ask Question Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class ask_questionController extends JControllerLegacy
{
    /**
     * Method to send Ask Question Mail.
     *
     */
    public function sendaskquestionmail()
    {
        $post        = JRequest::get('post');
        $product_id  = $post['pid'];
        $Itemid      = $post['Itemid'];
        $ask         = JRequest::getVar('ask');
        $category_id = JRequest::getVar('category_id');

        $model = $this->getModel('ask_question');

        if ($model->sendMailForAskQuestion($post))
        {
            $msg = JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY');
        }
        if ($ask == 1)
        {
            $link = 'index.php?option=com_redshop&view=product&pid=' . $product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid;
            $this->setRedirect($link, $msg);
        }
        else
        {
            echo $msg;?>
        <span id="closewindow"><input type="button" value="Close Window"
                                      onclick="window.parent.redBOX.close();"/></span>
        <script>
            setTimeout("window.parent.redBOX.close();", 5000);
        </script>
        <?php
            exit;
        }
    }
}
