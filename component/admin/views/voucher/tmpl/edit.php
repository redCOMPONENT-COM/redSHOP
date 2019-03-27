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
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton === 'voucher.apply' || pressbutton === 'voucher.save' || pressbutton ==='voucher.save2new') {
			if (form.jform_amount.value <= 0) {
				alert("<?php echo JText::_('COM_REDSHOP_VOUCHER_ERROR_DISCOUNT_AMOUNT_ZERO', true ); ?>");
				return false;
			}

			if (form.jform_voucher_left.value <= 0) {
				alert("<?php echo JText::_('COM_REDSHOP_VOUCHER_ERROR_DISCOUNT_AMOUNT_LEFT_ZERO', true ); ?>");
				return false;
			}
		}

		submitform(pressbutton);
	}
</script>