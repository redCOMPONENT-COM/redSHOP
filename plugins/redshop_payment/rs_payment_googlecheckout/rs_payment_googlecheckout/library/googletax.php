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
 * GoogleTaxRule
 */
class GoogleTaxRule
{

	var $tax_rate;

	var $world_area = false;
	var $country_codes_arr;
	var $postal_patterns_arr;
	var $state_areas_arr;
	var $zip_patterns_arr;
	var $country_area;

	function GoogleTaxRule()
	{
	}

	function SetWorldArea($world_area = true)
	{
		$this->world_area = $world_area;
	}

	function AddPostalArea($country_code, $postal_pattern = "")
	{
		$this->country_codes_arr[] = $country_code;
		$this->postal_patterns_arr[] = $postal_pattern;
	}

	function SetStateAreas($areas)
	{
		if (is_array($areas))
			$this->state_areas_arr = $areas;
		else
			$this->state_areas_arr = array($areas);
	}

	function SetZipPatterns($zips)
	{
		if (is_array($zips))
			$this->zip_patterns_arr = $zips;
		else
			$this->zip_patterns_arr = array($zips);
	}

	function SetCountryArea($country_area)
	{
		switch ($country_area)
		{
			case "CONTINENTAL_48":
			case "FULL_50_STATES":
			case "ALL":
				$this->country_area = $country_area;
				break;
			default:
				$this->country_area = "";
				break;
		}
	}
}

/*
 * GoogleDefaultTaxRule extends GoogleTaxRule
 */
class GoogleDefaultTaxRule extends GoogleTaxRule
{

	var $shipping_taxed = false;

	function GoogleDefaultTaxRule($tax_rate, $shipping_taxed = "false")
	{
		$this->tax_rate = $tax_rate;
		$this->shipping_taxed = $shipping_taxed;

		$this->country_codes_arr = array();
		$this->postal_patterns_arr = array();
		$this->state_areas_arr = array();
		$this->zip_patterns_arr = array();
	}
}

/*
 * GoogleAlternateTaxRule extends GoogleTaxRule
 */
class GoogleAlternateTaxRule extends GoogleTaxRule
{

	function GoogleAlternateTaxRule($tax_rate)
	{
		$this->tax_rate = $tax_rate;

		$this->country_codes_arr = array();
		$this->postal_patterns_arr = array();
		$this->state_areas_arr = array();
		$this->zip_patterns_arr = array();
	}

}

/*
 * GoogleAlternateTaxTable
 */
class GoogleAlternateTaxTable
{

	var $name;
	var $tax_rules_arr;
	var $standalone;

	function GoogleAlternateTaxTable($name = "", $standalone = "false")
	{
		if ($name != "")
		{
			$this->name = $name;
			$this->tax_rules_arr = array();
			$this->standalone = $standalone;
		}
	}

	function AddAlternateTaxRules($rules)
	{
		$this->tax_rules_arr[] = $rules;
	}
}

?>
