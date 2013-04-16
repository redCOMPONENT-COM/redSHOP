<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.controller');

/**
 * Product rating Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class Product_ratingController extends JController
{
	/**
	 * save function
	 *
	 * @access public
	 * @return void
	 */
	function save()
	{
		$post        = JRequest::get('post');
		$option      = JRequest::getVar('option');
		$Itemid      = JRequest::getVar('Itemid');
		$product_id  = JRequest::getInt('product_id');
		$category_id = JRequest::getInt('category_id');
		$model       = $this->getModel('product_rating');
		$rate        = JRequest::getVar('rate');

		if ($model->sendMailForReview($post))
		{
			$msg = JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY');
		}

		if ($rate == 1)
		{
			$link = 'index.php?option=' . $option . '&view=product&pid=' . $product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid;
			$this->setRedirect($link, $msg);
		}
		else
		{
			echo $msg;?>
			<span id="closewindow"><input type="button" value="Close Window" onclick="window.parent.redBOX.close();"/></span>
			<script>
				setTimeout("window.parent.redBOX.close();", 5000);
			</script>
			<?php
			exit;
		}
	}
}
