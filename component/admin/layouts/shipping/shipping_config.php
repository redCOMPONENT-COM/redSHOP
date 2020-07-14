<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
/**
 * Layout variables
 * ======================================
 *
 * @var  object $item
 * @var  object $element
 * @var  array  $displayData
 */
extract($displayData);
?>

<div class="box-body">
    <?php
    JPluginHelper::importPlugin('redshop_shipping');
    $dispatcher = RedshopHelperUtility::getDispatcher();
    $payment    = $dispatcher->trigger('onShowConfig', array($item));
    ?>
</div>