<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\User;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * User Helper
 *
 * @since 3.0
 */
class Helper
{
    /**
     * @param        $data
     * @param        $dataAdd
     * @param   int  $section
     *
     * @return string
     * @since 3.0
     */
    public static function userFieldValidation($data, $dataAdd, $section = 12)
    {
        $userFields = \Redshop\Product\Product::getProductUserFieldFromTemplate($dataAdd)[1];

        $msg = "";

        if (count($userFields) > 0) {
            $requiredFields = \RedshopHelperExtrafields::getSectionFieldList($section, 1, 1, 1);

            for ($i = 0, $in = count($requiredFields); $i < $in; $i++) {
                if (in_array($requiredFields[$i]->name, $userFields)) {
                    if (!isset($data[$requiredFields[$i]->name])
                        || (isset($data[$requiredFields[$i]->name])
                            && $data[$requiredFields[$i]->name] == "")) {
                        $msg .= $requiredFields[$i]->title . " " . Text::_('COM_REDSHOP_IS_REQUIRED') . "<br/>";
                    }
                }
            }
        }

        return $msg;
    }

    /**
     * @param array $columns
     * @param array $conditions
     * @return mixed
     * @since 3.0
     */
    public static function getUsers(
        $columns = []
        , $conditions = [
            'ui.address_type' => ['=' => 'BT']
        ]
    )
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        if (count($columns) < 1) {
            $query->select('u.*');
        } else {
            foreach ($columns as $col => $alias)
            {
                $query->select($db->qn($col, $alias));
            }
        }

        $query->from($db->qn('#__users', 'u'));
        $query->leftJoin($db->qn('#__redshop_users_info', 'ui')
            . 'ON' . $db->qn('u.id') . '=' . $db->qn('ui.user_id'));

        if (count($conditions) > 0)
        {
            foreach ($conditions as $key => $con) {
                foreach ($con as $operator => $value) {
                    $query->where($db->qn($key) . $operator . $db->q($value));
                }
            }
        }

        $db->setQuery($query);

        return $db->loadObjectlist();
    }

    /**
     * @param $uid
     * @return string
     * @since 3.0
     */
    public static function getUserFullName($uid)
    {
        $uid = (int) $uid;

        $user = \RedshopHelperUser::getUserInformation($uid);

        if (isset($user))
        {
            return $user->firstname . " " . $user->lastname . " (" . $user->user_email . ")";
        }

        return '';
    }

    /**
     * @return null
     * @throws \Exception
     */
    public static function getNewCustomers()
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->qn('#__redshop_users_info'))
            ->order($db->qn('users_info_id') . ' DESC');

        $db->setQuery($query, 0, 10);

        return \Redshop\DB\Tool::safeSelect($db, $query, true, []);
    }
}