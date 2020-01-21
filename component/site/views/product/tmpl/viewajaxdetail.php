<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

$productHelper = productHelper::getInstance();

$url = JURI::base();

$layout             = $this->input->getString('layout', '');
$relatedprdId       = $this->input->getInt('relatedprd_id', 0);
$ajaxdetalTemplate = \Redshop\Template\Helper::getAjaxDetailBox($this->data);

?>
    <script type="text/javascript" language="javascript">//var J=jQuery.noConflict();</script>
    <div style="clear:both"></div>
<?php
if (null !== $ajaxdetalTemplate)
{
	$ajaxdetalTemplateData = $ajaxdetalTemplate->template_desc;
	$dataAdd               = $ajaxdetalTemplateData;
	$dataAdd               = str_replace('{product_name}', $this->data->product_name, $dataAdd);

	if ($this->data->product_price != 0)
	{
		$dataAdd = str_replace('{product_price}', $this->data->product_price, $dataAdd);
	}
	else
	{
		$dataAdd = str_replace('{product_price}', " ", $dataAdd);
	}

	if (strstr($dataAdd, "{product_image}"))
	{
		if ($this->data->product_full_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $this->data->product_full_image))
		{
			$thumbUrl = RedshopHelperMedia::getImagePath(
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
			$dataAdd = str_replace('{product_image}', $productsrcPath, $dataAdd);
		}
		else
		{
			$dataAdd = str_replace('{product_image}', " ", $dataAdd);
		}
	}

	$countNoUserField = 0;

	$extrafieldNames = $this->input->getString('extrafieldNames', '');
	$nextrafield     = $this->input->getInt('nextrafield', 1);

	$data                         = array();
	$data['property_data']        = $this->input->getString('property_data', '');
	$data['subproperty_data']     = $this->input->getString('subproperty_data', '');
	$data['accessory_data']       = $this->input->getString('accessory_data', '');
	$data['acc_quantity_data']    = $this->input->getString('acc_quantity_data', '');
	$data['acc_property_data']    = $this->input->getString('acc_property_data', '');
	$data['acc_subproperty_data'] = $this->input->getString('acc_subproperty_data', '');

	$selectAcc = $productHelper->getSelectedAccessoryArray($data);
	$selectAtt = $productHelper->getSelectedAttributeArray($data);

	$returnArr         = $productHelper->getProductUserfieldFromTemplate($dataAdd);
	$templateUserfield = $returnArr[0];
	$userfieldArr      = $returnArr[1];

	if ($templateUserfield != "")
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

			$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($userfieldArr[$ui], 12, '', $cart_id, 1, $this->data->product_id);
			$ufield .= $productUserFields[0];

			if ($productUserFields[0] != "")
			{
				$countNoUserField++;
			}

			if ($nextrafield <= 0)
			{
				$dataAdd = str_replace('{' . $userfieldArr[$ui] . '_lbl}', '', $dataAdd);
				$dataAdd = str_replace('{' . $userfieldArr[$ui] . '}', '', $dataAdd);
			}
			else
			{
				if ($extrafieldNames)
				{
					$extrafieldName = @ explode(',', $extrafieldNames);

					if (!in_array($userfieldArr[$ui], $extrafieldName))
					{
						$dataAdd = str_replace('{' . $userfieldArr[$ui] . '_lbl}', '', $dataAdd);
						$dataAdd = str_replace('{' . $userfieldArr[$ui] . '}', '', $dataAdd);
					}
					else
					{
						$dataAdd = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $dataAdd);
						$dataAdd = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $dataAdd);
					}
				}
				else
				{
					$dataAdd = str_replace('{' . $userfieldArr[$ui] . '_lbl}', "", $dataAdd);
					$dataAdd = str_replace('{' . $userfieldArr[$ui] . '}', "", $dataAdd);
				}
			}
		}

		$productUserFieldsForm = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

		if ($ufield != "")
		{
			$dataAdd = str_replace("{if product_userfield}", $productUserFieldsForm, $dataAdd);
			$dataAdd = str_replace("{product_userfield end if}", "</form>", $dataAdd);
		}
		else
		{
			$dataAdd = str_replace("{if product_userfield}", "", $dataAdd);
			$dataAdd = str_replace("{product_userfield end if}", "", $dataAdd);
		}
	}
	else
	{
		$countNoUserField = 0;
	}

	$childProduct = RedshopHelperProduct::getChildProduct($this->data->product_id);

	if (count($childProduct) > 0 && Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 0)
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
		$attributes_set = $productHelper->getProductAttribute(0, $this->data->attribute_set_id, 0, 1);
	}

	$attributeTemplate = \Redshop\Template\Helper::getAttribute($dataAdd);
	$attributes        = $productHelper->getProductAttribute($this->data->product_id);
	$attributes        = array_merge($attributes, $attributes_set);
	$totalatt          = count($attributes);
	$dataAdd           = $productHelper->replaceAttributeData($this->data->product_id, 0, $relatedprdId, $attributes, $dataAdd, $attributeTemplate, $isChilds, $selectAtt);

	// Product attribute  End

	// Product accessory Start /////////////////////////////////
	$accessory      = $productHelper->getProductAccessory(0, $this->data->product_id);
	$totalAccessory = count($accessory);

	$dataAdd = RedshopHelperProductAccessory::replaceAccessoryData($this->data->product_id, $relatedprdId, $accessory, $dataAdd, $isChilds, $selectAcc);

	// Product accessory End /////////////////////////////////

	// Cart
	$dataAdd = Redshop\Cart\Render::replace($this->data->product_id, $this->data->category_id, 0, $relatedprdId, $dataAdd, $isChilds, $userfieldArr, $totalatt, $totalAccessory, $countNoUserField);

	$dataAdd = $dataAdd . "<input type='hidden' name='isAjaxBoxOpen' id='isAjaxBoxOpen' value='" . $layout . "' />";

	echo eval("?>" . $dataAdd . "<?php ");
}
