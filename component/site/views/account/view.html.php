<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Account  view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewAccount extends RedshopView
{
	/**
	 * @var  JPagination
	 */
	public $pagination;

	/**
	 * @var  JUser
	 */
	public $user;

	/**
	 * @var  mixed
	 */
	public $userdata;

	/**
	 * @var  Joomla\Registry\Registry
	 */
	public $params;

	/**
	 * @var  \JModelLegacy
	 */
	public $model;

	/**
	 * Execute and display a template script.
	 *
	 * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed         A string if successful, otherwise a JError object.
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->params = $this->app->getParams('com_redshop');
		$this->user   = JFactory::getUser();
		$twigParams   = [
			'url'    => \JURI::base(),
			'app'    => $this->app,
			'itemId' => $this->app->input->getInt('Itemid'),
			'tagId'  => $this->app->input->getInt('tagid'),
			'edit'   => $this->app->input->getInt('edit'),
			'user'   => $this->user,
			'params' => $this->params
		];

		RedshopHelperBreadcrumb::generate();

		$itemId = $this->input->getInt('Itemid');
		$layout = $this->input->getString('layout');

		/** @var RedshopModelAccount $this */
		$this->model = $this->getModel();

		$this->userdata = $this->model->getUserAccountInfo($this->user->id);

		if (empty($this->userdata) && $layout != 'mywishlist') {
			$this->app->redirect(
				JRoute::_("index.php?option=com_redshop&view=account_billto&Itemid=" . $itemId),
				JText::_('COM_REDSHOP_LOGIN_USER_IS_NOT_REDSHOP_USER')
			);
		}

		$layout = $this->input->getString('layout', 'default');
		$mail   = $this->input->getInt('mail');

		// Preform security checks. Give permission to send wishlist while not logged in
		if (($this->user->id == 0 && $layout !== 'mywishlist') || ($this->user->id == 0 && $layout === 'mywishlist' && !isset($mail))) {
			$this->app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . $itemId, false));
		}

		switch ($layout) {
			case 'cards':
				$twigParams = array_merge($twigParams, $this->layoutCards());
				break;
			case 'mytags':
				$twigParams = array_merge($twigParams, $this->layoutMytags());
				break;
			case 'mywishlist':
				$twigParams = array_merge($twigParams, $this->layoutMyWishlist());
				break;
			default:
				$twigParams = array_merge($twigParams, $this->layoutDefault());
		}

		$twigParams = array_merge(
			$twigParams,
			[
				'userData' => $this->userdata,
			]
		);

		print \RedshopLayoutHelper::render(
			$layout,
			$twigParams,
			'',
			array(
				'component'  => 'com_redshop',
				'layoutType' => 'Twig',
				'layoutOf'   => 'component',
				'prefix'     => 'com_redshop/account'
			)
		);
	}

	/**
	 * Layout my tags
	 *
	 * @return  mixed
	 *
	 * @since   3.0.1
	 */
	private function layoutMytags()
	{
		/** @var RedshopModelAccount $this ->model */
		$this->model = $this->getModel('account');

		JLoader::import('joomla.html.pagination');
		$this->setLayout('mytags');

		$remove = $this->input->getInt('remove', 0);

		if ($remove == 1) {
			$this->model->removeTag();
		}

		$maxcategory = $this->params->get('maxcategory', 5);
		$limit       = $this->app->getUserStateFromRequest(
			$this->model->context . 'limit',
			'limit',
			$maxcategory,
			5
		);

		$limitstart       = $this->input->getInt('limitstart', 0, '', 'int');
		$total            = $this->get('total');
		$pagination       = new JPagination($total, $limitstart, $limit);
		$this->pagination = $pagination;

		$twigParams = [
			'model'       => $this->getModel('account'),
			'pageTitle'   => \JText::_('COM_REDSHOP_MY_TAGS'),
			'maxCategory' => $maxcategory,
			'limit'       => $limit,
			'total'       => $total,
			'pagination'  => $pagination
		];

		return $twigParams;
	}

	/**
	 * Layout my wishlist
	 *
	 * @return  mixed
	 *
	 * @since   3.0.1
	 */
	private function layoutMyWishlist()
	{
		$wishlistId = $this->input->getInt('wishlist_id');
		$mail       = $this->input->getInt('mail', 0);
		$window     = $this->input->getInt('window');

		if ($wishlistId == 0 && !Redshop::getConfig()->get('WISHLIST_LIST')) {
			$usersWishlist = RedshopHelperWishlist::getUserWishlist();
			$usersWishlist = reset($usersWishlist);

			$this->app->redirect(
				JRoute::_(
					"index.php?option=com_redshop&view=account&layout=mywishlist&wishlist_id="
					. $usersWishlist->wishlist_id . "&Itemid=" . $itemId,
					false
				)
			);
		}

		// If wishlist Id is not set then redirect to it's main page
		if ($wishlistId == 0) {
			$this->app->redirect(
				JRoute::_("index.php?option=com_redshop&view=wishlist&layout=viewwishlist&Itemid=" . $itemId)
			);
		}

		JLoader::import('joomla.html.pagination');

		$this->setLayout('mywishlist');

		$remove = $this->input->getInt('remove', 0);

		if ($remove == 1) {
			$this->model->removeWishlistProduct();
		}

		$maxcategory = $this->params->get('maxcategory', 5);
		$limit       = $this->app->getUserStateFromRequest(
			$this->model->context . 'limit',
			'limit',
			$maxcategory,
			5
		);

		$limitstart       = $this->input->getInt('limitstart', 0, '', 'int');
		$total            = $this->get('total');
		$pagination       = new JPagination($total, $limitstart, $limit);
		$this->pagination = $pagination;
		$displayWishlist  = $mail == 0 ? $this->wishlistTemplate() : $this->wishlistMailTemplate();

		return [
			'wishlistId'      => $wishlistId,
			'mail'            => $mail,
			'window'          => $window,
			'disPlayWishlist' => $displayWishlist
		];
	}

	/**
	 * Wishlist template
	 *
	 * @return  string
	 *
	 * @since   3.0.1
	 */
	private function wishlistTemplate()
	{
		$wishlist = $this->model->getMyDetail();
		$template = RedshopHelperTemplate::getTemplate("wishlist_template");

		if (count($template) > 0 && $template[0]->template_desc != "") {
			$templateDesc = $template[0]->template_desc;
		} else {
			$templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('wishlist_template');
		}

		return \RedshopTagsReplacer::_(
			'wishlist',
			$templateDesc,
			array(
				'wishlist' => $wishlist
			)
		);
	}

	/**
	 * Wishlist mail template
	 *
	 * @return  string
	 *
	 * @since   3.0.1
	 */
	private function wishlistMailTemplate()
	{
		$mailTemplate = RedshopHelperTemplate::getTemplate("wishlist_mail_template");

		if (count($mailTemplate) > 0 && $mailTemplate[0]->template_desc != "") {
			$templateDesc = $mailTemplate[0]->template_desc;
		} else {
			$templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('wishlist_mail_template');
		}

		return \RedshopTagsReplacer::_(
			'wishlistmail',
			$templateDesc,
			array(
				'user'       => $this->user,
				'itemId'     => $this->app->input->getInt('Itemid'),
				'wishlistId' => $this->input->getInt('wishlist_id')
			)
		);
	}

	/**
	 * Layout default
	 *
	 * @return  mixed
	 *
	 * @since   3.0.1
	 */
	private function layoutDefault()
	{
		$template = RedshopHelperTemplate::getTemplate("account_template");

		if (count($template) > 0 && $template[0]->template_desc != "") {
			$templateDesc = $template[0]->template_desc;
		} else {
			$templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('account_template');
		}

		return [
			'displayTemplate' => RedshopTagsReplacer::_(
				'account',
				$templateDesc,
				[
					'params'   => $this->params,
					'userData' => $this->userdata
				]
			)
		];
	}

	/**
	 * Layout cards
	 *
	 * @return  mixed
	 *
	 * @since   3.0.1
	 */
	private function layoutCards()
	{
		JPluginHelper::importPlugin('redshop_payment');
		$dispatcher = \RedshopHelperUtility::getDispatcher();
		$cards      = $dispatcher->trigger('onListCreditCards', array());

		if (empty($cards)) {
			JFactory::getApplication()->enqueueMessage(
				JText::_('COM_REDSHOP_PAYMENT_NO_CREDIT_CARDS_PLUGIN_LIST_FOUND'),
				'warning'
			);
		}

		return ['cards' => $cards];
	}
}