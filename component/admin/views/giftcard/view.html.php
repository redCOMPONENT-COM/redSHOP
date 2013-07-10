<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class giftcardViewgiftcard extends JView
{
	/**
	 * The current user.
	 *
	 * @var  JUser
	 */
	public $user;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_GIFTCARD'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_GIFTCARD_MANAGEMENT'), 'redshop_giftcard_48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', JText::_('COM_REDSHOP_TOOLBAR_COPY'), true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();


		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'giftcard_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists ['order'] = $filter_order;
		$lists ['order_Dir'] = $filter_order_Dir;
		$giftcard = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->giftcard = $giftcard;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
