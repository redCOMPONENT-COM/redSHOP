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
require_once JPATH_COMPONENT . '/helpers/category.php';
/**
 * Export view class
 *
 * @since  2.5
 */
class ExportViewexport extends JView
{
	/**
	 * Method to display export view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$task = JRequest::getVar('task');
		$product_category = new product_category;
		$model = $this->getModel('export');

		if ($task == 'exportfile')
		{
			$this->get('Data');
		}

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_EXPORT'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_EXPORT_MANAGEMENT'), 'redshop_export48');

		JToolBarHelper::custom('exportfile', 'redshop_export_export32.png', JText::_('COM_REDSHOP_EXPORT'), JText::_('COM_REDSHOP_EXPORT'), false, false);

		$categories = $product_category->list_all("product_category[]", 0, array(), 10, true, true);
		$lists['categories'] = $categories;

		$manufacturers = $model->getmanufacturers();
		$lists['manufacturers'] = JHTML::_('select.genericlist', $manufacturers, 'manufacturer_id[]', 'class="inputbox"  multiple="multiple"  size="10" style="width: 250px;"> ', 'value', 'text', 0);

		$this->lists = $lists;

		parent::display($tpl);
	}
}
