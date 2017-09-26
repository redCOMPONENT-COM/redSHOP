<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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

		$task = JFactory::getApplication()->input->get('submit', 'post');

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
		$app     = JFactory::getApplication();
		$input   = $app->input;
		$user    = JFactory::getUser();
		$post    = $input->post->getArray();
		$itemId  = $input->getInt('Itemid', 0);
		$setExit = $input->getInt('setexit', 1);

		$post['users_info_id'] = $input->post->getInt('cid', 0);
		$post['id']            = $post['user_id'];
		$post['address_type']  = "BT";
		$post['email']         = $post['email1'];
		$post['password']      = $input->post->get('password1', '', 'RAW');
		$post['password2']     = $input->post->get('password2', '', 'RAW');

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

		$link = '';

		if ($return != "")
		{
			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&Itemid=' . $itemId, false);

			if (!isset($setExit) || $setExit != 0)
			{
				$app->redirect('index.php?option=com_redshop&view=account_billto&tmpl=component&is_edit=1&return=' . $return, $msg);
			}
		}
		else
		{
			$link = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $itemId, false);
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
		$input   = JFactory::getApplication()->input;
		$Itemid  = $input->get('Itemid');
		$msg     = JText::_('COM_REDSHOP_BILLING_INFORMATION_EDITING_CANCELLED');
		$return  = $input->get('return');
		$setexit = $input->getInt('setexit', 1);
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
