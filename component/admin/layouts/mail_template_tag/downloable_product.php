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
<b><?php echo JText::_('COM_REDSHOP_MAIL_TEMPLATE_TAG_DOWNLOADABLE_PRODUCT_HINT'); ?></b><br /><br />
{fullname} {order_id} {order_number} {order_date} {product_serial_loop_start} {token} {product_serial_number} {product_name} {product_serial_loop_end}