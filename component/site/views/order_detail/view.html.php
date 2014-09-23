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

JLoader::load('RedshopHelperAdminOrder');

class RedshopViewOrder_detail extends JView
{
	function display ($tpl = null)
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
				$app->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid'));

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
				$app->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid'));

				return;
			}
		}

		$this->OrdersDetail = $OrdersDetail;
		$this->user = $user;
		$this->params = $params;

		parent::display($tpl);
	}
}
