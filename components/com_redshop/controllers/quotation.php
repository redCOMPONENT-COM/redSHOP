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
 * quotationController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class quotationController extends RedshopCoreController
{
    /**
     * add quotation function
     *
     * @access public
     * @return void
     */
    public function addquotation()
    {
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');
        $post    = $this->input->getArray($_POST);
        $return  = $this->input->get('return');

        if (!$post['user_email'])
        {
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_EMAIL_ADDRESS');
            $this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=quotation&return=1&Itemid=' . $item_id, $msg);
            die();
        }

        $model                  = $this->getModel('quotation');
        $session                = JFactory::getSession();
        $cart                   = $session->get('cart');
        $cart['quotation_note'] = $post['quotation_note'];
        $row                    = $model->store($cart, $post);
        if ($row)
        {
            $sent = $model->sendQuotationMail($row->quotation_id);
            if ($sent)
            {
                $msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SENT');
            }
            else
            {
                $msg = JText::_('COM_REDSHOP_ERROR_SENDING_QUOTATION_MAIL');
            }

            $session = JFactory::getSession();
            $session->set('cart', NULL);
            $session->set('ccdata', NULL);
            $session->set('issplit', NULL);
            $session->set('userfiled', NULL);
            unset ($_SESSION ['ccdata']);
            if ($return)
            {
                $link = 'index.php?option=' . $option . '&view=cart&Itemid=' . $item_id . '&quotemsg=' . $msg;    ?>
            <script>
                window.parent.location.href = "<?php echo $link ?>";
                window.parent.reload();
            </script>
            <?php exit;
            }

            $this->setRedirect('index.php?option=' . $option . '&view=cart&Itemid=' . $item_id, $msg);
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
            $this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=quotation&return=1&Itemid=' . $item_id, $msg);
        }
    }

    /**
     * user create function
     *
     * @access public
     * @return void
     */
    public function usercreate()
    {
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');
        $post    = $this->input->getArray($_POST);

        $model = $this->getModel('quotation');

        $model->usercreate($post);

        $msg = JText::_('COM_REDSHOP_QUOTATION_SENT_AND_USERNAME_PASSWORD_HAS_BEEN_MAILED');
        $this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=quotation&return=1&Itemid=' . $item_id, $msg);
    }

    /**
     * cancel function
     *
     * @access public
     * @return void
     */
    public function cancel()
    {
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');

        $return = $this->input->get('return');
        if ($return != "")
        {
            $link = 'index.php?option=' . $option . '&view=cart&Itemid=' . $item_id;
            ?>
        <script language="javascript">
            window.parent.location.href = "<?php echo $link ?>";
        </script>
        <?php
            exit;
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=cart&Itemid=' . $item_id);
        }
    }
}

