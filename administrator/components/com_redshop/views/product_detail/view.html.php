<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'category.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'shopper.php' ); // reddesign edited
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );

class product_detailVIEWproduct_detail extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		$redTemplate = new Redtemplate();
		$redhelper = new redhelper();
		$producthelper = new producthelper();

		$option = JRequest::getVar('option');
		$db = JFactory::getDBO();
		$cfg = JFactory::getConfig();
	    $dbPrefix = $cfg->getValue('config.dbprefix');
		$lists = array();

		$model = $this->getModel ( 'product_detail' );
		$detail	= $this->get('data');
		$user 	= JFactory::getUser();

		// 	fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_('COM_REDSHOP_THE_DETAIL' ), $detail->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Check reddesign is installed
		$CheckRedDesign = $model->CheckRedDesign();
		$this->assignRef('CheckRedDesign',$CheckRedDesign);
		// Check reddesign is installed End

		// Check redproductfinder is installed
		$CheckRedProductFinder = $model->CheckRedProductFinder();
		$this->assignRef('CheckRedProductFinder',$CheckRedProductFinder);

		// get association id
		$getAssociation = $model->getAssociation();
		$this->assignRef('getassociation',$getAssociation);

		$sql = "SHOW TABLE STATUS LIKE '".$dbPrefix."redshop_product'";
		$db->setQuery($sql) ;
		$row = $db->loadObject();
		$next_product = $row->Auto_increment ;

		/* Get the tag names */
		$tags = $model->Tags();
		$associationtags = 0;
		if(isset($getAssociation) && count($getAssociation) > 0 )
			$associationtags = $model->AssociationTags($getAssociation->id);

		if (count($tags)>0)
		{
			if (!is_array($associationtags)) $associationtags = array();
				$lists['tags'] = JHTML::_('select.genericlist', $tags, 'tag_id[]', 'multiple', 'id', 'tag_name', $associationtags);
		}

		$types = $model->TypeTagList();

		/* Disabled as customer doesn't want a multi-select box now, who knows later??? */
		if (0) {
			/* Create the select list */
			$html = '<select id="tag_id" multiple="multiple" name="tag_id[]">';
			foreach ($types as $key => $type) {
				/* Add the type */
				$html .= '<option value="">'.JText::_('COM_REDSHOP_TYPE_LIST').' '.$type['type_name'].'</option>';
				/* Add the tags */
				if (count($type['tags']) > 0) {
					foreach ($type['tags'] as $tagid => $tag) {
						/* Check if the tag is selected */
						if (in_array($tagid, $associationtags)) $selected = 'selected="selected"';
						else $selected = '';
						$html .= '<option '.$selected.' value="'.$tagid.'" >--- '.JText::_('COM_REDSHOP_TAG_LIST').' '.$tag['tag_name'].'</option>';
					}
				}
			}
			$html .= '</select>';
			$lists['tags'] = $html;
		}

		/* Get the Quality Score data */
		$qs = $this->get('QualityScores', 'product_detail');

		/* Get the association ID */
		$assoc_id = JRequest::getVar('cid');
		$assoc_id = $assoc_id[0];

		/* Create the select list as checkboxes */
		$html = '<div id="select_box">';
		if (count($types) > 0)
		{
			foreach ($types as $typeid => $type) {
				$counttags = count($type['tags']);
				$rand = rand();
				/* Add the type */
				$html .= '<div class="select_box_parent" onClick="showBox('.$rand.')">'.JText::_('COM_REDSHOP_TYPE_LIST').' '.$type['type_name'].'</div>';
				$html .= '<div id="'.$rand.'" class="select_box_child';
				$html .= '">';
				/* Add the tags */
				if ($counttags > 0) {
					foreach ($type['tags'] as $tagid => $tag) {
						/* Check if the tag is selected */
						if (@in_array($tagid, $associationtags)) $selected = 'checked="checked"';
						else $selected = '';
						$html .= '<table><tr><td colspan="2"><input type="checkbox" class="select_box" '.$selected.' name="tag_id[]" value="'.$typeid.'.'.$tagid.'" />'.JText::_('COM_REDSHOP_TAG_LIST').' '.$tag['tag_name'];
						$html .= '</td></tr>';
						if (@array_key_exists($typeid.'.'.$tagid, $qs)) $qs_value = $qs[$typeid.'.'.$tagid]['quality_score'];
						else $qs_value = '';

						$html .= '<tr><td><span class="quality_score">'.JText::_('COM_REDSHOP_QUALITY_SCORE').'</span></td><td><input type="text" class="quality_score_input"  name="qs_id['.$typeid.'.'.$tagid.']" value="'.$qs_value.'" />';
						$html .= '</td></tr>';

						$html .= '<tr ><td colspan="2"><select name="sel_dep'.$typeid.'_'.$tagid.'[]" id="sel_dep'.$typeid.'_'.$tagid.'" multiple="multiple" size="10"  >';

						foreach($types as $sel_typeid => $sel_type)
						{
							if($typeid==$sel_typeid)
								continue;
							$dependent_tag = $model->getDependenttag($detail->product_id,$typeid,$tagid);

							$html .= '<optgroup label="'.$sel_type['type_name'].'">';
							foreach ($sel_type['tags'] as $sel_tagid => $sel_tag) {
								$selected = in_array($sel_tagid,$dependent_tag) ? "selected" : "";
								$html .= '<option value="'.$sel_tagid.'" '.$selected.' >'.$sel_tag['tag_name'].'</option>';
							}
							$html .= '</optgroup>';
						}
						$html .= '</select>&nbsp;<a href="#" onClick="javascript:add_dependency('.$typeid.','.$tagid.','.$detail->product_id.');" >'.JText::_('COM_REDSHOP_ADD_DEPENDENCY').'</a></td></tr></table>';
					}
				}
				$html .= '</div>';
			}
		}
		$html .= '</div>';
		$lists['tags'] = $html;

		$templates = $redTemplate->getTemplate("product");

		$manufacturers	= $model->getmanufacturers();

		$supplier	= $model->getsupplier();

		$post = JRequest::get('post');
		if(array_key_exists('product_category', $post))
		$productcats =$post['product_category'];
		else
		$productcats = $model->getproductcats();

		$attributes = $model->getattributes();

		$attributesSet = $model->getAttributeSetList();

		$product_category = new product_category();

		//merging select option in the select box
		$temps = array();
        $temps[0] = new stdClass;
		$temps[0]->template_id="0";
		$temps[0]->template_name=JText::_('COM_REDSHOP_SELECT');
		$templates=@array_merge($temps,$templates);


		//merging select option in the select box
		$supps = array();
        $supps[0] = new stdClass;
		$supps[0]->value="0";
		$supps[0]->text=JText::_('COM_REDSHOP_SELECT');
		$manufacturers=@array_merge($supps,$manufacturers);

		//merging select option in the select box
		$supps = array();
        $supps[0] = new stdClass;
		$supps[0]->value="0";
		$supps[0]->text=JText::_('COM_REDSHOP_SELECT');
		$supplier=@array_merge($supps,$supplier);

		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT_DETAIL' ),'redshop_products48');

		$document = JFactory::getDocument();

		$document->addScriptDeclaration ("

		var WANT_TO_DELETE = '".JText::_('COM_REDSHOP_DO_WANT_TO_DELETE')."';

		");

		$document->addScript ('components/'.$option.'/assets/js/fields.js');

		$document->addScript ('components/'.$option.'/assets/js/select_sort.js');

		$document->addScript ('components/'.$option.'/assets/js/json.js');

		$document->addScript ('components/'.$option.'/assets/js/validation.js');

		$document->addStyleSheet ( 'components/com_redshop/assets/css/search.css' );

		$document->addStyleSheet('components/com_redproductfinder/helpers/redproductfinder.css' );

		$document->addScript ('components/com_redshop/assets/js/search.js');

		$document->addScript ('components/com_redshop/assets/js/related.js');

		$uri = JFactory::getURI();

		$layout = JRequest::getVar('layout');

		if($layout == 'property_images'){

			$this->setLayout('property_images');

		}else if($layout == 'attribute_color'){

			$this->setLayout('attribute_color');

		}else if($layout == 'productstockroom'){

			$this->setLayout('productstockroom');

		}else{
			$this->setLayout('default');
		}

		$isNew = ($detail->product_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : $detail->product_name." - ".JText::_('COM_REDSHOP_EDIT' );

		JToolBarHelper::title(   JText::_('COM_REDSHOP_PRODUCT' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_products48' );

		if($detail->product_id > 0){
//			JToolBarHelper::addNewX('wrapper',JText::_('COM_REDSHOP_ADD_WRAPPER' ));
			JToolBarHelper::addNewX('prices',JText::_('COM_REDSHOP_ADD_PRICE_LBL' ));
		}

		JToolBarHelper::apply();

		JToolBarHelper::save();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

			//EDIT - check out the item
			$model->checkout( $user->get('id') );

			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
//--------------------- Accessory Product ---------------------------

		$model=  $this->getModel('product_detail');

		$accessory_product = array();
		if($detail->product_id)
			$accessory_product = $producthelper->getProductAccessory(0,$detail->product_id);
		$lists['accessory_product'] = $accessory_product;

		$navigator_product = array();
		if($detail->product_id)
			$navigator_product = $producthelper->getProductNavigator(0,$detail->product_id);
		$lists['navigator_product'] = $navigator_product;

		$lists['QUANTITY_SELECTBOX_VALUE']=$detail->quantity_selectbox_value;

		$result_related = $detail->product_id;

		$result = array();

		$lists['product_all'] 	= JHTML::_('select.genericlist',$result,  'product_all[]', 'class="inputbox" ondblclick="selectnone(this);" multiple="multiple"  size="15" style="width:200px;" ', 'value', 'text', 0 );

//------------------------- End ----------------------------------
		//--------------------- Related Product ---------------------------

		$related_product_data = $model->related_product_data($detail->product_id);

		$lists['related_product'] 	= JHTML::_('select.genericlist',$related_product_data,  'related_product[]', 'class="inputbox" onmousewheel="mousewheel_related(this);" ondblclick="selectnone_related(this);" multiple="multiple"  size="15" style="width:200px;" ', 'value', 'text', 0 );
		$result_related = $detail->product_id;

		$result = array();

		$lists['product_all_related'] 	= JHTML::_('select.genericlist',$result,  'product_all_related[]', 'class="inputbox" ondblclick="selectnone_related(this);" multiple="multiple"  size="15" style="width:200px;" ', 'value', 'text', 0 );

//------------------------- End ----------------------------------

		// for preselected
		if($detail->product_template =="")
		{
			$default_preselected= PRODUCT_TEMPLATE;
		 	$detail->product_template=$default_preselected;
		}
		// end
		$lists['product_template'] = JHTML::_('select.genericlist',$templates,'product_template','class="inputbox" size="1" onchange="set_dynamic_field(this.value,\''.$detail->product_id.'\',\'1,12,17\');"  ','template_id','template_name',$detail->product_template);

		$product_tax = $model->gettax();
		$temps = array();
        $temps[0] = new stdClass;
		$temps[0]->value="0";
		$temps[0]->text=JText::_('COM_REDSHOP_SELECT');
		$product_tax=@array_merge($temps,$product_tax);

		$lists['product_tax'] = JHTML::_('select.genericlist',$product_tax,'product_tax_id','class="inputbox" size="1"  ','value','text',$detail->product_tax_id);

		$categories = $product_category->list_all("product_category[]",0,$productcats,10,true,true);
		$lists['categories'] =$categories;
		$detail->first_selected_category_id = $productcats[0];

		$lists['manufacturers'] = JHTML::_('select.genericlist',$manufacturers,'manufacturer_id','class="inputbox" size="1" ','value','text',$detail->manufacturer_id);

		$lists['supplier'] = JHTML::_('select.genericlist',$supplier,'supplier_id','class="inputbox" size="1" ','value','text',$detail->supplier_id);

		$lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $detail->published );

		$lists['product_on_sale'] = JHTML::_('select.booleanlist','product_on_sale', 'class="inputbox"', $detail->product_on_sale );

		$lists['copy_attribute'] = JHTML::_('select.booleanlist','copy_attribute', 'class="inputbox"', 0);

		$lists['product_special'] = JHTML::_('select.booleanlist','product_special', 'class="inputbox"', $detail->product_special );

		$lists['product_download'] = JHTML::_('select.booleanlist','product_download', 'class="inputbox"', $detail->product_download );

		$lists['not_for_sale'] = JHTML::_('select.booleanlist','not_for_sale', 'class="inputbox"', $detail->not_for_sale );

		$lists['expired'] = JHTML::_('select.booleanlist','expired', 'class="inputbox"', $detail->expired );


		// for individual pre-order

		$preorder_data = $redhelper->getPreOrderByList();
		$lists['preorder'] = JHTML::_('select.genericlist',$preorder_data,'preorder','class="inputbox" size="1" ','value','text',$detail->preorder);


		// discount calculator
		$lists['use_discount_calc'] = JHTML::_('select.booleanlist','use_discount_calc', 'class="inputbox"', $detail->use_discount_calc );

		$option = array();
		$option[]   	= JHTML::_('select.option', '1', JText::_('COM_REDSHOP_RANGE'));
		$option[]   	= JHTML::_('select.option', '0', JText::_('COM_REDSHOP_PRICE_PER_PIECE'));
		$lists['use_range'] 	= JHTML::_('select.genericlist',$option,  'use_range', 'class="inputbox" size="1" ' , 'value', 'text',  $detail->use_range );
		//unset($option);
        $option = array();

		// calculation method

		$option[]   	= JHTML::_('select.option', '0',JText::_('COM_REDSHOP_SELECT'));
		$option[]   	= JHTML::_('select.option', 'volume', JText::_('COM_REDSHOP_VOLUME'));
		$option[]   	= JHTML::_('select.option', 'area', JText::_('COM_REDSHOP_AREA'));
		$option[]   	= JHTML::_('select.option', 'circumference', JText::_('COM_REDSHOP_CIRCUMFERENCE'));
		$lists['discount_calc_method'] 	= JHTML::_('select.genericlist',$option,  'discount_calc_method', 'class="inputbox" size="1" ' , 'value', 'text',  $detail->discount_calc_method );


		//unset($option);
        $option = array();

		// calculation UNIT

		$remove_format = JHtml::$formatOptions;

		$option[]   	= JHTML::_('select.option', 'mm', JText::_('COM_REDSHOP_MILLIMETER'));
		$option[]   	= JHTML::_('select.option', 'cm', JText::_('COM_REDSHOP_CENTIMETER'));
		$option[]   	= JHTML::_('select.option', 'm', JText::_('COM_REDSHOP_METER'));
		$lists['discount_calc_unit'] 	= JHTML::_('select.genericlist',$option,  'discount_calc_unit[]', 'class="inputbox" size="1" ' , 'value', 'text',  DEFAULT_VOLUME_UNIT );
		$lists['discount_calc_unit'] = str_replace ($remove_format['format.indent'], "", $lists['discount_calc_unit']);
		$lists['discount_calc_unit'] = str_replace ($remove_format['format.eol'], "", $lists['discount_calc_unit']);

		unset($option);

		/*$prod_types = array();
		$prod_types[] = JHTML::_('select.option',JText::_('COM_REDSHOP_REAL_PRODUCT'),JText::_('COM_REDSHOP_REAL_PRODUCT') );
		$prod_types[] = JHTML::_('select.option',JText::_('COM_REDSHOP_CONTAINER_PRODUCT'),JText::_('COM_REDSHOP_CONTAINER_PRODUCT'));
		$lists['product_type'] = JHTML::_('select.genericlist',$prod_types,'product_type','class="inputbox" size="1" ','value','text',$detail->product_type);*/

		$productVatGroup = $model->getVatGroup();
		$temps = array();
		$temps[0]->value="";
		$temps[0]->text=JText::_('COM_REDSHOP_SELECT');
		$productVatGroup=@array_merge($temps,$productVatGroup);

		if(DEFAULT_VAT_GROUP && !$detail->product_tax_group_id){
			$detail->product_tax_group_id = DEFAULT_VAT_GROUP;

		}

		$append_to_global_seo = array();
	//	$append_to_global_seo[] = JHTML::_('select.option','select',JText::_( 'SELECT') );
		$append_to_global_seo[] = JHTML::_('select.option','append',JText::_( 'COM_REDSHOP_APPEND_TO_GLOBAL_SEO'));
		$append_to_global_seo[] = JHTML::_('select.option','prepend',JText::_( 'COM_REDSHOP_PREPEND_TO_GLOBAL_SEO'));
		$append_to_global_seo[] = JHTML::_('select.option','replace',JText::_( 'COM_REDSHOP_REPLACE_TO_GLOBAL_SEO'));
		$lists['append_to_global_seo'] = JHTML::_('select.genericlist',$append_to_global_seo,'append_to_global_seo','class="inputbox" size="1" ','value','text',$detail->append_to_global_seo);


		$lists['product_tax_group_id'] = JHTML::_('select.genericlist',$productVatGroup,'product_tax_group_id','class="inputbox" size="1" ','value','text',$detail->product_tax_group_id);
		$prop_oprand = array();
		$prop_oprand[] = JHTML::_('select.option','select',JText::_('COM_REDSHOP_SELECT') );
		$prop_oprand[] = JHTML::_('select.option','+',JText::_('COM_REDSHOP_PLUS'));
		$prop_oprand[] = JHTML::_('select.option','=',JText::_('COM_REDSHOP_EQUAL'));
		$prop_oprand[] = JHTML::_('select.option','-',JText::_('COM_REDSHOP_MINUS'));
		//$lists['prop_oprand'] = JHTML::_('select.genericlist',$prop_oprand,'oprand','class="inputbox" size="1" ','value','text',$detail->oprand);

	//	$field = new extra_field();
	//	$list_field=$field->list_all_field(1, $detail->product_id); /// field_section 6 :Userinformations
	//	$lists['extra_field']=$list_field;

		$cat_in_sefurl=$model->catin_sefurl($detail->product_id);
		$lists['cat_in_sefurl'] = JHTML::_('select.genericlist',$cat_in_sefurl,'cat_in_sefurl','class="inputbox" size="1" ','value','text',$detail->cat_in_sefurl);

		$lists['attributes'] = $attributes;

		$temps = array();
		$temps[0]->value="";
		$temps[0]->text=JText::_('COM_REDSHOP_SELECT');

		$attributesSet=@array_merge($temps,$attributesSet);
		$lists['attributesSet'] = JHTML::_('select.genericlist',$attributesSet,'attribute_set_id','class="inputbox" size="1" ','value','text',@$detail->attribute_set_id);


		// reddesign
		if($CheckRedDesign)
		{
			$product_designtype = $model->getProductDesignType($detail->product_id);
			if(!empty($product_designtype)){
			$lists["product_designtype"] = $product_designtype;

			$designtype = $model->getDesignType();

			$optiondtype = array();
			$optiondtype[] = JHTML::_('select.option', '0',JText::_('COM_REDSHOP_SELECT'));
			for($i=0;$i<count($designtype);$i++)
				$optiondtype[] = JHTML::_('select.option', $designtype[$i]->designtype_id,$designtype[$i]->designtype_name);
			$lists["designlist"] = JHTML::_('select.genericlist',$optiondtype,  'designtype', 'class="inputbox" size="1" ', 'value', 'text' , $product_designtype[0]->designtype_id);


			// Edited 210110
			$lists['reddesign_enable'] = JHTML::_('select.booleanlist','reddesign_enable', 'class="inputbox"', $product_designtype[0]->reddesign_enable );


			$shoppergroup = new shoppergroup();
			$lists["shoppergrouplist"] = $shoppergroup->getshopperGroupListArray();
			}
		 }
		//var_dump($shoppergroup);
		// reddesign End
	 	// Product type selection
		 $product_type_opt = array();
		 $product_type_opt[] = JHTML::_('select.option','product',JText::_('COM_REDSHOP_PRODUCT') );

		 if($CheckRedDesign)
		 	$product_type_opt[] = JHTML::_('select.option','design',JText::_('COM_REDSHOP_DESIGN'));

		 $product_type_opt[] = JHTML::_('select.option','file',JText::_('COM_REDSHOP_FILE'));
		 $product_type_opt[] = JHTML::_('select.option','subscription',JText::_('COM_REDSHOP_SUBSCRIPTION'));

		if($detail->product_download==1)
			$detail->product_type = 'file';
		$lists["product_type"] = JHTML::_('select.genericlist',$product_type_opt,  'product_type', 'class="inputbox" size="1" onChange="changeProductDiv(this.value)" ', 'value', 'text' , $detail->product_type);

		$accountgroup = $redhelper->getEconomicAccountGroup();
		$op = array();
		$op[] = JHTML::_('select.option', '0',JText::_('COM_REDSHOP_SELECT'));
		$accountgroup = array_merge($op,$accountgroup);
		$lists["accountgroup_id"] = JHTML::_('select.genericlist',$accountgroup,  'accountgroup_id', 'class="inputbox" size="1" ', 'value', 'text' , $detail->accountgroup_id);

		// for downloadable products
		$productSerialDetail = $model->getProdcutSerialNumbers();

		$this->assignRef('model',		$model);
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('productSerialDetail',		$productSerialDetail);
		$this->assignRef('next_product',		$next_product);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
