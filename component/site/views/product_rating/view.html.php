<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperProduct');

/**
 * Class RedshopViewProduct_rating
 *
 * @since  1.5
 */
class RedshopViewProduct_Rating extends RedshopView
{
	protected $state;

	protected $form;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display ($tpl = null)
	{
		$this->state = $this->get('State');
		$this->form = $this->get('Form');

		$app = JFactory::getApplication();

		$user = JFactory::getUser();

		// Preform security checks
		if (!$user->id && RATING_REVIEW_LOGIN_REQUIRED)
		{
			echo JText::_('COM_REDSHOP_ALERTNOTAUTH_REVIEW');

			return;
		}

		$model         = $this->getModel('product_rating');
		$userinfo      = $model->getuserfullname($user->id);
		$params        = $app->getParams('com_redshop');
		$Itemid        = JRequest::getInt('Itemid');
		$product_id    = JRequest::getInt('product_id');
		$category_id   = JRequest::getInt('category_id');
		$user          = JFactory::getUser();
		$model         = $this->getModel('product_rating');
		$rate          = JRequest::getInt('rate');
		$already_rated = $model->checkRatedProduct($product_id, $user->id);

	if ($already_rated == 1)
	{
		if ($rate == 1)
		{
			$msg  = JText::_('COM_REDSHOP_YOU_CAN_NOT_REVIEW_SAME_PRODUCT_AGAIN');
			$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid);
			$app->redirect($link, $msg);
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


		$this->userinfo = $userinfo;
		$this->params = $params;
		$this->rate = $rate;

		parent::display($tpl);
	}
}
