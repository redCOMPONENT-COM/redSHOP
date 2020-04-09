<?php
/**
 * @package     Redshop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2020 - 2021 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Wishlist;

use Joomla\CMS\Factory;

defined('_JEXEC') or die;


/**
 * @package     Redshop\Wishlist
 *
 * @since       3.0.1
 */
class Helper
{
    /**
     * Get number of wishlist
     *
     * @return  integer
     *
     * @since   2.0.2
     */
    public static function countMyWishlist()
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__redshop_wishlist', 'pw'))
            ->where('pw.user_id = ' . (int)Factory::getUser()->id);

        return $db->setQuery($query)->loadResult();
    }
}