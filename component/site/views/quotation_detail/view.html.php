<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopViewQuotation_detail
 *
 * @since  1.6.0
 */
class RedshopViewQuotation_Detail extends RedshopView
{
    /**
     * @param   null  $tpl
     *
     * @return mixed|void
     * @throws Exception
     */
    public function display($tpl = null)
    {
        $app   = JFactory::getApplication();
        $print = $app->input->getInt('print');

        if ($print) {
            ?>
            <script type="text/javascript" language="javascript">
                window.print();
            </script>
            <?php
        }

        $user   = JFactory::getUser();
        $itemId = $app->input->getInt('Itemid');
        $quoid  = $app->input->getInt('quoid');
        $encr   = $app->input->getString('encr');

        if (!$quoid) {
            $app->redirect(Redshop\IO\Route::_('index.php?option=com_redshop&view=account&Itemid=' . $itemId, false));
        }

        $quotationDetail = RedshopHelperQuotation::getQuotationDetail($quoid);

        if (empty($quotationDetail)) {
            JError::raiseWarning(404, JText::_('COM_REDSHOP_NOACCESS_QUOTATION'));
            echo JText::_('COM_REDSHOP_NOACCESS_QUOTATION');

            return;
        }

        if (!$user->id) {
            if (isset($encr)) {
                $model         = $this->getModel('quotation_detail');
                $authorization = $model->checkAuthorization($quoid, $encr);

                if (!$authorization) {
                    JError::raiseWarning(404, JText::_('COM_REDSHOP_QUOTATION_ENCKEY_FAILURE'));
                    echo JText::_('COM_REDSHOP_QUOTATION_ENCKEY_FAILURE');

                    return false;
                }
            } else {
                $app->redirect(
                    Redshop\IO\Route::_('index.php?option=com_redshop&view=login&Itemid=' . $app->input->getInt('Itemid'))
                );

                return;
            }
        } else {
            if (isset($quotationDetail->user_id) && $quotationDetail->user_id != $user->id) {
                JError::raiseWarning(404, JText::_('COM_REDSHOP_NOACCESS_QUOTATION'));
                echo JText::_('COM_REDSHOP_NOACCESS_QUOTATION');

                return;
            }
        }

        parent::display($tpl);
    }
}
