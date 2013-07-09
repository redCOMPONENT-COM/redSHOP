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

class pricesViewprices extends JView
{
	public function display($tpl = null)
	{
		global $context;

		$uri      = JFactory::getURI();
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT_PRICE'));
		jimport('joomla.html.pagination');

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_PRICE'), 'redshop_vatrates48');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();

		$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

		$total      = $this->get('Total');
		$media      = $this->get('Data');
		$product_id = $this->get('ProductId');

		$pagination = new JPagination($total, $limitstart, $limit);
		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->media = $media;
		$this->product_id = $product_id;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
