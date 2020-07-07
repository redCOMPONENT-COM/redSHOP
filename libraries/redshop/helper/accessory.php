<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Class Redshop Helper for Product Accessory
 *
 * @since  1.5
 */
class RedshopHelperAccessory
{
    /**
     * @var  array
     */
    protected static $accessories = array();

    /**
     * Prepare and collect accessory as a product information from cart.
     *
     * @param   array  $data  Information array from cart
     *
     * @return  object        Accessory as product information
     */
    public static function getAccessoryAsProduct($data)
    {
        $collection = new stdClass;

        if (!Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')) {
            return $collection;
        }

        // Check the accessory as product ids are not null
        if (!empty($data[0])) {
            $collection->accessory_data       = explode("@@", $data[0]);
            $collection->acc_quantity_data    = explode("@@", $data[1]);
            $collection->acc_attribute_data   = explode("@@", $data[2]);
            $collection->acc_property_data    = explode("@@", $data[3]);
            $collection->acc_subproperty_data = explode("@@", $data[4]);

            $info = array();

            foreach ($collection->accessory_data as $key => $accessoryId) {
                $accessoryDetail       = self::getProductAccessories($accessoryId);
                $object                = $accessoryDetail[0];
                $object->quantity      = $collection->acc_quantity_data[$key];
                $object->attributes    = $collection->acc_attribute_data[$key];
                $object->properties    = $collection->acc_property_data[$key];
                $object->subproperties = $collection->acc_subproperty_data[$key];

                $info[$object->child_product_id] = $object;
            }

            $collection->accessory = $info;
        }

        return $collection;
    }

    /**
     * Method for get Product Accessories.
     *
     * @param   string  $accessoryId     ID of accessory.
     * @param   string  $productId       ID of product.
     * @param   int     $childProductId  ID of child product.
     * @param   int     $categoryId      ID of category.
     *
     * @return  array                 List of accessories.
     *
     * @since   2.0.3
     */
    public static function getProductAccessories(
        $accessoryId = '',
        $productId = '',
        $childProductId = 0,
        $categoryId = 0
    ) {
        $key = $accessoryId . '_' . $productId . '_' . $childProductId . '_' . $categoryId;

        if (!array_key_exists($key, static::$accessories)) {
            $db = JFactory::getDbo();
	        $query = $db->getQuery(true)
                    ->order($db->qn('a.child_product_id') . ' ASC');

            if (Redshop::getConfig()->get('DEFAULT_ACCESSORY_ORDERING_METHOD')) {
                $query->clear('order')
	                ->order(Redshop::getConfig()->get('DEFAULT_ACCESSORY_ORDERING_METHOD'));
            }

            if ($accessoryId != 0) {
                // Sanitize ids
                $accessoryId = explode(',', $accessoryId);
                $accessoryId = ArrayHelper::toInteger($accessoryId);

                $query->where($db->qn('a.accessory_id') . ' IN ('. implode(',', $accessoryId) .')');
            }

            if ($productId != 0) {
                // Sanitize ids
                $productId = explode(',', $productId);
                $productId = ArrayHelper::toInteger($productId);

	            $query->where($db->qn('a.product_id') . ' IN ('. implode(',', $productId) .')');
            }

            if ($childProductId != 0) {
                $query->where($db->qn('a.child_product_id') . ' = ' . (int)$childProductId);
            }

            if ($categoryId != 0) {
	            $query->where($db->qn('a.category_id') . ' = ' . (int)$categoryId);
	            $query->group($db->qn('a.child_product_id'));
            }

            $switchQuery = ", CASE ". $db->qn('a.oprand')
                . " WHEN '+' THEN IF ( (". $db->qn('p.product_on_sale') .">0 && ((". $db->qn('p.discount_enddate') ."='' AND "
	            . $db->qn('p.discount_stratdate') ."='') OR ( ". $db->qn('p.discount_enddate') .">='"
                . time() . "' AND ". $db->qn('p.discount_stratdate') ."<='" . time() . "'))), "
	            . $db->qn('p.discount_price') .", ". $db->qn('p.product_price') ." ) + accessory_price "
                . "WHEN '-' THEN IF ( (". $db->qn('p.product_on_sale') .">0 && ((". $db->qn('p.discount_enddate')
	            . "='' AND ". $db->qn('p.discount_stratdate') ."='') OR ( ". $db->qn('p.discount_enddate') .">='"
                . time() . "' AND ". $db->qn('p.discount_stratdate') ."<='" . time(
                ) . "'))), ". $db->qn('p.discount_price') .", ". $db->qn('p.product_price') ." ) - accessory_price "
                . "WHEN '=' THEN accessory_price "
                . "END AS newaccessory_price ";

            $priceQuery = "IF ( (". $db->qn('p.product_on_sale') .">0 && ((". $db->qn('p.discount_enddate') ."='' AND "
	            . $db->qn('p.discount_stratdate') ."='') OR ( ". $db->qn('p.discount_enddate') .">='"
                . time() . "' AND ". $db->qn('p.discount_stratdate') ."<='" . time() . "'))), "
	            . $db->qn('p.discount_price') .", ". $db->qn('p.product_price') ." ) AS accessory_main_price ";

		        $query->select(
		        	[
				        'a.*',
				        'p.product_number',
				        'p.product_name',
				        $priceQuery,
				        'p.product_s_desc',
				        'p.product_full_image',
				        'p.product_on_sale ' . $switchQuery,
				        'p.expired'
			        ]
		        )
		        ->from($db->qn('#__redshop_product_accessory', 'a'))
		        ->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = a.child_product_id')
		        ->where($db->qn('p.published') . ' = 1');

	            if (\Redshop::getConfig()->getInt('SHOW_DISCONTINUED_PRODUCTS')) {
		            $query->where($db->qn('p.expired') . ' IN (0, 1)');
	            } else {
		            $query->where($db->qn('p.expired') . ' IN (0)');
	            }

            static::$accessories[$key] = $db->setQuery($query)->loadObjectList();
        }

        return static::$accessories[$key];
    }

    /**
     * Check if Accessory is existed
     *
     * @param   integer  $productId    Product ID
     * @param   integer  $accessoryId  Accessory ID
     *
     * @return integer
     *
     * @since  2.0.0.3
     */
    public static function checkAccessoryExists($productId, $accessoryId)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select($db->qn(array('pa.accessory_id', 'pa.product_id')))
            ->from($db->qn('#__redshop_product_accessory', 'pa'))
            ->where($db->qn('pa.product_id') . ' = ' . (int)$productId)
            ->where($db->qn('pa.child_product_id') . ' = ' . (int)$accessoryId);

        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (count($result) > 0) {
            $return = $result[0]->accessory_id;
        } else {
            $return = 0;
        }

        return $return;
    }
}
