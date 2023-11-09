<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Layout variables
 * ======================================
 *
 * @var  object $field
 * @var  array  $displayData
 */

extract($displayData);

?>
<div class="form-group row-fluid ">
    <label for="name" class="col-md-2 control-label hasPopover">
        <?php
        echo Text::_($field->getAttribute('label')) ?>:
    </label>
    <div class="col-md-8">
        <strong>
            <?php
            echo Text::_($field->value) ?>
        </strong>
    </div>
</div>