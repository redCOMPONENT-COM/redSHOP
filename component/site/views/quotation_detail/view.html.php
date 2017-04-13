<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewQuotation_detail extends RedshopView
{
function display ($tpl = null)
{
	$app = JFactory::getApplication();

	$quotationHelper = quotationHelper::getInstance();

	$print = JRequest::getInt('print');

if ($print)
{
	?>
	<script type="text/javascript" language="javascript">
		window.print();
	</script>
<?php
}

	$user   = JFactory::getUser();
	$Itemid = JRequest::getInt('Itemid');
	$quoid = JRequest::getInt('quoid');
	$encr  = JRequest::getString('encr');

	if (!$quoid)
	{
		$app->redirect(JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $Itemid));
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
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));

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
