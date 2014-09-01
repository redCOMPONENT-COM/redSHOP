<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Check Attribute stock status and disable options
 *
 * @since  1.4
 */
class PlgRedshop_ProductAttributeStock extends JPlugin
{
	/**
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param   string  &$template  Current Template Object
	 * @param   object  &$params    Component Parameters
	 * @param   object  $product    Current Product Information
	 *
	 * @return  void
	 */
	public function onAfterDisplayProduct(&$template, &$params, $product)
	{
		JFactory::getDocument()->addScriptDeclaration('

			var jsonStockStatus = ' . json_encode(
					$this->isAttributeStockExists($product->product_id)
				) . ';

			var onchangePropertyDropdown = function(allarg){
				jQuery("select[id^=subproperty_id_prd_] option").each(function(index, option) {
					if (!jsonStockStatus[allarg[4]].subProperty[option.value] && option.value != 0)
					{
						option.disabled = true;
						option.text += " (udsolgt)";
						option.style = "font-style: italic;";
					}
				});
			};

			jQuery(document).ready(function(){

				jQuery("select[id^=property_id_prd_] option").each(function(index, option) {
					if (!jsonStockStatus[option.value] && option.value != 0)
					{
						option.disabled = true;
						option.text += " (udsolgt)";
						option.style = "font-style: italic;";
					}
				});
			});
		');
	}

	/**
	 * Get Attribute Stock for Property and Subproperty
	 *
	 * @param   integer  $productId  Product Information Id
	 *
	 * @return  array    Stock Information Array
	 */
	private function isAttributeStockExists($productId)
	{
		$stockroomHelper = new rsstockroomhelper;
		$producthelper   = new producthelper;
		$propertyStock   = array();
		$property        = $producthelper->getAttibuteProperty(0, 0, $productId);

		for ($i = 0; $i < count($property); $i++)
		{
			$propertyId = $property[$i]->property_id;

			$propertyStock[$propertyId] = $stockroomHelper->isStockExists(
											$propertyId,
											"property"
										);

			$subProperty = $producthelper->getAttibuteSubProperty(0, $propertyId);

			for ($j = 0; $j < count($subProperty); $j++)
			{
				$subPropertyId = $subProperty[$j]->subattribute_color_id;

				$propertyStock[$propertyId]['subProperty'][$subPropertyId] = $stockroomHelper->isStockExists(
																	$subPropertyId,
																	'subproperty'
																);
			}
		}

		return $propertyStock;
	}
}
