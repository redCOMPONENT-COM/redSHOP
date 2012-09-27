<?php
/**
 * @version    2.5
 * @package    Joomla.Site
 * @subpackage com_redshop
 * @author     redWEB Aps
 * @copyright  com_redshop (C) 2008 - 2012 redCOMPONENT.com
 * @license    GNU/GPL, see LICENSE.php
 *             com_redshop can be downloaded from www.redcomponent.com
 *             com_redshop is free software; you can redistribute it and/or
 *             modify it under the terms of the GNU General Public License 2
 *             as published by the Free Software Foundation.
 *             com_redshop is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License
 *             along with com_redshop; if not, write to the Free Software
 *             Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 **/
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

/**
 * ask_questionController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class ask_questionController extends RedshopCoreController
{
    /**
     * Method to send Ask Question Mail.
     *
     */
    public function sendaskquestionmail()
    {
        $post        = $this->input->getArray($_POST);
        $product_id  = $post['pid'];
        $item_id     = $post['Itemid'];
        $ask         = $this->input->get('ask');
        $category_id = $this->input->get('category_id');

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
            $link = 'index.php?option=com_redshop&view=product&pid=' . $product_id . '&cid=' . $category_id . '&Itemid=' . $item_id;
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
