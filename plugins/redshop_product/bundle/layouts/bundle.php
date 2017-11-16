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
<a href="#bundle_content_<?php echo $detail->product_id ?>_<?php echo $detail->bundle_id ?>" class="bundle_title" id="bundle_title_<?php echo $detail->product_id ?>_<?php echo $detail->bundle_id ?>">
	<?php echo JText::_('JLIB_FORM_BUTTON_SELECT'); ?> <?php echo $detail->bundle_name ?>
</a>
<div class="bundle_content_wrap">
	<div class="bundle_content" id="bundle_content_<?php echo $detail->product_id ?>_<?php echo $detail->bundle_id ?>">
		<?php echo $content; ?>
	</div>
</div>
