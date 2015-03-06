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
		$input = $app->input;

		$user = JFactory::getUser();
		$userHelper = new rsUserhelper;

		// Preform security checks
		if (!$user->id && RATING_REVIEW_LOGIN_REQUIRED)
		{
			echo JText::_('COM_REDSHOP_ALERTNOTAUTH_REVIEW');

			return;
		}

		$model         = $this->getModel('product_rating');
		$userinfo      = $userHelper->getRedSHOPUserInfo($user->id);
		$params        = $app->getParams('com_redshop');
		$Itemid        = $input->getInt('Itemid', 0);
		$product_id    = $input->getInt('product_id', 0);
		$category_id   = $input->getInt('category_id', 0);
		$tmpl          = $input->getCmd('tmpl', '');

		if ($input->getInt('rate', 0))
		{
			if ($tmpl == 'component')
			{
				?>
				<script>
					setTimeout("window.parent.redBOX.close();", 5000);
				</script>
			<?php
			}
		}
		elseif ($model->checkRatedProduct($product_id, $user->id))
		{
			$msg  = JText::_('COM_REDSHOP_YOU_CAN_NOT_REVIEW_SAME_PRODUCT_AGAIN');

			if ($tmpl != 'component')
			{
				$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product_id . '&cid=' . $category_id . '&modal=0&Itemid=' . $Itemid);
				$app->redirect($link, $msg);
			}
			else
			{
				echo $msg;
				?>
				<script>
					setTimeout("window.parent.redBOX.close();", 5000);
				</script>
				<?php
				return;
			}
		}

		$this->userinfo = $userinfo;
		$this->params = $params;

		parent::display($tpl);
	}
}
