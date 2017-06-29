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

class  plgredshop_shippingdefault_shipping_gls extends JPlugin
{
	const SHIPPING_NAME = "default_shipping_gls";

	public $client       = '';

	public $errorMsg     = '';

	public $error        = 0;

	/**
	 * specific redform plugin parameters
	 *
	 * @var JParameter object
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		$this->onlabels_GLSConnection();
	}

	public function onlabels_GLSConnection()
	{
		$url = 'http://www.gls.dk/webservices_v4/wsShopFinder.asmx?WSDL';

		try
		{
			$this->client = new SoapClient($url, array("trace" => 1, "exceptions" => 1));
		}
		catch (Exception $exception)
		{
			$this->error = 1;

			echo $this->errorMsg = "Unable to connect soap client";

			JError::raiseWarning(21, $exception->getMessage());
		}
	}

	public function GetNearstParcelShops($values)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$Handle = $this->client->SearchNearestParcelShops(
				array(
					'street'           => (string) $values->address,
					'zipcode'          => (string) $values->zipcode,
					'countryIso3166A2' => Redconfiguration::getInstance()->getCountryCode2($values->country_code),
					'Amount'           => $this->params->get('amount_shop', 10)
				)
			)->SearchNearestParcelShopsResult;

			return $this->ShopArray($Handle->parcelshops->PakkeshopData);
		}
		catch (Exception $exception)
		{
			if ($exception->getMessage())
			{
				return "<p><i>" . $exception->getMessage() . "</i></p>";
			}

			return false;
		}
	}

	public function ShopArray($PakkeshopData)
	{
		$j              = 0;
		$returnArr      = array();

		for ($i = 0, $in = count($PakkeshopData); $i < $in; $i++)
		{
			$shopNUmber           = $PakkeshopData[$i]->Number;
			$CompanyName          = $PakkeshopData[$i]->CompanyName;
			$Streetname           = $PakkeshopData[$i]->Streetname;
			$ZipCode              = $PakkeshopData[$i]->ZipCode;
			$Telephone            = $PakkeshopData[$i]->Telephone;
			$CountryCodeISO3166A2 = $PakkeshopData[$i]->CountryCodeISO3166A2;
			$CityName             = $PakkeshopData[$i]->CityName;

			$stropeningTime       = $this->WeekdaysTime($PakkeshopData[$i]->OpeningHours->Weekday);
			$shop_id              = $shopNUmber . "|" . $CompanyName
									. "|" . $Streetname . "|" . $ZipCode
									. "|" . $CountryCodeISO3166A2
									. "|" . $Telephone . "|" . $stropeningTime
									. "|" . $CityName;

			$returnArr[$j]                       = new stdClass;
			$returnArr[$j]->shop_id              = $shop_id;
			$returnArr[$j]->Number               = $shopNUmber;
			$returnArr[$j]->CompanyName          = $CompanyName;
			$returnArr[$j]->Streetname           = $Streetname;
			$returnArr[$j]->ZipCode              = $ZipCode;
			$returnArr[$j]->Telephone            = $Telephone;
			$returnArr[$j]->openingTime          = $stropeningTime;
			$returnArr[$j]->CityName             = $CityName;
			$returnArr[$j]->CountryCodeISO3166A2 = $CountryCodeISO3166A2;

			$j++;
		}

		return  $returnArr;
	}

	public function WeekdaysTime($Weekday)
	{
		$opningTime = Array();

		for ($i = 0, $in = count($Weekday); $i < $in; $i++)
		{
			if ($Weekday[$i]->day == 'Monday')
			{
				$day = JText::_('MON');
			}
			elseif ($Weekday[$i]->day == 'Tuesday')
			{
				$day = JText::_('TUE');
			}
			elseif ($Weekday[$i]->day == 'Wednesday')
			{
				$day = JText::_('WED');
			}
			elseif ($Weekday[$i]->day == 'Thursday')
			{
				$day = JText::_('THU');
			}
			elseif ($Weekday[$i]->day == 'Friday')
			{
				$day = JText::_('FRI');
			}
			elseif ($Weekday[$i]->day == 'Saturday')
			{
				$day = JText::_('SAT');
			}
			elseif ($Weekday[$i]->day == 'Sunday')
			{
				$day = JText::_('SUN');
			}
			else
			{
				$day = $Weekday[$i]->day;
			}

			$opningTime[] = "<b>" . $day . '</b> '
							. $Weekday[$i]->openAt->From . '-' . $Weekday[$i]->openAt->To;
		}

		$stropeningTime = implode('  ', $opningTime);

		return $stropeningTime;
	}

	public function onListRates(&$d)
	{
		$shippinghelper = shipping::getInstance();
		$shippingrate   = array();
		$rate           = 0;
		$shipping       = $shippinghelper->getShippingMethodByClass(self::SHIPPING_NAME);
		$ratelist       = $shippinghelper->listshippingrates($shipping->element, $d['users_info_id'], $d);
		$countRate      = count($ratelist) >= 1 ? 1 : 0;

		for ($i = 0; $i < $countRate; $i++)
		{
			$rs                         = $ratelist[$i];
			$shippingRate               = $rs->shipping_rate_value;
			$rs->shipping_rate_value    = $shippinghelper->applyVatOnShippingRate($rs, $d);
			$shippingVatRate            = $rs->shipping_rate_value - $shippingRate;
			$economic_displaynumber     = $rs->economic_displaynumber;
			$shipping_rate_id           = RedshopShippingRate::encrypt(
											array(
												__CLASS__,
												$shipping->name,
												$rs->shipping_rate_name,
												number_format($rs->shipping_rate_value, 2, '.', ''),
												$rs->shipping_rate_id,
												'single',
												$shippingVatRate,
												$economic_displaynumber
											)
										);

			$shippingrate[$rate]        = new stdClass;
			$shippingrate[$rate]->text  = $rs->shipping_rate_name;
			$shippingrate[$rate]->value = $shipping_rate_id;
			$shippingrate[$rate]->rate  = $rs->shipping_rate_value;
			$shippingrate[$rate]->vat   = $shippingVatRate;

			$rate++;
		}

		return $shippingrate;
	}
}
