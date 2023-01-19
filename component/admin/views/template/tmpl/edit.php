<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<div class="row">
    <?php // Tweak by Ronni - Change to class="col-md-9" ?>
    <div class="col-md-9">
        <?php echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this)) ?>
    </div>
    <?php // Tweak by Ronni - Change to class="col-md-3" ?>
    <div class="col-md-3">
        <?php if ($this->item->section): ?>
            <?php echo $this->loadTemplate('hints') ?>
        <?php endif; ?>
    </div>
</div>
