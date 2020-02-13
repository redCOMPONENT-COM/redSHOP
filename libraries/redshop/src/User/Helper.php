<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\User;

defined('_JEXEC') or die;

/**
 * User Helper
 *
 * @since __DEPLOY_VERSION__
 */
class Helper
{
    /**
     * @param        $data
     * @param        $dataAdd
     * @param   int  $section
     *
     * @return string
     * @since __DEPLOY_VERSION__
     */
    public static function userFieldValidation($data, $dataAdd, $section = 12)
    {
        $userFields = \Redshop\Product\Product::getProductUserFieldFromTemplate($dataAdd)[1];

        $msg = "";

        if (count($userFields) > 0) {
            $reqiredFields = RedshopHelperExtrafields::getSectionFieldList($section, 1, 1, 1);

            for ($i = 0, $in = count($reqiredFields); $i < $in; $i++) {
                if (in_array($reqiredFields[$i]->name, $userFields)) {
                    if (!isset($data[$reqiredFields[$i]->name])
                        || (isset($data[$reqiredFields[$i]->name])
                            && $data[$reqiredFields[$i]->name] == "")) {
                        $msg .= $reqiredFields[$i]->title . " " . JText::_('COM_REDSHOP_IS_REQUIRED') . "<br/>";
                    }
                }
            }
        }

        return $msg;
    }
}