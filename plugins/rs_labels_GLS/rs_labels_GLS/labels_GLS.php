<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/shipping.php';

class plgrs_labels_GLSlabels_GLS extends JPlugin
{
	public $client   = '';

	public $errorMsg = '';

	public $error    = 0;

	/**
	 * Constructor
	 *
	 * @param       $subject
	 * @param array $config
	 */
	public function __construct( &$subject, $config = array() )
	{
		parent::__construct($subject, $config);

		$this->onlabels_GLSConnection();
	}

	public function onlabels_GLSConnection()
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

	/**
	 * Get Parcel shops from around
	 *
	 * @param $values
	 *
	 * @return array|string
	 */
	public function GetNearstParcelShops($values)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$d['street']  = $values->address;
			$d['zipcode'] = $values->zipcode;
			$d['Amount']  = 4;

			$Handle = $this->client->SearchNearestParcelShops(
				array(
					'street'  => $d['street'],
					'zipcode' => $d['zipcode'],
					'Amount'  => $d['Amount']
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

	/**
	 * Prepares an array of shops
	 *
	 * @param $PakkeshopData
	 *
	 * @return array
	 */
	public function ShopArray($PakkeshopData)
	{
		$j              = 0;
		$returnArr      = array();

		for ($i = 0; $i < count($PakkeshopData); $i++)
		{
			$shopNUmber 			= $PakkeshopData[$i]->Number;
			$CompanyName 			= $PakkeshopData[$i]->CompanyName;
			$Streetname				= $PakkeshopData[$i]->Streetname;
			$ZipCode				= $PakkeshopData[$i]->ZipCode;
			$Telephone				= $PakkeshopData[$i]->Telephone;
			$CountryCodeISO3166A2 	= $PakkeshopData[$i]->CountryCodeISO3166A2;
			$CityName 	= $PakkeshopData[$i]->CityName;

			$stropeningTime = $this->WeekdaysTime($PakkeshopData[$i]->OpeningHours->Weekday);

			$shop_id = $shopNUmber . "|" . $CompanyName . "|" . $Streetname . "|" . $ZipCode . "|" . $CountryCodeISO3166A2 . "|" . $Telephone . "|" . $stropeningTime . "|" . $CityName;

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

	function WeekdaysTime($Weekday){

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

			$opningTime[] = "<B>" . $day . '</b> ' . $Weekday[$i]->openAt->From . '-' . $Weekday[$i]->openAt->To;
		}

		$stropeningTime = implode('  ', $opningTime);

		return $stropeningTime;
	}
}
