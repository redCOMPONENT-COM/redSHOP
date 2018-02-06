<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Manufacturer detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewManufacturer_Detail extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed         A string if successful, otherwise a Error object.
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$uri = JUri::getInstance();

		/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/redshop.validation.min.js', false, true);
		$this->setLayout('default');

		/** @var RedshopModelManufacturer_detail $model */
		$model = $this->getModel('manufacturer_detail');

		$lists        = array();
		$detail       = $this->get('data');
		$templateData = $model->templateData();
		$isNew        = ($detail->manufacturer_id < 1);
		$text         = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_MANUFACTURER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'flag redshop_manufact48');
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

		$optionTemplate   = array();
		$optionTemplate[] = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_Select'));

		$result = array_merge($optionTemplate, $templateData);

		$lists['template'] = JHtml::_('select.genericlist', $result, 'template_id',
			'class="inputbox form-control" size="1" ', 'value', 'text', $detail->template_id
		);

		$detail->excluding_category_list  = explode(',', $detail->excluding_category_list);
		$lists['excluding_category_list'] = RedshopHelperCategory::listAll("excluding_category_list[]", 0,
			$detail->excluding_category_list, 10, false, true
		);

		$lists['published']   = JHtml::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);
		$lists['extra_field'] = RedshopHelperExtrafields::listAllField(
			RedshopHelperExtrafields::SECTION_MANUFACTURER, $detail->manufacturer_id
		);

		$this->lists       = $lists;
		$this->detail      = $detail;
		$this->request_url = $uri->toString();
		$this->tabmenu     = $this->getTabMenu();

		parent::display($tpl);
	}

	/**
	 * Tab Menu
	 *
	 * @return  object  Tab menu
	 * @throws  Exception
	 *
	 * @since   1.7
	 */
	private function getTabMenu()
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
}
