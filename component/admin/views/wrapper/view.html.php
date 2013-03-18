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
jimport('joomla.html.pagination');

class wrapperViewwrapper extends JView
{
	public function display($tpl = null)
	{
		global $mainframe, $context;

		$product_id = JRequest::getVar('product_id');
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_WRAPPER'));

		$total = & $this->get('Total');
		$data = & $this->get('Data');
		JToolBarHelper::title(JText::_('COM_REDSHOP_WRAPPER'), 'redshop_wrapper48');

		JToolBarHelper::addNewX();
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		$pagination = & $this->get('Pagination');
		$uri =& JFactory::getURI();
		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('data', $data);
		$this->assignRef('product_id', $product_id);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
