<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<div class="row">
    <div class="col-md-6">
		<?php echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this)) ?>
    </div>
    <div class="col-md-6">
		<?php echo $this->loadTemplate('hints') ?>
    </div>
</div>
