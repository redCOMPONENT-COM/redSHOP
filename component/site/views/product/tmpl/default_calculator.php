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

$height = "<div class='form-group'>
                <div class='input-group'>
                    <span class='input-group-text' id='addon-height'>" . Text::_('COM_REDSHOP_HEIGHT') . "</span>
                    <input type='text' class='form-control' name='calc_height' id='calc_height' value='' 
                        aria-label='" . Text::_('COM_REDSHOP_HEIGHT') . "' aria-describedby='addon-height'>
                </div>
            </div>";

$width = "<div class='form-group'>
                <div class='input-group'>
                    <span class='input-group-text' id='addon-width'>" . Text::_('COM_REDSHOP_WIDTH') . "</span>
                    <input type='text' class='form-control' name='calc_width' id='calc_width' value='' 
                        aria-label='" . Text::_('COM_REDSHOP_WIDTH') . "' aria-describedby='addon-width'>
                </div>
            </div>";

$depth = "<div class='form-group'>
                <div class='input-group'>
                    <span class='input-group-text' id='addon-depth'>" . Text::_('COM_REDSHOP_LENGTH') . "</span>
                    <input type='text' class='form-control' name='calc_depth' id='calc_depth' value='' 
                        aria-label='" . Text::_('COM_REDSHOP_LENGTH') . "' aria-describedby='addon-depth'>
                </div>
            </div>";

$radius = "<div class='form-group'>
                <div class='input-group'>
                    <span class='input-group-text' id='addon-radius'>" . Text::_('COM_REDSHOP_RADIUS') . "</span>
                    <input type='text' class='form-control' name='calc_radius' id='calc_radius' value='' 
                        aria-label='" . Text::_('COM_REDSHOP_RADIUS') . "' aria-describedby='addon-radius'>
                </div>
            </div>";

switch ($calcMethod) {
    case "volume":
        $calcOutputOption  = $height;
        $calcOutputOption .= $width;
        $calcOutputOption .= $depth;
        break;

    case "area":
        $calcOutputOption  = $depth;
        $calcOutputOption .= $width;
        break;

    case "circumference":
        $calcOutputOption  = $radius;
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

    $pdcExtraOutput .= '<div class="form-group">
                            <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="pdc-extra" name="pdc_option_name[]" 
                                    onclick="discountCalculation(\'' . $this->pid . '\')" value="' . $pdcExtraId . '">
                            <label class="form-check-label" for="pdc-extra">' . $pdcString . '</label>
                        </div>
                        </div>';
}

$calcOutput =  '<div class="container discount-calculator-container">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group">
                                    <span id="discount_cal_final_price"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            ' . $calcOutputOption . '
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            ' . $pdcExtraOutput . '
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="calc_unit" id="calc_unit" value="" />
                            <input type="hidden" name="calc_method" id="calc_method" value="' . $calcMethod . '" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text" id="addon-unit">' . Text::_('COM_REDSHOP_UNIT') . '</span>
                                ' . $discountCalcUnit . '
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <div class="align-right">
                                <button type="button" class="btn btn-primary calculator-btn" name="calc_calculate" 
                                        id="calc_calculate" onclick="discountCalculation(\'' . $this->pid . '\')" />
                                    ' . Text::_('COM_REDSHOP_CALCULATE') . '
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

echo $calcOutput;