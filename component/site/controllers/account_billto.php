<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Account Billing Address Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerAccount_billto extends RedshopController
{
	/**
	 * Constructor.
	 *
	 * @param   array  $default  config array.
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
		$this->registerTask('', 'edit');
		$this->registerTask('display', 'edit');
	}

	/**
	 * Method to edit billing Address
	 *
	 * @return  boolean  True if the ID is in the edit list.
	 */
	public function edit()
	{
		$user                        = JFactory::getUser();
		$order_functions             = order_functions::getInstance();
		$billingaddresses            = $order_functions->getBillingAddress($user->id);
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
	 * @return void
	 */
	public function save()
	{
		$user   = JFactory::getUser();
		$post   = JRequest::get('post');
		$return = JRequest::getVar('return');
		$Itemid = JRequest::getInt('Itemid');

		$post['users_info_id'] = JRequest::getInt('cid');
		$post['id']            = $post['user_id'];
		$post['address_type']  = "BT";
		$post['email']         = $post['email1'];
		$post['password']      = JRequest::getVar('password1', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['password2']     = JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);

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
			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&Itemid=' . $Itemid, false);

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
			$link = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $Itemid, false);
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Method called when user pressed cancel button
	 *
	 * @return void
	 */
	function cancel()
	{
		$Itemid  = JRequest::getVar('Itemid');
		$msg     = JText::_('COM_REDSHOP_BILLING_INFORMATION_EDITING_CANCELLED');
		$return  = JRequest::getVar('return');
		$setexit = JRequest::getInt('setexit', 1);
		$link    = '';

		if ($return != "")
		{
			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&Itemid=' . $Itemid, false);

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
			$link = 'index.php?option=com_redshop&view=account&Itemid=' . $Itemid;
		}

		$this->setRedirect($link, $msg);
	}
}
