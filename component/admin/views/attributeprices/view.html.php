<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.html.pagination');

class RedshopViewAttributeprices extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		global $context;

		$app = JFactory::getApplication();

		$section_id = $app->input->get('section_id');
		$section    = $app->input->get('section');

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_ATTRIBUTE_PRICE'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_PRICE'), 'redshop_vatrates48');

		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolBarHelper::deleteList();
		$uri = JFactory::getURI();

		$limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', '0');
		$limit      = $app->getUserStateFromRequest($context . 'limit', 'limit', '10');

		$total = $this->get('Total');
		$data = $this->get('Data');
		$pagination = new JPagination($total, $limitstart, $limit);

		$this->user = JFactory::getUser();
		$this->data = $data;
		$this->section_id = $section_id;
		$this->section = $section;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
