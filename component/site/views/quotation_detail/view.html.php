<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/quotation.php';

class quotation_detailViewquotation_detail extends JView
{
function display ($tpl = null)
{
	$app = JFactory::getApplication();

	$quotationHelper = new quotationHelper;

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
		$app->Redirect('index.php?option=' . $option . '&view=account&Itemid=' . $Itemid);
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
			$app->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getVar('Itemid'));

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
