<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopViewOrder_detail extends RedshopView
{
	public function display ($tpl = null)
	{
		$app = JFactory::getApplication();

		$order_functions = order_functions::getInstance();

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

		$prodhelperobj = productHelper::getInstance();
		$prodhelperobj->generateBreadcrumb();

		$user          = JFactory::getUser();
		$session       = JFactory::getSession();
		$auth          = $session->get('auth');
		$orderId       = $app->input->getInt('oid', $session->get('order_id'));
		$encr          = $app->input->getString('encr', null);
		$order_payment = $order_functions->getOrderPaymentDetail($orderId);

		if ($order_payment && count($order_payment))
		{
			// Load payment language file
			$language      = JFactory::getLanguage();
			$base_dir      = JPATH_ADMINISTRATOR;
			$language_tag  = $language->getTag();
			$extension = 'plg_redshop_payment_' . ($order_payment[0]->payment_method_class);
			$language->load($extension, $base_dir, $language_tag, true);
		}

		$model = $this->getModel('order_detail');

		$OrdersDetail = $order_functions->getOrderDetails($orderId);

		if ($user->id)
		{
			if ($OrdersDetail->user_id != $user->id)
			{
				$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));

				return;
			}
		}
		else
		{
			if ($encr)
			{
				$authorization = $model->checkauthorization($orderId, $encr);

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
				$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));

				return;
			}
		}

		$this->OrdersDetail = $OrdersDetail;
		$this->user         = $user;
		$this->params       = $app->getParams('com_redshop');

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
