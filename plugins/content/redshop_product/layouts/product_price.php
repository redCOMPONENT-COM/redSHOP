<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Plugin.Content.Redshop_Product
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;
extract($displayData);
?>
<div id='mod_redsavedprice' class='mod_redsavedprice'>
	<?php echo JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' '
		. $productHelper->getProductFormattedPrice($realPrice) ?></div>
