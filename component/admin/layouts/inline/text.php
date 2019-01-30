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
 * ===========================
 *
 * @var  array  $displayData Layout data.
 * @var  string $type        Field type.
 * @var  string $value       Field value.
 * @var  string $name        Field name.
 * @var  string $id          Field ID.
 * @var  string $display     Field display HTML.
 */
extract($displayData);
?>
<input type="<?php echo $type ?>" id="<?php echo $name ?>-<?php echo $id ?>-edit-inline"
       value="<?php echo $value ?>" name="jform_inline[<?php echo $id ?>][<?php echo $name ?>]"
       class="form-control edit-inline"
       data-original-value="<?php echo $value ?>" disabled="disabled" style="display: none;"/>
<div id="<?php echo $name ?>-<?php echo $id ?>" data-target="<?php echo $name ?>-<?php echo $id ?>-edit-inline"
     data-id="<?php echo $id ?>" class="label-edit-inline">
	<?php echo $display ?>
</div>
