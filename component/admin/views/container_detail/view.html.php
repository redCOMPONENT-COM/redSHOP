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

class container_detailVIEWcontainer_detail extends JView
{
	public function display($tpl = null)
	{
		$option = JRequest::getVar('option');
		$conid = JRequest::getVar('conid');

		$layout = JRequest::getVar('layout');

		JToolBarHelper::title(JText::_('COM_REDSHOP_CONTAINER_MANAGEMENT_DETAIL'), 'redshop_container48');

		$document = JFactory::getDocument();

		$document->addScript('components/' . $option . '/assets/js/select_sort.js');
		$document->addStyleSheet('components/com_redshop/assets/css/search.css');
		$document->addScript('components/com_redshop/assets/js/search.js');
		$document->addScript('components/' . $option . '/assets/js/fields.js');
		$document->addScript('components/' . $option . '/assets/js/validation.js');
		$document->addScript('components/' . $option . '/assets/js/json.js');

		$uri = JFactory::getURI();

		$stock_data = JRequest::getVar('stockroom_data', array());

		if ($layout == 'products')
		{
			$this->setLayout('products');
		}

		else
		{
			$this->setLayout('default');
		}

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->container_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_CONTAINER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_container48');
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

		$model = $this->getModel('container_detail');

		if (count($conid) > 0)
		{
			$chk_new = 1;
			$container_product_data = $model->Container_newProduct($conid);

			if (count($container_product_data) > 0)
			{
				$detail->supplier_id = $container_product_data[0]->supplier_id;
			}
		}
		else
		{
			$container_product_data = $model->Container_Product_Data($detail->container_id);
			$chk_new = 0;
		}

		if (count($container_product_data) > 0)
		{
			$result_container = $container_product_data;
		}

		else
		{
			$result_container = array();
		}

		$lists['container_product'] = $result_container;
		$manufacturers = $model->getmanufacturers();
		$supplier = $model->getsupplier();
		$result = array();

		$manufac = array();
		$manufac[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$manufacturers = @array_merge($manufac, $manufacturers);

		$manufac = array();
		$manufac[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$supplier = @array_merge($manufac, $supplier);

		$lists['supplier'] = JHTML::_('select.genericlist', $supplier, 'supplier_id',
			'class="inputbox" onchange="chk_manufacturer();"  size="1" ',
			'value', 'text', $detail->supplier_id
		);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$stock = array();
		$stock[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$stokroom = @array_merge($stock, $stock_data);

		$lists['stock'] = JHTML::_('select.genericlist', $stokroom, 'stockroom_id',
			'class="inputbox" size="1"', 'value', 'text', $detail->stockroom_id
		);

		$this->conid = $conid;
		$this->chk_new = $chk_new;
		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
