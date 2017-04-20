<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);


?>
<div class="bundle_title"><?php echo JText::_('PLG_REDSHOP_PRODUCT_BUNDLE_TITLE'); ?></div>
<?php foreach ($data as $row) : ?>
	<div class="bundle_row">
	<?php echo $row[1]->bundle_name; ?>:
		<?php if ($row[0]) : ?>
			<?php echo $row[0]->property_name; ?> (<?php echo $row[0]->property_number; ?>)
		<?php else : ?>
			(<?php echo $row[1]->product_number; ?>)
		<?php endif; ?>
	</div>
<?php endforeach; ?>