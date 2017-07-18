<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */

JLoader::import('redshop.library');

/**
 * Class redSHOP Shipping - Bring
 *
 * @since  1.5
 */
class PlgRedshop_ShippingBring extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object $subject    The object to observe
	 * @param   array  $config     An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_shipping_bring', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Event on show configuration
	 *
	 * @param   object $shipping Shipping detail
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function onShowConfig($shipping)
	{
		if ($shipping->element != $this->_name)
		{
			return false;
		}

		// Load config
		include_once JPATH_ROOT . '/plugins/redshop_shipping/' . $this->_name . '/config/' . $this->_name . '.cfg.php';

		echo RedshopLayoutHelper::render(
			'config',
			array(
				'services' => array(
					'SERVICEPAKKE'   => 'SERVICEPAKKE',
					'A-POST'         => 'A-POST',
					'B-POST'         => 'B-POST',
					'PA_DOREN'       => 'PA_DOREN',
					'BPAKKE_DOR-DOR' => 'BPAKKE_DOR-DOR',
					'EKSPRESS09'     => 'EKSPRESS09'
				),
				'selected' => explode(',', BRING_SERVICE)
			),
			__DIR__ . '/layouts'
		);

		return true;
	}

	/**
	 * Event on write configuration
	 *
	 * @param   array $data Configuration data
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function onWriteConfig($data)
	{
		if ($data['element'] != $this->_name)
		{
			return true;
		}

		$configFile = JPATH_ROOT . '/plugins/redshop_shipping/' . $this->_name . '/config/' . $this->_name . '.cfg.php';

		$configs = array(
			"BRING_USERCODE"              => $data['BRING_USERCODE'],
			"BRING_SERVER"                => $data['BRING_SERVER'],
			"BRING_PATH"                  => $data['BRING_PATH'],
			"BRING_ZIPCODE_FROM"          => $data['BRING_ZIPCODE_FROM'],
			"BRING_PRICE_SHOW_WITHVAT"    => $data['BRING_PRICE_SHOW_WITHVAT'],
			"BRING_PRICE_SHOW_SHORT_DESC" => $data['BRING_PRICE_SHOW_SHORT_DESC'],
			"BRING_PRICE_SHOW_DESC"       => $data['BRING_PRICE_SHOW_DESC'],
			"BRING_USE_SHIPPING_BOX"      => $data['BRING_USE_SHIPPING_BOX'],
			"BRING_SERVICE"               => implode(',', $data['BRING_SERVICE'])
		);

		$config = "<?php\n";
		$config .= "defined('_JEXEC') or die;\n";

		foreach ($configs as $key => $value)
		{
			$config .= "define('$key', '$value');\n";
		}

		return JFile::write($configFile, $config);
	}

	/**
	 * Event run on list rates
	 *
	 * @param   array $data Data
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public function onListRates(&$data)
	{
		$shippingInformation = RedshopHelperShipping::getShippingAddress($data['users_info_id']);

		if (is_null($shippingInformation))
		{
			return array();
		}

		if (isset($shippingInformation->country_code))
		{
			$shippingInformation->country_2_code = RedshopHelperWorld::getCountryCode2($shippingInformation->country_code);
		}

		include_once JPATH_ROOT . '/plugins/redshop_shipping/' . $this->_name . '/config/' . $this->_name . '.cfg.php';
		$productHelper = productHelper::getInstance();

		// Conversation of weight ( ration )
		$unitRatio       = $productHelper->getUnitConversation('gram', Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'));
		$unitRatioVolume = $productHelper->getUnitConversation('inch', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));
		$totalDimension  = RedshopHelperShipping::getCartItemDimension();

		// Converting weight in pounds
		$orderWeight = $unitRatio != 0 ? $totalDimension['totalweight'] * $unitRatio : $totalDimension['totalweight'];

		if (BRING_USE_SHIPPING_BOX == '1')
		{
			$whereShippingBoxes = RedshopHelperShipping::getBoxDimensions($data['shipping_box_id']);
		}
		else
		{
			$whereShippingBoxes               = array();
			$productData                      = RedshopHelperShipping::getProductVolumeShipping();
			$whereShippingBoxes['box_length'] = $productData[2]['length'];
			$whereShippingBoxes['box_width']  = $productData[1]['width'];
			$whereShippingBoxes['box_height'] = $productData[0]['height'];
		}

		$shippingLength = $totalDimension['totallength'];
		$shippingHeight = $totalDimension['totalheight'];
		$shippingWidth  = $totalDimension['totalwidth'];
		$shippingVolume = $totalDimension['totalvolume'];

		if (is_array($whereShippingBoxes) && count($whereShippingBoxes) > 0 && $unitRatioVolume > 0)
		{
			$shippingLength = (int) ($whereShippingBoxes['box_length'] * $unitRatioVolume);
			$shippingWidth  = (int) ($whereShippingBoxes['box_width'] * $unitRatioVolume);
			$shippingHeight = (int) ($whereShippingBoxes['box_height'] * $unitRatioVolume);
		}

		$url    = $this->buildUrl($shippingInformation->zipcode, $shippingLength, $shippingWidth, $shippingHeight, $shippingVolume, $orderWeight);
		$xmlDoc = $this->loadData($url);

		if ($xmlDoc === false)
		{
			return array();
		}

		// Get shipping options that are selected as available in VM from XML response
		$bringProducts = $this->loadProducts($xmlDoc);

		return $this->populateShippingRates($bringProducts);
	}

	/**
	 * Method for build service url
	 *
	 * @param   string   $zipcode  Zip code
	 * @param   integer  $length   Length
	 * @param   integer  $width    Width
	 * @param   integer  $height   Height
	 * @param   integer  $volume   Volume
	 * @param   integer  $weight   Weight
	 *
	 * @return  string
	 */
	protected function buildUrl($zipcode, $length, $width, $height, $volume, $weight)
	{
		$length = !$length ? 1 : $length;
		$width  = !$width ? 1 : $width;
		$height = !$height ? 1 : $height;
		$volume = !$volume ? 1 : $volume;

		/*
		 * Determine weight in pounds and ounces send integer rounded down
		 * end cw733 fix
		 */
		$weight = floor($weight);

		// WeightInGrams=1500&from=7600&to=1407&length=30&width=40&height=40&volume=33&date=2009-2-3
		$query = 'from=' . BRING_ZIPCODE_FROM . '&to=' . substr($zipcode, 0, 5);

		$query = $weight ? $query . '&weightInGrams=' . $weight : $query;
		$query .= '&length=' . $length . '&width=' . $width . '&height=' . $height . '&volume=' . $volume;

		return "http://" . BRING_SERVER . BRING_PATH . "?" . $query;
	}

	/**
	 * Method for load xml from url
	 *
	 * @param   string $url URL
	 *
	 * @return  boolean|SimpleXMLElement
	 */
	protected function loadData($url = '')
	{
		if (empty($url) || !function_exists("curl_init"))
		{
			return false;
		}

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPGET, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$xmlResult = curl_exec($curl);
		$error     = curl_error($curl);

		if (!empty($error))
		{
			return false;
		}

		// Disable libxml errors and allow to fetch error information as needed
		libxml_use_internal_errors(true);
		curl_close($curl);

		return simplexml_load_string($xmlResult, 'SimpleXMLElement');
	}

	/**
	 * Method for prepare an list of products base on XML data.
	 *
	 * @param   SimpleXMLElement $data XML data
	 *
	 * @return  array
	 */
	protected function loadProducts(SimpleXMLElement $data)
	{
		$results = array();

		foreach ($data->Product as $oneProduct)
		{
			$bringProduct = new stdClass;

			$bringProduct->product_id   = (string) $oneProduct->ProductId;
			$bringProduct->product_name = $bringProduct->product_id;

			if ((string) $oneProduct->GuiInformation->ProductName)
			{
				$bringProduct->product_name = (string) $oneProduct->GuiInformation->ProductName;
			}

			if ((string) $oneProduct->GuiInformation->DescriptionText)
			{
				$bringProduct->product_desc = (string) $oneProduct->GuiInformation->DescriptionText;
			}

			if ((string) $oneProduct->GuiInformation->HelpText)
			{
				$bringProduct->product_desc1 = (string) $oneProduct->GuiInformation->HelpText;
			}

			$attributePrice                             = $oneProduct->Price->attributes();
			$bringProduct->currencyidentificationcode = (string) $attributePrice['currencyIdentificationCode'];
			$bringProduct->AmountWithoutVAT            = (string) $oneProduct->Price->PackagePriceWithoutAdditionalServices->AmountWithoutVAT;
			$bringProduct->AmountWithVAT               = (string) $oneProduct->Price->PackagePriceWithoutAdditionalServices->AmountWithVAT;
			$bringProduct->VAT                          = (string) $oneProduct->Price->PackagePriceWithoutAdditionalServices->VAT;
			$bringProduct->delivery                    = (string) $oneProduct->ExpectedDelivery->WorkingDays;

			$results[] = $bringProduct;
		}

		return $results;
	}

	/**
	 * Method for populate shipping rates
	 *
	 * @param   array  $products  Products
	 *
	 * @return  array
	 */
	protected function populateShippingRates($products = array())
	{
		if (empty($products))
		{
			return array();
		}

		$shippingName  = RedshopHelperShipping::getShippingMethodByClass($this->_name)->name;
		$bringServices = explode(',', BRING_SERVICE);
		$index         = 0;
		$results       = array();

		foreach ($products as $product)
		{
			if (!in_array($product->product_id, $bringServices))
			{
				continue;
			}

			$productName = $product->product_name;
			$currencyCode = $product->currencyidentificationcode;

			$rate = new stdClass;

			$rate->text      = $productName;
			$rate->vat       = RedshopHelperCurrency::convert($product->VAT, $currencyCode);
			$rate->shortdesc = (BRING_PRICE_SHOW_SHORT_DESC) ? $product->product_desc : '';
			$rate->desc      = (BRING_PRICE_SHOW_DESC) ? $product->product_desc1 : '';

			// Convert NOK currency to site currency
			$rate->rate = RedshopHelperCurrency::convert($product->AmountWithoutVAT, $currencyCode);
			$rate->value = RedshopShippingRate::encrypt(
				array(
					__CLASS__,
					$shippingName,
					$productName,
					number_format($rate->rate + $rate->vat, 2, '.', ''),
					$productName,
					'single',
					'0'
				)
			);

			if (BRING_PRICE_SHOW_WITHVAT)
			{
				$rate->rate = RedshopHelperCurrency::convert($product->AmountWithVAT, $currencyCode);
			}

			$results[$index] = $rate;
			$index++;
		}

		return $results;
	}

	/**
	 * Show all configuration parameters for this Shipping method
	 *
	 * @return  boolean  False when the Shipping method has no configration
	 */
	public function show_Configuration()
	{
		return true;
	}
}
