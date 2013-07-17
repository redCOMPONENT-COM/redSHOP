<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

require_once JPATH_COMPONENT . '/helpers/extra_field.php';
require_once JPATH_COMPONENT . '/helpers/category.php';
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

class category_detailVIEWcategory_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$redTemplate      = new Redtemplate;
		$product_category = new product_category;
		$producthelper    = new producthelper;

		$option = JRequest::getVar('option');
		$this->setLayout('default');
		$uri        = JFactory::getURI();
		$model      = $this->getModel('category_detail');
		$categories = $model->getcategories();

		JToolBarHelper::title(JText::_('COM_REDSHOP_CATEGORY_MANAGEMENT_DETAIL'), 'redshop_categories48');
		$document = JFactory::getDocument();
		$document->addScript('components/' . $option . '/assets/js/validation.js');
		$document->addScript('components/' . $option . '/assets/js/fields.js');
		$document->addScript('components/' . $option . '/assets/js/select_sort.js');
		$document->addScript('components/' . $option . '/assets/js/json.js');
		$document->addStyleSheet('components/com_redshop/assets/css/search.css');
		$document->addScript('components/com_redshop/assets/js/search.js');
		$document->addScript('components/com_redshop/assets/js/related.js');
		$detail = $this->get('data');
		$isNew  = ($detail->category_id < 1);

		// Assign default values for new categories
		if ($isNew)
		{
			$detail->append_to_global_seo = 'append';
			$detail->canonical_url        = '';
		}

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : $detail->category_name . " - " . JText::_('COM_REDSHOP_EDIT');
		JToolBarHelper::title(JText::_('COM_REDSHOP_CATEGORY') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_categories48');
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
		}

		$lists = array();

		/*
		 * get total Template from configuration helper
		 */
		$templates = $redTemplate->getTemplate('category');

		/*
		 * multiple select box for
		 * 	Front-End category Template Selector
		 */
		if (strstr($detail->category_more_template, ","))
		{
			$category_more_template = explode(",", $detail->category_more_template);
		}
		else
		{
			$category_more_template = $detail->category_more_template;
		}

		$lists['category_more_template'] = JHTML::_('select.genericlist', $templates,
			'category_more_template[]', 'class="inputbox" multiple="multiple" size="10" ',
			'template_id', 'template_name', $category_more_template
		);

		$append_to_global_seo   = array();
		$append_to_global_seo[] = JHTML::_('select.option', 'append', JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO'));
		$append_to_global_seo[] = JHTML::_('select.option', 'prepend', JText::_('COM_REDSHOP_PREPEND_TO_GLOBAL_SEO'));
		$append_to_global_seo[] = JHTML::_('select.option', 'replace', JText::_('COM_REDSHOP_REPLACE_TO_GLOBAL_SEO'));

		$lists['append_to_global_seo'] = JHTML::_('select.genericlist', $append_to_global_seo,
			'append_to_global_seo', 'class="inputbox" size="1" ', 'value',
			'text', $detail->append_to_global_seo
		);

		// Merging select option in the select box
		$temps                      = array();
		$temps[0]                   = new stdClass;
		$temps[0]->template_id      = 0;
		$temps[0]->template_name    = JText::_('COM_REDSHOP_SELECT');
		$templates                  = @array_merge($temps, $templates);
		$lists['category_template'] = JHTML::_('select.genericlist', $templates, 'category_template', 'class="inputbox" size="1"  onchange="select_dynamic_field(this.value,\'' . $detail->category_id . '\',\'2\');" ', 'template_id', 'template_name', $detail->category_template);

		/*
		 * class name product_category
		 * from helper/category.php
		 * get select box for select category parent Id
		 */
		$categories          = $product_category->list_all("category_parent_id", $detail->category_id);
		$lists['categories'] = $categories;

		// Select box for ProductCompareTemplate
		$comparetemplate              = $redTemplate->getTemplate('compare_product');
		$temp                         = array();
		$temp[0]                      = new stdClass;
		$temp[0]->template_id         = 0;
		$temp[0]->template_name       = JText::_('COM_REDSHOP_SELECT');
		$comparetemplate              = @array_merge($temp, $comparetemplate);
		$lists['compare_template_id'] = JHTML::_('select.genericlist', $comparetemplate, 'compare_template_id',
			'class="inputbox" size="1" ', 'template_id',
			'template_name', $detail->compare_template_id
		);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		// Accessory of Category
		$categroy_accessory_product = array();

		if ($detail->category_id)
		{
			$categroy_accessory_product = $producthelper->getProductAccessory(0, 0, 0, $detail->category_id);
		}

		$lists['categroy_accessory_product'] = $categroy_accessory_product;

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
