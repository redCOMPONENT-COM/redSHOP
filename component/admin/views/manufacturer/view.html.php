<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Manufacturer view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View.Manufacturer
 * @since       2.0.0.3
 */
class RedshopViewManufacturer extends RedshopViewAdmin
{

	/**
	 * Display view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  bool
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->tabmenu     = $this->getTabMenu();

		$this->item->excluding_category_list  = explode(',', $this->item->excluding_category_list);
		$productCategory                 = new product_category;
		$this->excluding_category_list = $productCategory->list_all("excluding_category_list[]", 0,
			$this->item->excluding_category_list, 10, false, true
		);

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Tab Menu
	 *
	 * @return  object  Tab menu
	 *
	 * @since   1.7
	 */
	protected function getTabMenu()
	{
		$tabMenu = RedshopAdminMenu::getInstance()->init();
		$tabMenu->section('tab')
			->title('COM_REDSHOP_DETAILS')
			->addItem(
				'#information',
				'COM_REDSHOP_DETAILS',
				true,
				'information'
			)->addItem(
				'#seo',
				'COM_REDSHOP_META_DATA_TAB',
				false,
				'seo'
			)->addItem(
				'#extrafield',
				'COM_REDSHOP_FIELDS',
				false,
				'extrafield'
			);

		return $tabMenu;
	}

	/**
	 * Add toolbar
	 *
	 * @since   2.0.0.3
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		if (JFactory::getApplication()->input->get('id'))
		{
			$text = JText::_('COM_REDSHOP_MANUFACTURER_EDIT');
		}
		else
		{
			$text = JText::_('COM_REDSHOP_MANUFACTURER_NEW');
		}

		JToolBarHelper::title(JText::_('COM_REDSHOP_MANUFACTURER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'flag redshop_manufact48');
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}
}
