<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Promotion;

defined('_JEXEC') or die;

/**
 * Discount Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Discount
{
    /**
     * @param $productData
     * @param $data
     *
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public static function discountCalculatorData($productData, $data)
    {
        $useDiscountCalculator = $productData->use_discount_calc;
        $discountCalcMethod    = $productData->discount_calc_method;
        $useRange              = $productData->use_range;
        $calcOutputs           = array();

        if ($useDiscountCalculator) {
            $discountCalc = self::discountCalculator($data);

            $calculatorPrice = $discountCalc['product_price'];
            $productNetPricesTax = $discountCalc['product_price_tax'];

            $discounts = array();
            if ($calculatorPrice) {
                $calcOutput          = "Type : " . $discountCalcMethod . "<br />";
                $calcOutputs['type'] = $discountCalcMethod;

                if ($useRange) {
                    $calcHeight        = @$data['calcHeight'];
                    $calcWidth         = @$data['calcWidth'];
                    $calcDepth         = @$data['calcDepth'];
                    $calcRadius        = @$data['calcRadius'];
                    $calcPricePerPiece = "";
                    $totalPiece        = "";
                } else {
                    $calcHeight        = @$productData->product_height;
                    $calcWidth         = @$productData->product_width;
                    $calcDepth         = @$productData->product_length;
                    $calcRadius        = @$data['calcRadius'];
                    $calcPricePerPiece = @$discountCalc['price_per_piece'];
                    $totalPiece        = @$discountCalc['total_piece'];
                }

                switch ($discountCalcMethod) {
                    case "volume":
                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_HEIGHT'
                            ) . " " . $calcHeight . "<br />";
                        $calcOutputs['calcHeight'] = $calcHeight;

                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_WIDTH'
                            ) . " " . $calcWidth . "<br />";
                        $calcOutputs['calcWidth']  = $calcWidth;

                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_LENGTH'
                            ) . " " . $calcDepth . "<br />";
                        $calcOutputs['calcDepth']  = $calcDepth;

                        if ($calcPricePerPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_PRICE_PER_PIECE'
                                ) . " " . $calcPricePerPiece . "<br />";
                            $calcOutputs['calcPricePerPiece'] = $calcDepth;
                        }

                        if ($totalPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_TOTAL_PIECE'
                                ) . " " . $totalPiece . "<br />";
                            $calcOutputs['totalPiece'] = $totalPiece;
                        }

                        break;

                    case "area":

                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_DEPTH'
                            ) . " " . $calcDepth . "<br />";
                        $calcOutputs['calcDepth'] = $calcDepth;

                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_WIDTH'
                            ) . " " . $calcWidth . "<br />";
                        $calcOutputs['calcWidth'] = $calcWidth;

                        if ($calcPricePerPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_PRICE_PER_PIECE'
                                ) . " " . $calcPricePerPiece . "<br />";
                            $calcOutputs['calcPricePerPiece'] = $calcDepth;
                        }

                        if ($totalPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_TOTAL_PIECE'
                                ) . " " . $totalPiece . "<br />";
                            $calcOutputs['totalPiece'] = $totalPiece;
                        }

                        break;

                    case "circumference":

                        $calcOutput .= JText::_(
                                'COM_REDSHOP_DISCOUNT_CALC_RADIUS'
                            ) . " " . $calcRadius . "<br />";
                        $calcOutputs['calcRadius'] = $calcRadius;

                        if ($calcPricePerPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_PRICE_PER_PIECE'
                                ) . " " . $calcPricePerPiece . "<br />";
                            $calcOutputs['calcPricePerPiece'] = $calcDepth;
                        }

                        if ($totalPiece != "") {
                            $calcOutput .= JText::_(
                                    'COM_REDSHOP_TOTAL_PIECE'
                                ) . " " . $totalPiece . "<br />";
                            $calcOutputs['totalPiece'] = $totalPiece;
                        }
                        break;
                }

                $calcOutput .= JText::_('COM_REDSHOP_DISCOUNT_CALC_UNIT') . " " . $data['calcUnit'];
                $calcOutputs['calcUnit'] = $data['calcUnit'];

                // Extra selected value data
                $calcOutput .= "<br />" . $discountCalc['pdcextra_data'];

                // Extra selected value ids
                $calcOutputs['calcextra_ids'] = $discountCalc['pdcextra_ids'];

                $discounts[] = $calcOutput;
                $discounts[] = $calcOutputs;
                $discounts[] = $calculatorPrice;
                $discounts[] = $productNetPricesTax;

                return $discounts;
            } else {
                return array();
            }
        }
    }

    /**
     * @param $get
     *
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public static function discountCalculator($get)
    {
        $productId = (int)$get['product_id'];

        $discountCalc = array();

        $productNetPrices = RedshopHelperProductPrice::getNetPrice($productId);

        $productPriceNoVat = $productNetPrices['product_price_novat'];

        $data = \Redshop\Product\Product::getProductById($productId);

        // Default calculation method
        $calcMethod = $data->discount_calc_method;

        // Default calculation unit
        $globalUnit = "m";

        // Use range or not
        $useRange = $data->use_range;

        $calcHeight = $get['calcHeight'];
        $calcWidth  = $get['calcWidth'];
        $calcLength = $get['calcDepth'];
        $calcRadius = $get['calcRadius'];
        $calcUnit   = trim($get['calcUnit']);

        $calcHeight = str_replace(",", ".", $calcHeight);
        $calcWidth  = str_replace(",", ".", $calcWidth);
        $calcLength = str_replace(",", ".", $calcLength);
        $calcRadius = $cartMiddleData = str_replace(",", ".", $calcRadius);
        $calcUnit   = $cartMiddleData = str_replace(",", ".", $calcUnit);

        // Convert unit using helper function
        $unit = \Redshop\Helper\Utility::getUnitConversation($globalUnit, $calcUnit);

        $calcHeight *= $unit;
        $calcWidth  *= $unit;
        $calcLength *= $unit;
        $calcRadius *= $unit;

        $productUnit = 1;

        if (!$useRange) {
            $productUnit = \Redshop\Helper\Utility::getUnitConversation(
                $globalUnit,
                Redshop::getConfig()->get(
                    'DEFAULT_VOLUME_UNIT'
                )
            );

            $productHeight   = $data->product_height * $productUnit;
            $productWidth    = $data->product_width * $productUnit;
            $productLength   = $data->product_length * $productUnit;
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
            $discountCalcData = self::getDiscountCalcData($finalArea, $productId);
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

            $discountCalcData    = array();
            $discountCalcData[0] = new stdClass;

            // Generating array
            $discountCalcData[0]->area_price         = $productPriceNoVat;
            $discountCalcData[0]->discount_calc_unit = $productUnit;
            $discountCalcData[0]->price_per_piece    = $totalProductPriceNoVat;
        }

        $areaPrice       = 0;
        $pricePerPieceTax = 0;

        if (count($discountCalcData)) {
            $areaPrice = $discountCalcData[0]->area_price;

            // Discount calculator extra price enhancement
            $pdcExtraId = $get['pdcextraid'];
            $pdcString  = $pdcIds = array();

            if (trim($pdcExtraId) != "") {
                $pdcExtraData = self::getDiscountCalcDataExtra($pdcExtraId);

                for ($pdc = 0, $countExtraField = count($pdcExtraData); $pdc < $countExtraField; $pdc++) {
                    $pdcExtraDatum = $pdcExtraData[$pdc];
                    $optionName    = $pdcExtraDatum->option_name;
                    $pdcPrice      = $pdcExtraDatum->price;
                    $pdcOprand     = $pdcExtraDatum->oprand;
                    $pdcExtraId    = $pdcExtraDatum->pdcextra_id;

                    $pdcString[] = $optionName . ' (' . $pdcOprand . ' ' . $pdcPrice . ' )';
                    $pdcIds[]    = $pdcExtraId;

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
            $checkTax = \Redshop\Template\Helper::isApplyAttributeVat();

            if ($useRange) {
                $displayFinalArea = $finalArea / ($unit * $unit);
                $pricePerPiece    = $areaPrice;

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

        $discountCalc['product_price']     = $pricePerPiece;
        $discountCalc['product_price_tax'] = $pricePerPieceTax;
        $discountCalc['pdcextra_data']     = "";

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

    /**
     * @param   int  $area
     * @param   int  $pid
     * @param   int  $areabetween
     *
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function getDiscountCalcData($area = 0, $pid = 0, $areabetween = 0)
    {
        $area = floatval($area);

        $db = \JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select("*")
            ->from($db->quoteName("#__redshop_product_discount_calc"))
            ->where($db->quoteName("product_id") . "=" . $db->quote((int)$pid))
            ->order("id ASC");

        if ($areabetween) {
            $query->where($db->q($area) . " BETWEEN " . $db->qn('area_start') . " AND " . $db->qn('area_end'));
        }

        if ($area) {
            $query->where($db->quoteName("area_start_converted") . "<=" . $db->q($area))
                ->where($db->quoteName("area_end_converted") . ">=" . $db->q($area));
        }

        $db->setQuery($query);
        $list = $db->loadObjectlist();

        return $list;
    }

    /**
     * @param   string  $pdcExtraIds
     * @param   int     $productId
     *
     * @return mixed
     * @since __DEPLOY_VERSION
     */
    public static function getDiscountCalcDataExtra($pdcExtraIds = "", $productId = 0)
    {
        return RedshopHelperCartDiscount::getDiscountCalcDataExtra($pdcExtraIds, $productId);
    }
}
