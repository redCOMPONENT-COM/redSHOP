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
 * Quotation Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerQuotation extends RedshopController
{
    /**
     * add quotation function
     *
     * @access public
     * @return void
     */
    public function addquotation()
    {
        $app    = JFactory::getApplication();
        $Itemid = $app->input->get('Itemid');
        $return = $app->input->get('return');
        $post   = $app->input->post->getArray();

        JPluginHelper::importPlugin('redshop_product');
        $dispatcher = RedshopHelperUtility::getDispatcher();

        if (!$post['user_email']) {
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_EMAIL_ADDRESS');
            $this->setRedirect(
                'index.php?tmpl=component&option=com_redshop&view=quotation&return=1&Itemid=' . $Itemid,
                $msg
            );
            die();
        }

        /** @var RedshopModelQuotation $model */
        $model                  = $this->getModel('quotation');
        $session                = JFactory::getSession();
        $cart                   = $session->get('cart');
        $cart['quotation_note'] = $post['quotation_note'];

        $dispatcher->trigger('onRedshopQuotationBeforeAdding', array(&$cart, &$post));

        $row = $model->store($cart, $post);

        if ($row) {
            RedshopHelperExtrafields::extraFieldSave(
                $post,
                RedshopHelperExtrafields::SECTION_QUOTATION,
                $row->quotation_id
            );
            $dispatcher->trigger('onRedshopQuotationAfterAdded', array(&$cart, &$post, $row));

            $sent = $model->sendQuotationMail($row->quotation_id);

            if ($sent) {
                $msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SENT');
            } else {
                $msg = JText::_('COM_REDSHOP_ERROR_SENDING_QUOTATION_MAIL');
            }

            $session = JFactory::getSession();
            \Redshop\Cart\Helper::setCart(null);
            $session->set('ccdata', null);
            $session->set('issplit', null);
            $session->set('userfield', null);
            unset ($_SESSION ['ccdata']);

            if ($return != "") {
                $link = Redshop\IO\Route::_(
                    'index.php?option=com_redshop&view=cart&Itemid=' . $Itemid . '&quotemsg=' . $msg,
                    false
                );

                ?>
                <script>
                    window.parent.location.href = "<?php echo $link ?>";
                </script>
                <?php
                $app->close();
            }

            $this->setRedirect('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid, $msg);
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
            $this->setRedirect(
                'index.php?tmpl=component&option=com_redshop&view=quotation&return=1&Itemid=' . $Itemid,
                $msg
            );
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
        $input  = JFactory::getApplication()->input;
        $Itemid = $input->get('Itemid');
        $model  = $this->getModel('quotation');
        $post   = $input->post->getArray();

        $model->usercreate($post);

        $msg = JText::_('COM_REDSHOP_QUOTATION_SENT_AND_USERNAME_PASSWORD_HAS_BEEN_MAILED');
        $this->setRedirect(
            'index.php?tmpl=component&option=com_redshop&view=quotation&return=1&Itemid=' . $Itemid,
            $msg
        );
    }

    /**
     * cancel function
     *
     * @access public
     * @return void
     */
    public function cancel()
    {
        $app    = JFactory::getApplication();
        $Itemid = $app->input->get('Itemid');
        $return = $app->input->get('return');

        if ($return != "") {
            $link = Redshop\IO\Route::_('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid, false);

            ?>
            <script language="javascript">
                window.parent.location.href = "<?php echo $link ?>";
            </script>
            <?php
            $app->close();
        } else {
            $this->setRedirect('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid);
        }
    }
}
