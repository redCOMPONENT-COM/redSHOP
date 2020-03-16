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
 * Tags replacer for Change Ajax Cart Attribute
 *
 * @since  3.0
 */
class RedshopTagsSectionsChangeCartAttribute extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since 3.0
	 */
	public $tags = array(
		'{apply_button}',
		'{cancel_button}',
		'{change_attribute}'
	);

	/**
	 * Init function
	 * @return mixed|void
	 *
	 * @throws Exception
	 * @since 3.0
	 */
	public function init()
	{
	}

	/**
	 * Executing replace
	 * @return string
	 *
	 * @throws Exception
	 * @since 3.0
	 */
	public function replace()
	{
		$cart = $this->data['cart'];
		$cartIndex = $this->data['cartIndex'];
		$productId = $this->data['productId'];
		$product = \Redshop\Product\Product::getProductById($productId);

		// Checking for child products
		$childProduct = RedshopHelperProduct::getChildProduct($productId);

		if (count($childProduct) > 0) {
			$isChildren = true;
		} else {
			$isChildren = false;
		}

		// Product attribute  Start
		$attributeTemplate = '';

		if ($isChildren) {
			$attributes = array();
			$selectAtt = array(array(), array());
		} else {
			$attributesSet = array();

			if ($product->attribute_set_id > 0) {
				$attributesSet = \Redshop\Product\Attribute::getProductAttribute(
					0,
					$product->attribute_set_id,
					0,
					1
				);
			}

			$bool = (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE')) ? false : true;
			$attributeTemplate = \Redshop\Template\Helper::getAttribute($this->template, $bool);

			$attributeTemplate->template_desc = str_replace(
				"{property_image_scroller}",
				"",
				$attributeTemplate->template_desc
			);

			$attributeTemplate->template_desc = str_replace(
				"{subproperty_image_scroller}",
				"",
				$attributeTemplate->template_desc
			);

			$attributes = \Redshop\Product\Attribute::getProductAttribute($productId);
			$attributes = array_merge($attributes, $attributesSet);

			$selectAtt = \Redshop\Attribute\Helper::getSelectedCartAttributeArray($cart[$cartIndex]['cart_attribute']);
		}

		$totalAtt = count($attributes);
		$this->template = RedshopTagsReplacer::_(
			'attributes',
			$this->template,
			array(
				'productId' => $productId,
				'attributes' => $attributes,
				'attributeTemplate' => $attributeTemplate,
				'isChild' => $isChildren,
				'selectedAttributes' => $selectAtt,
			)
		);

		$cancelButton = RedshopLayoutHelper::render(
			'tags.common.button',
			array(
				'class' => 'btn',
				'attr' => 'name="cancel" onclick="javascript:cancelForm(this.form);"',
				'text' => JText::_('COM_REDSHOP_CANCEL')
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$applyButton = RedshopLayoutHelper::render(
			'tags.change_attribute.apply_button',
			array(
				'cartIndex' => $cartIndex,
				'productId' => $productId
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$this->addReplace('{apply_button}', $applyButton);
		$this->addReplace('{cancel_button}', $cancelButton);
		$this->addReplace('{change_attribute}', JText::_("COM_REDSHOP_CHANGE_ATTRIBUTE"));

		$this->template = RedshopLayoutHelper::render(
			'tags.common.form',
			array(
				'name' => 'frmchngAttribute',
				'id' => 'frmchngAttribute',
				'method' => 'post',
				'content' => $this->template
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		if ($totalAtt > 0) {
			$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);
		} else {
			return JText::_("COM_REDSHOP_NO_ATTRIBUTE_TO_CHANGE");
		}

		return parent::replace();
	}
}
