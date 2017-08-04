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
 * Wrapper_Detail View
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.6
 */
class RedshopViewWrapper_Detail extends RedshopViewAdmin
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
	 * [display description]
	 *
	 * @param   [string]  $tpl  [layout name]
	 *
	 * @return  [void]
	 */
	public function display($tpl = null)
	{
		global $context;

		$context = "wrapper";
		$uri = JFactory::getURI();
		$lists = array();
		$detail = $this->get('data');
		$model = $this->getModel('wrapper_detail');

		$isNew = ($detail->wrapper_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_WRAPPER') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_wrapper48');

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

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);
		$lists['use_to_all'] = JHTML::_('select.booleanlist', 'wrapper_use_to_all', 'class="inputbox"', $detail->wrapper_use_to_all);
		$product_id = 0;

		$jinput = JFactory::getApplication()->input;

		$showall = $jinput->get('showall', '0');

		if ($showall)
		{
			$product_id = $jinput->get('product_id');
		}

		$category = $model->getCategoryInfo();

		if (count($detail) > 0)
		{
			$catid = explode(",", $detail->category_id);
		}

		$lists['category_name'] = $model->getMultiselectBox("categoryid[]", $category, $catid, "id", "name", true);

		$product = $model->getProductInfo($product_id);

		$productData = $model->getProductInfowrapper($detail->product_id);

		if (count($productData) > 0)
		{
			$result_container = $productData;
		}
		else
		{
			$result_container = array();
		}

		$lists['product_name'] = JHTML::_('redshopselect.search', $result_container, 'container_product',
			array(
				'select2.ajaxOptions' => array('typeField' => ', alert:"wrapper"'),
				'select2.options' => array('multiple' => true)
			)
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
