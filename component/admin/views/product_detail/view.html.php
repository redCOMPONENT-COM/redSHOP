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
 * Product Detail View
 *
 * @package     RedShop.Component
 * @subpackage  Admin
 *
 * @since       1.0
 */
class RedshopViewProduct_Detail extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public $productSerialDetail;

	public $input;

	public $producthelper;

	public $dispatcher;

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
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @see     fetch()
	 * @since   11.1
	 */
	public function display($tpl = null)
	{
		JHtml::_('behavior.tooltip');

		$app                 = JFactory::getApplication();
		$this->input         = $app->input;
		$user                = JFactory::getUser();

		JPluginHelper::importPlugin('redshop_product');
		JPluginHelper::importPlugin('redshop_product_type');
		$this->dispatcher    = RedshopHelperUtility::getDispatcher();

		$redTemplate         = Redtemplate::getInstance();
		$redhelper           = redhelper::getInstance();
		$this->producthelper = productHelper::getInstance();

		$this->option        = $this->input->getString('option', 'com_redshop');
		$lists               = array();

		$model               = $this->getModel('product_detail');
		$detail              = $this->get('data');

		$isNew               = ($detail->product_id < 1);

		// Load new product default values
		if ($isNew)
		{
			$detail->append_to_global_seo = '';
			$detail->canonical_url        = '';
		}

		// Fail if checked out not by 'me'
		if ($model->isCheckedOut($user->get('id')))
		{
			$msg = JText::_('COM_REDSHOP_PRODUCT_BEING_EDITED');
			$app->redirect('index.php?option=com_redshop&view=product', $msg);
		}

		// Check redproductfinder is installed
		$CheckRedProductFinder       = $model->CheckRedProductFinder();
		$this->CheckRedProductFinder = $CheckRedProductFinder;

		// Get association id
		$getAssociation              = $model->getAssociation();
		$this->getassociation        = $getAssociation;

		// Get the tag names
		$tags = $model->Tags();
		$associationtags = array();

		if (isset($getAssociation) && count($getAssociation) > 0)
		{
			$associationtags = $model->AssociationTags($getAssociation->id);
		}

		if (count($tags) > 0)
		{
			$lists['tags'] = JHtml::_('select.genericlist', $tags, 'tag_id[]', 'multiple', 'id', 'tag_name', $associationtags);
		}

		$types = $model->TypeTagList();

		/* Get the Quality Score data */
		$qs = $this->get('QualityScores', 'product_detail');

		// ToDo: Don't echo HTML but use tmpl files.
		/* Create the select list as checkboxes */
		$html = '<div id="select_box">';

		if (count($types) > 0)
		{
			foreach ($types as $typeid => $type)
			{
				$counttags = count($type['tags']);
				$rand = rand();
				/* Add the type */
				$html .= '<div class="select_box_parent" onClick="showBox(' . $rand . ')">' . JText::_('COM_REDSHOP_TYPE_LIST')
					. ' ' . $type['type_name'] . '</div>';
				$html .= '<div id="' . $rand . '" class="select_box_child';
				$html .= '">';

				/* Add the tags */
				if ($counttags > 0)
				{
					foreach ($type['tags'] as $tagid => $tag)
					{
						/* Check if the tag is selected */
						if (in_array($tagid, $associationtags))
						{
							$selected = 'checked="checked"';
						}

						else
						{
							$selected = '';
						}

						$html .= '<table><tr><td colspan="2"><input type="checkbox" class="select_box" ' . $selected
							. ' name="tag_id[]" value="' . $typeid . '.' . $tagid . '" />'
							. JText::_('COM_REDSHOP_TAG_LIST') . ' ' . $tag['tag_name'];
						$html .= '</td></tr>';

						$qs_value = '';

						if (is_array($qs))
						{
							if (array_key_exists($typeid . '.' . $tagid, $qs))
							{
								$qs_value = $qs[$typeid . '.' . $tagid]['quality_score'];
							}
						}

						$html .= '<tr><td><span class="quality_score">' . JText::_('COM_REDSHOP_QUALITY_SCORE')
							. '</span></td><td><input type="text" class="quality_score_input"  name="qs_id[' . $typeid
							. '.' . $tagid . ']" value="' . $qs_value . '" />';
						$html .= '</td></tr>';

						$html .= '<tr ><td colspan="2"><select name="sel_dep' . $typeid . '_' . $tagid
							. '[]" id="sel_dep' . $typeid . '_' . $tagid . '" multiple="multiple" size="10"  >';

						foreach ($types as $sel_typeid => $sel_type)
						{
							if ($typeid == $sel_typeid)
							{
								continue;
							}

							$dependent_tag = $model->getDependenttag($detail->product_id, $typeid, $tagid);

							$html .= '<optgroup label="' . $sel_type['type_name'] . '">';

							foreach ($sel_type['tags'] as $sel_tagid => $sel_tag)
							{
								$selected = in_array($sel_tagid, $dependent_tag) ? "selected" : "";
								$html .= '<option value="' . $sel_tagid . '" ' . $selected . ' >' . $sel_tag['tag_name'] . '</option>';
							}

							$html .= '</optgroup>';
						}

						$html .= '</select>&nbsp;<a href="#" onClick="javascript:add_dependency('
							. $typeid . ',' . $tagid . ',' . $detail->product_id . ');" >'
							. JText::_('COM_REDSHOP_ADD_DEPENDENCY') . '</a></td></tr></table>';
					}
				}

				$html .= '</div>';
			}
		}

		$html .= '</div>';
		$lists['tags'] = $html;

		$templates = $redTemplate->getTemplate("product");

		$manufacturers = $model->getmanufacturers();

		$supplier = $model->getsupplier();

		$product_categories = $this->input->post->get('product_category', array(), 'array');

		if (!empty($product_categories))
		{
			$productcats = $product_categories;
		}
		else
		{
			$productcats = $model->getproductcats();
		}

		$attributes = $model->getattributes();

		$attributesSet = $model->getAttributeSetList();

		$product_category = new product_category;

		// Merging select option in the select box
		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->template_id = "0";
		$temps[0]->template_name = JText::_('COM_REDSHOP_SELECT');

		if (is_array($templates))
		{
			$templates = array_merge($temps, $templates);
		}

		// Merging select option in the select box
		$supps = array();
		$supps[0] = new stdClass;
		$supps[0]->value = "0";
		$supps[0]->text = JText::_('COM_REDSHOP_SELECT');

		if (is_array($manufacturers))
		{
			$manufacturers = array_merge($supps, $manufacturers);
		}

		// Merging select option in the select box
		$supps = array();
		$supps[0] = new stdClass;
		$supps[0]->value = "0";
		$supps[0]->text = JText::_('COM_REDSHOP_SELECT');

		if (is_array($supplier))
		{
			$supplier = array_merge($supps, $supplier);
		}

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT_DETAIL'), 'pencil-2 redshop_products48');

		$document = JFactory::getDocument();

		$document->addScriptDeclaration("var WANT_TO_DELETE = '" . JText::_('COM_REDSHOP_DO_WANT_TO_DELETE') . "';");

		/**
		 * Override field.js file.
		 * With this trigger the file can be loaded from a plugin. This can be used
		 * to display different JS generated interface for attributes depending on a product type.
		 * So, product type plugins should be used for this event. Be aware that this file should
		 * be loaded only once.
		 */
		$loadedFromAPlugin = $this->dispatcher->trigger('loadFieldsJSFromPlugin', array($detail));

		if (in_array(1, $loadedFromAPlugin))
		{
			$loadedFromAPlugin = true;
		}
		else
		{
			$loadedFromAPlugin = false;
		}

		if (!$loadedFromAPlugin)
		{
			$document->addScript('components/com_redshop/assets/js/fields.js');
		}

		$document->addScript('components/com_redshop/assets/js/json.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');

		if (version_compare(JVERSION, '3.0', '<'))
		{
			$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/update.css');
		}

		$document->addScript(JURI::root() . 'administrator/components/com_redshop/assets/js/attribute_manipulation.js');

		if (file_exists(JPATH_SITE . '/components/com_redproductfinder/helpers/redproductfinder.css'))
		{
			$document->addStyleSheet('components/com_redproductfinder/helpers/redproductfinder.css');
		}

		$uri = JFactory::getURI();

		$layout = $this->input->getString('layout', '');

		if ($layout == 'property_images')
		{
			$this->setLayout('property_images');
		}
		elseif ($layout == 'attribute_color')
		{
			$this->setLayout('attribute_color');
		}
		elseif ($layout == 'productstockroom')
		{
			$this->setLayout('productstockroom');
		}
		else
		{
			$this->setLayout('default');
		}

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : $detail->product_name . " - " . JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT') . ': <small><small>[ ' . $text . ' ]</small></small>', 'pencil-2 redshop_products48');

		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::save2new();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolbarHelper::save2copy();
			$model->checkout($user->get('id'));

			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		if ($detail->product_id > 0)
		{
			$ItemData = $this->producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $detail->product_id);
			$catidmain = $detail->cat_in_sefurl;

			if (count($ItemData) > 0)
			{
				$pItemid = $ItemData->id;
			}
			else
			{
				$pItemid = RedshopHelperUtility::getItemId($detail->product_id, $catidmain);
			}

			$link  = JURI::root();
			$link .= 'index.php?option=com_redshop';
			$link .= '&view=product&pid=' . $detail->product_id;
			$link .= '&cid=' . $catidmain;
			$link .= '&Itemid=' . $pItemid;

			RedshopToolbarHelper::link($link, 'preview', 'JGLOBAL_PREVIEW', '_blank');
			JToolBarHelper::addNew('prices', JText::_('COM_REDSHOP_ADD_PRICE_LBL'));
		}

		$model = $this->getModel('product_detail');

		$accessory_product = array();

		if ($detail->product_id)
		{
			$accessory_product = $this->producthelper->getProductAccessory(0, $detail->product_id);
		}

		$lists['accessory_product'] = $accessory_product;
		$lists['QUANTITY_SELECTBOX_VALUE'] = $detail->quantity_selectbox_value;

		// For preselected.
		if ($detail->product_template == "")
		{
			$default_preselected = Redshop::getConfig()->get('PRODUCT_TEMPLATE');
			$detail->product_template = $default_preselected;
		}

		$lists['product_template'] = JHtml::_('select.genericlist', $templates, 'product_template',
			'class="inputbox" size="1" onchange="set_dynamic_field(this.value,\'' . $detail->product_id . '\',\'1,12,17\');"  ',
			'template_id', 'template_name', $detail->product_template
		);

		$lists['related_product'] = JHtml::_('redshopselect.search', $model->related_product_data($detail->product_id), 'related_product',
			array(
				'select2.ajaxOptions' => array('typeField' => ', related:1, product_id:' . $detail->product_id),
				'select2.options' => array('multiple' => 'true')
			)
		);

		$product_tax = $model->gettax();
		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->value = "0";
		$temps[0]->text = JText::_('COM_REDSHOP_SELECT');

		if (is_array($product_tax))
		{
			$product_tax = array_merge($temps, $product_tax);
		}

		$lists['product_tax'] = JHtml::_('select.genericlist', $product_tax, 'product_tax_id',
			'class="inputbox" size="1"  ', 'value', 'text', $detail->product_tax_id
		);

		$categories = $product_category->list_all("product_category[]", 0, $productcats, 10, false, true);
		$lists['categories'] = $categories;
		$detail->first_selected_category_id = isset($productcats[0]) ? $productcats[0] : null;

		$lists['manufacturers'] = JHtml::_('select.genericlist', $manufacturers, 'manufacturer_id',
			'class="inputbox" size="1" ', 'value', 'text', $detail->manufacturer_id
		);

		$lists['supplier'] = JHtml::_('select.genericlist', $supplier, 'supplier_id', 'class="inputbox" size="1" ', 'value', 'text', $detail->supplier_id);
		$lists['published'] = JHtml::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);
		$lists['product_on_sale'] = JHtml::_('select.booleanlist', 'product_on_sale', 'class="inputbox"', $detail->product_on_sale);
		$lists['copy_attribute'] = JHtml::_('select.booleanlist', 'copy_attribute', 'class="inputbox"', 0);
		$lists['product_special'] = JHtml::_('select.booleanlist', 'product_special', 'class="inputbox"', $detail->product_special);
		$lists['product_download'] = JHtml::_('select.booleanlist', 'product_download', 'class="inputbox"', $detail->product_download);

		$detail->not_for_sale_showprice = 0;

		if ($detail->not_for_sale == 2)
		{
			$detail->not_for_sale = 1;
			$detail->not_for_sale_showprice = 1;
		}

		$lists['not_for_sale']           = JHtml::_('select.booleanlist', 'not_for_sale', 'class="inputbox"', $detail->not_for_sale);
		$lists['not_for_sale_showprice'] = JHtml::_('select.booleanlist', 'not_for_sale_showprice', 'class="inputbox"', $detail->not_for_sale_showprice);

		$lists['expired'] = JHtml::_('select.booleanlist', 'expired', 'class="inputbox"', $detail->expired);
		$lists['allow_decimal_piece'] = JHtml::_('select.booleanlist', 'allow_decimal_piece', 'class="inputbox"', $detail->allow_decimal_piece);

		// For individual pre-order
		$preorder_data = RedshopHelperUtility::getPreOrderByList();
		$lists['preorder'] = JHtml::_('select.genericlist', $preorder_data, 'preorder', 'class="inputbox" size="1" ', 'value', 'text', $detail->preorder);

		// Discount calculator
		$lists['use_discount_calc'] = JHtml::_('select.booleanlist', 'use_discount_calc', 'class="inputbox"', $detail->use_discount_calc);

		$selectOption = array();
		$selectOption[] = JHtml::_('select.option', '1', JText::_('COM_REDSHOP_RANGE'));
		$selectOption[] = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_PRICE_PER_PIECE'));
		$lists['use_range'] = JHtml::_('select.genericlist', $selectOption, 'use_range', 'class="inputbox" size="1" ', 'value', 'text', $detail->use_range);
		unset($selectOption);

		// Calculation method
		$selectOption[] = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$selectOption[] = JHtml::_('select.option', 'volume', JText::_('COM_REDSHOP_VOLUME'));
		$selectOption[] = JHtml::_('select.option', 'area', JText::_('COM_REDSHOP_AREA'));
		$selectOption[] = JHtml::_('select.option', 'circumference', JText::_('COM_REDSHOP_CIRCUMFERENCE'));
		$lists['discount_calc_method'] = JHtml::_('select.genericlist', $selectOption, 'discount_calc_method',
			'class="inputbox" size="1" ', 'value', 'text', $detail->discount_calc_method
		);
		unset($selectOption);

		// Calculation UNIT
		$remove_format = JHtml::$formatOptions;

		$selectOption[] = JHtml::_('select.option', 'mm', JText::_('COM_REDSHOP_MILLIMETER'));
		$selectOption[] = JHtml::_('select.option', 'cm', JText::_('COM_REDSHOP_CENTIMETER'));
		$selectOption[] = JHtml::_('select.option', 'm', JText::_('COM_REDSHOP_METER'));
		$lists['discount_calc_unit'] = JHtml::_('select.genericlist', $selectOption, 'discount_calc_unit[]',
			'class="inputbox" size="1" ', 'value', 'text', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT')
		);
		$lists['discount_calc_unit'] = str_replace($remove_format['format.indent'], "", $lists['discount_calc_unit']);
		$lists['discount_calc_unit'] = str_replace($remove_format['format.eol'], "", $lists['discount_calc_unit']);
		unset($selectOption);

		$productVatGroup = $model->getVatGroup();
		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->value = "";
		$temps[0]->text = JText::_('COM_REDSHOP_SELECT');

		if (is_array($productVatGroup))
		{
			$productVatGroup = array_merge($temps, $productVatGroup);
		}

		if (Redshop::getConfig()->get('DEFAULT_VAT_GROUP') && !$detail->product_tax_group_id)
		{
			$detail->product_tax_group_id = Redshop::getConfig()->get('DEFAULT_VAT_GROUP');
		}

		$append_to_global_seo = array();
		$append_to_global_seo[] = JHtml::_('select.option', 'append', JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO'));
		$append_to_global_seo[] = JHtml::_('select.option', 'prepend', JText::_('COM_REDSHOP_PREPEND_TO_GLOBAL_SEO'));
		$append_to_global_seo[] = JHtml::_('select.option', 'replace', JText::_('COM_REDSHOP_REPLACE_TO_GLOBAL_SEO'));
		$lists['append_to_global_seo'] = JHtml::_('select.genericlist', $append_to_global_seo, 'append_to_global_seo',
			'class="inputbox" size="1" ', 'value', 'text', $detail->append_to_global_seo
		);

		$lists['product_tax_group_id'] = JHtml::_('select.genericlist', $productVatGroup, 'product_tax_group_id',
			'class="inputbox" size="1" ', 'value', 'text', $detail->product_tax_group_id
		);
		$prop_oprand = array();
		$prop_oprand[] = JHtml::_('select.option', 'select', JText::_('COM_REDSHOP_SELECT'));
		$prop_oprand[] = JHtml::_('select.option', '+', JText::_('COM_REDSHOP_PLUS'));
		$prop_oprand[] = JHtml::_('select.option', '=', JText::_('COM_REDSHOP_EQUAL'));
		$prop_oprand[] = JHtml::_('select.option', '-', JText::_('COM_REDSHOP_MINUS'));

		$cat_in_sefurl = $model->catin_sefurl($detail->product_id);
		$lists['cat_in_sefurl'] = JHtml::_('select.genericlist', $cat_in_sefurl, 'cat_in_sefurl',
			'class="inputbox" size="1" ', 'value', 'text', $detail->cat_in_sefurl
		);

		$lists['attributes'] = $attributes;

		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->value = "";
		$temps[0]->text = JText::_('COM_REDSHOP_SELECT');

		if (is_array($attributesSet))
		{
			$attributesSet = array_merge($temps, $attributesSet);
		}

		$lists['attributesSet'] = JHtml::_('select.genericlist', $attributesSet, 'attribute_set_id',
			'class="inputbox" size="1" ', 'value', 'text', $detail->attribute_set_id
		);

		// Product type selection
		$productTypeOptions = array();
		$productTypeOptions[] = JHtml::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$productTypeOptions[] = JHtml::_('select.option', 'file', JText::_('COM_REDSHOP_FILE'));
		$productTypeOptions[] = JHtml::_('select.option', 'subscription', JText::_('COM_REDSHOP_SUBSCRIPTION'));

		/*
		 * Trigger event which can update list of product types.
		 * Example of a returned value:
		 * return array('value' => 'redDESIGN', 'text' => JText::_('PLG_REDSHOP_PRODUCT_TYPE_REDDESIGN_REDDESIGN_PRODUCT_TYPE'));
		 */
		$productTypePluginOptions = $this->dispatcher->trigger('onListProductTypes');

		foreach ($productTypePluginOptions as $productTypePluginOption)
		{
			$productTypeOptions[] = JHtml::_('select.option', $productTypePluginOption['value'], $productTypePluginOption['text']);
		}

		if ($detail->product_download == 1)
		{
			$detail->product_type = 'file';
		}

		$lists["product_type"] = JHtml::_(
									'select.genericlist',
									$productTypeOptions,
									'product_type',
									'onchange="set_dynamic_field(this.value,\'' . $detail->product_id . '\',\'1,12,17\');"',
									'value',
									'text',
									$detail->product_type
								);

		$accountgroup = RedshopHelperUtility::getEconomicAccountGroup();
		$op = array();
		$op[] = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$accountgroup = array_merge($op, $accountgroup);

		$lists["accountgroup_id"] = JHtml::_('select.genericlist', $accountgroup, 'accountgroup_id',
			'class="inputbox" size="1" ', 'value', 'text', $detail->accountgroup_id
		);

		// For downloadable products
		$productSerialDetail = $model->getProdcutSerialNumbers();

		// Joomla tags
		$tagsHelper = new JHelperTags;
		$jtags = $tagsHelper->searchTags();

		$currentTags = null;

		if (!empty($detail->product_id))
		{
			$tagsHelper  = new JHelperTags;
			$currentTags = $tagsHelper->getTagIds($detail->product_id, 'com_redshop.product');
			$currentTags = explode(',', $currentTags);
		}

		$lists['jtags'] = JHtml::_('select.genericlist', $jtags, 'jtags[]', 'class="inputbox" size="10" multiple="multiple"', 'value', 'text', $currentTags);

		$this->model = $model;
		$this->lists = $lists;
		$this->detail = $detail;
		$this->productSerialDetail = $productSerialDetail;
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
		$selectedTabPosition = $app->getUserState('com_redshop.product_detail.selectedTabPosition', 'general_data');

		$tabMenu = RedshopAdminMenu::getInstance()->init();
		$tabMenu->section('tab')
					->title('COM_REDSHOP_PRODUCT_INFORMATION')
					->addItem(
						'#general_data',
						'COM_REDSHOP_PRODUCT_INFORMATION',
						($selectedTabPosition == 'general_data') ? true : false,
						'general_data'
					);

		if ($this->detail->product_type != 'product' && !empty($this->detail->product_type))
		{
			$tabMenu->addItem(
					'#producttype',
					'COM_REDSHOP_CHANGE_PRODUCT_TYPE_TAB',
					($selectedTabPosition == 'producttype') ? true : false,
					'producttype'
				);
		}

		$tabMenu->addItem(
					'#extrafield',
					'COM_REDSHOP_FIELDS',
					($selectedTabPosition == 'extrafield') ? true : false,
					'extrafield'
				)->addItem(
					'#product_images',
					'COM_REDSHOP_PRODUCT_IMAGES',
					($selectedTabPosition == 'product_images') ? true : false,
					'product_images'
				)->addItem(
					'#product_attribute',
					'COM_REDSHOP_PRODUCT_ATTRIBUTES',
					($selectedTabPosition == 'product_attribute') ? true : false,
					'product_attribute'
				)->addItem(
					'#product_accessory',
					'COM_REDSHOP_ACCESSORY_RELATED_PRODUCT',
					($selectedTabPosition == 'product_accessory') ? true : false,
					'product_accessory'
				);

		if ($this->CheckRedProductFinder > 0)
		{
			$tabMenu->addItem(
					'#productfinder',
					'COM_REDSHOP_REDPRODUCTFINDER_ASSOCIATION',
					($selectedTabPosition == 'productfinder') ? true : false,
					'productfinder'
				);
		}

		$tabMenu->addItem(
					'#product_meta_data',
					'COM_REDSHOP_META_DATA_TAB',
					($selectedTabPosition == 'product_meta_data') ? true : false,
					'product_meta_data'
				);

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$tabMenu->addItem(
					'#productstockroom',
					'COM_REDSHOP_STOCKROOM_TAB',
					($selectedTabPosition == 'productstockroom') ? true : false,
					'productstockroom'
				);
		}

		$tabMenu->addItem(
					'#calculator',
					'COM_REDSHOP_DISCOUNT_CALCULATOR',
					($selectedTabPosition == 'calculator') ? true : false,
					'calculator'
				);

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION'))
		{
			$tabMenu->addItem(
				'#economic_settings',
				'COM_REDSHOP_ECONOMIC_SETTINGS',
				($selectedTabPosition == 'economic_settings') ? true : false,
				'economic_settings'
			);
		}

		return $tabMenu;
	}
}
