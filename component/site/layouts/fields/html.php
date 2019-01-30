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
 * @var   array   $displayData Layout data
 * @var   object  $form        A JForm object
 * @var   integer $product_id  Id current product
 * @var   integer $modal       Flag use form in modal
 */
extract($displayData);
?>

<div class="form-group">
    <label><?php echo JText::_($fieldHandle->title); ?></label>
	<?php echo $inputField; ?>
	<?php if ($fieldHandle->required == 1) : ?>
        <span class='required'>*</span>
	<?php endif; ?>
	<?php if (trim($fieldHandle->desc) != '') : ?>
		<?php
		echo '&nbsp; ' . JHTML::tooltip($fieldHandle->desc, '', 'tooltip.png', '', '', false);
		?>
	<?php endif; ?>
</div>
