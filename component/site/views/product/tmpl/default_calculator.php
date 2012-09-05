<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
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

defined ('_JEXEC') or die ('restricted access');

$rsCarthelper = new rsCarthelper();

$product_id = JRequest::getInt('pid',0);

$Itemid = JRequest::getVar ( 'Itemid' );
$url = JURI::base();
// get Model
$model = $this->getModel('product');


// check that we need to use discount calculator

// default calculation method
$calcMethod = $this->data->discount_calc_method;

// default calculation unit
//$globalUnit = $this->data->discount_calc_unit;

// calculation prices as per various area
$discount_calc_data = $rsCarthelper->getDiscountCalcData(0,$this->data->product_id);

// calculation UNIT
$calcoption = array();
$calcoption[]   	= JHTML::_('select.option', 'mm', JText::_('MILLIMETER'));
$calcoption[]   	= JHTML::_('select.option', 'cm', JText::_('CENTIMETER'));
$calcoption[]   	= JHTML::_('select.option', 'm', JText::_('METER'));
$discount_calc_unit 	= JHTML::_('select.genericlist',$calcoption,  'discount_calc_unit', 'class="inputbox" size="1" ' , 'value', 'text',  DEFAULT_VOLUME_UNIT );
unset($calcoption);

$height = "<tr><td><label>".JText::_('HEIGHT')."</label></td><td><input type='text' name='calc_height' id='calc_height' value='' /></td></tr>";
$width = "<tr><td><label>".JText::_('WIDTH')."</label></td><td><input type='text' name='calc_width' id='calc_width' value='' /></td></tr>";
$depth = "<tr><td><label>".JText::_('LENGTH')."</label></td><td><input type='text' name='calc_depth' id='calc_depth' value='' /></td></tr>";
$radius = "<tr><td><label>".JText::_('RADIUS')."</label></td><td><input type='text' name='calc_radius' id='calc_radius' value='' /></td></tr>";
$calculate = "<tr><td>&nbsp;</td><td>";
$calculate .= '<input type="button" name="calc_calculate" id="calc_calculate" onclick="discountCalculation(\''.$product_id.'\')" value="'.JText::_('CALCULATE').'" /></td></tr>';
$hiddenVar = "<tr><td colspan='2'><input type='hidden' name='calc_unit' id='calc_unit' value='' />
			  <input type='hidden' name='calc_method' id='calc_method' value='".$calcMethod."' /></td></tr>";

$calc_output = "<table><tr><td colspan='2'><span id='discount_cal_final_price'></span></td></tr>";

$calc_output .= "<tr><td><label>".JText::_('UNIT')."</label></td><td>".$discount_calc_unit."</td></tr>";

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
$pdc_extra_datas = $rsCarthelper->getDiscountCalcDataExtra("",$this->data->product_id);
for($p=0;$p<count($pdc_extra_datas);$p++){

	$pdc_extra_data = $pdc_extra_datas[$p];
	$option_name = $pdc_extra_data->option_name;
	$pdcextra_id = $pdc_extra_data->pdcextra_id;
	$pdcprice = $pdc_extra_data->price;
	$pdcoprand = $pdc_extra_data->oprand;

	$pdcstring = $option_name.' ('.$pdcoprand.' '.$pdcprice.' )';

	$pdc_extra_output .= "<tr>";
	$pdc_extra_output .= '<td colspan="2">'.$pdcstring.'<input type="checkbox" name="pdc_option_name[]" onclick="discountCalculation(\''.$product_id.'\')" value="'.$pdcextra_id.'"></td>';
	$pdc_extra_output .= "</tr>";
}

$calc_output .= $pdc_extra_output.$calculate.$hiddenVar;
$calc_output .= "</table>";

echo $calc_output;

?>