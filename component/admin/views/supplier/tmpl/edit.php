<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this));
?>
<script>
	jQuery(document).ready(function() {
		jQuery('#jform_email').change(function() {
			jQuery('label.error').remove();
			jQuery.ajax({
				url: 'index.php?option=com_redshop&task=supplier.ajaxValidateNewSupplier',
				type: 'post',
				dataType: 'json',
				data: {email: jQuery(this).val()},
			})
				.done(function(res) {
					if (res == 1) {
						jQuery('#jform_email').after('<label class="error" style="display: block;"><?php echo JText::_('COM_REDSHOP_EMAIL_ALREADY_EXISTS') ?></label>');
					}
				})
		})
	})
</script>
