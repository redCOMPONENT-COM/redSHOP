<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewManufacturer_detail extends RedshopViewAdmin
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

	public function display($tpl = null)
	{

		$uri = JFactory::getURI();
		$document = JFactory::getDocument();

		$document->addScript('components/com_redshop/assets/js/validation.js');
		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$model = $this->getModel('manufacturer_detail');

		$template_data = $model->TemplateData();

		$isNew = ($detail->manufacturer_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

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

		$optiontemplet = array();
		$optiontemplet[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_Select'));

		$result = array_merge($optiontemplet, $template_data);

		$lists['template'] = JHTML::_('select.genericlist', $result, 'template_id',
			'class="inputbox" size="1" ', 'value', 'text', $detail->template_id
		);

		$detail->excluding_category_list = explode(',', $detail->excluding_category_list);
		$product_category = new product_category;
		$lists['excluding_category_list'] = $product_category->list_all("excluding_category_list[]", 0,
			$detail->excluding_category_list, 10, false, true
		);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);
		$field = extra_field::getInstance();

		$list_field = $field->list_all_field(10, $detail->manufacturer_id);
		$lists['extra_field'] = $list_field;


		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();
		$this->tabmenu = $this->getTabMenu();

		parent::display($tpl);
	}

	/**
	 * Tab Menu
	 *
	 * @return  object  Tab menu
	 *
	 * @since   1.7
	 */
	private function getTabMenu()
	{
		$app = JFactory::getApplication();

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
