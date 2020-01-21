<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   string  $name
 * @param   string  $commonId
 * @param   string  $accqua
 *
 */
extract($displayData);
?>

<input type='text' name='<?php echo $name ?>[]'
        id='accquantity_<?php echo $commonId ?>'
        value='<?php echo $accqua ?>'
        maxlength='<?php echo Redshop::getConfig()->get('DEFAULT_QUANTITY') ?>'
        size='<?php echo Redshop::getConfig()->get('DEFAULT_QUANTITY') ?>'
        onchange='validateInputNumber(this.id);'
>