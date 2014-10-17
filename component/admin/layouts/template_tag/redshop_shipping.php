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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_METHOD_HINT'); ?></b><br /><br />
{shipping_heading} {shipping_method_loop_start} {shipping_method_title} {shipping_rate_loop_start} {shipping_rate_name} {shipping_rate} {shipping_location} {shipping_rate_loop_end} {shipping_method_loop_end}