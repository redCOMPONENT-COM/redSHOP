<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$rsCarthelper = rsCarthelper::getInstance();

$url    = JURI::base();

// Check that we need to use discount calculator

// Default calculation method
$calcMethod = $this->data->discount_calc_method;

// Default calculation unit

// Calculation prices as per various area
$discount_calc_data = $rsCarthelper->getDiscountCalcData(0, $this->data->product_id);

// Calculation UNIT
$calcoption         = array();
$calcoption[]       = JHTML::_('select.option', 'mm', JText::_('COM_REDSHOP_MILLIMETER'));
$calcoption[]       = JHTML::_('select.option', 'cm', JText::_('COM_REDSHOP_CENTIMETER'));
$calcoption[]       = JHTML::_('select.option', 'm', JText::_('COM_REDSHOP_METER'));
$discount_calc_unit = JHTML::_('select.genericlist', $calcoption, 'discount_calc_unit', 'class="inputbox" size="1" ', 'value', 'text', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));
unset($calcoption);

$height    = "<tr><td><label>" . JText::_('COM_REDSHOP_HEIGHT') . "</label></td><td><input type='text' name='calc_height' id='calc_height' value='' /></td></tr>";
$width     = "<tr><td><label>" . JText::_('COM_REDSHOP_WIDTH') . "</label></td><td><input type='text' name='calc_width' id='calc_width' value='' /></td></tr>";
$depth     = "<tr><td><label>" . JText::_('COM_REDSHOP_LENGTH') . "</label></td><td><input type='text' name='calc_depth' id='calc_depth' value='' /></td></tr>";
$radius    = "<tr><td><label>" . JText::_('COM_REDSHOP_RADIUS') . "</label></td><td><input type='text' name='calc_radius' id='calc_radius' value='' /></td></tr>";
$calculate = "<tr><td>&nbsp;</td><td>";
$calculate .= '<input type="button" name="calc_calculate" id="calc_calculate" onclick="discountCalculation(\'' . $this->pid . '\')" value="' . JText::_('COM_REDSHOP_CALCULATE') . '" /></td></tr>';
$hiddenVar = "<tr><td colspan='2'><input type='hidden' name='calc_unit' id='calc_unit' value='' />
			  <input type='hidden' name='calc_method' id='calc_method' value='" . $calcMethod . "' /></td></tr>";

$calc_output = "<table><tr><td colspan='2'><span id='discount_cal_final_price'></span></td></tr>";

$calc_output .= "<tr><td><label>" . JText::_('COM_REDSHOP_UNIT') . "</label></td><td>" . $discount_calc_unit . "</td></tr>";

switch ($calcMethod)
{
	case "volume":
		$calc_output .= $height;
		$calc_output .= $width;
		$calc_output .= $depth;
		break;

	case "area":
		$calc_output .= $depth;
		$calc_output .= $width;
		break;

	case "circumference":
		$calc_output .= $radius;
		break;
}

$pdc_extra_output = "";
$pdc_extra_datas  = $rsCarthelper->getDiscountCalcDataExtra("", $this->data->product_id);

for ($p = 0, $pn = count($pdc_extra_datas); $p < $pn; $p++)
{
	$pdc_extra_data = $pdc_extra_datas[$p];
	$option_name    = $pdc_extra_data->option_name;
	$pdcextra_id    = $pdc_extra_data->pdcextra_id;
	$pdcprice       = $pdc_extra_data->price;
	$pdcoprand      = $pdc_extra_data->oprand;

	$pdcstring = $option_name . ' (' . $pdcoprand . ' ' . $pdcprice . ' )';

	$pdc_extra_output .= "<tr>";
	$pdc_extra_output .= '<td colspan="2">' . $pdcstring . '<input type="checkbox" name="pdc_option_name[]" onclick="discountCalculation(\'' . $this->pid . '\')" value="' . $pdcextra_id . '"></td>';
	$pdc_extra_output .= "</tr>";
}

$calc_output .= $pdc_extra_output . $calculate . $hiddenVar;
$calc_output .= "</table>";

echo $calc_output;
