<?php
/**
* @version		$Id: economic.php 2010-11-19 19:34:56Z ian $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.event.plugin');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'shipping.php');

class plgrs_labels_GLSlabels_GLS extends JPlugin
{
	var $client			= '';
	var $errorMsg		= '';
	var $error = 0;

	/**
	 * specific redform plugin parameters
	 *
	 * @var JParameter object
	 */
	function plgrs_labels_GLSlabels_GLS( &$subject, $config = array() )
	{
		parent::__construct( $subject, $config );
		$this->onlabels_GLSConnection();
	}
	function onlabels_GLSConnection()
	{
		$url = 'http://www.gls.dk/webservices_v2/wsPakkeshop.asmx?WSDL';
		try
		{
			$this->client = new SoapClient ( $url, array ("trace" => 1, "exceptions" => 1 ) );
		}
		catch ( Exception $exception )
		{
			$this->error = 1;
			echo $this->errorMsg = "Unable to connect soap client";
			JError::raiseWarning(21,$exception->getMessage());
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
			$d ['zipcode'] 	= $values->zipcode;//'5260';
			$d ['Amount'] 	= 15;

			$Handle = $this->client
							->SearchNearestParcelShops(
								array(
									'street' => $d['street'],
									'zipcode' => $d['zipcode'],
									'Amount' => $d ['Amount']
								)
							)
							->SearchNearestParcelShopsResult;

			return $this->ShopArray($Handle->parcelshops->PakkeshopData);

		} catch ( Exception $exception ) {
			//print("<p><i>error msg in SearchNearestParcelShops" . $exception->getMessage() . "</i></p>");
			//JError::raiseWarning(21,"SearchNearestParcelShops:".$exception->getMessage());
		}
	}

	function ShopArray($PakkeshopData)
	{
		$j=0;
		$shippinghelper = new shipping();
		$returnArr 		= array();
		for($i=0;$i<count($PakkeshopData);$i++)
		{
			$shopNUmber 			= $PakkeshopData[$i]->Number;
			$CompanyName 			= $PakkeshopData[$i]->CompanyName;
			$Streetname				= $PakkeshopData[$i]->Streetname;
			$ZipCode				= $PakkeshopData[$i]->ZipCode;
			$Telephone				= $PakkeshopData[$i]->Telephone;
			$CountryCodeISO3166A2 	= $PakkeshopData[$i]->CountryCodeISO3166A2;
			$CityName 	= $PakkeshopData[$i]->CityName;

			$stropeningTime = $this->WeekdaysTime($PakkeshopData[$i]->OpeningHours->Weekday);

			$shop_id =  $shopNUmber."|".$CompanyName."|".$Streetname."|".$ZipCode."|".$CountryCodeISO3166A2."|".$Telephone."|".$stropeningTime."|".$CityName ;
			$returnArr[$j]->shop_id				= $shop_id;
			$returnArr[$j]->Number				= $shopNUmber;
			$returnArr[$j]->CompanyName			= $CompanyName;
			$returnArr[$j]->Streetname			= $Streetname;
			$returnArr[$j]->ZipCode				= $ZipCode;
			$returnArr[$j]->Telephone			= $Telephone;
			$returnArr[$j]->openingTime			= $stropeningTime;
			$returnArr[$j]->CityName			= $CityName;
			$returnArr[$j]->CountryCodeISO3166A2	= $CountryCodeISO3166A2;

			$j++;
		}

		return  $returnArr;
	}

	function WeekdaysTime($Weekday){

		$opningTime = Array();
		for($i=0;$i<count($Weekday);$i++)
		{
			if($Weekday[$i]->day=='Monday')
			{
				$day = JText::_('MON');
			}else if($Weekday[$i]->day=='Tuesday')
			{
				$day = JText::_('TUE');
			}else if($Weekday[$i]->day=='Wednesday')
			{
				$day = JText::_('WED');
			}else if($Weekday[$i]->day=='Thursday')
			{
				$day = JText::_('THU');
			}else if($Weekday[$i]->day=='Friday')
			{
				$day = JText::_('FRI');
			}else if($Weekday[$i]->day=='Saturday')
			{
				$day = JText::_('SAT');
			}else if($Weekday[$i]->day=='Sunday')
			{
				$day = JText::_('SUN');
			}else{
				$day = $Weekday[$i]->day;
			}

			$opningTime[]	= "<B>".$day .'</b> '. $Weekday[$i]->openAt->From .'-'. $Weekday[$i]->openAt->To;
		}

		$stropeningTime = implode('  ',$opningTime);
		return $stropeningTime;
	}

}
?>

