<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtmlBehavior::modal();

$producthelper = productHelper::getInstance();
$extraField = extraField::getInstance();

$url = JURI::base();

$layout             = $this->input->getString('layout', '');
$relatedprd_id      = $this->input->getInt('relatedprd_id', 0);
$ajaxdetal_template = $producthelper->getAjaxDetailboxTemplate($this->data);

?>
	<script type="text/javascript" language="javascript">//var J=jQuery.noConflict();</script>
	<div style="clear:both"></div>
<?php
if (count($ajaxdetal_template) > 0)
{
	$ajaxdetal_templatedata = $ajaxdetal_template->template_desc;
	$data_add               = $ajaxdetal_templatedata;
	$data_add               = str_replace('{product_name}', $this->data->product_name, $data_add);

	if ($this->data->product_price != 0)
	{
		$data_add = str_replace('{product_price}', $this->data->product_price, $data_add);
	}
	else
	{
		$data_add = str_replace('{product_price}', " ", $data_add);
	}

	if (strstr($data_add, "{product_image}"))
	{
		if ($this->data->product_full_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $this->data->product_full_image))
		{
			$thumbUrl = RedShopHelperImages::getImagePath(
						$this->data->product_full_image,
						'',
						'thumb',
						'product',
						Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
						Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT'),
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
			$productsrcPath = "<a href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_full_image . "' title='" . $this->data->product_name . "' rel='lightbox[product7]'>";
			$productsrcPath .= "<img src='" . $thumbUrl . "'>";
			$productsrcPath .= "</a>";
			$data_add = str_replace('{product_image}', $productsrcPath, $data_add);
		}
		else
		{
			$data_add = str_replace('{product_image}', " ", $data_add);
		}
	}

	$count_no_user_field = 0;

	$extrafieldNames = $this->input->getString('extrafieldNames', '');
	$nextrafield     = $this->input->getInt('nextrafield', 1);

	$data                         = array();
	$data['property_data']        = $this->input->getString('property_data', '');
	$data['subproperty_data']     = $this->input->getString('subproperty_data', '');
	$data['accessory_data']       = $this->input->getString('accessory_data', '');
	$data['acc_quantity_data']    = $this->input->getString('acc_quantity_data', '');
	$data['acc_property_data']    = $this->input->getString('acc_property_data', '');
	$data['acc_subproperty_data'] = $this->input->getString('acc_subproperty_data', '');

	$selectAcc = $producthelper->getSelectedAccessoryArray($data);
	$selectAtt = $producthelper->getSelectedAttributeArray($data);

	$returnArr          = $producthelper->getProductUserfieldFromTemplate($data_add);
	$template_userfield = $returnArr[0];
	$userfieldArr       = $returnArr[1];

	if ($template_userfield != "")
	{
		$ufield = "";
		$cart   = $this->session->get('cart');

		if (isset($cart['idx']))
		{
			$idx = (int) ($cart['idx']);
		}

		$idx     = 0;
		$cart_id = '';

		for ($j = 0; $j < $idx; $j++)
		{
			if ($cart[$j]['product_id'] == $this->data->product_id)
			{
				$cart_id = $j;
			}
		}

		for ($ui = 0; $ui < count($userfieldArr); $ui++)
		{
			if (!$idx)
			{
				$cart_id = "";
			}

			$productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', $cart_id, 1, $this->data->product_id);
			$ufield .= $productUserFields[0];

			if ($productUserFields[0] != "")
			{
				$count_no_user_field++;
			}

			if ($nextrafield <= 0)
			{
				$data_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', '', $data_add);
				$data_add = str_replace('{' . $userfieldArr[$ui] . '}', '', $data_add);
			}
			else
			{
				if ($extrafieldNames)
				{
					$extrafieldName = @ explode(',', $extrafieldNames);

					if (!in_array($userfieldArr[$ui], $extrafieldName))
					{
						$data_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', '', $data_add);
						$data_add = str_replace('{' . $userfieldArr[$ui] . '}', '', $data_add);
					}
					else
					{
						$data_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $data_add);
						$data_add = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $data_add);
					}
				}
				else
				{
					$data_add = str_replace('{' . $userfieldArr[$ui] . '_lbl}', "", $data_add);
					$data_add = str_replace('{' . $userfieldArr[$ui] . '}', "", $data_add);
				}
			}
		}

		$productUserFieldsForm = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

		if ($ufield != "")
		{
			$data_add = str_replace("{if product_userfield}", $productUserFieldsForm, $data_add);
			$data_add = str_replace("{product_userfield end if}", "</form>", $data_add);
		}
		else
		{
			$data_add = str_replace("{if product_userfield}", "", $data_add);
			$data_add = str_replace("{product_userfield end if}", "", $data_add);
		}
	}
	else
	{
		$count_no_user_field = 0;
	}

	$childproduct = $producthelper->getChildProduct($this->data->product_id);

	if (count($childproduct) > 0 && Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 0)
	{
		$isChilds = true;
	}
	else
	{
		$isChilds = false;
	}

	// Get attribute Template data
	// Product attribute  Start
	$attributes_set = array();

	if ($this->data->attribute_set_id > 0)
	{
		$attributes_set = $producthelper->getProductAttribute(0, $this->data->attribute_set_id, 0, 1);
	}

	$attribute_template = $producthelper->getAttributeTemplate($data_add);
	$attributes         = $producthelper->getProductAttribute($this->data->product_id);
	$attributes         = array_merge($attributes, $attributes_set);
	$totalatt           = count($attributes);
	$data_add           = $producthelper->replaceAttributeData($this->data->product_id, 0, $relatedprd_id, $attributes, $data_add, $attribute_template, $isChilds, $selectAtt);

	// Product attribute  End

	// Product accessory Start /////////////////////////////////
	$accessory      = $producthelper->getProductAccessory(0, $this->data->product_id);
	$totalAccessory = count($accessory);

	$data_add = $producthelper->replaceAccessoryData($this->data->product_id, $relatedprd_id, $accessory, $data_add, $isChilds, $selectAcc);

	// Product accessory End /////////////////////////////////

	// Cart
	$data_add = $producthelper->replaceCartTemplate($this->data->product_id, $this->data->category_id, 0, $relatedprd_id, $data_add, $isChilds, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field);

	$data_add = $data_add . "<input type='hidden' name='isAjaxBoxOpen' id='isAjaxBoxOpen' value='" . $layout . "' />";

	echo eval("?>" . $data_add . "<?php ");
}
