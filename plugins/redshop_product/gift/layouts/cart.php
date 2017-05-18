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
<div class="gift_title"><?php echo JText::_('PLG_REDSHOP_PRODUCT_GIFT_TITLE'); ?></div>
<?php foreach ($data as $key => $gift) : ?>
	<div class="gift_row">
		<?php $product = RedshopHelperProduct::getProductById($gift['product_id']); ?>
		<div class="product_name"><?php echo $product->product_name; ?></div>
		<div class="quantity"><?php echo $gift['quantity']; ?></div>
	</div>
<?php endforeach; ?>