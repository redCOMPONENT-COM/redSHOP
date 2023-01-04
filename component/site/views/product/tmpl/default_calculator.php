<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$url = JURI::base();

// Check that we need to use discount calculator

// Default calculation method
$calcMethod = $this->data->discount_calc_method;

// Default calculation unit

// Calculation prices as per various area
$discount_calc_data = \Redshop\Promotion\Discount::getDiscountCalcData(0, $this->data->product_id);

// Calculation UNIT
$calcoption         = array();
// Tweak by Ronni START - comment out mm + m
// $calcoption[]       = JHTML::_('select.option', 'mm', JText::_('COM_REDSHOP_MILLIMETER'));
$calcoption[]       = JHTML::_('select.option', 'cm', JText::_('COM_REDSHOP_CENTIMETER'));
// $calcoption[]       = JHTML::_('select.option', 'm', JText::_('COM_REDSHOP_METER'));
// Tweak by Ronni END - comment out mm + m
$discountCalcUnit = JHTML::_('select.genericlist', $calcoption, 'discount_calc_unit', 'class="inputbox" size="1" ', 'value', 'text', Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));

unset($calcoption);

// Tweak by Ronni START - Remove <td><label>" . JText::_('COM_REDSHOP_HEIGHT') . "</label></td> + Add title and placeholder + <tr><td>&nbsp;
$height    = "<tr><td>
                <input type='text' title='" . JTEXT::_('COM_REDSHOP_HEIGHT') . "' 
                        placeholder='" . JText::_('COM_REDSHOP_HEIGHT') . "' name='calc_height' 
                        id='calc_height' value='' />
                </td></tr>";
$width     = "<tr><td>
                <input type='text' title='" . JTEXT::_('COM_REDSHOP_WIDTH_CM') . "' 
                        placeholder='" . JText::_('COM_REDSHOP_WIDTH_CM') . "' name='calc_width' 
                        id='calc_width' value='' />";
$depth     = "<input type='text' title='" . JTEXT::_('COM_REDSHOP_LENGTH_CM') . "' 
                        placeholder='" . JText::_('COM_REDSHOP_LENGTH_CM') . "' name='calc_depth' 
                        id='calc_depth' value='' /></td></tr>";
$radius    = "<tr><td><label>" . JText::_(
        'COM_REDSHOP_RADIUS'
    ) . "</label></td><td><input type='text' name='calc_radius' id='calc_radius' value='' /></td></tr>";
$calculate = "<tr><td>";
// Tweak by Ronni - Add Help tip popover
$calculate .= '<input type="button" name="calc_calculate" id="calc_calculate" onclick="discountCalculation(\'' . $this->pid . '\')" value="' . JText::_(
        'COM_REDSHOP_CALCULATE'
    ) . '" />
        <span class="hasPopover" title="' . JText::_('COM_REDSHOP_HELP_CALCULATE_TITLE') . '" 
                data-content="' . JText::_('COM_REDSHOP_HELP_CALCULATE_TIP') . '">
            ' . JText::_('COM_REDSHOP_HELP_CALCULATE') . '
        </span>
        </td></tr>';
$hiddenVar = "<tr><td colspan='2'><input type='hidden' name='calc_unit' id='calc_unit' value='' />
			  <input type='hidden' name='calc_method' id='calc_method' value='" . $calcMethod . "' /></td></tr>";

$calcOutput = "<table><tr><td colspan='2'><span id='discount_cal_final_price'></span></td></tr>";
// Tweak by Ronni START - Remove table items
$calcOutput .= "" . JText::_('COM_REDSHOP_UNIT') . "" . $discountCalcUnit . "";
// Tweak by Ronni END - Remove table items
switch ($calcMethod)
{
    case "volume":
	    $calcOutput .= $height;
	    $calcOutput .= $width;
	    $calcOutput .= $depth;
        break;

    case "area":
	    $calcOutput .= $depth;
	    $calcOutput .= $width;
        break;

    case "circumference":
	    $calcOutput .= $radius;
        break;
}

$pdcExtraOutput = "";
$pdcExtraDatas  = \Redshop\Promotion\Discount::getDiscountCalcDataExtra("", $this->data->product_id);

for ($p = 0, $pn = count($pdcExtraDatas); $p < $pn; $p++)
{
	$pdcExtraData = $pdcExtraDatas[$p];
	$optionName    = $pdcExtraData->option_name;
	$pdcExtraId    = $pdcExtraData->pdcextra_id;
	$pdcPrice       = $pdcExtraData->price;
	$pdcOprand      = $pdcExtraData->oprand;

	$pdcString = $optionName . ' (' . $pdcOprand . ' ' . $pdcPrice . ' )';

	$pdcExtraOutput .= "<tr>";
	$pdcExtraOutput .= '<td colspan="2">' . $pdcString . '<input type="checkbox" name="pdc_option_name[]" onclick="discountCalculation(\'' . $this->pid . '\')" value="' . $pdcExtraId . '"></td>';
	$pdcExtraOutput .= "</tr>";
}

$calcOutput .= $pdcExtraOutput . $calculate . $hiddenVar;
$calcOutput .= "</table>";

echo $calcOutput;
