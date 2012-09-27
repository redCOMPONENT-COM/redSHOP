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
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'quotation.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');

/**
 * quotation_detailController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class quotation_detailController extends JControllerLegacy
{
    /**
     * update status function
     *
     * @access public
     * @return void
     */
    public function updatestatus()
    {
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');
        $post    = $this->input->getArray($_POST);
        $encr    = $this->input->get('encr');

        $quotationHelper = new quotationHelper();
        $redshopMail     = new redshopMail();
        $quotationHelper->updateQuotationStatus($post['quotation_id'], $post['quotation_status']);

        $redshopMail->sendQuotationMail($post['quotation_id'], $post['quotation_status']);

        $msg = JText::_('COM_REDSHOP_QUOTATION_STATUS_UPDATED_SUCCESSFULLY');

        $this->setRedirect('index.php?option=' . $option . '&view=quotation_detail&quoid=' . $post['quotation_id'] . '&encr=' . $encr . '&Itemid=' . $item_id, $msg);
    }

    /**
     * checkout function
     *
     * @access public
     * @return void
     */
    public function checkout()
    {
        $option = $this->input->get('option');
        $post   = $this->input->getArray($_POST);
        $encr   = $this->input->get('encr');

        $quotationHelper = new quotationHelper();
        $model           = $this->getmodel();
        $session         =& JFactory::getSession();
        $redhelper       = new redhelper();

        $cart        = array();
        $cart['idx'] = 0;
        $session->set('cart', $cart);

        $quotationProducts = $quotationHelper->getQuotationProduct($post['quotation_id']);
        for ($q = 0; $q < count($quotationProducts); $q++)
        {
            $model->addtocart($quotationProducts[$q]);
        }
        $cart = $session->get('cart');

        $quotationDetail       = $quotationHelper->getQuotationDetail($post['quotation_id']);
        $cart['customer_note'] = $quotationDetail->quotation_note;
        $cart['quotation_id']  = $quotationDetail->quotation_id;
        $cart['cart_discount'] = $quotationDetail->quotation_discount;
        $session->set('cart', $cart);

        $model->modifyQuotation($quotationDetail->user_id);
        $item_id = $redhelper->getCheckoutItemid();
        $this->setRedirect('index.php?option=' . $option . '&view=checkout&quotation=1&encr=' . $encr . '&Itemid=' . $item_id);
    }
}

