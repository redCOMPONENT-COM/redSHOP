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
 * wishlist Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerWishlist extends RedshopController
{
	/**
	 * createsave wishlist function
	 *
	 * @access public
	 * @return void
	 */
	public function createsave()
	{
		$user = JFactory::getUser();

		/** @var RedshopModelWishlist $model */
		$model = $this->getModel("wishlist");
		$input = JFactory::getApplication()->input;

		$post                  = array();
		$post['wishlist_name'] = $input->post->getString('txtWishlistname', '');
		$post['user_id']       = $user->id;
		$post['cdate']         = time();
		$post['product_id']    = $input->post->getInt('product_id', 0);

		if ($model->store($post))
		{
			echo "<div class='wishlistmsg'>" . JText::_('COM_REDSHOP_PRODUCT_SAVED_IN_WISHLIST_SUCCESSFULLY') . "</div>";
		}
		else
		{
			echo "<div class='wishlistmsg-error'>" . JText::_('COM_REDSHOP_PRODUCT_NOT_SAVED_IN_WISHLIST') . "</div>";
		}

		if ($input->getInt('loginwishlist', 0) == 1)
		{
			$return = JRoute::_('index.php?option=com_redshop&view=wishlist&task=viewwishlist&Itemid=' . $this->input->post->getInt('Itemid'), false);
			$this->setRedirect($return);
		}
		else
		{
			?>
			<script language="javascript">
				var t = setTimeout("window.parent.SqueezeBox.close();window.parent.location.reload();", 2000);
			</script>
		<?php
		}
	}

	/**
	 * Save wishlist function
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function savewishlist()
	{
		// Check for request forgeries.
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		/** @var RedshopModelWishlist $model */
		$model = $this->getModel("wishlist");

		$data = JFactory::getApplication()->input->post->getArray();

		if ($model->savewishlist($data))
		{
			echo "<div class='wishlistmsg'>" . JText::_('COM_REDSHOP_PRODUCT_SAVED_IN_WISHLIST_SUCCESSFULLY') . "</div>";
		}
		else
		{
			echo "<div class='wishlistmsg-error'>" . JText::_('COM_REDSHOP_PRODUCT_NOT_SAVED_IN_WISHLIST') . "</div>";
		}

		?>
		<script language="javascript">
			var t = setTimeout("window.parent.SqueezeBox.close();window.parent.location.reload()", 2000);
		</script>
		<?php
	}

	/**
	 * delete wishlist function
	 *
	 * @access public
	 * @return void
	 */
	public function delwishlist()
	{
		$app    = JFactory::getApplication();
		$user   = JFactory::getUser();
		$model  = $this->getModel("wishlist");
		$Itemid = $app->input->get('Itemid');
		$post   = $app->input->getArray();
		$link = JRoute::_("index.php?option=com_redshop&view=wishlist&task=viewwishlist&Itemid=" . $Itemid, false);

		if ($model->check_user_wishlist_authority($user->id, $post["wishlist_id"]))
		{
			if ($model->delwishlist($user->id, $post["wishlist_id"]))
			{
				$msg = JText::_('COM_REDSHOP_WISHLIST_DELETED_SUCCESSFULLY');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_ERROR_IN_DELETING_WISHLIST');
			}
		}
		else
		{
			$msg  = JText::_('COM_REDSHOP_YOU_ARE_NOT_AUTHORIZE_TO_DELETE');
		}

		$app->redirect($link, $msg);
	}

	/**
	 * My sess del wish list
	 *
	 * @return void
	 */
	public function mysessdelwishlist()
	{
		$input = JFactory::getApplication()->input;
		$post  = array();
		$mydel = $input->get('mydel');
		$model = $this->getModel("wishlist");

		$Itemid = $input->getInt('Itemid', 0);
		$post['wishlist_id'] = $input->getInt('wishlist_id');

		if (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			$post['attribute_id'] = $input->getInt('attribute_id', 0);
			$post['property_id'] = $input->getInt('property_id', 0);
			$post['subattribute_id'] = $input->getInt('subattribute_id', 0);
		}

		$link = JRoute::_("index.php?mydel=1&option=com_redshop&view=wishlist&task=viewwishlist&Itemid=" . $Itemid, false);

		if (!empty($mydel))
		{
			if ($model->mysessdelwishlist($post))
			{
				$msg = JText::_('COM_REDSHOP_WISHLIST_DELETED_SUCCESSFULLY');
			}

			$this->setRedirect($link, $msg);
		}

		$this->setRedirect($link);
	}
}
