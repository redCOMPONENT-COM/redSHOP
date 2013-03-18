<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php');

jimport('joomla.application.component.controller');
/**
 * Account Billing Address Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class account_billtoController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
		$this->registerTask('', 'edit');
	}
	/**
	 * Method to edit billing Address
	 *
	 */
	public function edit()
	{
		$user = & JFactory::getUser();
		$order_functions = new order_functions();
		$billingaddresses = $order_functions->getBillingAddress($user->id);
		$GLOBALS['billingaddresses'] = $billingaddresses;

		$task = JRequest::getVar('submit', 'post');

		if ($task == 'Cancel')
		{
			$this->registerTask('save', 'cancel');
		}
		parent::display();
	}
	/**
	 * Method to save Billing Address
	 *
	 */
	public function save()
	{
		$user = & JFactory::getUser();
		$post = JRequest::get('post');
		$return = JRequest::getVar('return');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post['users_info_id'] = $cid[0];
		$post['id'] = $post['user_id'];
		$post['address_type'] = "BT";
		$post['email'] = $post['email1'];
		$post['password'] = JRequest::getVar('password1', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['password2'] = JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if (isset($user->username))
		{
			$post['username'] = $user->username;
		}

		$model = $this->getModel('account_billto');

		if ($reduser = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_BILLING_INFORMATION_SAVE');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_BILLING_INFORMATION');
		}
		$setexit = JRequest::getInt('setexit', 1);
		$link = '';

		if ($return != "")
		{
			$link = JRoute::_('index.php?option=' . $option . '&view=' . $return . '&Itemid=' . $Itemid, false);

			if (!isset($setexit) || $setexit != 0)
			{
				?>
				<script language="javascript">
					window.parent.location.href = "<?php echo $link ?>";
				</script>
				<?php
				exit;
			}
		}
		else
		{
			$link = JRoute::_('index.php?option=' . $option . '&view=account&Itemid=' . $Itemid, false);
		}
		$this->setRedirect($link, $msg);
	}
	/**
	 * Method called when user pressed cancel button
	 *
	 */
function cancel()
{
	$option = JRequest::getVar('option');
	$Itemid = JRequest::getVar('Itemid');

	$msg = JText::_('COM_REDSHOP_BILLING_INFORMATION_EDITING_CANCELLED');

	$return = JRequest::getVar('return');
	$setexit = JRequest::getInt('setexit', 1);
	$link = '';
if ($return != "")
{
	$link = JRoute::_('index.php?option=' . $option . '&view=' . $return . '&Itemid=' . $Itemid, false);
if (!isset($setexit) || $setexit != 0)
{
	?>
	<script language="javascript">
		window.parent.location.href = "<?php echo $link ?>";
	</script>
	<?php
	exit;
}

}
else
{
	$link = 'index.php?option=' . $option . '&view=account&Itemid=' . $Itemid;
}
	$this->setRedirect($link, $msg);
}
}    ?>