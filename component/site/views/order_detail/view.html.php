<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperAdminOrder');

class RedshopViewOrder_detail extends RedshopView
{
	public function display ($tpl = null)
	{
		$app = JFactory::getApplication();

		$order_functions = new order_functions;

		$print = JRequest::getInt('print');

		if ($print)
		{
			?>
			<script type="text/javascript" language="javascript">
				window.print();
			</script>
		<?php
		}

		$params = $app->getParams('com_redshop');

		$prodhelperobj = new producthelper;
		$prodhelperobj->generateBreadcrumb();

		$user     = JFactory::getUser();
		$session  = JFactory::getSession();
		$order_id = $session->get('order_id');
		$auth   = $session->get('auth');

		$oid    = JRequest::getInt('oid', $order_id);
		$encr   = JRequest::getString('encr', null);
		$layout = JRequest::getCmd('layout');

		$model = $this->getModel('order_detail');

		$OrdersDetail = $order_functions->getOrderDetails($oid);

		if ($user->id)
		{
			if ($OrdersDetail->user_id != $user->id)
			{
				$app->redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid'));

				return;
			}
		}
		else
		{
			if ($encr)
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
			elseif (!$user->id && !isset($auth['users_info_id']))
			{
				$app->redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid'));

				return;
			}
		}

		$this->OrdersDetail = $OrdersDetail;
		$this->user = $user;
		$this->params = $params;

		parent::display($tpl);
	}

	/**
	 * Replace Reorder Button
	 *
	 * @param   string  &$template  Template Data
	 *
	 * @return  void
	 */
	public function replaceReorderButton(&$template)
	{
		$app     = JFactory::getApplication();
		$order   = $this->OrdersDetail;
		$orderId = $app->input->getInt('oid', 0);
		$print   = $app->input->getInt('print', 0);
		$reorder = '';

		if ($order->order_status != 'C' && $order->order_status != 'S' && $order->order_status != 'PR' && $order->order_status != 'APP' && $print != 1 && $order->order_payment_status != 'Paid')
		{
			$reorder = "<form method='post'>
			<input type='hidden' name='order_id' value='" . $orderId . "'>
			<input type='hidden' name='option' value='com_redshop'>
			<input type='hidden' name='view' value='order_detail'>
			<input type='hidden' name='task' value='payment'>
			<input type='submit' name='payment' value='" . JText::_("COM_REDSHOP_PAY") . "'>
			</form>";
		}
		else
		{
			JFactory::getDocument()->addScriptDeclaration('
				function submitReorder() {
					if (!confirm("' . JText::_('COM_REDSHOP_CONFIRM_CART_EMPTY') . '")) {
						return false;
					}
					return true;
				}
			');
			$reorder = "<form method='post' name='frmreorder' id='frmreorder'>";
			$reorder .= "<input type='submit' name='reorder' id='reorder' value='" . JText::_('COM_REDSHOP_REORDER') . "' onclick='return submitReorder();' />";
			$reorder .= "<input type='hidden' name='order_id' value='" . $orderId . "'>";
			$reorder .= "<input type='hidden' name='option' value='com_redshop'>";
			$reorder .= "<input type='hidden' name='view' value='order_detail'>";
			$reorder .= "<input type='hidden' name='task' value='reorder'></form>";
		}

		$template = str_replace("{reorder_button}", $reorder, $template);
	}
}
