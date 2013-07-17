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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';

class stockroom_detailVIEWstockroom_detail extends JView
{
	public function display($tpl = null)
	{
		$layout = JRequest::getVar('layout', '');

		if ($layout == 'default_product')
		{
			$this->display_product();

			return false;
		}

		$lists = array();
		$uri = JFactory::getURI();
		$option = JRequest::getVar('option', '', 'request', 'string');
		$model = $this->getModel('stockroom_detail');

		if ($layout == 'importstock')
		{
			$stockroom_name = $model->getStockRoomList();
			$op = array();
			$op[0]->value = 0;
			$op[0]->text = JText::_('COM_REDSHOP_SELECT');
			$stockroom_name = array_merge($op, $stockroom_name);
			$lists['stockroom_id'] = JHTML::_('select.genericlist', $stockroom_name, 'stockroom_id', 'class="inputbox" size="1" ', 'value', 'text');

			JToolBarHelper::title(JText::_('COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC'), 'redshop_stockroom48');
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
			$this->setLayout($layout);
		}
		else
		{
			$document = JFactory::getDocument();
			$document->addScript('components/' . $option . '/assets/js/select_sort.js');
			$document->addStyleSheet('components/com_redshop/assets/css/search.css');
			$document->addScript('components/com_redshop/assets/js/search.js');

			$this->setLayout('default');
			$detail = $this->get('data');

			$isNew = ($detail->stockroom_id < 1);
			$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
			JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKROOM') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_stockroom48');

			// Create the toolbar
			JToolBarHelper::apply();
			JToolBarHelper::save();

			if ($isNew)
			{
				JToolBarHelper::cancel();
			}
			else
			{
				JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
			}

			// Get data from the model
			$model = $this->getModel('stockroom_detail');

			// Get stockroom container product data from the model
			$stock_product_data = $model->stock_product_data($detail->stockroom_id);

			if (count($stock_product_data) > 0)
			{
				$result_stock = $stock_product_data;
			}
			else
			{
				$result_stock = array();
			}

			// Get stockroom product
			$lists['stockroom_product'] = JHTML::_('select.genericlist', $result_stock, 'container_product[]',
				'class="inputbox" onmousewheel="mousewheel(this);" ondblclick="selectnone(this);" multiple="multiple"  size="15" style="width:200px;" ',
				'value', 'text', 0
			);

			$result = array();

			// Get all product
			$lists['product_all'] = JHTML::_('select.genericlist', $result, 'product_all[]',
				'class="inputbox" ondblclick="selectnone(this);" multiple="multiple"  size="15" style="width:200px;" ', 'value', 'text', 0
			);

			$lists['show_in_front'] = JHTML::_('select.booleanlist', 'show_in_front', 'class="inputbox"', $detail->show_in_front);

			$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

			$delivery_time = array();
			$delivery_time['value'] = "days";
			$delivery_time['value'] .= "weeks";

			$extra_field = new extra_field;

			$booleanlist = $extra_field->booleanlist('delivery_time', 'class="inputbox"', $detail->delivery_time,
				$yes = JText::_('COM_REDSHOP_DAYS'), $no = JText::_('COM_REDSHOP_WEEKS')
			);

			$this->booleanlist = $booleanlist;
			$this->detail = $detail;
		}

		$this->lists       = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}

	public function display_product($tpl = null)
	{
		$id = JRequest::getVar('id', '');

		// Get data from the model
		$model = $this->getModel('stockroom_detail');
		$container = $model->stock_container($id);

		// Assign stock room product template
		$this->setLayout('default_product');

		$uri = JFactory::getURI();

		// Assign data to template
		$this->lists       = $container;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
