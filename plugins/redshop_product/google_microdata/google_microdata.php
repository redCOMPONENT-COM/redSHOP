<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

JLoader::import('redshop.library');

/**
 * PlgRedshop_ProductGoogle_Microdata Class
 *
 * @since  1.0
 */
class PlgRedshop_ProductGoogle_Microdata extends JPlugin
{
	/**
	 * @var  boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * Example prepare redSHOP Product method
	 *
	 * @TODO: Need to unify onPrepareProduct and onBeforeDisplayProduct
	 *
	 * @param   string  $templateContent  The Product Template Data
	 * @param   object  $params           The product params
	 * @param   object  $product          The product object
	 *
	 * @return  void
	 */
	public function onPrepareProduct(&$templateContent, &$params, $product)
	{
		// Skip on product detail run since it use another event.
		if (JFactory::getApplication()->input->getCmd('view') == 'product')
		{
			return;
		}

		$type = $this->params->get('type', 'json-ld');

		if ($type == 'microdata')
		{
			$this->renderMicroData($templateContent, $product);
		}
		elseif ($type == 'rdfa')
		{
			$this->renderRDFa($templateContent, $product);
		}
		else
		{
			$this->renderJSON($templateContent, $product);
		}
	}

	/**
	 * onBeforeDisplayProduct on product view only.
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
			$this->renderMicroData($templateContent, $product);
		}
		elseif ($type == 'rdfa')
		{
			$this->renderRDFa($templateContent, $product);
		}
		else
		{
			$this->renderJSON($templateContent, $product);
		}
	}

	/**
	 * Method for prepare data
	 *
	 * @param   object $product Product data
	 *
	 * @return  Registry
	 *
	 * @since   1.0
	 */
	protected function prepareData($product)
	{
		if (empty($product))
		{
			return new Registry;
		}

		$image = '';

		if (JFile::exists(REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image))
		{
			$image = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image;
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
				'@type'       => 'AggregateRating',
				'ratingValue' => round($productData->sum_rating / $productData->count_rating, 1),
				'reviewCount' => $productData->count_rating
			);
		}

		$availability = "http://schema.org/OutOfStock";

		if (RedshopHelperStockroom::isStockExists($product->product_id, 'product'))
		{
			$availability = "http://schema.org/InStock";
		}
		elseif (RedshopHelperStockroom::isPreorderStockExists($product->product_id, 'product'))
		{
			$availability = "http://schema.org/PreOrder";
		}

		// Offer
		$data['offers'] = array(
			'@type'         => 'Offer',
			'priceCurrency' => Redshop::getConfig()->get('REDCURRENCY_SYMBOL'),
			'price'         => ($product->product_price < 0) ? 0.0 : $product->product_price,
			"availability"  => $availability
		);

		if ($product->product_on_sale)
		{
			$data['offers']['price']           = ($product->discount_price < 0) ? 0.0 : $product->discount_price;
			$data['offers']['priceValidUntil'] = date('Y-m-d', $product->discount_enddate);
		}

		return new Registry($data);
	}

	/**
	 * Method for render JSON
	 *
	 * @param   string  $template  Product template content
	 * @param   object  $product   Product data
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	protected function renderJSON(&$template, $product)
	{
		$template .= '<script type="application/ld+json">' . $this->prepareData($product)->toString() . '</script>';
	}

	/**
	 * Method for render Micro data format.
	 *
	 * @param   string  $template  Product template content
	 * @param   object  $product   Product data
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	protected function renderMicroData(&$template, $product)
	{
		$data = $this->prepareData($product)->toArray();

		if (empty($data))
		{
			return;
		}

		$html = '<div itemscope itemtype="http://schema.org/Product" class="hidden" style="visibility: hidden;">'
			. '<span itemprop="name">' . $data['name'] . '</span>'
			. '<span itemprop="description">' . $data['description'] . '</span>'
			. '<span itemprop="sku">' . $data['sku'] . '</span>';


		if (isset($data['brand']))
		{
			$html .= '<span itemprop="brand">' . $data['brand']['name'] . '</span>';
		}

		if (isset($data['image']))
		{
			$html .= '<img itemprop="image" src="' . $data['image'] . '" alt="' . $data['name'] . '" />';
		}

		if (isset($data['aggregateRating']))
		{
			$html .= '<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">'
				. '<span itemprop="ratingValue">' . $data['aggregateRating']['ratingValue'] . '</span>'
				. '<span itemprop="reviewCount">' . $data['aggregateRating']['reviewCount'] . '</span></span>';
		}

		if (isset($data['offers']))
		{
			$html .= '<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">'
				. '<meta itemprop="priceCurrency" content="' . $data['offers']['priceCurrency'] . '" />'
				. '<span itemprop="price">' . $data['offers']['price'] . '</span>'
				. '<link itemprop="availability" href="' . $data['offers']['availability'] . '"/>';

			if (isset($data['offers']['priceValidUntil']))
			{
				$html .= '<time itemprop="priceValidUntil" datetime="' . $data['offers']['priceValidUntil'] . '"></time>';
			}

			$html .= '</span>';
		}

		$html .= '</div>';

		$template .= $html;
	}

	/**
	 * Method for render RDFa format.
	 *
	 * @param   string  $template  Product template content
	 * @param   object  $product   Product data
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function renderRDFa(&$template, $product)
	{
		$data = $this->prepareData($product)->toArray();

		if (empty($data))
		{
			return;
		}

		$html = '<div vocab="http://schema.org/" typeof="Product" class="hidden" style="visibility: hidden;">'
			. '<span property="name">' . $data['name'] . '</span>'
			. '<span property="description">' . $data['description'] . '</span>'
			. '<span property="sku">' . $data['sku'] . '</span>';


		if (isset($data['brand']))
		{
			$html .= '<span property="brand">' . $data['brand']['name'] . '</span>';
		}

		if (isset($data['image']))
		{
			$html .= '<img property="image" src="' . $data['image'] . '" alt="' . $data['name'] . '" />';
		}

		if (isset($data['aggregateRating']))
		{
			$html .= '<span property="aggregateRating" typeof="AggregateRating">'
				. '<span property="ratingValue">' . $data['aggregateRating']['ratingValue'] . '</span>'
				. '<span property="reviewCount">' . $data['aggregateRating']['reviewCount'] . '</span>'
				. '</span>';
		}

		if (isset($data['offers']))
		{
			$html .= '<span property="offers" typeof="Offer">'
				. '<meta property="priceCurrency" content="' . $data['offers']['priceCurrency'] . '" />'
				. '<span property="price">' . $data['offers']['price'] . '</span>'
				. '<link property="availability" href="' . $data['offers']['availability'] . '"/>';

			if (isset($data['offers']['priceValidUntil']))
			{
				$html .= '<time property="priceValidUntil" datetime="' . $data['offers']['priceValidUntil'] . '"></time>';
			}

			$html .= '</span>';
		}

		$html .= '</div>';

		$template .= $html;
	}
}
