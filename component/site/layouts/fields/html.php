<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   object  $form        A JForm object
 * @param   int     $product_id  Id current product
 * @param   int     $modal       Flag use form in modal
 */
extract($displayData);

?>

<div class="form-group">
	<label><?php echo JText::_($fieldHandle->field_title); ?></label>
	<?php echo $inputField; ?>
	<?php if ($fieldHandle->required == 1) : ?>
		<span class='required'>*</span>
	<?php endif; ?>
	<?php if (trim($fieldHandle->field_desc) != '') : ?>
		<?php
			echo '&nbsp; ' . JHTML::tooltip($fieldHandle->field_desc, '', 'tooltip.png', '', '', false);
		?>
	<?php endif; ?>
</div>
