<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Promotion\Discount;

use Joomla\CMS\Language\Text;
use JText;
use Redshop;
use Redshop\Helper\Utility;
use Redshop\Product\Product;
use Redshop\Template\Helper;
use RedshopHelperProduct;
use RedshopHelperProductPrice;
use stdClass;

defined('_JEXEC') or die;

/**
 * Discount Helper
 *
 * @since 3.0
 */
class Calculation
{
    /**
     * @param $productData
     * @param $data
     *
     * @return array
     * @since  __DEPLOY_VERSION__
     */
    public static function productMeasurement($productData, $data)
    {
        $useDiscountCalculator = $productData->use_discount_calc;
        $discountCalcMethod = $productData->discount_calc_method;
        $useRange = $productData->use_range;
        $calculationOutputs = [];

        if ($useDiscountCalculator) {
            $discountCalc = self::discountCalculator($data);

            $calculatorPrice = $discountCalc['product_price'];
            $productNetPricesTax = $discountCalc['product_price_tax'];

            $discounts = [];
            if ($calculatorPrice) {
                $calcOutput = "Type : " . $discountCalcMethod . "<br />";
                $calculationOutputs['type'] = $discountCalcMethod;

                if ($useRange) {
                    $calcHeight = @$data['calcHeight'];
                    $calcWidth = @$data['calcWidth'];
                    $calcDepth = @$data['calcDepth'];
                    $calcRadius = @$data['calcRadius'];
                    $calcPricePerPiece = "";
                    $totalPiece = "";
                } else {
                    $calcHeight = @$productData->product_height;
                    $calcWidth = @$productData->product_width;
                    $calcDepth = @$productData->product_length;
                    $calcRadius = @$data['calcRadius'];
                    $calcPricePerPiece = @$discountCalc['price_per_piece'];
                    $totalPiece = @$discountCalc['total_piece'];
                }

                switch ($discountCalcMethod) {
                    case "volume":
                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_HEIGHT'
                            ) . " " . $calcHeight . "<br />";
                        $calculationOutputs['calcHeight'] = $calcHeight;

                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_WIDTH'
                            ) . " " . $calcWidth . "<br />";
                        $calculationOutputs['calcWidth'] = $calcWidth;

                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_LENGTH'
                            ) . " " . $calcDepth . "<br />";
                        $calculationOutputs['calcDepth'] = $calcDepth;

                        if ($calcPricePerPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_PRICE_PER_PIECE'
                                ) . " " . $calcPricePerPiece . "<br />";
                            $calculationOutputs['calcPricePerPiece'] = $calcDepth;
                        }

