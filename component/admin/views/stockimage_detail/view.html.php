<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewStockimage_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKIMAGE_MANAGEMENT_DETAIL'), 'redshop_stockroom48');
		$uri = JFactory::getURI();
		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');
		$isNew = ($detail->stock_amount_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
		JToolBarHelper::title(JText::_('COM_REDSHOP_STOCKIMAGE') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_stockroom48');

		// Create the toolbar
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}
		$model = $this->getModel('stockimage_detail');

		$stock_option = $model->getStockAmountOption();
		$stockroom_name = $model->getStockRoomList();
		$op = array();
		$op[0] = new stdClass;
		$op[0]->value = 0;
		$op[0]->text = JText::_('COM_REDSHOP_SELECT');
		$stockroom_name = array_merge($op, $stockroom_name);

		$lists['stock_option'] = JHTML::_('select.genericlist', $stock_option, 'stock_option',
			'class="inputbox" size="1" ', 'value', 'text', $detail->stock_option
		);

		$lists['stockroom_id'] = JHTML::_('select.genericlist', $stockroom_name, 'stockroom_id',
			'class="inputbox" size="1" ', 'value', 'text', $detail->stockroom_id
		);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
