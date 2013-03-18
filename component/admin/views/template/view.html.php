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

class templateViewtemplate extends JView
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($tpl = null)
	{
		global $mainframe, $context;
		$context = 'template_id';
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_TEMPLATES'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES_MANAGEMENT'), 'redshop_templates48');

		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::customX('copy', 'copy.png', 'copy_f2.png', 'Copy', true);
		JToolBarHelper::deleteList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();


		$uri =& JFactory::getURI();
		$context = 'template';
		$filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'template_id');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$template_section = $mainframe->getUserStateFromRequest($context . 'template_section', 'template_section', 0);

		$lists['order'] = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;
		$templates = & $this->get('Data');

		$total = & $this->get('Total');
		$pagination = & $this->get('Pagination');

		$redtemplate = new Redtemplate();
		$optionsection = $redtemplate->getTemplateSections();
		$lists['section'] = JHTML::_('select.genericlist', $optionsection, 'template_section', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $template_section);

		$this->assignRef('user', JFactory::getUser());
		$this->assignRef('lists', $lists);
		$this->assignRef('templates', $templates);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('request_url', $uri->toString());
		parent::display($tpl);
	}
}

?>
