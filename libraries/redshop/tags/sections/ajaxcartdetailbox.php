<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  2.1.5
 */
class RedshopTagsSectionsAjaxCartDetailBox extends RedshopTagsAbstract
{
	public $tags = array(
		'{product_name}',
		'{product_price}',
		'{product_image}'
	);

	public $input;

	public function init()
	{
		$this->input = JFactory::getApplication()->input;
	}

	public function replace()
	{
		$productHelper = productHelper::getInstance();
		$product          = $this->data['product'];
		$layout           = $this->input->getString('layout', '');
		$relatedprdId     = $this->input->getInt('relatedprd_id', 0);
		$productUserField = $productHelper->getProductUserfieldFromTemplate($this->template);
		$dataUserField    = $this->replaceUserField($product, $productUserField);
		$this->template   = $dataUserField['template'];
		$countNoUserField = $dataUserField['countNoUserField'];

		$this->addReplace('{product_name}', $product->product_name);

		if ($product->product_price != 0)
		{
			$htmlPrice = RedshopHelperProductPrice::formattedPrice($product->product_price);

			$tagPrice = RedshopLayoutHelper::render(
				'tags.common.price',
				array(
					'price' => $product->product_price,
					'htmlPrice' => $htmlPrice,
					'class' => 'product_price product_price' . $product->product_id
				),
				'',
				array(
					'component'  => 'com_redshop',
					'layoutType' => 'Twig',
					'layoutOf'   => 'library'
				)
			);
		}
		else
		{
			$tagPrice = '';
		}

		$this->addReplace('{product_price}', $tagPrice);

		if ($this->isTagExists('{product_image}'))
		{
			if ($product->product_full_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
			{
				$thumbUrl = RedshopHelperMedia::getImagePath(
					$product->product_full_image,
					'',
					'thumb',
					'product',
					Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
					Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);

				$productImage = RedshopLayoutHelper::render(
					'tags.product.image',
					array(
						'fullImage' => REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image,
						'title' => $product->product_name,
						'attr' => "rel='lightbox[product7]'",
						'thumbUrl' => $thumbUrl
					),
					'',
					array(
						'component'  => 'com_redshop',
						'layoutType' => 'Twig',
						'layoutOf'   => 'library'
					)
				);
			}
			else
			{
				$productImage = '';
			}

			$this->addReplace('{product_image}', $productImage);
		}

		$data                         = array();
		$data['property_data']        = $this->input->getString('property_data', '');
		$data['subproperty_data']     = $this->input->getString('subproperty_data', '');
		$data['accessory_data']       = $this->input->getString('accessory_data', '');
		$data['acc_quantity_data']    = $this->input->getString('acc_quantity_data', '');
		$data['acc_property_data']    = $this->input->getString('acc_property_data', '');
		$data['acc_subproperty_data'] = $this->input->getString('acc_subproperty_data', '');

		$selectAcc = $productHelper->getSelectedAccessoryArray($data);
		$selectAtt = $productHelper->getSelectedAttributeArray($data);

		$childProduct = RedshopHelperProduct::getChildProduct($product->product_id);

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

		if ($product->attribute_set_id > 0)
		{
			$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
		}

		$attributeTemplate = \Redshop\Template\Helper::getAttribute($this->template);
		$attributes        = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
		$attributes        = array_merge($attributes, $attributes_set);
		$totalatt          = count($attributes);
		$this->template    = RedshopHelperAttribute::replaceAttributeData($product->product_id, 0, $relatedprdId, $attributes, $this->template, $attributeTemplate, $isChilds, $selectAtt);

		// Product attribute  End

		// Product accessory Start /////////////////////////////////
		$accessory      = RedshopHelperAccessory::getProductAccessories(0, $product->product_id);
		$totalAccessory = count($accessory);

		$this->template = RedshopHelperProductAccessory::replaceAccessoryData($product->product_id, $relatedprdId, $accessory, $this->template, $isChilds, $selectAcc);

		// Product accessory End /////////////////////////////////

		// Cart
		$this->template = Redshop\Cart\Render::replace($product->product_id, $product->category_id, 0, $relatedprdId, $this->template, $isChilds, $productUserField[1], $totalatt, $totalAccessory, $countNoUserField);

		$hidden = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'isAjaxBoxOpen',
				'id' => 'isAjaxBoxOpen',
				'type' => 'hidden',
				'value' => $layout,
				'attr' => '',
				'class' => ''
			),
			'',
			array(
				'component'  => 'com_redshop',
				'layoutType' => 'Twig',
				'layoutOf'   => 'library'
			)
		);
		$this->template = $this->template . $hidden;

		return parent::replace();
	}

	public function replaceUserField($product, $productUserField)
	{
		$subTemplate = $this->getTemplateBetweenLoop('{if product_userfield}', '{product_userfield end if}');

		$template = $subTemplate['template'];

		$extrafieldNames = $this->input->getString('extrafieldNames', '');
		$nextrafield     = $this->input->getInt('nextrafield', 1);
		$countNoUserField = 0;

		$templateUserfield = $productUserField[0];
		$userfieldArr      = $productUserField[1];

		if ($templateUserfield != "")
		{
			$ufield = "";
			$cart   = RedshopHelperCartSession::getCart();

			$idx    = 0;
			$cartId = '';

			for ($j = 0; $j < $idx; $j++)
			{
				if ($cart[$j]['product_id'] == $product->product_id)
				{
					$cartId = $j;
				}
			}

			for ($ui = 0; $ui < count($userfieldArr); $ui++)
			{
				if (!$idx)
				{
					$cartId = "";
				}

				$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($userfieldArr[$ui], 12, '', $cartId, 1, $product->product_id);
				$ufield .= $productUserFields[0];

				if ($productUserFields[0] != "")
				{
					$countNoUserField++;
				}

				if ($nextrafield <= 0)
				{
					$this->replacements['{' . $userfieldArr[$ui] . '_lbl}'] = ' ';
					$this->replacements['{' . $userfieldArr[$ui] . '}']     = ' ';
				}
				else
				{
					if ($extrafieldNames)
					{
						$extrafieldName = @ explode(',', $extrafieldNames);

						if (!in_array($userfieldArr[$ui], $extrafieldName))
						{
							$this->replacements['{' . $userfieldArr[$ui] . '_lbl}'] = ' ';
							$this->replacements['{' . $userfieldArr[$ui] . '}']     = ' ';
						}
						else
						{
							$this->replacements['{' . $userfieldArr[$ui] . '_lbl}'] = $productUserFields[0];
							$this->replacements['{' . $userfieldArr[$ui] . '}']     = $productUserFields[1];
						}
					}
					else
					{
						$this->replacements['{' . $userfieldArr[$ui] . '_lbl}'] = ' ';
						$this->replacements['{' . $userfieldArr[$ui] . '}']     = ' ';
					}
				}
			}

			if ($ufield != "")
			{
				$template = RedshopLayoutHelper::render(
					'tags.product.userfieldform',
					array('content' => $template),
					'',
					array(
						'component'  => 'com_redshop',
						'layoutType' => 'Twig',
						'layoutOf'   => 'library'
					)
				);
			}
		}
		else
		{
			$countNoUserField = 0;
		}

		$template = $this->strReplace($this->replacements, $template);

		$template = $subTemplate['begin'] . $template . $subTemplate['end'];

		return array('template' => $template, 'countNoUserField' => $countNoUserField);
	}
}