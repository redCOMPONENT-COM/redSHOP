<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<fieldset class="adminform">
    <div class="row">
        <div class="col-sm-4">
			<?php echo $this->loadTemplate('category'); ?>
			<?php echo $this->loadTemplate('cattab_nplinks'); ?>
        </div>

        <div class="col-sm-4">
			<?php echo $this->loadTemplate('category_suffix'); ?>
        </div>

        <div class="col-sm-4">
			<?php echo $this->loadTemplate('category_template'); ?>
			<?php echo $this->loadTemplate('image_setting'); ?>
			<?php echo $this->loadTemplate('procat_images'); ?>
        </div>
    </div>
</fieldset>