                        if ($totalPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_TOTAL_PIECE'
                                ) . " " . $totalPiece . "<br />";
                            $calculationOutputs['totalPiece'] = $totalPiece;
                        }

                        break;

                    case "area":
                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_DEPTH'
                            ) . " " . $calcDepth . "<br />";
                        $calculationOutputs['calcDepth'] = $calcDepth;

                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_WIDTH'
                            ) . " " . $calcWidth . "<br />";
                        $calculationOutputs['calcWidth'] = $calcWidth;

                        if ($calcPricePerPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_PRICE_PER_PIECE'
                                ) . " " . $calcPricePerPiece . "<br />";
                            $calculationOutputs['calcPricePerPiece'] = $calcDepth;
                        }

                        if ($totalPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_TOTAL_PIECE'
                                ) . " " . $totalPiece . "<br />";
                            $calculationOutputs['totalPiece'] = $totalPiece;
                        }

                        break;

                    case "circumference":
                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_RADIUS'
                            ) . " " . $calcRadius . "<br />";
                        $calculationOutputs['calcRadius'] = $calcRadius;

                        if ($calcPricePerPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_PRICE_PER_PIECE'
                                ) . " " . $calcPricePerPiece . "<br />";
                            $calculationOutputs['calcPricePerPiece'] = $calcDepth;
                        }

                        if ($totalPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_TOTAL_PIECE'
                                ) . " " . $totalPiece . "<br />";
                            $calculationOutputs['totalPiece'] = $totalPiece;
                        }
                        break;
                }

                $calcOutput .= JText::_('COM_REDSHOP_DISCOUNT_CALC_UNIT') . " " . $data['calcUnit'];
                $calcOutput .= JText::_('COM_REDSHOP_DISCOUNT_CALC_UNIT') . " " . $data['calcUnit'];
                $calculationOutputs['calcUnit'] = $data['calcUnit'];

                // Extra selected value data
                $calcOutput .= "<br />" . $discountCalc['pdcextra_data'];

                // Extra selected value ids
                $calculationOutputs['calcextra_ids'] = $discountCalc['pdcextra_ids'];

                $discounts[] = $calcOutput;
                $discounts[] = $calculationOutputs;
                $discounts[] = $calculatorPrice;
                $discounts[] = $productNetPricesTax;

                return $discounts;
            } else {
                return [];
            }
        }
    }

    /**
     * @param $get
     *
     * @return array
     * @since  __DEPLOY_VERSION__
     */
    public static function discountCalculator($get)
    {
        $productId = (int) $get['product_id'];

        $discountCalc = [];

        $productNetPrices = RedshopHelperProductPrice::getNetPrice($productId);

        $productPriceNoVat = $productNetPrices['product_price_novat'];

        $data = Product::getProductById($productId);

        // Default calculation method
        $calcMethod = $data->discount_calc_method;

        // Default calculation unit
        $globalUnit = "m";

        // Use range or not
        $useRange = $data->use_range;

        $calcHeight = $get['calcHeight'];
        $calcWidth = $get['calcWidth'];
        $calcLength = $get['calcDepth'];
        $calcRadius = $get['calcRadius'];
        $calcUnit = trim($get['calcUnit']);

        $calcHeight = str_replace(",", ".", $calcHeight);
        $calcWidth = str_replace(",", ".", $calcWidth);
        $calcLength = str_replace(",", ".", $calcLength);
        $calcRadius = $cartMiddleData = str_replace(",", ".", $calcRadius);
        $calcUnit = $cartMiddleData = str_replace(",", ".", $calcUnit);

        // Convert unit using helper function
        $unit = Utility::getUnitConversation($globalUnit, $calcUnit);

        $calcHeight *= $unit;
        $calcWidth *= $unit;
        $calcLength *= $unit;
        $calcRadius *= $unit;

        $productUnit = 1;

        if (!$useRange) {
            $productUnit = Utility::getUnitConversation(
                $globalUnit,
                Redshop::getConfig()->get(
                    'DEFAULT_VOLUME_UNIT'
                )
            );

            $productHeight = $data->product_height * $productUnit;
            $productWidth = $data->product_width * $productUnit;
            $productLength = $data->product_length * $productUnit;
            $productDiameter = $data->product_diameter * $productUnit;
        }

        $area = 0;

        switch ($calcMethod) {
            case "volume":

                $area = $calcHeight * $calcWidth * $calcLength;

                if (!$useRange) {
                    $productArea = $productHeight * $productWidth * $productLength;
                }
                break;

            case "area":
                $area = $calcLength * $calcWidth;

                if (!$useRange) {
                    $productArea = $productLength * $productWidth;
                }
                break;

            case "circumference":

                $area = 2 * PI * $calcRadius;

                if (!$useRange) {
                    $productArea = PI * $productDiameter;
                }
                break;
        }

        $finalArea = $area;

        if ($useRange) {
            $finalArea = number_format($finalArea, 8, '.', '');

            // Calculation prices as per various area
            $discountCalcData = \Redshop\Promotion\Discount::getDiscountCalcData($finalArea, $productId);
        } else {
            // Shandard size of product
            $finalProductArea = $productArea;

            // Total sheet calculation
            if ($finalProductArea <= 0) {
                $finalProductArea = 1;
            }
            $totalSheet = $finalArea / $finalProductArea;

            // Returns the next highest integer value by rounding up value if necessary.
            if (isset($data->allow_decimal_piece) && $data->allow_decimal_piece) {
                $totalSheet = ceil($totalSheet);
            }

            // If sheet is less than 0 or equal to 0 than
            if ($totalSheet <= 0) {
                $totalSheet = 1;
            }

            // Product price of all sheets
            $totalProductPriceNoVat = $totalSheet * $productPriceNoVat;

            $discountCalcData = array();
            $discountCalcData[0] = new stdClass;

            // Generating array
            $discountCalcData[0]->area_price = $productPriceNoVat;
            $discountCalcData[0]->discount_calc_unit = $productUnit;
            $discountCalcData[0]->price_per_piece = $totalProductPriceNoVat;
        }

        $areaPrice = 0;
        $pricePerPieceTax = 0;

        if (count($discountCalcData)) {
            $areaPrice = $discountCalcData[0]->area_price;

            // Discount calculator extra price enhancement
            $pdcExtraId = $get['pdcextraid'];
            $pdcString = $pdcIds = array();

            if (trim($pdcExtraId) != "") {
                $pdcExtraData = \Redshop\Promotion\Discount::getDiscountCalcDataExtra($pdcExtraId);

                for ($pdc = 0, $countExtraField = count($pdcExtraData); $pdc < $countExtraField; $pdc++) {
                    $pdcExtraDatum = $pdcExtraData[$pdc];
                    $optionName = $pdcExtraDatum->option_name;
                    $pdcPrice = $pdcExtraDatum->price;
                    $pdcOprand = $pdcExtraDatum->oprand;
                    $pdcExtraId = $pdcExtraDatum->pdcextra_id;

                    $pdcString[] = $optionName . ' (' . $pdcOprand . ' ' . $pdcPrice . ' )';
                    $pdcIds[] = $pdcExtraId;

                    switch ($pdcOprand) {
                        case "+":
                            $areaPrice += $pdcPrice;
                            break;
                        case "-":
                            $areaPrice -= $pdcPrice;
                            break;
                        case "%":
                            $areaPrice *= 1 + ($pdcPrice / 100);
                            break;
                    }
                }
            }

            // Applying TAX
            $checkTax = Helper::isApplyAttributeVat();

            if ($useRange) {
                $displayFinalArea = $finalArea / ($unit * $unit);
                $pricePerPiece = $areaPrice;

                $pricePerPieceTax = RedshopHelperProduct::getProductTax($productId, $pricePerPiece, 0, 1);

                echo $displayFinalArea . "\n";

                echo $areaPrice . "\n";

                echo $pricePerPiece . "\n";

                echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

                echo JText::_('COM_REDSHOP_PRICE_PER_AREA') . "\n";

                echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

                echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

                echo $pricePerPieceTax . "\n";
                echo $checkTax . "\n";
            } else {
                $pricePerPiece = $discountCalcData[0]->price_per_piece;

                $pricePerPieceTax = RedshopHelperProduct::getProductTax($productId, $pricePerPiece, 0, 1);

                echo $area . "<br />" . JText::_('COM_REDSHOP_TOTAL_PIECE') . $totalSheet . "\n";

                echo $areaPrice . "\n";

                echo $pricePerPiece . "\n";

                echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

                echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

                echo JText::_('COM_REDSHOP_PRICE_OF_ALL_PIECE') . "\n";

                echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

                echo $pricePerPieceTax . "\n";
                echo $checkTax . "\n";
            }
        } else {
            $pricePerPiece = false;
            echo "fail";
        }

        $discountCalc['product_price'] = $pricePerPiece;
        $discountCalc['product_price_tax'] = $pricePerPieceTax;
        $discountCalc['pdcextra_data'] = "";

        if (isset($pdcString) && count($pdcString) > 0) {
            $discountCalc['pdcextra_data'] = implode("<br />", $pdcString);
        }

        $discountCalc['pdcextra_ids'] = '';

        if (isset($pdcIds) && (count($pdcIds) > 0)) {
            $discountCalc['pdcextra_ids'] = implode(",", $pdcIds);
        }

        if (isset($totalSheet)) {
            $discountCalc['total_piece'] = $totalSheet;
        }

        $discountCalc['price_per_piece'] = $areaPrice;

        return $discountCalc;
    }
}