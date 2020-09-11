<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewSearch extends RedshopView
{
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();

        $lists = array();

        $params   = $app->getParams('com_redshop');
        $document = JFactory::getDocument();

        $layout = $app->input->getCmd('layout', '');
        $model  = $this->getModel('search');

        if ($layout == 'default') {
            $pagetitle = JText::_('COM_REDSHOP_SEARCH');
            $document->setTitle($pagetitle);
        }

        if ($layout == 'redfilter') {
            $session      = JSession::getInstance('none', array());
            $tagid        = $app->input->getInt('tagid', 0);
            $typeid       = $app->input->getInt('typeid', 0);
            $remove       = $app->input->getInt('remove', 0);
            $Itemid       = $app->input->getInt('Itemid', 0);
            $cntproduct   = $app->input->getInt('cnt', 0);
            $getredfilter = $session->get('redfilter');

            if (empty($getredfilter)) {
                $redfilter = array();
            } else {
                $redfilter = $getredfilter;
            }

            if ($tagid != 0 && $typeid != 0 && !array_key_exists($typeid, $redfilter)) {
                $redfilter[$typeid] = $tagid;
            }

            if ($remove == 1) {
                if ($typeid != 0) {
                    unset($redfilter[$typeid]);
                    $session->set('redfilter', $redfilter);

                    $this->setLayout('redfilter');
                    $model->getRedFilterProduct($remove);

                    echo $model->mod_redProductfilter($Itemid, $typeid) . '~';
                } else {
                    $session->destroy('redfilter');
                }
            }

            $session->set('redfilter', $redfilter);

            if ($cntproduct == 1) {
                $mypid = $app->input->getInt('pid', 0);

                $app->redirect(
                    Redshop\IO\Route::_('index.php?option=com_redshop&view=product&pid=' . $mypid . '&Itemid=' . $Itemid)
                );
            }
        }

        $order_data = RedshopHelperUtility::getOrderByList();
        $getorderby = $app->input->getString(
            'order_by',
            $app->getUserState('order_by', Redshop::getConfig()->get('DEFAULT_PRODUCT_ORDERING_METHOD'))
        );
        $app->setUserState('order_by', $getorderby);
        JFactory::getApplication()->input->set('order_by', $app->getUserState('order_by'));
        $lists['order_select'] = JHTML::_(
            'select.genericlist',
            $order_data,
            'order_by',
            'class="inputbox" size="1" onchange="document.orderby_form.submit();" ',
            'value',
            'text',
            $getorderby
        );
        $search                = $this->get('Data');
        $pagination            = $this->get('Pagination');

        $this->params       = $params;
        $this->lists        = $lists;
        $this->templatedata = $model->getState('templateDesc');
        $this->search       = $search;
        $this->pagination   = $pagination;
        parent::display($tpl);
    }

    /**
     * Generate product search output
     */
    public function onRSProductSearch()
    {
        if (count($this->search) > 0) {
            JPluginHelper::importPlugin('redshop_product');

            $app   = JFactory::getApplication();
            $input = JFactory::getApplication()->input;
            $input->set('order_by', $app->getUserState('order_by'));

            $dispatcher = RedshopHelperUtility::getDispatcher();

            $Itemid         = $app->input->getInt('Itemid');
            $search_type    = $app->input->getCmd('search_type');
            $cid            = $app->input->getInt('category_id');
            $manufacture_id = $app->input->getInt('manufacture_id');

            $templateid = $app->input->getInt('templateid');

            // Cmd removes space between to words
            $keyword = $app->input->getString('keyword');
            $layout  = $app->input->getCmd('layout', 'default');

            $db    = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->qn('name'))
                ->from($db->qn('#__redshop_category'))
                ->where($db->qn('id') . ' = ' . $db->q((int)$input->getInt('cid', 0)));

            $cat_name = $db->setQuery($query)->loadResult();

            $model = $this->getModel('search');
            $total = $model->getTotal();

            JHTML::_('behavior.modal');
            $url = JURI::base();

            if ($this->params->get('page_title') != "") {
                $pagetitle = $this->params->get('page_title');
            } else {
                $pagetitle = JText::_('COM_REDSHOP_SEARCH');
            }

            if ($this->params->get('show_page_heading', 1)) {
                echo '<h1 class="componentheading' . $this->escape($this->params->get('pageclass_sfx')) . '">';
                echo $pagetitle;
                echo '</h1>';
            }

            echo '<div style="clear:both"></div>';
            $category_tmpl = "";

            if ($this->templatedata != "") {
                $templateDesc = $this->templatedata;
            } else {
                $templateDesc = "<div class=\"category_print\">{print}</div>\r\n<div style=\"clear: both;\"></div>\r\n<div class=\"category_main_description\">{category_main_description}</div>\r\n<p>{if subcats} {category_loop_start}</p>\r\n<div id=\"categories\">\r\n<div style=\"float: left; width: 200px;\">\r\n<div class=\"category_image\">{category_thumb_image}</div>\r\n<div class=\"category_description\">\r\n<h2 class=\"category_title\">{category_name}</h2>\r\n{category_description}</div>\r\n</div>\r\n</div>\r\n<p>{category_loop_end} {subcats end if}</p>\r\n<div style=\"clear: both;\"></div>\r\n<div id=\"category_header\">\r\n<div class=\"category_order_by\">{order_by}</div>\r\n</div>\r\n<div class=\"category_box_wrapper\">{product_loop_start}\r\n<div class=\"category_box_outside\">\r\n<div class=\"category_box_inside\">\r\n<div class=\"category_product_image\">{product_thumb_image}</div>\r\n<div class=\"category_product_title\">\r\n<h3>{product_name}</h3>\r\n</div>\r\n<div class=\"category_product_price\">{product_price}</div>\r\n<div class=\"category_product_readmore\">{read_more}</div>\r\n<div>{product_rating_summary}</div>\r\n<div class=\"category_product_addtocart\">{form_addtocart:add_to_cart1}</div>\r\n</div>\r\n</div>\r\n{product_loop_end}\r\n<div class=\"category_product_bottom\" style=\"clear: both;\"></div>\r\n</div>\r\n<div class=\"pagination\">{pagination}</div>";
            }

            $template_org = $templateDesc;
            $template_d1  = explode("{category_loop_start}", $template_org);

            if (count($template_d1) > 1) {
                $template_d2 = explode("{category_loop_end}", $template_d1[1]);

                if (count($template_d2) > 0) {
                    $category_tmpl = $template_d2[0];
                }
            }

            $template_org = str_replace($category_tmpl, "", $template_org);
            $template_org = str_replace("{category_loop_start}", "", $template_org);
            $template_org = str_replace("{category_loop_end}", "", $template_org);
            $print        = $app->input->getInt('print');
            $p_url        = @ explode('?', $app->input->server->get('REQUEST_URI', '', 'raw'));
            $print_tag    = '';

            if ($print) {
                $print_tag = "<a onclick='window.print();' title='" . JText::_(
                        'COM_REDSHOP_PRINT_LBL'
                    ) . "' ><img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_(
                        'COM_REDSHOP_PRINT_LBL'
                    ) . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' /></a>";
            } else {
                $print_url = $url . "index.php?option=com_redshop&view=search&print=1&tmpl=component";
                $print_tag = "<a href='#' onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")' title='" . JText::_(
                        'COM_REDSHOP_PRINT_LBL'
                    ) . "' ><img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_(
                        'COM_REDSHOP_PRINT_LBL'
                    ) . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' /></a>";
            }

            if (strstr($template_org, '{compare_product_div}')) {
                $compareProductDiv = '';

                if (Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE') != '') {
                    $compareProductDiv = RedshopLayoutHelper::render('product.compare_product');
                }

                $template_org = str_replace('{compare_product_div}', $compareProductDiv, $template_org);
            }

            // Skip html if nosubcategory
            if (strstr($template_org, "{if subcats}")) {
                $template_d1  = explode("{if subcats}", $template_org);
                $template_d2  = explode("{subcats end if}", $template_d1[1]);
                $template_org = $template_d1[0] . $template_d2[1];
            }

            // End skip html if nosubcategory
            $template_org = str_replace("{print}", $print_tag, $template_org);
            $template_org = str_replace("{product_price_slider}", '', $template_org);
            $template_org = str_replace("{filter_by}", '', $template_org);
            $template_org = str_replace("{template_selector_category_lbl}", '', $template_org);
            $template_org = str_replace("{template_selector_category}", '', $template_org);
            $template_org = str_replace("{category_main_name}", $cat_name, $template_org);
            $template_org = str_replace("{category_main_description}", '', $template_org);
            $template_org = str_replace("{category_description}", '', $template_org);
            $template_org = str_replace("{category_short_desc}", '', $template_org);
            $template_org = str_replace("{category_name}", '', $template_org);
            $template_org = str_replace("{if subcats}", '', $template_org);
            $template_org = str_replace("{subcats end if}", '', $template_org);
            $template_org = str_replace("{category_main_thumb_image_3}", '', $template_org);
            $template_org = str_replace("{category_main_short_desc}", '', $template_org);
            $template_org = str_replace("{category_main_thumb_image_2}", '', $template_org);
            $template_org = str_replace("{category_main_thumb_image_1}", '', $template_org);
            $template_org = str_replace("{category_main_thumb_image}", '', $template_org);
            $template_org = str_replace("{attribute_price_without_vat}", '', $template_org);
            $template_org = str_replace("{redproductfinderfilter_formstart}", '', $template_org);
            $template_org = str_replace("{redproductfinderfilter:rp_myfilter}", '', $template_org);
            $template_org = str_replace("{redproductfinderfilter_formend}", '', $template_org);
            $template_org = str_replace("{total_product}", $total, $template_org);
            $template_org = str_replace("{total_product_lbl}", JText::_('COM_REDSHOP_TOTAL_PRODUCT'), $template_org);

            // Replace redproductfilder filter tag
            if (strstr($template_org, "{redproductfinderfilter:")) {
                $redProductFinerHelper = JPATH_SITE . "/components/com_redproductfinder/helpers/redproductfinder_helper.php";
                if (file_exists($redProductFinerHelper)) {
                    include_once $redProductFinerHelper;
                    $redproductfinder_helper = new redproductfinder_helper;
                    $hdnFields               = array(
                        'texpricemin'       => '0',
                        'texpricemax'       => '0',
                        'manufacturer_id'   => $filter_by,
                        'category_template' => $templateid
                    );
                    $hide_filter_flag        = false;

                    if ($this->_id) {
                        $prodctofcat = RedshopHelperProduct::getProductCategory($this->_id);

                        if (empty($prodctofcat)) {
                            $hide_filter_flag = true;
                        }
                    }

                    $template_org = $redproductfinder_helper->replaceProductfinder_tag(
                        $template_org,
                        $hdnFields,
                        $hide_filter_flag
                    );
                }
            }

            // Replace redproductfilder filter tag end here
            $template_d1       = explode("{product_loop_start}", $template_org);
            $template_d2       = explode("{product_loop_end}", $template_d1[1]);
            $template_tmp_desc = $template_d2[0];
            $templateDesc      = $template_d2[0];

            // Order By
            $order_by     = "";
            $orderby_form = "<form name='orderby_form' action='' method='post' >";
            $orderby_form .= $this->lists['order_select'];
            $orderby_form .= "<input type='hidden' name='view' value='search'>
			<input type='hidden' name='layout' value='$layout'>
			<input type='hidden' name='keyword' value='$keyword'>
			<input type='hidden' name='category_id' value='$cid'>
			<input type='hidden' name='manufacture_id' value='$manufacture_id'>
			<input type='hidden' name='templateid' value='$templateid'></form>";

            if (strstr($templateDesc, '{order_by}')) {
                $order_by = $orderby_form;
            }

            $extraFieldName                = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
            $extraFieldsForCurrentTemplate = RedshopHelperTemplate::getExtraFieldsForCurrentTemplate(
                $extraFieldName,
                $templateDesc,
                1
            );
            $attribute_template            = \Redshop\Template\Helper::getAttribute($templateDesc);

            $total_product = $model->getTotal();
            $endlimit      = $model->getState('list.limit');
            $start         = $model->getState('list.start');

            $tagarray            = RedshopHelperText::getTextLibraryTagArray();
            $data                = "";
            $count_no_user_field = 0;
            $fieldArray          = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

            for ($i = 0, $countSearch = count($this->search); $i < $countSearch; $i++) {
                $data_add = $templateDesc;

                // RedSHOP Product Plugin
                $params = array();
                $dispatcher->trigger('onPrepareProduct', array(&$data_add, &$params, $this->search[$i]));

                $thum_image = "";
                $pname      = RedshopHelperUtility::maxChars(
                    $this->search[$i]->product_name,
                    Redshop::getConfig()->get(
                        'CATEGORY_PRODUCT_TITLE_MAX_CHARS'
                    ),
                    Redshop::getConfig()->get(
                        'CATEGORY_PRODUCT_TITLE_END_SUFFIX'
                    )
                );

                if ($search_type == 'product_number') {
                    $product_number = preg_replace(
                        "/($keyword)/i",
                        "<b class='search_hightlight'>$1</b>",
                        $this->search[$i]->product_number
                    );
                    $pro_s_desc     = $this->search[$i]->product_s_desc;
                    $pro_desc       = $this->search[$i]->product_desc;
                } else {
                    $product_number = $this->search[$i]->product_number;
                    $pro_s_desc     = $this->search[$i]->product_s_desc;
                    $pro_desc       = $this->search[$i]->product_desc;

                    if (!empty($keyword) && !in_array($keyword, $tagarray)) {
                        $regex          = "/" . preg_quote($keyword, "/") . "(?![^<]*>)/";
                        $pname          = preg_replace(
                            $regex,
                            "<b class='search_hightlight'>" . $keyword . "</b>",
                            $pname
                        );
                        $product_number = preg_replace(
                            $regex,
                            "<b class='search_hightlight'>" . $keyword . "</b>",
                            $product_number
                        );
                        $pro_s_desc     = preg_replace(
                            $regex,
                            "<b class='search_hightlight'>" . $keyword . "</b>",
                            $pro_s_desc
                        );
                        $pro_desc       = preg_replace(
                            $regex,
                            "<b class='search_hightlight'>" . $keyword . "</b>",
                            $pro_desc
                        );
                    }
                }

                $productUnit = RedshopLayoutHelper::render(
                    'tags.common.tag',
                    [
                        'tag'   => 'span',
                        'class' => 'product_unit_variable',
                        'text'  => Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT')
                    ],
                    '',
                    RedshopLayoutHelper::$layoutOption
                );

                $data_add = str_replace(
                    '{product_length}',
                    RedshopHelperProduct::redunitDecimal($this->search[$i]->product_length) . "&nbsp;" . $productUnit,
                    $data_add
                );

                $data_add = str_replace(
                    '{product_width}',
                    RedshopHelperProduct::redunitDecimal($this->search[$i]->product_width) . "&nbsp;" . $productUnit,
                    $data_add
                );

                $data_add = str_replace(
                    '{product_height}',
                    RedshopHelperProduct::redunitDecimal($this->search[$i]->product_height) . "&nbsp;" . $productUnit,
                    $data_add
                );

                $pro_s_desc = RedshopHelperUtility::maxChars(
                    $pro_s_desc,
                    Redshop::getConfig()->get(
                        'CATEGORY_PRODUCT_DESC_MAX_CHARS'
                    ),
                    Redshop::getConfig()->get(
                        'CATEGORY_PRODUCT_DESC_END_SUFFIX'
                    )
                );

                $ItemData = RedshopHelperProduct::getMenuInformation(
                    0,
                    0,
                    '',
                    'product&pid=' . $this->search[$i]->product_id
                );

                if (!empty($ItemData)) {
                    $pItemid = $ItemData->id;
                } else {
                    $pItemid = RedshopHelperRouter::getItemId(
                        $this->search[$i]->product_id,
                        $this->search[$i]->category_id
                    );
                }

                $link = Redshop\IO\Route::_(
                    'index.php?option=com_redshop&view=product&pid=' . $this->search[$i]->product_id . '&cid=' . $this->search[$i]->category_id . '&Itemid=' . $pItemid
                );

                if (strstr($data_add, '{product_name}')) {
                    $pname    = "<a href='" . $link . "'>" . $pname . "</a>";
                    $data_add = str_replace("{product_name}", $pname, $data_add);
                }

                if (strstr($data_add, '{product_name_nolink}')) {
                    $data_add = str_replace("{product_name_nolink}", $pname, $data_add);
                }

                $readmore = "<a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
                $data_add = str_replace("{read_more}", $readmore, $data_add);
                $data_add = str_replace("{read_more_link}", $link, $data_add);

                if (strstr($data_add, "{product_delivery_time}")) {
                    $product_delivery_time = RedshopHelperProduct::getProductMinDeliveryTime(
                        $this->search[$i]->product_id
                    );

                    if ($product_delivery_time != "") {
                        $data_add = str_replace("{delivery_time_lbl}", JText::_('DELIVERY_TIME'), $data_add);
                        $data_add = str_replace("{product_delivery_time}", $product_delivery_time, $data_add);
                    } else {
                        $data_add = str_replace("{delivery_time_lbl}", "", $data_add);
                        $data_add = str_replace("{product_delivery_time}", "", $data_add);
                    }
                }

                // Product Review/Rating
                // Fetching reviews
                $final_avgreview_data = Redshop\Product\Rating::getRating($this->search[$i]->product_id);

                // Attribute ajax chage
                $data_add = str_replace("{product_rating_summary}", $final_avgreview_data, $data_add);
                $data_add = RedshopHelperProduct::getJcommentEditor($this->search[$i], $data_add);

                if ($extraFieldsForCurrentTemplate) {
                    $data_add = Redshop\Helper\ExtraFields::displayExtraFields(
                        1,
                        $this->search[$i]->product_id,
                        $extraFieldsForCurrentTemplate,
                        $data_add,
                        true
                    );
                }

                $data_add = str_replace("{product_s_desc}", $pro_s_desc, $data_add);
                $data_add = str_replace("{product_desc}", $pro_desc, $data_add);
                $data_add = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $data_add);
                $data_add = str_replace("{product_id}", $this->search[$i]->product_id, $data_add);
                $data_add = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $data_add);
                $data_add = str_replace("{product_number}", $product_number, $data_add);

                // Product category tags
                $data_add = str_replace("{returntocategory_name}", $this->search[$i]->category_name, $data_add);

                if (strstr($data_add, "{returntoparent_category_name}")) {
                    $parentCategoryId = RedshopHelperProduct::getParentCategory($this->search[$i]->category_id);

                    if ($parentCategoryId != 0) {
                        $parentCategory = RedshopEntityCategory::getInstance($parentCategoryId)->getItem();
                        $data_add       = str_replace(
                            "{returntoparent_category_name}",
                            $parentCategory->category_name,
                            $data_add
                        );
                    } else {
                        $data_add = str_replace("{returntoparent_category_name}", '', $data_add);
                    }
                }

                /**
                 * related Product List in Lightbox
                 * Tag Format = {related_product_lightbox:<related_product_name>[:width][:height]}
                 */
                if (strstr($data_add, '{related_product_lightbox:')) {
                    $related_product = RedshopHelperProduct::getRelatedProduct($this->search[$i]->product_id);
                    $rtlnone         = explode("{related_product_lightbox:", $data_add);
                    $rtlntwo         = explode("}", $rtlnone[1]);
                    $rtlnthree       = explode(":", $rtlntwo[0]);
                    $rtln            = $rtlnthree[0];
                    $rtlnfwidth      = (isset($rtlnthree[1])) ? $rtlnthree[1] : "900";
                    $rtlnwidthtag    = (isset($rtlnthree[1])) ? ":" . $rtlnthree[1] : "";

                    $rtlnfheight   = (isset($rtlnthree[2])) ? $rtlnthree[2] : "600";
                    $rtlnheighttag = (isset($rtlnthree[2])) ? ":" . $rtlnthree[2] : "";

                    $rtlntag = "{related_product_lightbox:$rtln$rtlnwidthtag$rtlnheighttag}";

                    if (count($related_product) > 0) {
                        $linktortln = JURI::root(
                            ) . "index.php?option=com_redshop&view=product&pid=" . $this->search[$i]->product_id . "&tmpl=component&template=" . $rtln . "&for=rtln";
                        $rtlna      = '<a class="modal" href="' . $linktortln . '" rel="{handler:\'iframe\',size:{x:' . $rtlnfwidth . ',y:' . $rtlnfheight . '}}" >' . JText::_(
                                'COM_REDSHOP_RELATED_PRODUCT_LIST_IN_LIGHTBOX'
                            ) . '</a>';
                    } else {
                        $rtlna = "";
                    }

                    $data_add = str_replace($rtlntag, $rtlna, $data_add);
                }

                $data_add = RedshopHelperTax::replaceVatInformation($data_add);

                /************************************
                 *  Conditional tag
                 *  if product on discount : Yes
                 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
                 *  NO : // OUTPUT : Display blank
                 ************************************/
                $data_add = RedshopHelperProduct::getProductOnSaleComment($this->search[$i], $data_add);

                $data_add = RedshopHelperStockroom::replaceStockroomAmountDetail(
                    $data_add,
                    $this->search[$i]->product_id
                );

                if (strstr($data_add, "{product_thumb_image_3}")) {
                    $cimg_tag = '{product_thumb_image_3}';
                    $ch_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_3');
                    $cw_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_3');
                } elseif (strstr($data_add, "{product_thumb_image_2}")) {
                    $cimg_tag = '{product_thumb_image_2}';
                    $ch_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_2');
                    $cw_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_2');
                } elseif (strstr($data_add, "{product_thumb_image_1}")) {
                    $cimg_tag = '{product_thumb_image_1}';
                    $ch_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
                    $cw_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
                } else {
                    $cimg_tag = '{product_thumb_image}';
                    $ch_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
                    $cw_thumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
                }

                $hidden_thumb_image = "<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='" . $cw_thumb . "'><input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='" . $ch_thumb . "'>";
                $thum_image         = Redshop\Product\Image\Image::getImage(
                    $this->search[$i]->product_id,
                    $link,
                    $cw_thumb,
                    $ch_thumb
                );
                $data_add           = str_replace($cimg_tag, $thum_image . $hidden_thumb_image, $data_add);

                // More documents
                if (strstr($data_add, "{more_documents}")) {
                    $media_documents = RedshopHelperMedia::getAdditionMediaImage(
                        $this->search[$i]->product_id,
                        "product",
                        "document"
                    );
                    $more_doc        = '';

                    for ($m = 0, $countMedia = count($media_documents); $m < $countMedia; $m++) {
                        $alttext = RedshopHelperMedia::getAlternativeText(
                            "product",
                            $media_documents[$m]->section_id,
                            "",
                            $media_documents[$m]->media_id,
                            "document"
                        );

                        if (!$alttext) {
                            $alttext = $media_documents[$m]->media_name;
                        }

                        if (JFile::exists(
                            REDSHOP_FRONT_DOCUMENT_RELPATH . "product/" . $media_documents[$m]->media_name
                        )) {
                            $downlink = JURI::root(
                                ) . 'index.php?tmpl=component&option=com_redshop&view=product&pid=' . $this->search[$i]->product_id . '&task=downloadDocument&fname=' . $media_documents[$m]->media_name . '&Itemid=' . $Itemid;
                            $more_doc .= "<div><a href='" . $downlink . "' title='" . $alttext . "'>";
                            $more_doc .= $alttext;
                            $more_doc .= "</a></div>";
                        }
                    }

                    $data_add = str_replace(
                        "{more_documents}",
                        "<span id='additional_docs" . $this->search[$i]->product_id . "'>" . $more_doc . "</span>",
                        $data_add
                    );
                }

                // More documents end

                /************************************************ user fields*******************************************************/
                $hidden_userfield    = "";
                $returnArr           = \Redshop\Product\Product::getProductUserfieldFromTemplate($data_add);
                $template_userfield  = $returnArr[0];
                $userfieldArr        = $returnArr[1];
                $count_no_user_field = 0;

                if ($template_userfield != "") {
                    $ufield = "";

                    for ($ui = 0, $countUserField = count($userfieldArr); $ui < $countUserField; $ui++) {
                        $productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
                            $userfieldArr[$ui],
                            12,
                            '',
                            '',
                            0,
                            $this->search[$i]->product_id
                        );
                        $ufield            .= $productUserFields[1];

                        if ($productUserFields[1] != "") {
                            $count_no_user_field++;
                        }

                        $data_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $data_add);
                        $data_add = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $data_add);
                    }

                    $productUserFieldsForm = "<form method='post' action='' id='user_fields_form_" . $this->search[$i]->product_id . "' name='user_fields_form_" . $this->search[$i]->product_id . "'>";

                    if ($ufield != "") {
                        $data_add = str_replace("{if product_userfield}", $productUserFieldsForm, $data_add);
                        $data_add = str_replace("{product_userfield end if}", "</form>", $data_add);
                    } else {
                        $data_add = str_replace("{if product_userfield}", "", $data_add);
                        $data_add = str_replace("{product_userfield end if}", "", $data_add);
                    }
                } elseif (Redshop::getConfig()->get('AJAX_CART_BOX')) {
                    $ajax_detail_template_desc = "";
                    $ajax_detail_template      = \Redshop\Template\Helper::getAjaxDetailBox($this->search[$i]);

                    if (null !== $ajax_detail_template) {
                        $ajax_detail_template_desc = $ajax_detail_template->template_desc;
                    }

                    $returnArr          = \Redshop\Product\Product::getProductUserfieldFromTemplate(
                        $ajax_detail_template_desc
                    );
                    $template_userfield = $returnArr[0];
                    $userfieldArr       = $returnArr[1];

                    if ($template_userfield != "") {
                        $ufield = "";

                        for ($ui = 0, $countUserField = count($userfieldArr); $ui < $countUserField; $ui++) {
                            $productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
                                $userfieldArr[$ui],
                                12,
                                '',
                                '',
                                0,
                                $this->search[$i]->product_id
                            );
                            $ufield            .= $productUserFields[1];

                            if ($productUserFields[1] != "") {
                                $count_no_user_field++;
                            }

                            $template_userfield = str_replace(
                                '{' . $userfieldArr[$ui] . '_lbl}',
                                $productUserFields[0],
                                $template_userfield
                            );
                            $template_userfield = str_replace(
                                '{' . $userfieldArr[$ui] . '}',
                                $productUserFields[1],
                                $template_userfield
                            );
                        }

                        if ($ufield != "") {
                            $hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $this->search[$i]->product_id . "' name='user_fields_form_" . $this->search[$i]->product_id . "'>" . $template_userfield . "</form></div>";
                        }
                    }
                }

                $data_add = $data_add . $hidden_userfield;

                /*************** end user fields ***************/

                // ProductFinderDatepicker Extra Field Start

                $data_add = RedshopHelperProduct::getProductFinderDatepickerValue(
                    $data_add,
                    $this->search[$i]->product_id,
                    $fieldArray
                );

                // ProductFinderDatepicker Extra Field End

                /*
                 * manufacturer data
                 */
                $manufacturer_id = $this->search[$i]->id;

                if ($manufacturer_id != 0) {
                    $manufacturer_link_href = Redshop\IO\Route::_(
                        'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $manufacturer_id . '&Itemid=' . $Itemid
                    );
                    $manufacturer_name      = $this->search[$i]->name;
                    $manufacturer_link      = '<a href="' . $manufacturer_link_href . '" title="' . $manufacturer_name . '">' . $manufacturer_name . '</a>';

                    if (strstr($data_add, "{manufacturer_link}")) {
                        $data_add = str_replace("{manufacturer_name}", "", $data_add);
                    } else {
                        $data_add = str_replace("{manufacturer_name}", $manufacturer_name, $data_add);
                    }

                    $data_add = str_replace("{manufacturer_link}", $manufacturer_link, $data_add);
                } else {
                    $data_add = str_replace("{manufacturer_link}", "", $data_add);
                    $data_add = str_replace("{manufacturer_name}", "", $data_add);
                }

                // End

                // Replace wishlistbutton
                $data_add = RedshopHelperWishlist::replaceWishlistTag($this->search[$i]->product_id, $data_add);

                // Replace compare product button
                $data_add = Redshop\Product\Compare::replaceCompareProductsButton(
                    $this->search[$i]->product_id,
                    0,
                    $data_add
                );

                // Checking for child products
                if ($this->search[$i]->count_child_products > 0) {
                    $isChilds   = true;
                    $attributes = array();
                } else {
                    $isChilds = false;

                    // Get attributes
                    $attributes_set = array();

                    if ($this->search[$i]->attribute_set_id > 0) {
                        $attributes_set = \Redshop\Product\Attribute::getProductAttribute(
                            0,
                            $this->search[$i]->attribute_set_id,
                            0,
                            1
                        );
                    }

                    $attributes = \Redshop\Product\Attribute::getProductAttribute($this->search[$i]->product_id);
                    $attributes = array_merge($attributes, $attributes_set);
                }

                // Product attribute  Start
                $totalatt = count($attributes);

                // Check product for not for sale
                $data_add = RedshopHelperProduct::getProductNotForSaleComment(
                    $this->search[$i],
                    $data_add,
                    $attributes
                );

                $data_add = Redshop\Product\Stock::replaceInStock(
                    $this->search[$i]->product_id,
                    $data_add,
                    $attributes,
                    $attribute_template
                );

                $data_add = RedshopHelperAttribute::replaceAttributeData(
                    $this->search[$i]->product_id,
                    0,
                    0,
                    $attributes,
                    $data_add,
                    $attribute_template,
                    $isChilds
                );

                // Cart Template
                $data_add = Redshop\Cart\Render::replace(
                    $this->search[$i]->product_id,
                    0,
                    0,
                    0,
                    $data_add,
                    $isChilds,
                    $userfieldArr,
                    $totalatt,
                    $this->search[$i]->total_accessories,
                    $count_no_user_field
                );

                $data_add = RedshopHelperProductTag::getExtraSectionTag(
                    $extraFieldName,
                    $this->search[$i]->product_id,
                    "1",
                    $data_add
                );

                $productAvailabilityDate = strstr($data_add, "{product_availability_date}");
                $stockNotifyFlag         = strstr($data_add, "{stock_notify_flag}");
                $stockStatus             = strstr($data_add, "{stock_status");

                $attributeproductStockStatus = array();

                if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus) {
                    $attributeproductStockStatus = RedshopHelperProduct::getproductStockStatus(
                        $this->search[$i]->product_id,
                        $totalatt
                    );
                }

                $data_add = \Redshop\Helper\Stockroom::replaceProductStockData(
                    $this->search[$i]->product_id,
                    0,
                    0,
                    $data_add,
                    $attributeproductStockStatus
                );

                $dispatcher->trigger('onAfterDisplayProduct', array(&$data_add, array(), $this->search[$i]));

                $data .= $data_add;
            }

            $app    = JFactory::getApplication();
            $router = $app->getRouter();

            $getorderby = $app->input->get('order_by', Redshop::getConfig()->get('DEFAULT_PRODUCT_ORDERING_METHOD'));

            $vars = array(
                'option'         => 'com_redshop',
                'view'           => 'search',
                'layout'         => $layout,
                'keyword'        => $keyword,
                'manufacture_id' => $manufacture_id,
                'order_by'       => $getorderby,
                'category_id'    => $cid,
                'Itemid'         => $Itemid,
                'search_type'    => $search_type
            );
            $router->setVars($vars);
            unset($vars);

            if (strstr($template_org, "{show_all_products_in_category}")) {
                $template_org = str_replace("{show_all_products_in_category}", "", $template_org);
                $template_org = str_replace("{pagination}", "", $template_org);
            }

            $pagination = new JPagination($total_product, $start, $endlimit);

            if (strstr($template_org, "{pagination}")) {
                $template_org = str_replace("{pagination}", $pagination->getPagesLinks(), $template_org);
            }

            $usePerPageLimit = false;

            if (strstr($template_org, "perpagelimit:")) {
                $usePerPageLimit = true;
                $perpage         = explode('{perpagelimit:', $template_org);
                $perpage         = explode('}', $perpage[1]);
                $template_org    = str_replace("{perpagelimit:" . intval($perpage[0]) . "}", "", $template_org);
            }

            if (strstr($template_org, "{product_display_limit}")) {
                if ($usePerPageLimit) {
                    $limitBox = '';
                } else {
                    $limitBox = "<form action='' method='post'>
						<input type='hidden' name='keyword' value='$keyword'>
						<input type='hidden' name='category_id' value='$cid'>
						<input type='hidden' name='manufacture_id' value='$manufacture_id'>"
                        . $pagination->getLimitBox() . "</form>";
                }

                $template_org = str_replace("{product_display_limit}", $limitBox, $template_org);
            }

            $template_org = str_replace("{order_by}", $orderby_form, $template_org);
            $template_org = str_replace("{order_by_lbl}", JText::_('COM_REDSHOP_SELECT_ORDER_BY'), $template_org);
            $template_org = str_replace("{filter_by_lbl}", JText::_('COM_REDSHOP_SELECT_FILTER_BY'), $template_org);
            $template_org = str_replace("{attribute_price_with_vat}", "", $template_org);
            $template_org = str_replace("{attribute_price_without_vat}", "", $template_org);
            $template_org = str_replace("{product_loop_start}", "", $template_org);
            $template_org = str_replace("{product_loop_end}", "", $template_org);
            $template_org = str_replace($template_tmp_desc, $data, $template_org);

            $template_org = str_replace("{with_vat}", "", $template_org);
            $template_org = str_replace("{without_vat}", "", $template_org);

            $template_org = RedshopHelperTemplate::parseRedshopPlugin($template_org);
            $template_org = RedshopHelperText::replaceTexts($template_org);

            eval("?>" . $template_org . "<?php ");
        } else {
            echo "<br><h3>" . JText::_('COM_REDSHOP_MSG_SORRY_NO_RESULT_FOUND') . "</h3>";
        }
    }
}
