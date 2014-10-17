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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_AJAX_PRODUCT_HINT'); ?></b><br /><br />
{product_name} {product_price} {product_image} {attribute_template:attributes} {accessory_template:templatename} {if product_userfield} {product_userfield end if}