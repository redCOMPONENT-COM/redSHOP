<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

JLoader::import('redshop.library');

/**
 * PlgRedshop_ProductGoogle_Tag Class
 *
 * @since  1.0
 */
class PlgRedshop_ProductGoogle_Tag extends JPlugin
{
	/**
	 * @var  boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * onBeforeDisplayProduct - Replace {bundle_template}
	 *
	 * @param   string $templateContent Template content
	 * @param   object $params          Params
	 * @param   object $product         Product detail
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onBeforeDisplayProduct(&$templateContent, $params, $product)
	{
		$type = $this->params->get('type', 'json-ld');

		if ($type == 'microdata')
		{

		}
		elseif ($type == 'rdfa')
		{

		}
		else
		{
			$this->renderJSON($product);
		}
	}

	protected function renderJSON($product)
	{
		$document = JFactory::getDocument();

		/*

		"offers": {
			"@type": "Offer",
			"priceCurrency": "USD",
			"price": "119.99",
			"priceValidUntil": "2020-11-05",
			"itemCondition": "http://schema.org/UsedCondition",
			"availability": "http://schema.org/InStock",
			"seller": {
				"@type": "Organization",
		  "name": "Executive Objects"
		},
		"itemOffered" : "10"
		}
		*/

		$image = '';

		if (JFile::exists(REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image))
		{
			$image = JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image);
		}

		$data = array(
			"@context"    => "http://schema.org/",
			"@type"       => "Product",
			"name"        => $product->product_name,
			"image"       => $image,
			"description" => strip_tags($product->product_s_desc),
			"sku"         => $product->product_number
		);

		// Brand prepare
		$manufacturer = RedshopEntityManufacturer::getInstance($product->manufacturer_id);

		if ($manufacturer->isValid())
		{
			$data["brand"] = array(
				"@type" => "Thing",
				"name"  => $manufacturer->get('manufacturer_name')
			);
		}

		$productData = RedshopHelperProduct::getProductById($product->product_id);

		if (!empty($productData->count_rating))
		{
			$data['aggregateRating'] = array(
				'@type' => 'AggregateRating',
				'ratingValue' => round($productData->sum_rating / $productData->count_rating, 1),
				'reviewCount' => $productData->count_rating
			);
		}

		// Offer
	}
}
