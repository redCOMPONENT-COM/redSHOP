<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');


require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');

class order_detailVieworder_detail extends JView
{
	function display ($tpl = null)
	{
		global $mainframe;

		$order_functions = new order_functions;

		$print = JRequest::getVar('print');
	if ($print)
	{
		?>
		<script type="text/javascript" language="javascript">
			window.print();
		</script>
	<?php
	}

		$params = & $mainframe->getParams('com_redshop');

		$prodhelperobj = new producthelper();
		$prodhelperobj->generateBreadcrumb();

		$user     =& JFactory::getUser();
		$session  =& JFactory::getSession();
		$order_id = $session->get('order_id');

		$oid    = JRequest::getInt('oid', $order_id);
		$encr   = JRequest::getVar('encr');
		$layout = JRequest::getVar('layout');

		$model = $this->getModel('order_detail');

		$OrdersDetail = $order_functions->getOrderDetails($oid);

		if ($user->id)
		{
			if ($OrdersDetail->user_id != $user->id)
			{
				$mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getVar('Itemid'));

				return;
			}
		}
		else
		{
			if (isset($encr))
			{
				$authorization = $model->checkauthorization($oid, $encr);

				if (!$authorization)
				{
					JError::raiseWarning(404, JText::_('COM_REDSHOP_ORDER_ENCKEY_FAILURE'));
					echo JText::_('COM_REDSHOP_ORDER_ENCKEY_FAILURE');

					return false;
				}
			}

			// Preform security checks
			elseif (!$user->id)
			{
				$mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getVar('Itemid'));

				return;
			}
		}

		$this->assignRef('OrdersDetail', $OrdersDetail);
		$this->assignRef('user', $user);
		$this->assignRef('params', $params);

		parent::display($tpl);
	}
}
