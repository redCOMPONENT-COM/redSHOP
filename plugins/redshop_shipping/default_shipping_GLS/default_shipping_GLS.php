<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
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
JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminShipping');

class  plgredshop_shippingdefault_shipping_GLS extends JPlugin
{
	public $payment_code = "default_shipping_GLS";

	public $classname    = "default_shipping_GLS";

	public $client       = '';

	public $errorMsg     = '';

	public $error        = 0;

	/**
	 * specific redform plugin parameters
	 *
	 * @var JParameter object
	 */
	function __construct( &$subject, $config = array() )
	{
		parent::__construct($subject, $config);

		$this->onlabels_GLSConnection();
	}

	public function onShowconfig()
	{
		return true;
	}

	function onWriteconfig($values)
	{
		return true;
	}

	function onlabels_GLSConnection()
	{
		$url = 'http://www.gls.dk/webservices_v2/wsPakkeshop.asmx?WSDL';

		try
		{
			$this->client = new SoapClient($url, array ("trace" => 1, "exceptions" => 1 ));
		}
		catch ( Exception $exception )
		{
			$this->error = 1;
			echo $this->errorMsg = "Unable to connect soap client";

			JError::raiseWarning(21, $exception->getMessage());
		}
	}

	function GetNearstParcelShops ($values)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$d ['street'] 	= $values->address;
			$d ['zipcode'] 	= $values->zipcode;
			$d ['Amount'] 	= 4;

			$Handle = $this->client->SearchNearestParcelShops(
				array(
					'street' => $d['street'],
					'zipcode' => $d['zipcode'],
					'Amount' => $d['Amount']
				)
			)->SearchNearestParcelShopsResult;

			return $this->ShopArray($Handle->parcelshops->PakkeshopData);
		}
		catch ( Exception $exception )
		{
			print("<p><i>error msg in GetNearstParcelShops" . $exception->getMessage() . "</i></p>");
			JError::raiseWarning(21, "GetNearstParcelShops:" . $exception->getMessage());
		}
	}

	function ShopArray($PakkeshopData)
	{
		$j              = 0;
		$shippinghelper = new shipping;
		$returnArr      = array();

		for ($i = 0; $i < count($PakkeshopData); $i++)
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

	function WeekdaysTime($Weekday)
	{
		$opningTime = Array();

		for ($i = 0; $i < count($Weekday); $i++)
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
			elseif($Weekday[$i]->day == 'Friday')
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

			$opningTime[] = "<B>" . $day . '</b> '
							. $Weekday[$i]->openAt->From . '-' . $Weekday[$i]->openAt->To;
		}

		$stropeningTime = implode('  ', $opningTime);

		return $stropeningTime;
	}

	function onListRates(&$d)
	{
		$shippinghelper = new shipping;
		$shippingrate   = array();
		$rate           = 0;
		$shipping       = $shippinghelper->getShippingMethodByClass($this->classname);
		$ratelist       = $shippinghelper->listshippingrates($shipping->element, $d['users_info_id'], $d);
		$countRate      = count($ratelist) >= 1 ? 1 : 0;

		for ($i = 0; $i < $countRate; $i++)
		{
			$rs                         = $ratelist[$i];
			$shippingRate               = $rs->shipping_rate_value;
			$rs->shipping_rate_value    = $shippinghelper->applyVatOnShippingRate($rs, $d['user_id']);
			$shippingVatRate            = $rs->shipping_rate_value - $shippingRate;
			$economic_displaynumber     = $rs->economic_displaynumber;
			$shipping_rate_id           = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $rs->shipping_rate_name . "|" . number_format($rs->shipping_rate_value, 2, '.', '') . "|" . $rs->shipping_rate_id . "|single|" . $shippingVatRate . '|' . $economic_displaynumber);
			$shippingrate[$rate]->text  = $rs->shipping_rate_name;
			$shippingrate[$rate]->value = $shipping_rate_id;
			$shippingrate[$rate]->rate  = $rs->shipping_rate_value;
			$shippingrate[$rate]->vat   = $shippingVatRate;
			$rate++;
		}

		return $shippingrate;
	}
}
