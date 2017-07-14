<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


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
		$this->form  = $this->get('Form');

		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		// Preform security checks
		if (!$user->id && Redshop::getConfig()->get('RATING_REVIEW_LOGIN_REQUIRED'))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ALERTNOTAUTH_REVIEW'), 'warning');

			return;
		}

		$this->params      = $app->getParams('com_redshop');
		$model             = $this->getModel('product_rating');
		$this->productId   = $app->input->getInt('product_id', 0);
		$rate              = $app->input->getInt('rate', 0);
		$this->productInfo = RedshopHelperProduct::getProductById($this->productId);

		if (!$rate && $user->id && $model->checkRatedProduct($this->productId, $user->id))
		{
			$app->input->set('rate', 1);
			$app->enqueueMessage(JText::_('COM_REDSHOP_YOU_CAN_NOT_REVIEW_SAME_PRODUCT_AGAIN'), 'warning');
		}

		parent::display($tpl);
	}
}
