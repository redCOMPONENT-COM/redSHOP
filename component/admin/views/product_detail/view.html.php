<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

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

    /**
     * @var object
     */
    public $productSerialDetail;

    /**
     * @var JInput
     */
    public $input;

    /**
     * @var productHelper
     */
    public $producthelper;

    /**
     * @var  JEventDispatcher
     */
    public $dispatcher;

    /**
     * @var  RedshopModelProduct_Detail
     */
    public $model;

    /**
     * @var  object
     */
    public $detail;

    /**
     * @var  object
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
     * @return  mixed  A string if successful, otherwise a JError object.
     * @throws  Exception
     *
     * @see     fetch()
     * @since   11.1
     */
    public function display($tpl = null)
    {
        $app         = JFactory::getApplication();
        $this->input = $app->input;
        $user        = JFactory::getUser();

        JPluginHelper::importPlugin('redshop_product');
        JPluginHelper::importPlugin('redshop_product_type');

        $this->dispatcher = RedshopHelperUtility::getDispatcher();

        $this->option = $this->input->getString('option', 'com_redshop');
        $lists        = array();

        $model  = $this->getModel('product_detail');
        $detail = $this->get('data');

        $isNew = ($detail->product_id < 1);

        // Load new product default values
        if ($isNew) {
            $detail->append_to_global_seo = '';
            $detail->canonical_url        = '';
        }

        // Fail if checked out not by 'me'
        if ($model->isCheckedOut($user->get('id'))) {
            $msg = Text::_('COM_REDSHOP_PRODUCT_BEING_EDITED');
            $app->enqueueMessage($msg);
            $app->redirect('index.php?option=com_redshop&view=product');
        }

        // Check redproductfinder is installed
        $CheckRedProductFinder       = $model->CheckRedProductFinder();
        $this->CheckRedProductFinder = $CheckRedProductFinder;

        // Get association id
        $getAssociation       = $model->getAssociation();
        $this->getassociation = $getAssociation;

        // Get the tag names
        $tags            = $model->Tags();
        $associationtags = array();

        if (isset($getAssociation) && count($getAssociation) > 0) {
            $associationtags = $model->AssociationTags($getAssociation->id);
        }

        if (count($tags) > 0) {
            $lists['tags'] = HtmlHelper::_(
                'select.genericlist',
                $tags,
                'tag_id[]',
                'multiple',
                'id',
                'tag_name',
                $associationtags
            );
        }

        $types = $model->TypeTagList();

        // Get the Quality Score data

        $qs = $this->get('QualityScores', 'product_detail');

        // ToDo: Don't echo HTML but use tmpl files.
        // Create the select list as checkboxes

        $html = '<div id="select_box">';

        if (count($types) > 0) {
            foreach ($types as $typeid => $type) {
                $counttags = count($type['tags']);
                $rand      = rand();

                // Add the type

                $html .= '<div class="select_box_parent" onClick="showBox(' . $rand . ')">' . Text::_(
                    'COM_REDSHOP_TYPE_LIST'
                )
                    . ' ' . $type['type_name'] . '</div>';
                $html .= '<div id="' . $rand . '" class="select_box_child';
                $html .= '">';

                // Add the tags

                if ($counttags > 0) {
                    foreach ($type['tags'] as $tagid => $tag) {
                        // Check if the tag is selected

                        if (in_array($tagid, $associationtags)) {
                            $selected = 'checked="checked"';
                        } else {
                            $selected = '';
                        }

                        $html .= '<table><tr><td colspan="2"><input type="checkbox" class="select_box" ' . $selected
                            . ' name="tag_id[]" value="' . $typeid . '.' . $tagid . '" />'
                            . Text::_('COM_REDSHOP_TAG_LIST') . ' ' . $tag['tag_name'];
                        $html .= '</td></tr>';

                        $qs_value = '';

                        if (is_array($qs)) {
                            if (array_key_exists($typeid . '.' . $tagid, $qs)) {
                                $qs_value = $qs[$typeid . '.' . $tagid]['quality_score'];
                            }
                        }

                        $html .= '<tr><td><span class="quality_score">' . Text::_('COM_REDSHOP_QUALITY_SCORE')
                            . '</span></td><td><input type="text" class="quality_score_input"  name="qs_id[' . $typeid
                            . '.' . $tagid . ']" value="' . $qs_value . '" />';
                        $html .= '</td></tr>';

                        $html .= '<tr ><td colspan="2"><select name="sel_dep' . $typeid . '_' . $tagid
                            . '[]" id="sel_dep' . $typeid . '_' . $tagid . '" multiple="multiple" size="10"  >';

                        foreach ($types as $sel_typeid => $sel_type) {
                            if ($typeid == $sel_typeid) {
                                continue;
                            }

                            $dependent_tag = $model->getDependenttag($detail->product_id, $typeid, $tagid);

                            $html .= '<optgroup label="' . $sel_type['type_name'] . '">';

                            foreach ($sel_type['tags'] as $sel_tagid => $sel_tag) {
                                $selected = in_array($sel_tagid, $dependent_tag) ? "selected" : "";
                                $html .= '<option value="' . $sel_tagid . '" ' . $selected . ' >' . $sel_tag['tag_name'] . '</option>';
                            }

                            $html .= '</optgroup>';
                        }

                        $html .= '</select>&nbsp;<a href="#" onClick="javascript:add_dependency('
                            . $typeid . ',' . $tagid . ',' . $detail->product_id . ');" >'
                            . Text::_('COM_REDSHOP_ADD_DEPENDENCY') . '</a></td></tr></table>';
                    }
                }

                $html .= '</div>';
            }
        }

        $html .= '</div>';
        $lists['tags']     = $html;
        $templates         = RedshopHelperTemplate::getTemplate("product");
        $manufacturers     = $model->getmanufacturers();
        $supplier          = $model->getsupplier();
        $productCategories = $this->input->post->get('product_category', array(), 'array');

        if (!empty($productCategories)) {
            $productcats = $productCategories;
        } else {
            $productcats = $model->getproductcats();
        }

        $attributes    = $model->getattributes();
        $attributesSet = $model->getAttributeSetList();

        // Merging select option in the select box
        $temps          = array();
        $temps[0]       = new stdClass;
        $temps[0]->id   = "0";
        $temps[0]->name = Text::_('COM_REDSHOP_SELECT');

        if (is_array($templates)) {
            $templates = array_merge($temps, $templates);
        }

        // Merging select option in the select box
        $supps           = array();
        $supps[0]        = new stdClass;
        $supps[0]->value = "0";
        $supps[0]->text  = Text::_('COM_REDSHOP_SELECT');

        if (is_array($manufacturers)) {
            $manufacturers = array_merge($supps, $manufacturers);
        }

        // Merging select option in the select box
        $supps           = array();
        $supps[0]        = new stdClass;
        $supps[0]->value = "0";
        $supps[0]->text  = Text::_('COM_REDSHOP_SELECT');

        if (is_array($supplier)) {
            $supplier = array_merge($supps, $supplier);
        }

        JToolBarHelper::title(Text::_('COM_REDSHOP_PRODUCT_MANAGEMENT_DETAIL'), 'pencil-2 redshop_products48');

        $document = JFactory::getDocument();

        $document->addScriptDeclaration("var WANT_TO_DELETE = '" . Text::_('COM_REDSHOP_DO_WANT_TO_DELETE') . "';");

        /**
         * Override field.js file.
         * With this trigger the file can be loaded from a plugin. This can be used
         * to display different JS generated interface for attributes depending on a product type.
         * So, product type plugins should be used for this event. Be aware that this file should
         * be loaded only once.
         */
        $loadedFromAPlugin = $this->dispatcher->trigger('loadFieldsJSFromPlugin', array($detail));

        if (in_array(1, $loadedFromAPlugin)) {
            $loadedFromAPlugin = true;
        } else {
            $loadedFromAPlugin = false;
        }

        if (!$loadedFromAPlugin) {
            /** @scrutinizer ignore-deprecated */
            HTMLHelper::script('com_redshop/redshop.fields.min.js', ['relative' => true]);
        }

        HTMLHelper::script('com_redshop/json.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.validation.min.js', ['relative' => true]);
        HTMLHelper::script('com_redshop/redshop.attribute-manipulation.min.js', ['relative' => true]);

        if (file_exists(JPATH_SITE . '/components/com_redproductfinder/helpers/redproductfinder.css')) {
            $document->addStyleSheet('components/com_redproductfinder/helpers/redproductfinder.css');
        }

        $uri = \Joomla\CMS\Uri\Uri::getInstance();

        $layout = $this->input->getString('layout', '');

        if ($layout === 'property_images') {
            $this->setLayout('property_images');
        } elseif ($layout === 'attribute_color') {
            $this->setLayout('attribute_color');
        } elseif ($layout === 'productstockroom') {
            $this->setLayout('productstockroom');
        } else {
            $this->setLayout('default');
        }

        $text = $isNew ? Text::_('COM_REDSHOP_NEW') : $detail->product_name . " - " . Text::_('COM_REDSHOP_EDIT');

        JToolBarHelper::title(
            Text::_('COM_REDSHOP_PRODUCT') . ': <small><small>[ ' . $text . ' ]</small></small>',
            'pencil-2 redshop_products48'
        );

        JToolBarHelper::apply();
        JToolBarHelper::save();
        JToolBarHelper::save2new();

        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            JToolbarHelper::save2copy();
            $model->checkout($user->get('id'));

            JToolBarHelper::cancel('cancel', Text::_('JTOOLBAR_CLOSE'));
        }

        if ($detail->product_id > 0) {
            $menu           = RedshopHelperProduct::getMenuInformation(0, 0, '', 'product&pid=' . $detail->product_id);
            $mainCategoryId = $detail->cat_in_sefurl;

            if (!empty($menu)) {
                $pItemid = $menu->id;
            } else {
                $pItemid = RedshopHelperRouter::getItemId($detail->product_id, $mainCategoryId);
            }

            $link = JUri::root();
            $link .= 'index.php?option=com_redshop';
            $link .= '&view=product&pid=' . $detail->product_id;
            $link .= '&cid=' . $mainCategoryId;
            $link .= '&Itemid=' . $pItemid;

            RedshopToolbarHelper::link($link, 'preview', 'JGLOBAL_PREVIEW', '_blank');
            JToolBarHelper::addNew('prices', Text::_('COM_REDSHOP_ADD_PRICE_LBL'));
        }

        $model = $this->getModel('product_detail');

        $accessoryProducts = array();

        if ($detail->product_id) {
            $accessoryProducts = RedshopHelperAccessory::getProductAccessories(0, $detail->product_id);
        }

        $lists['accessory_product']        = $accessoryProducts;
        $lists['QUANTITY_SELECTBOX_VALUE'] = $detail->quantity_selectbox_value;

        // For preselected.
        if ($detail->product_template == "") {
            $default_preselected      = Redshop::getConfig()->get('PRODUCT_TEMPLATE');
            $detail->product_template = $default_preselected;
        }

        $lists['product_template'] = HtmlHelper::_(
            'select.genericlist',
            $templates,
            'product_template',
            'class="inputbox" size="1" onchange="set_dynamic_field(this.value,\'' . $detail->product_id . '\',\'1,12,17\');"  ',
            'id',
            'name',
            $detail->product_template
        );

        $lists['related_product'] = HtmlHelper::_(
            'redshopselect.search',
            $model->related_product_data($detail->product_id),
            'related_product',
            array(
                'select2.ajaxOptions' => array('typeField' => ', related:1, product_id:' . $detail->product_id),
                'select2.options'     => array('multiple' => 'true')
            )
        );

        $product_tax     = $model->gettax();
        $temps           = array();
        $temps[0]        = new stdClass;
        $temps[0]->value = "0";
        $temps[0]->text  = Text::_('COM_REDSHOP_SELECT');

        if (is_array($product_tax)) {
            $product_tax = array_merge($temps, $product_tax);
        }

        $lists['product_tax'] = HtmlHelper::_(
            'select.genericlist',
            $product_tax,
            'product_tax_id',
            'class="inputbox" size="1"  ',
            'value',
            'text',
            $detail->product_tax_id
        );

        $categories                         = RedshopHelperCategory::listAll(
            "product_category[]",
            0,
            $productcats,
            10,
            false,
            true
        );
        $lists['categories']                = $categories;
        $detail->first_selected_category_id = isset($productcats[0]) ? $productcats[0] : null;

        // Payment method list
        $lists['payment_methods'] = RedshopHelperPayment::listAll("payment_method[]", $detail->product_id, 10, true);

        $detail->use_individual_payment_method = isset($detail->use_individual_payment_method) ? $detail->use_individual_payment_method : null;

        $lists['use_individual_payment_method'] = HtmlHelper::_(
            'redshopselect.booleanlist',
            'use_individual_payment_method',
            'class="inputbox"',
            $detail->use_individual_payment_method
        );

        $lists['manufacturers'] = HtmlHelper::_(
            'select.genericlist',
            $manufacturers,
            'manufacturer_id',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->manufacturer_id
        );

        $lists['supplier']         = HtmlHelper::_(
            'select.genericlist',
            $supplier,
            'supplier_id',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->supplier_id
        );
        $lists['published']        = HtmlHelper::_(
            'redshopselect.booleanlist',
            'published',
            'class="inputbox"',
            $detail->published
        );
        $lists['product_on_sale']  = HtmlHelper::_(
            'redshopselect.booleanlist',
            'product_on_sale',
            'class="inputbox"',
            $detail->product_on_sale
        );
        $lists['copy_attribute']   = HtmlHelper::_(
            'redshopselect.booleanlist',
            'copy_attribute',
            'class="inputbox"',
            0
        );
        $lists['product_special']  = HtmlHelper::_(
            'redshopselect.booleanlist',
            'product_special',
            'class="inputbox"',
            $detail->product_special
        );
        $lists['product_download'] = HtmlHelper::_(
            'redshopselect.booleanlist',
            'product_download',
            'class="inputbox"',
            $detail->product_download
        );

        $detail->not_for_sale_showprice = 0;

        if ($detail->not_for_sale == 2) {
            $detail->not_for_sale           = 1;
            $detail->not_for_sale_showprice = 1;
        }

        $lists['not_for_sale']           = HtmlHelper::_(
            'redshopselect.booleanlist',
            'not_for_sale',
            'class="inputbox"',
            $detail->not_for_sale
        );
        $lists['not_for_sale_showprice'] = HtmlHelper::_(
            'redshopselect.booleanlist',
            'not_for_sale_showprice',
            'class="inputbox"',
            $detail->not_for_sale_showprice
        );

        $lists['expired']             = HtmlHelper::_(
            'redshopselect.booleanlist',
            'expired',
            'class="inputbox"',
            $detail->expired
        );
        $lists['allow_decimal_piece'] = HtmlHelper::_(
            'redshopselect.booleanlist',
            'allow_decimal_piece',
            'class="inputbox"',
            $detail->allow_decimal_piece
        );

        // For individual pre-order
        $preorder_data     = RedshopHelperUtility::getPreOrderByList();
        $lists['preorder'] = HtmlHelper::_(
            'select.genericlist',
            $preorder_data,
            'preorder',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->preorder
        );

        // Discount calculator
        $lists['use_discount_calc'] = HtmlHelper::_(
            'redshopselect.booleanlist',
            'use_discount_calc',
            'class="inputbox"',
            $detail->use_discount_calc
        );

        $selectOption       = array();
        $selectOption[]     = HtmlHelper::_('select.option', '1', Text::_('COM_REDSHOP_RANGE'));
        $selectOption[]     = HtmlHelper::_('select.option', '0', Text::_('COM_REDSHOP_PRICE_PER_PIECE'));
        $lists['use_range'] = HtmlHelper::_(
            'select.genericlist',
            $selectOption,
            'use_range',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->use_range
        );
        unset($selectOption);

        // Calculation method
        $selectOption[]                = HtmlHelper::_('select.option', '0', Text::_('COM_REDSHOP_SELECT'));
        $selectOption[]                = HtmlHelper::_('select.option', 'volume', Text::_('COM_REDSHOP_VOLUME'));
        $selectOption[]                = HtmlHelper::_('select.option', 'area', Text::_('COM_REDSHOP_AREA'));
        $selectOption[]                = HtmlHelper::_(
            'select.option',
            'circumference',
            Text::_('COM_REDSHOP_CIRCUMFERENCE')
        );
        $lists['discount_calc_method'] = HtmlHelper::_(
            'select.genericlist',
            $selectOption,
            'discount_calc_method',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->discount_calc_method
        );
        unset($selectOption);

        // Calculation UNIT
        $remove_format = HtmlHelper::$formatOptions;

        $selectOption[]              = HtmlHelper::_('select.option', 'mm', Text::_('COM_REDSHOP_MILLIMETER'));
        $selectOption[]              = HtmlHelper::_('select.option', 'cm', Text::_('COM_REDSHOP_CENTIMETER'));
        $selectOption[]              = HtmlHelper::_('select.option', 'm', Text::_('COM_REDSHOP_METER'));
        $lists['discount_calc_unit'] = HtmlHelper::_(
            'select.genericlist',
            $selectOption,
            'discount_calc_unit[]',
            'class="inputbox" size="1" ',
            'value',
            'text',
            Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT')
        );
        $lists['discount_calc_unit'] = str_replace($remove_format['format.indent'], "", $lists['discount_calc_unit']);
        $lists['discount_calc_unit'] = str_replace($remove_format['format.eol'], "", $lists['discount_calc_unit']);
        unset($selectOption);

        $productVatGroup = $model->getVatGroup();
        $temps           = array();
        $temps[0]        = new stdClass;
        $temps[0]->value = "";
        $temps[0]->text  = Text::_('COM_REDSHOP_SELECT');

        if (is_array($productVatGroup)) {
            $productVatGroup = array_merge($temps, $productVatGroup);
        }

        if (Redshop::getConfig()->get('DEFAULT_VAT_GROUP') && !$detail->product_tax_group_id) {
            $detail->product_tax_group_id = Redshop::getConfig()->get('DEFAULT_VAT_GROUP');
        }

        $append_to_global_seo          = array();
        $append_to_global_seo[]        = HtmlHelper::_(
            'select.option',
            'append',
            Text::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO')
        );
        $append_to_global_seo[]        = HtmlHelper::_(
            'select.option',
            'prepend',
            Text::_('COM_REDSHOP_PREPEND_TO_GLOBAL_SEO')
        );
        $append_to_global_seo[]        = HtmlHelper::_(
            'select.option',
            'replace',
            Text::_('COM_REDSHOP_REPLACE_TO_GLOBAL_SEO')
        );
        $lists['append_to_global_seo'] = HtmlHelper::_(
            'select.genericlist',
            $append_to_global_seo,
            'append_to_global_seo',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->append_to_global_seo
        );

        $lists['product_tax_group_id'] = HtmlHelper::_(
            'select.genericlist',
            $productVatGroup,
            'product_tax_group_id',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->product_tax_group_id
        );

        $propOprand   = array();
        $propOprand[] = HtmlHelper::_('select.option', '+', '+');
        $propOprand[] = HtmlHelper::_('select.option', '-', '-');
        $propOprand[] = HtmlHelper::_('select.option', '=', '=');
        $propOprand[] = HtmlHelper::_('select.option', '*', '*');
        $propOprand[] = HtmlHelper::_('select.option', '/', '/');

        $lists['prop_oprand'] = $propOprand;

        $cat_in_sefurl = $model->catin_sefurl($detail->product_id);

        $lists['cat_in_sefurl'] = HtmlHelper::_(
            'redshopselect.genericlist',
            $cat_in_sefurl,
            'cat_in_sefurl',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->cat_in_sefurl
        );

        $lists['attributes'] = $attributes;

        $temps           = array();
        $temps[0]        = new stdClass;
        $temps[0]->value = "";
        $temps[0]->text  = Text::_('COM_REDSHOP_SELECT');

        if (is_array($attributesSet)) {
            $attributesSet = array_merge($temps, $attributesSet);
        }

        $lists['attributesSet'] = HtmlHelper::_(
            'select.genericlist',
            $attributesSet,
            'attribute_set_id',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->attribute_set_id
        );

        // Product type selection
        $productTypeOptions   = array();
        $productTypeOptions[] = HtmlHelper::_('select.option', 'product', Text::_('COM_REDSHOP_PRODUCT'));
        $productTypeOptions[] = HtmlHelper::_('select.option', 'file', Text::_('COM_REDSHOP_FILE'));
        $productTypeOptions[] = HtmlHelper::_('select.option', 'subscription', Text::_('COM_REDSHOP_SUBSCRIPTION'));

        /*
         * Trigger event which can update list of product types.
         * Example of a returned value:
         * return array('value' => 'redDESIGN', 'text' => Text::_('PLG_REDSHOP_PRODUCT_TYPE_REDDESIGN_REDDESIGN_PRODUCT_TYPE'));
         */
        $productTypePluginOptions = $this->dispatcher->trigger('onListProductTypes');

        foreach ($productTypePluginOptions as $productTypePluginOption) {
            $productTypeOptions[] = HtmlHelper::_(
                'select.option',
                $productTypePluginOption['value'],
                $productTypePluginOption['text']
            );
        }

        if ($detail->product_download == 1) {
            $detail->product_type = 'file';
        }

        $lists["product_type"] = HtmlHelper::_(
            'select.genericlist',
            $productTypeOptions,
            'product_type',
            'onchange="set_dynamic_field(this.value,\'' . $detail->product_id . '\',\'1,12,17\');"',
            'value',
            'text',
            $detail->product_type
        );

        $accountgroup = RedshopHelperUtility::getEconomicAccountGroup();
        $op           = array();
        $op[]         = HtmlHelper::_('select.option', '0', Text::_('COM_REDSHOP_SELECT'));
        $accountgroup = array_merge($op, $accountgroup);

        $lists["accountgroup_id"] = HtmlHelper::_(
            'select.genericlist',
            $accountgroup,
            'accountgroup_id',
            'class="inputbox" size="1" ',
            'value',
            'text',
            $detail->accountgroup_id
        );

        // For downloadable products
        $productSerialDetail = $model->getProdcutSerialNumbers();

        // Joomla tags
        $jtags = JHelperTags::searchTags();

        $currentTags = null;

        if (!empty($detail->product_id)) {
            $tagsHelper  = new JHelperTags;
            $currentTags = $tagsHelper->getTagIds($detail->product_id, 'com_redshop.product');
            $currentTags = explode(',', $currentTags);
        }

        $lists['jtags'] = HtmlHelper::_(
            'select.genericlist',
            $jtags,
            'jtags[]',
            'class="inputbox" size="10" multiple="multiple"',
            'value',
            'text',
            $currentTags
        );

        $this->model               = $model;
        $this->lists               = $lists;
        $this->detail              = $detail;
        $this->productSerialDetail = $productSerialDetail;
        $this->request_url         = $uri->toString();
        $this->tabmenu             = $this->getTabMenu();

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
        $app                 = JFactory::getApplication();
        $selectedTabPosition = $app->getUserState('com_redshop.product_detail.selectedTabPosition', 'general_data');

        $tabMenu = new RedshopMenu();
        $tabMenu->section('tab')
            ->title('COM_REDSHOP_PRODUCT_INFORMATION')
            ->addItem(
                '#general_data',
                'COM_REDSHOP_PRODUCT_INFORMATION',
                ($selectedTabPosition === 'general_data') ? true : false,
                'general_data'
            );

        if ($this->detail->product_type !== 'product' && !empty($this->detail->product_type)) {
            $tabMenu->addItem(
                '#producttype',
                'COM_REDSHOP_CHANGE_PRODUCT_TYPE_TAB',
                ($selectedTabPosition === 'producttype') ? true : false,
                'producttype'
            );
        }

        $tabMenu->addItem(
            '#extrafield',
            'COM_REDSHOP_FIELDS',
            ($selectedTabPosition === 'extrafield') ? true : false,
            'extrafield'
        )->addItem(
                '#product_images',
                'COM_REDSHOP_PRODUCT_IMAGES',
                ($selectedTabPosition === 'product_images') ? true : false,
                'product_images'
            )->addItem(
                '#product_attribute',
                'COM_REDSHOP_PRODUCT_ATTRIBUTES',
                ($selectedTabPosition === 'product_attribute') ? true : false,
                'product_attribute'
            )->addItem(
                '#product_accessory',
                'COM_REDSHOP_ACCESSORY_RELATED_PRODUCT',
                ($selectedTabPosition === 'product_accessory') ? true : false,
                'product_accessory'
            );

        if ($this->CheckRedProductFinder > 0) {
            $tabMenu->addItem(
                '#productfinder',
                'COM_REDSHOP_REDPRODUCTFINDER_ASSOCIATION',
                $selectedTabPosition === 'productfinder',
                'productfinder'
            );
        }

        $tabMenu->addItem(
            '#product_meta_data',
            'COM_REDSHOP_META_DATA_TAB',
            $selectedTabPosition === 'product_meta_data',
            'product_meta_data'
        );

        if (Redshop::getConfig()->getBool('USE_STOCKROOM')) {
            $tabMenu->addItem(
                '#productstockroom',
                'COM_REDSHOP_STOCKROOM_TAB',
                $selectedTabPosition === 'productstockroom',
                'productstockroom'
            );
        }

        $tabMenu->addItem(
            '#calculator',
            'COM_REDSHOP_DISCOUNT_CALCULATOR',
            $selectedTabPosition === 'calculator',
            'calculator'
        );

        $tabMenu->addItem(
            '#product_payment_method',
            'COM_REDSHOP_TEMPLATE_PAYMENT_METHOD',
            $selectedTabPosition === 'product_payment_method',
            'product_payment_method'
        );

        if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION')) {
            $tabMenu->addItem(
                '#economic_settings',
                'COM_REDSHOP_ECONOMIC_SETTINGS',
                $selectedTabPosition === 'economic_settings',
                'economic_settings'
            );
        }

        $this->dispatcher->trigger('onDisplayTabMenu', array(&$tabMenu, $selectedTabPosition));

        return $tabMenu;
    }
}