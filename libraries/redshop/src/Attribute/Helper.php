<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Attribute;

defined('_JEXEC') or die;

/**
 * Attribute Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param int $cartItemId
     * @param int $isAccessory
     * @param string $section
     * @param int $parentSectionId
     *
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public static function getCartItemAttributeDetail(
        $cartItemId = 0,
        $isAccessory = 0,
        $section = "attribute",
        $parentSectionId = 0
    ) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->qn('#__redshop_usercart_attribute_item'))
            ->where($db->qn('is_accessory_att') . '=' . $db->q((int)$isAccessory))
            ->where($db->qn('section') . '=' . $db->q($section));

        if ($cartItemId != 0) {
            $query->where($db->qn('cart_item_id') . '=' . $db->q((int)$cartItemId));
        }

        if ($parentSectionId != 0) {
            $query->where($db->qn('parent_section_id') . '=' . $db->q((int)$parentSectionId));
        }


        $db->setQuery($query);
        $list = $db->loadObjectlist();

        return $list;
    }
}