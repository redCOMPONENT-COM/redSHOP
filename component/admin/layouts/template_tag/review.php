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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_REVIEW_HINT'); ?></b><br /><br />
{product_loop_start} {product_title} <br />
{review_loop_start} {fullname} {title} {comment} {stars} {review_date} {review_loop_end} {product_loop_end}