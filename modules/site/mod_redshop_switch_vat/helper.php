<?php
/**
 * @package     RedSHOP.Site
 * @subpackage  mod_redshop_switch_vat
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_redshop_switch_vat
 *
 * @since  1.0
 */
class ModRedshopSwitchVatHelper
{
    public static function getAjax() {
        $input = \JFactory::getApplication()->input;
        $showVat = $input->getInt('show_vat');

        echo $showVat;

        \JFactory::getSession()->set('mod_redshop_switch_vat', $showVat);
    }
}