<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'quotation.php');

class quotation_detailViewquotation_detail extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe;

        $quotationHelper = new quotationHelper();

        $print = JRequest::getVar('print');
        if ($print)
        {
            ?>
        <script type="text/javascript" language="javascript">
            window.print();
        </script>
        <?php
        }

        $user   = JFactory::getUser();
        $option = JRequest::getVar('option');
        $Itemid = JRequest::getVar('Itemid');

        $quoid = JRequest::getInt('quoid');
        $encr  = JRequest::getVar('encr');

        if (!$quoid)
        {
            $mainframe->Redirect('index.php?option=' . $option . '&view=account&Itemid=' . $Itemid);
        }
        $quotationDetail = $quotationHelper->getQuotationDetail($quoid);

        if (count($quotationDetail) < 1)
        {
            JError::raiseWarning(404, JText::_('COM_REDSHOP_NOACCESS_QUOTATION'));
            echo JText::_('COM_REDSHOP_NOACCESS_QUOTATION');
            return;
        }
        if (!$user->id)
        {
            if (isset($encr))
            {
                $model         = $this->getModel('quotation_detail');
                $authorization = $model->checkAuthorization($quoid, $encr);
                if (!$authorization)
                {
                    JError::raiseWarning(404, JText::_('COM_REDSHOP_QUOTATION_ENCKEY_FAILURE'));
                    echo JText::_('COM_REDSHOP_QUOTATION_ENCKEY_FAILURE');
                    return false;
                }
            }
            else
            {
                $mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getVar('Itemid'));
                return;
            }
        }
        else
        {
            if (count($quotationDetail) > 0 && $quotationDetail->user_id != $user->id)
            {
                JError::raiseWarning(404, JText::_('COM_REDSHOP_NOACCESS_QUOTATION'));
                echo JText::_('COM_REDSHOP_NOACCESS_QUOTATION');
                return;
            }
        }

        parent::display($tpl);
    }
}

