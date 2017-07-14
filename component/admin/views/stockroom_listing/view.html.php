<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewStockroom_listing extends RedshopViewAdmin
{
	public $state;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$uri      = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_STOCKROOM_LISTING'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKROOM_LISTING_MANAGEMENT'), 'redshop_stockroom48');
		RedshopToolbarHelper::link('index.php?option=com_redshop&view=stockroom_listing&format=csv', 'save', JText::_('COM_REDSHOP_EXPORT_DATA_LBL'));
		JToolBarHelper::custom('print_data', 'save.png', 'save_f2.png', 'Print Data', false);

		$this->state = $this->get('State');
		$stockroom_type   = $this->state->get('stockroom_type');
		$category_id      = $this->state->get('category_id');

		// Stockroom type and attribute type
		$optiontype = array();

		$optiontype[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$optiontype[] = JHTML::_('select.option', 'property', JText::_('COM_REDSHOP_PROPERTY'));
		$optiontype[] = JHTML::_('select.option', 'subproperty', JText::_('COM_REDSHOP_SUBPROPERTY'));

		$lists['stockroom_type'] = JHTML::_('select.genericlist', $optiontype, 'stockroom_type',
			'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'value', 'text', $stockroom_type
		);

		$categories = RedshopHelperCategory::getCategoryListArray();

		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->id = "0";
		$temps[0]->name = JText::_('COM_REDSHOP_SELECT');
		$categories = @array_merge($temps, $categories);
		$lists['category'] = JHTML::_('select.genericlist', $categories, 'category_id',
			'class="inputbox" onchange="getTaskChange();document.adminForm.submit();" ',
			'id', 'name', $category_id
		);

		$lists ['order']     = $this->state->get('list.ordering', 'p.product_id');
		$lists ['order_Dir'] = $this->state->get('list.direction');

		$resultlisting = $this->get('Items');
		$stockroom     = $this->get('Stockroom');
		$pagination    = $this->get('Pagination');
		$model = $this->getModel('stockroom_listing');
		$ids = array();

		if ($resultlisting)
		{
			if ($stockroom_type != 'product')
			{
				$nameId = 'section_id';
			}
			else
			{
				$nameId = 'product_id';
			}

			foreach ($resultlisting as $item)
			{
				$ids[] = $item->$nameId;
			}
		}

		$this->quantities = $model->getQuantity($stockroom_type, '', $ids);

		$this->lists = $lists;
		$this->resultlisting = $resultlisting;
		$this->stockroom = $stockroom;
		$this->stockroom_type = $stockroom_type;

		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
