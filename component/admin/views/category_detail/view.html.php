<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

/**
 * View Category
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewCategory_Detail extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * @var  array
	 */
	public $lists;

	/**
	 * Category data
	 *
	 * @var  object
	 */
	public $detail;

	/**
	 * List of extra fields
	 *
	 * @var  array
	 */
	public $extraFields;

	/**
	 * List of tab menu
	 *
	 * @var  array
	 */
	public $tabmenu;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   12.2
	 */
	public function display($tpl = null)
	{
		$redTemplate   = Redtemplate::getInstance();
		$producthelper = productHelper::getInstance();

		$document = JFactory::getDocument();
		$document->addScript('components/com_redshop/assets/js/validation.js');
		$document->addScript('components/com_redshop/assets/js/fields.js');
		$document->addScript('components/com_redshop/assets/js/json.js');

		$model = $this->getModel('category_detail');
		$this->detail = $this->get('data');
		$isNew        = ($this->detail->category_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : $this->detail->category_name . " - " . JText::_('COM_REDSHOP_EDIT');
		JToolBarHelper::title(JText::_('COM_REDSHOP_CATEGORY') . ': <small><small>[ ' . $text . ' ]</small></small>', 'folder redshop_categories48');
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::save2new();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));

			$objhelper = redhelper::getInstance();
			$itemId    = (int) $objhelper->getCategoryItemid($this->detail->category_id);

			$link  = JURI::root() . 'index.php?option=com_redshop'
					. '&view=&view=category&layout=detail'
					. '&cid=' . $this->detail->category_id
					. '&Itemid=' . $itemId;

			RedshopToolbarHelper::link($link, 'preview', 'JGLOBAL_PREVIEW', '_blank');
		}

		/*
		 * get total Template from configuration helper
		 */
		$templates = RedshopHelperTemplate::getTemplate('category');

		/*
		 * multiple select box for
		 * 	Front-End category Template Selector
		 */
		if (!is_array($this->detail->category_more_template) && strstr($this->detail->category_more_template, ","))
		{
			$category_more_template = explode(",", $this->detail->category_more_template);
		}
		else
		{
			$category_more_template = $this->detail->category_more_template;
		}

		$this->lists['category_more_template'] = JHTML::_('select.genericlist', $templates,
			'category_more_template[]', 'class="inputbox" multiple="multiple" size="10" ',
			'template_id', 'template_name', $category_more_template
		);

		$append_to_global_seo   = array();
		$append_to_global_seo[] = JHTML::_('select.option', 'append', JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO'));
		$append_to_global_seo[] = JHTML::_('select.option', 'prepend', JText::_('COM_REDSHOP_PREPEND_TO_GLOBAL_SEO'));
		$append_to_global_seo[] = JHTML::_('select.option', 'replace', JText::_('COM_REDSHOP_REPLACE_TO_GLOBAL_SEO'));

		$this->lists['append_to_global_seo'] = JHTML::_('select.genericlist', $append_to_global_seo,
			'append_to_global_seo', 'class="inputbox" size="1" ', 'value',
			'text', $this->detail->append_to_global_seo
		);

		// Merging select option in the select box
		$temps                            = array();
		$temps[0]                         = new stdClass;
		$temps[0]->template_id            = 0;
		$temps[0]->template_name          = JText::_('COM_REDSHOP_SELECT');
		$templates                        = array_merge($temps, $templates);
		$this->lists['category_template'] = JHTML::_(
			'select.genericlist',
			$templates,
			'category_template',
			'class="inputbox" size="1"  onchange="Joomla.submitbutton(\'category_detail.edit\');" ',
			'template_id',
			'template_name',
			$this->detail->category_template
		);

		/*
		 * class name product_category
		 * from helper/category.php
		 * get select box for select category parent Id
		 */
		$this->lists['categories'] = RedshopHelperCategory::listAll(
			'category_parent_id',
			$this->detail->category_id,
			array(),
			1,
			true
		);

		// Select box for ProductCompareTemplate
		$temp                   = array();
		$temp[0]                = new stdClass;
		$temp[0]->template_id   = 0;
		$temp[0]->template_name = JText::_('COM_REDSHOP_SELECT');
		$compareTemplate        = array_merge($temp, RedshopHelperTemplate::getTemplate('compare_product'));
		$this->lists['compare_template_id'] = JHTML::_(
			'select.genericlist',
			$compareTemplate,
			'compare_template_id',
			'class="inputbox" size="1" ',
			'template_id',
			'template_name',
			$this->detail->compare_template_id
		);

		$this->lists['published'] = JHTML::_(
			'select.booleanlist',
			'published',
			'class="inputbox"',
			$this->detail->published
		);

		// Accessory of Category
		$categoryAccessoryProduct = array();

		if ($this->detail->category_id)
		{
			$categoryAccessoryProduct = $producthelper->getProductAccessory(0, 0, 0, $this->detail->category_id);
		}

		$this->lists['categroy_accessory_product'] = $categoryAccessoryProduct;
		$this->extraFields = $model->getExtraFields($this->detail);
		$this->tabmenu     = $this->getTabMenu();

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
		$tabMenu = new RedshopMenu;

		$tabMenu->section('tab')
			->title('COM_REDSHOP_CATEGORY_INFORMATION')
			->addItem(
				'#information',
				'COM_REDSHOP_CATEGORY_INFORMATION',
				true,
				'information'
			)
			->addItem(
				'#seo',
				'COM_REDSHOP_META_DATA_TAB',
				false,
				'seo'
			);

		if (!empty($this->extraFields))
		{
			$tabMenu->addItem(
				'#extrafield',
				'COM_REDSHOP_FIELDS',
				false,
				'extrafield'
			);
		}

		$tabMenu->addItem(
			'#accessory',
			'COM_REDSHOP_ACCESSORY_PRODUCT',
			false,
			'accessory'
		);

		return $tabMenu;
	}
}
