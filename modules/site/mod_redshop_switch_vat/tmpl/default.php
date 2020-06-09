<?php
/**
 * @package     Joomla.Site
 * @subpackage  MOD_REDSHOP_LOGIN
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$checked = '';

if (\JFactory::getSession()->get('mod_redshop_switch_vat', 0) == 1) {
    $checked = ' checked="checked"';
}

?>
<form method="post" id="login-form" class="form-inline">
    <div class="mod_redshop_switch_vat wrapper">
        <label for="show_price_with_vat"><?php echo \JText::_('MOD_REDSHOP_SWITCH_VAT_LBL_FOR') ?></label>
        <input
                type="checkbox"
                name="show_price_with_vat"
                onclick="switchShowVat();"
                value="1"
                <?php echo $checked ?>
        />
    </div>
</form>
