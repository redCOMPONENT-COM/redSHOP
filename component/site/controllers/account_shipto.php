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
 * Account shipping Address Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerAccount_Shipto extends RedshopController
{
	/**
	 * Method to save Shipping Address
	 *
	 * @return void
	 * @throws Exception
	 */
	public function save()
	{
		$app     = JFactory::getApplication();
		$input   = $app->input;
		$post    = $input->post->getArray();
		$return  = $input->getString('return', '');
		$itemId  = $input->getInt('Itemid', 0);
		$setExit = $input->getInt('setexit', 1);

		$post['users_info_id'] = $input->post->getInt('cid', 1);
		$post['id']            = $post['user_id'];
		$post['address_type']  = REDSHOP_ADDRESS_TYPE_SHIPPING;

		/** @var RedshopModelAccount_shipto $model */
		$model   = $this->getModel('account_shipto');
		$redUser = $model->store($post);

		$msg  = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING_INFORMATION');
		$link = JRoute::_('index.php?option=com_redshop&view=account_shipto&Itemid=' . $itemId, false);

		if (false !== $redUser)
		{
			$post['users_info_id'] = $redUser->users_info_id;
			$msg                   = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_SAVE');
		}

		if (!empty($return))
		{
			$link = JRoute::_(
				'index.php?option=com_redshop&view=' . $return
				. '&users_info_id=' . $post['users_info_id'] . '&Itemid=' . $itemId,
				false
			);

			if (!isset($setExit) || $setExit != 0)
			{
				$app->redirect('index.php?option=com_redshop&view=account_shipto&tmpl=component&is_edit=1&return='
					. $return . '&Itemid=' . $itemId, $msg);
			}
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Method to delete shipping address
	 *
	 * @return void
	 * @throws Exception
	 */
	public function remove()
	{
		$input  = JFactory::getApplication()->input;
		$itemId = $input->get('Itemid');
		$infoId = $input->getInt('infoid', 0);

		/** @var RedshopModelAccount_shipto $model */
		$model = $this->getModel('account_shipto');

		if (!$infoId)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		if (!$model->delete($infoId))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg    = JText::_('COM_REDSHOP_ACCOUNT_SHIPPING_DELETED_SUCCESSFULLY');
		$return = $input->get('return');
		$link   = JRoute::_('index.php?option=com_redshop&view=account_shipto&Itemid=' . $itemId, false);

		if (!empty($return))
		{
			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&Itemid=' . $itemId, false);
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
		$itemId  = $input->getInt('Itemid');
		$return  = $input->get('return');
		$message = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_EDITING_CANCELLED');

		if (empty($return))
		{
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=account_shipto&Itemid=' . $itemId, false), $message);
			$this->redirect();
		}

		$setExit = $input->getInt('setexit', 1);
		$link = JRoute::_(
			'index.php?option=com_redshop&view=' . $return . '&users_info_id=' . $input->getInt('cid') . '&Itemid=' . $itemId . '',
			false
		);

		if ($setExit != 1)
		{
			$this->setRedirect($link, $message);
			$this->redirect();
		}
		?>
        <script language="javascript">window.parent.location.href = "<?php echo $link ?>";</script>
		<?php
		JFactory::getApplication()->close();
	}
}
