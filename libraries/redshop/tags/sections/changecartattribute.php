
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
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsChangeCartAttribute extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since __DEPLOY_VERSION__
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
	 * @since __DEPLOY_VERSION__
	 */
	public function init()
	{
	}

	/**
	 * Executing replace
	 * @return string
	 *
	 * @throws Exception
	 * @since __DEPLOY_VERSION__
	 */
	public function replace()
	{
		$cart      = $this->data['cart'];
		$cartIndex = $this->data['cartIndex'];
		$productId = $this->data['productId'];
		$product   = \Redshop\Product\Product::getProductById($productId);

		// Checking for child products
		$childProduct = RedshopHelperProduct::getChildProduct($productId);

		if (count($childProduct) > 0)
		{
			$isChildren = true;
		}
		else
		{
			$isChildren = false;
		}

		// Product attribute  Start
		if ($isChildren)
		{
			$attributes = array();
			$selectAtt  = array(array(), array());
		}
		else
		{
			$attributesSet = array();

			if ($product->attribute_set_id > 0)
			{
				$attributesSet = \Redshop\Product\Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$bool                             = (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE')) ? false : true;
			$attributeTemplate                = \Redshop\Template\Helper::getAttribute($this->template, $bool);
			$attributeTemplate->template_desc = str_replace("{property_image_scroller}", "", $attributeTemplate->template_desc);
			$attributeTemplate->template_desc = str_replace("{subproperty_image_scroller}", "", $attributeTemplate->template_desc);
			$attributes                       = \Redshop\Product\Attribute::getProductAttribute($productId);
			$attributes                       = array_merge($attributes, $attributesSet);

			$selectAtt = \Redshop\Attribute\Helper::getSelectedCartAttributeArray($cart[$cartIndex]['cart_attribute']);
		}

		$totalAtt       = count($attributes);
		$this->template = RedshopHelperAttribute::replaceAttributeData($productId, 0, 0, $attributes, $this->template, $attributeTemplate, $isChildren, $selectAtt, 0);

		// Product attribute  End
		$stockAddToCart = "stockaddtocartprd_" . $productId;
		$pdAddToCart    = "pdaddtocartprd_" . $productId;

		$applyButton = RedshopLayoutHelper::render(
			'tags.common.button',
			array(
				'class' => 'btn btn-primary',
				'attr' => 'name="apply" onclick="javascript:submitChangeAttribute();"',
				'text' => JText::_('COM_REDSHOP_APPLY')
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$hiddenInput = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'task',
				'type' => 'hidden',
				'value' => 'changeAttribute'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$hiddenInput .= RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'cart_index',
				'type' => 'hidden',
				'value' => $cartIndex
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$hiddenInput .= RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'product_id',
				'type' => 'hidden',
				'value' => $productId
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$hiddenInput .= RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'view',
				'type' => 'hidden',
				'value' => 'cart'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$hiddenInput .= RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'requiedAttribute',
				'id' => 'requiedAttribute',
				'type' => 'hidden',
				'value' => '',
				'attr' => 'reattribute=""'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$hiddenInput .= RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'requiedProperty',
				'id' => 'requiedProperty',
				'type' => 'hidden',
				'value' => '',
				'attr' => 'reproperty=""'
			),
			'',
			RedshopLayoutHelper::$layoutOption
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

		$span = RedshopLayoutHelper::render(
			'tags.common.tag',
			array(
				'tag' => 'span',
				'id' => $stockAddToCart
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		/**
         * @TODO: In case there are many hidden input, better way to enhance performance is use only one layout for
         * replace - Will create task for enhancement later.
         * **/
		$span .= \RedshopLayoutHelper::render(
			'tags.common.tag',
			array(
				'tag' => 'span',
				'id' => $pdAddToCart,
				'text' => $applyButton . $hiddenInput
			),
			'',
			\RedshopLayoutHelper::$layoutOption
		);

		$this->addReplace('{apply_button}', $span);
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

		if ($totalAtt > 0)
		{
			$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

		}
		else
		{
			return JText::_("COM_REDSHOP_NO_ATTRIBUTE_TO_CHANGE");
		}

		return parent::replace();
	}
}
