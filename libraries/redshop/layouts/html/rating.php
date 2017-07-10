<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

extract($displayData);

?>
<div class="row-fluid">
	<div class="span2">
		<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/5.gif" border="0"
		     align="absmiddle"><br/>
		<input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>5"
		       value="5" <?php if ($value == 5) echo "checked='checked'"; ?>>
	</div>
	<div class="span2">
		<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/4.gif" border="0"
		     align="absmiddle"><br/>
		<input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>4"
		       value="4" <?php if ($value == 4) echo "checked='checked'"; ?>>
	</div>
	<div class="span2">
		<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>/star_rating/3.gif" border="0"
		     align="absmiddle"><br/>
		<input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>3"
		       value="3" <?php if ($value == 3) echo "checked='checked'"; ?>>
	</div>
	<div class="span2">
		<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/2.gif" border="0"
		     align="absmiddle"><br/>
		<input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>2"
		       value="2" <?php if ($value == 2) echo "checked='checked'"; ?>>
	</div>
	<div class="span2">
		<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/1.gif" border="0"
		     align="absmiddle"><br/>
		<input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>1"
		       value="1" <?php if ($value == 1) echo "checked='checked'"; ?>>
	</div>
	<div class="span2">
		<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>star_rating/0.gif" border="0"
		     align="absmiddle"><br/>
		<input type="radio" name="<?php echo $name ?>" id="<?php echo $id ?>0"
		       value="0" <?php if ($value == 0) echo "checked='checked'"; ?>>
	</div>
</div>
