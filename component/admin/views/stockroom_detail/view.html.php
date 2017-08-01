<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewStockroom_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$layout = JRequest::getVar('layout', '');

		$lists = array();
		$uri = JFactory::getURI();

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

			$lists['show_in_front'] = JHTML::_('select.booleanlist', 'show_in_front', 'class="inputbox"', $detail->show_in_front);

			$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

			$delivery_time = array();
			$delivery_time['value'] = "days";
			$delivery_time['value'] .= "weeks";

			$extra_field = extra_field::getInstance();

			$booleanlist = $extra_field->booleanlist('delivery_time', 'class="inputbox"', $detail->delivery_time,
				JText::_('COM_REDSHOP_DAYS'), JText::_('COM_REDSHOP_WEEKS')
			);

			$this->booleanlist = $booleanlist;
			$this->detail = $detail;
		}

		$this->lists       = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
