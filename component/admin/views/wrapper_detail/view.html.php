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

class wrapper_detailVIEWwrapper_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		global $context;

		$context = "wrapper";
		$uri = JFactory::getURI();
		$lists = array();
		$detail = $this->get('data');
		$model = $this->getModel('wrapper_detail');
		$option = JRequest::getVar('option');
		require_once JPATH_COMPONENT . '/helpers/extra_field.php';
		$document = JFactory::getDocument();
		$document->addScript('components/' . $option . '/assets/js/select_sort.js');
		$document->addStyleSheet('components/' . $option . '/assets/css/search.css');
		$document->addScript('components/' . $option . '/assets/js/search.js');

		$isNew = ($detail->wrapper_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_WRAPPER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_wrapper48');
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);
		$lists['use_to_all'] = JHTML::_('select.booleanlist', 'wrapper_use_to_all', 'class="inputbox"', $detail->wrapper_use_to_all);
		$product_id = 0;
		$category_id = 0;

		$showall = JRequest::getVar('showall', '0');

		if ($showall)
		{
			$product_id = JRequest::getVar('product_id');
		}

		$category = $model->getCategoryInfo($category_id);

		if (count($detail) > 0)
		{
			$catid = explode(",", $detail->category_id);
		}

		$lists['category_name'] = $model->getMultiselectBox("categoryid[]", $category, $catid, "category_id", "category_name", true);

		$product = $model->getProductInfo($product_id);

		if (count($detail) > 0)
		{
			$pid = explode(",", $detail->product_id);
		}

		$productData = $model->getProductInfowrapper($detail->product_id);

		if (count($productData) > 0)
		{
			$result_container = $productData;
		}
		else
		{
			$result_container = array();
		}

		$lists['product_all'] = JHTML::_('select.genericlist', $productData, 'product_all[]',
			'class="inputbox" multiple="multiple" ', 'value', 'text', $detail->product_id
		);
		$lists['product_name'] = JHTML::_('select.genericlist', $result_container, 'container_product[]',
			'class="inputbox" onmousewheel="mousewheel(this);" ondblclick="selectnone(this);" multiple="multiple"  size="15" style="width:200px;" ',
			'value', 'text', 0
		);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->product = $product;
		$this->product_id = $product_id;
		$this->category = $category;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
