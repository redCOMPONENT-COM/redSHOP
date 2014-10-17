<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_PAYMENT_METHOD_HINT'); ?></b><br /><br />
{payment_heading} {split_payment} {payment_loop_start} {payment_method_name} {creditcard_information} {payment_loop_end}