<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
class RedshopControllerAccount_Billto extends RedshopController
{
	/**
	 * Constructor.
	 *
	 * @param   array $default config array.
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
	 * @return  void
	 * @throws  Exception
	 */
	public function edit()
	{
		$user             = JFactory::getUser();
		$billingAddresses = RedshopHelperOrder::getBillingAddress($user->id);

		Redshop\User\Billing\Billing::setGlobal($billingAddresses);

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
	 * @return  void
	 *
	 * @since   1.0.0
	 *
	 * @throws  Exception
	 */
	public function save()
	{
		$app     = JFactory::getApplication();
		$input   = $app->input;
		$user    = JFactory::getUser();
		$post    = $input->post->getArray();
		$itemId  = $input->getInt('Itemid', 0);
		$setExit = $input->getInt('setexit', 0);
		$return  = $input->getString('return', '');

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

		/** @var RedshopModelAccount_billto $model */
		$model       = $this->getModel('account_billto');
		$redshopUser = $model->store($post);

		$msg = $redshopUser ? JText::_('COM_REDSHOP_BILLING_INFORMATION_SAVE')
			: JText::_('COM_REDSHOP_ERROR_SAVING_BILLING_INFORMATION');

		if ($return != "")
		{
			if ($setExit)
			{
				$app->redirect(
					JRoute::_(
						'index.php?option=com_redshop&view=account_billto&tmpl=component&is_edit=1&return=' . $return,
						false
					),
					$msg
				);
			}

			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&Itemid=' . $itemId, false);
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
	 * @throws Exception
	 */
	public function cancel()
	{
		$input   = JFactory::getApplication()->input;
		$itemId  = $input->get('Itemid');
		$msg     = JText::_('COM_REDSHOP_BILLING_INFORMATION_EDITING_CANCELLED');
		$return  = $input->get('return');
		$setexit = $input->getInt('setexit', 1);

		if ($return != "")
		{
			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&Itemid=' . $itemId, false);

			if (!isset($setexit) || $setexit != 0)
			{
				?>
                <script language="javascript">
                    window.parent.location.href = "<?php echo $link ?>";
                </script>
				<?php
				JFactory::getApplication()->close();
			}
		}
		else
		{
			$link = 'index.php?option=com_redshop&view=account&Itemid=' . $itemId;
		}

		$this->setRedirect($link, $msg);
	}
}
