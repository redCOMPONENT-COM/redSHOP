<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

$url = JURI::base();

// Check that we need to use discount calculator

// Default calculation method
$calcMethod = $this->data->discount_calc_method;

// Default calculation unit

// Calculation prices as per various area
$discount_calc_data = \Redshop\Promotion\Discount::getDiscountCalcData(0, $this->data->product_id);

// Calculation UNIT
$calcoption       = array();
$calcoption[]     = HTMLHelper::_('select.option', 'mm', Text::_('COM_REDSHOP_MILLIMETER'));
$calcoption[]     = HTMLHelper::_('select.option', 'cm', Text::_('COM_REDSHOP_CENTIMETER'));
$calcoption[]     = HTMLHelper::_('select.option', 'm', Text::_('COM_REDSHOP_METER'));
$discountCalcUnit = HTMLHelper::_(
        'select.genericlist', 
        $calcoption, 
        'discount_calc_unit', 
        'class="inputbox" size="1" ', 
        'value', 
        'text', 
        Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT')
    );

unset($calcoption);

$height = "<div class='input-group'>
                <span class='input-group-text' id='addon-height'>" . Text::_('COM_REDSHOP_HEIGHT') . "</span>
                <input type='text' class='form-control' name='calc_height' id='calc_height' value='' 
                    placeholder='" . Text::_('COM_REDSHOP_HEIGHT') . "' aria-label='" . Text::_('COM_REDSHOP_HEIGHT') . "' 
                    aria-describedby='addon-height'>
            </div>";
/*
$height    = "<tr><td><label for='calc_height'>" . Text::_(
    'COM_REDSHOP_HEIGHT'
) . "</label></td><td><input type='text' class='form-control' name='calc_height' id='calc_height' value='' /></td></tr>";
*/
$width = "<div class='input-group'>
                <span class='input-group-text' id='addon-width'>" . Text::_('COM_REDSHOP_WIDTH') . "</span>
                <input type='text' class='form-control' name='calc_width' id='calc_width' value='' 
                    placeholder='" . Text::_('COM_REDSHOP_WIDTH') . "' aria-label='" . Text::_('COM_REDSHOP_WIDTH') . "' 
                    aria-describedby='addon-width'>
            </div>";
/*
$width     = "<tr><td><label>" . Text::_(
    'COM_REDSHOP_WIDTH'
) . "</label></td><td><input type='text' class='form-control' name='calc_width' id='calc_width' value='' /></td></tr>";
*/
$depth = "<div class='input-group'>
                <span class='input-group-text' id='addon-depth'>" . Text::_('COM_REDSHOP_LENGTH') . "</span>
                <input type='text' class='form-control' name='calc_depth' id='calc_depth' value='' 
                    placeholder='" . Text::_('COM_REDSHOP_LENGTH') . "' aria-label='" . Text::_('COM_REDSHOP_LENGTH') . "' 
                    aria-describedby='addon-depth'>
            </div>";
/*
$depth     = "<tr><td><label>" . Text::_(
    'COM_REDSHOP_LENGTH'
) . "</label></td><td><input type='text' class='form-control' name='calc_depth' id='calc_depth' value='' /></td></tr>";
*/
$radius = "<div class='input-group'>
                <span class='input-group-text' id='addon-radius'>" . Text::_('COM_REDSHOP_RADIUS') . "</span>
                <input type='text' class='form-control' name='calc_radius' id='calc_radius' value='' 
                    placeholder='" . Text::_('COM_REDSHOP_RADIUS') . "' aria-label='" . Text::_('COM_REDSHOP_RADIUS') . "' 
                    aria-describedby='addon-radius'>
            </div>";
/*
$radius    = "<tr><td><label>" . Text::_(
    'COM_REDSHOP_RADIUS'
) . "</label></td><td><input type='text' class='form-control' name='calc_radius' id='calc_radius' value='' /></td></tr>";
$calculate = "<tr><td>&nbsp;</td><td>";
*/
$calculate = '<div class="input-group">
                <button type="button" class="btn btn-primary calculator-btn" name="calc_calculate" 
                        id="calc_calculate" onclick="discountCalculation(\'' . $this->pid . '\')" />
                    ' . Text::_('COM_REDSHOP_CALCULATE') . '
                </button>
            </div>';
/*
$calculate .= '<input type="button" class="btn btn-primary calculator-btn" name="calc_calculate" 
    id="calc_calculate" onclick="discountCalculation(\'' . $this->pid . '\')" value="' . Text::_(
    'COM_REDSHOP_CALCULATE'
) . '" /></td></tr>';
*/
$hiddenVar = "<input type='hidden' name='calc_unit' id='calc_unit' value='' />
              <input type='hidden' name='calc_method' id='calc_method' value='" . $calcMethod . "' />";

$calcOutput = "<div class='form-group'>
                    <div class='input-group'><span id='discount_cal_final_price'></span></div>";

$calcOutput .= "<div class='input-group'><label>" . Text::_('COM_REDSHOP_UNIT') . "</label>" . $discountCalcUnit . "</div>";
/*
$calcOutput = "<table><tr><td colspan='2'><span id='discount_cal_final_price'></span></td></tr>";

$calcOutput .= "<tr><td><label>" . Text::_('COM_REDSHOP_UNIT') . "</label></td><td>" . $discountCalcUnit . "</td></tr>";
*/

switch ($calcMethod) {
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

for ($p = 0, $pn = count($pdcExtraDatas); $p < $pn; $p++) {
    $pdcExtraData = $pdcExtraDatas[$p];
    $optionName   = $pdcExtraData->option_name;
    $pdcExtraId   = $pdcExtraData->pdcextra_id;
    $pdcPrice     = $pdcExtraData->price;
    $pdcOprand    = $pdcExtraData->oprand;

    $pdcString = $optionName . ' (' . $pdcOprand . ' ' . $pdcPrice . ' )';

    $pdcExtraOutput .= '<div class="input-group">';
    $pdcExtraOutput .= '<div>' . $pdcString . ' <input type="checkbox" class="form-control" name="pdc_option_name[]" onclick="discountCalculation(\'' . $this->pid . '\')" value="' . $pdcExtraId . '"></div>';
    $pdcExtraOutput .= "</div>";
}

$calcOutput .= $pdcExtraOutput . $calculate . $hiddenVar;
$calcOutput .= "</div>";

echo $calcOutput;