<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Statistic Order controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Statistic Order
 * @since       2.0.0.3
 */
class RedshopControllerStatistic_Order extends RedshopControllerAdmin
{
    /**
     * Export orders CSV.
     *
     * @return  mixed.
     *
     * @since   2.0.0.3
     */
    public function exportOrder()
    {
        $model        = $this->getModel();
        $data         = $model->getItems();
        $noProducts   = $model->countProductByOrder();
        $productCount = array();

        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: text/x-csv");
        header("Content-type: text/csv");
        header("Content-type: application/csv");
        header('Content-Disposition: attachment; filename=Order.csv');

        for ($i = 0, $in = count($data); $i < $in; $i++) {
            $productCount[] = $noProducts[$i]->noproduct;
        }

        $noProducts = max($productCount);

        ob_clean();

        echo "Order number, Order status, Order date , Shipping method , Shipping user, Shipping address,";
        echo "Shipping postalcode,Shipping city, Shipping country, Company name, Email ,Billing address,";
        echo "Billing postalcode, Billing city, Billing country,Billing User ,";

        for ($i = 1; $i <= $noProducts; $i++) {
            echo Text::_('COM_REDSHOP_PRODUCT_NAME') . $i . ' ,';
            echo Text::_('COM_REDSHOP_PRODUCT') . ' ' . Text::_('COM_REDSHOP_PRODUCT_PRICE') . $i . ' ,';
            echo Text::_('COM_REDSHOP_PRODUCT_ATTRIBUTE') . $i . ' ,';
        }

        echo "Order Total\n";

        for ($i = 0, $in = count($data); $i < $in; $i++) {
            $userBilling = RedshopEntityOrder::getInstance($data[$i]->order_id)->getBilling()->getItem();
            $details     = Redshop\Shipping\Rate::decrypt($data[$i]->ship_method_id);

            echo $data[$i]->order_id . ",";
            echo utf8_decode(RedshopHelperOrder::getOrderStatusTitle($data[$i]->order_status)) . " ,";
            echo date('d-m-Y H:i', $data[$i]->cdate) . " ,";

            if (empty($details)) {
                echo str_replace(",", " ", $details[1]) . "(" . str_replace(",", " ", $details[2]) . ") ,";
            } else {
                echo '';
            }

            $shippingAddresses = RedshopEntityOrder::getInstance($data[$i]->order_id)->getShipping()->getItem();

            echo str_replace(",", " ", $shippingAddresses->firstname) . " " . str_replace(
                ",",
                " ",
                $shippingAddresses->lastname
            ) . " ,";
            echo str_replace(",", " ", utf8_decode($shippingAddresses->address)) . " ,";
            echo $shippingAddresses->zipcode . " ,";
            echo str_replace(",", " ", utf8_decode($shippingAddresses->city)) . " ,";
            echo $shippingAddresses->country_code . " ,";
            echo str_replace(",", " ", $shippingAddresses->company_name) . " ,";
            echo $shippingAddresses->user_email . " ,";

            echo str_replace(",", " ", utf8_decode($userBilling->address)) . " ,";
            echo $userBilling->zipcode . " ,";
            echo str_replace(",", " ", utf8_decode($userBilling->city)) . " ,";
            echo $userBilling->country_code . " ,";
            echo str_replace(",", " ", $userBilling->firstname) . " " . str_replace(
                ",",
                " ",
                $userBilling->lastname
            ) . " ,";

            $noItems = RedshopHelperOrder::getOrderItemDetail($data[$i]->order_id);

            for ($it = 0, $countItem = count($noItems); $it < $countItem; $it++) {
                echo str_replace(",", " ", utf8_decode($noItems[$it]->order_item_name)) . " ,";
                echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $noItems[$it]->product_final_price . ",";

                $productAttribute = RedshopHelperProduct::makeAttributeOrder(
                    $noItems[$it]->order_item_id,
                    0,
                    $noItems[$it]->product_id,
                    0,
                    1
                );
                $productAttribute = strip_tags(str_replace(",", " ", $productAttribute->product_attribute));
                echo trim(utf8_decode($productAttribute)) . " ,";
            }

            $temp = $noProducts - count($noItems);

            if ($temp >= 0) {
                echo str_repeat(' ,', $temp * 3);
            }

            echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $data[$i]->order_total . "\n";
        }

        exit();
    }

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   2.0.0.3
     */
    public function getModel(
        $name = 'Statistic_Order',
        $prefix = 'RedshopModel',
        $config = array('ignore_request' => true)
    ) {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}