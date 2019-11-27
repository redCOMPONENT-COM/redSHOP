<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewNewslettersubscr extends RedshopViewAdmin
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

	public $state;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();
		$lists    = array();

		$document->setTitle(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_MANAGEMENT'), 'envelope redshop_newsletter48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$this->state        = $this->get('State');
		$lists['order']     = $this->state->get('list.ordering', 'subscription_id');
		$lists['order_Dir'] = $this->state->get('list.direction');

		$newslettersubscrs = $this->get('Data');
		$pagination        = $this->get('Pagination');

		$this->user              = JFactory::getUser();
		$this->lists             = $lists;
		$this->newslettersubscrs = $newslettersubscrs;
		$this->pagination        = $pagination;
		$this->request_url       = $uri->toString();

		parent::display($tpl);
	}
}
