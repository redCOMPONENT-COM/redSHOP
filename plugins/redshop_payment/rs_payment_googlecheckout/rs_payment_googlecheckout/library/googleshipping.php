<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/*
 * GoogleFlatRateShipping
 */
class GoogleFlatRateShipping
{
	var $price;
	var $name;
	var $type = "flat-rate-shipping";
	var $shipping_restrictions;

	function GoogleFlatRateShipping($name, $price)
	{
		$this->name = $name;
		$this->price = $price;
	}

	function AddShippingRestrictions($restrictions)
	{
		$this->shipping_restrictions = $restrictions;
	}
}
/*
 * GoogleMerchantCalculatedShipping
 */
class GoogleMerchantCalculatedShipping
{
	var $price;
	var $name;
	var $type = "merchant-calculated-shipping";
	var $shipping_restrictions;
	var $address_filters;

	function GoogleMerchantCalculatedShipping($name, $price)
	{
		$this->price = $price;
		$this->name = $name;
	}

	function AddShippingRestrictions($restrictions)
	{
		$this->shipping_restrictions = $restrictions;
	}

	function AddAddressFilters($filters)
	{
		$this->address_filters = $filters;
	}
}
/*
 * GoogleShippingFilters
 */
class GoogleShippingFilters
{
	var $allow_us_po_box = true;

	var $allowed_restrictions = false;
	var $excluded_restrictions = false;

	var $allowed_world_area = false;
	var $allowed_country_codes_arr;
	var $allowed_postal_patterns_arr;
	var $allowed_country_area;
	var $allowed_state_areas_arr;
	var $allowed_zip_patterns_arr;

	var $excluded_country_codes_arr;
	var $excluded_postal_patterns_arr;
	var $excluded_country_area;
	var $excluded_state_areas_arr;
	var $excluded_zip_patterns_arr;

	function GoogleShippingFilters()
	{
		$this->allowed_country_codes_arr = array();
		$this->allowed_postal_patterns_arr = array();
		$this->allowed_state_areas_arr = array();
		$this->allowed_zip_patterns_arr = array();

		$this->excluded_country_codes_arr = array();
		$this->excluded_postal_patterns_arr = array();
		$this->excluded_state_areas_arr = array();
		$this->excluded_zip_patterns_arr = array();
	}

	function SetAllowUsPoBox($allow_us_po_box = true)
	{
		$this->allow_us_po_box = $allow_us_po_box;
	}

	function SetAllowedWorldArea($world_area = true)
	{
		$this->allowed_restrictions = true;
		$this->allowed_world_area = $world_area;
	}

	// Allows
	function AddAllowedPostalArea($country_code, $postal_pattern = "")
	{
		$this->allowed_restrictions = true;
		$this->allowed_country_codes_arr[] = $country_code;
		$this->allowed_postal_patterns_arr[] = $postal_pattern;
	}

	function SetAllowedCountryArea($country_area)
	{
		switch ($country_area)
		{
			case "CONTINENTAL_48":
			case "FULL_50_STATES":
			case "ALL":
				$this->allowed_country_area = $country_area;
				$this->allowed_restrictions = true;
				break;
			default:
				$this->allowed_country_area = "";
				break;
		}
	}

	function SetAllowedStateAreas($areas)
	{
		$this->allowed_restrictions = true;
		$this->allowed_state_areas_arr = $areas;
	}

	function AddAllowedStateArea($area)
	{
		$this->allowed_restrictions = true;
		$this->allowed_state_areas_arr[] = $area;
	}

	function SetAllowedZipPattens($zips)
	{
		$this->allowed_restrictions = true;
		$this->allowed_zip_patterns_arr = $zips;
	}

	function AddAllowedZipPatten($zip)
	{
		$this->allowed_restrictions = true;
		$this->allowed_zip_patterns_arr[] = $zip;
	}

	// Excludes
	function AddExcludedPostalArea($country_code, $postal_pattern = "")
	{
		$this->excluded_restrictions = true;
		$this->excluded_country_codes_arr[] = $country_code;
		$this->excluded_postal_patterns_arr[] = $postal_pattern;
	}

	function SetExcludedStateAreas($areas)
	{
		$this->excluded_restrictions = true;
		$this->excluded_state_areas_arr = $areas;
	}

	function AddExcludedStateArea($area)
	{
		$this->excluded_restrictions = true;
		$this->excluded_state_areas_arr[] = $area;
	}

	function SetExcludedZipPatternsStateAreas($zips)
	{
		$this->excluded_restrictions = true;
		$this->excluded_zip_patterns_arr = $zips;
	}

	function SetExcludedZipPatternsStateArea($zip)
	{
		$this->excluded_restrictions = true;
		$this->excluded_zip_patterns_arr[] = $zip;
	}

	function SetExcludedCountryArea($country_area)
	{
		switch ($country_area)
		{
			case "CONTINENTAL_48":
			case "FULL_50_STATES":
			case "ALL":
				$this->excluded_country_area = $country_area;
				$this->excluded_restrictions = true;
				break;

			default:
				$this->excluded_country_area = "";
				break;
		}
	}
}
/*
 * GooglePickUp
 */
class GooglePickUp
{
	var $price;
	var $name;
	var $type = "pickup";

	function GooglePickUp($name, $price)
	{
		$this->price = $price;
		$this->name = $name;
	}
}
?>
