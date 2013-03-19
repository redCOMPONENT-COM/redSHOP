<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php';

class product_ratingViewproduct_rating extends JView
{
	function display ($tpl = null)
	{
		global $mainframe;
		$producthelper = new producthelper;
		$pathway       = & $mainframe->getPathway();
		$document      = & JFactory::getDocument();

		$user = & JFactory::getUser();
		// preform security checks
		if ($user->id == 0)
		{
			echo JText::_('COM_REDSHOP_ALERTNOTAUTH_REVIEW');

			return;
		}
		$option        = JRequest::getVar('option');
		$model         = $this->getModel('product_rating');
		$userinfo      = $model->getuserfullname($user->id);
		$params        = & $mainframe->getParams('com_redshop');
		$Itemid        = JRequest::getVar('Itemid');
		$product_id    = JRequest::getInt('product_id');
		$category_id   = JRequest::getInt('category_id');
		$user          = & JFactory::getUser();
		$model         = $this->getModel('product_rating');
		$rate          = JRequest::getVar('rate');
		$already_rated = $model->checkRatedProduct($product_id, $user->id);

	if ($already_rated == 1)
	{
		if ($rate == 1)
		{
			$msg  = JText::_('COM_REDSHOP_YOU_CAN_NOT_REVIEW_SAME_PRODUCT_AGAIN');
			$link = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid);
			$mainframe->redirect($link, $msg);

		}
		else
		{
			echo  JText::_('COM_REDSHOP_YOU_CAN_NOT_REVIEW_SAME_PRODUCT_AGAIN');
			?>
			<span id="closewindow"><input type="button" value="Close Window" onclick="window.parent.redBOX.close();"/></span>
			<script>
				setTimeout("window.parent.redBOX.close();", 2000);
			</script>
			<?php
			return;
		}
	}

		$productinfo = $producthelper->getProductById($product_id);

		// $pathway->addItem($productinfo->product_name,'');

		$this->assignRef('user', $user);
		$this->assignRef('userinfo', $userinfo);
		$this->assignRef('product_id', $product_id);
		$this->assignRef('rate', $rate);
		$this->assignRef('category_id', $category_id);
		$this->assignRef('productinfo', $productinfo);
		$this->assignRef('params', $params);

		parent::display($tpl);
	}
}
