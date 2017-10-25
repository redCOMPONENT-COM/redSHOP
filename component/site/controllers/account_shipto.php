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
 * Account shipping Address Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerAccount_shipto extends RedshopController
{
	/**
	 * Method to save Shipping Address
	 *
	 * @return void
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
		$post['address_type']  = "ST";

		$model = $this->getModel('account_shipto');

		if ($redUser = $model->store($post))
		{
			$post['users_info_id'] = $redUser->users_info_id;
			$msg = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_SAVE');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING_INFORMATION');
		}

		if ($return != "")
		{
			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&users_info_id=' . $post['users_info_id'] . '&Itemid=' . $itemId, false);

			if (!isset($setExit) || $setExit != 0)
			{
				$app->redirect('index.php?option=com_redshop&view=account_shipto&tmpl=component&is_edit=1&return=' . $return, $msg);
			}
		}
		else
		{
			$link = JRoute::_('index.php?option=com_redshop&view=account_shipto&Itemid=' . $itemId, false);
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Method to delete shipping address
	 *
	 * @return void
	 */
	public function remove()
	{
		$input  = JFactory::getApplication()->input;
		$Itemid = $input->get('Itemid');
		$infoid = $input->getInt('infoid', 0);
		$model  = $this->getModel('account_shipto');

		if (!$infoid)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		if (!$model->delete($infoid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg    = JText::_('COM_REDSHOP_ACCOUNT_SHIPPING_DELETED_SUCCESSFULLY');
		$return = $input->get('return');

		if ($return != "")
		{
			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&Itemid=' . $Itemid, false);
		}
		else
		{
			$link = JRoute::_('index.php?option=com_redshop&view=account_shipto&Itemid=' . $Itemid, false);
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
		$input                 = JFactory::getApplication()->input;
		$Itemid                = $input->getInt('Itemid');
		$post['users_info_id'] = $input->getInt('cid');
		$msg                   = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_EDITING_CANCELLED');
		$return                = $input->get('return');
		$setexit               = $input->getInt('setexit', 1);
		$link                  = '';

		if ($return != "")
		{
			$link = JRoute::_('index.php?option=com_redshop&view=' . $return . '&users_info_id=' . $post['users_info_id'] . '&Itemid=' . $Itemid . '', false);

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
			$link = 'index.php?option=com_redshop&view=account_shipto&Itemid=' . $Itemid;
		}

		$this->setRedirect($link, $msg);
	}
}
