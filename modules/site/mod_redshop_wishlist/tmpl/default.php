<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_wishlist
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

?>
<?php if (Redshop::getConfig()->get('MY_WISHLIST')): ?>
	<?php if (!$user->id): ?>
		<div class='mod_redshop_wishlist'>
		<?php if (count($rows) > 0): ?>
			<?php $myWishlistLink = JRoute::_('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=' . $Itemid); ?>
			<a href="<?php echo $myWishlistLink ?>" ><?php echo JText::_('COM_REDSHOP_VIEW_WISHLIST') ?></a>
		<?php else: ?>
			<div><?php echo JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST'); ?></div>
		</div>
		<?php endif; ?>
	<?php else: ?>
		<div class='mod_redshop_wishlist'>
		<?php if ((count($wishlists) > 0) || (count($rows) > 0)): ?>
			<?php $myWishlistLink = JRoute::_('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=' . $Itemid); ?>
			<a href="<?php echo $myWishlistLink ?>" ><?php echo JText::_('COM_REDSHOP_VIEW_WISHLIST') ?></a>
		<?php else: ?>
			<div><?php echo JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') ?></div>
		<?php endif; ?>
		</div>
	<?php endif; ?>
<?php else: ?>
	<div><?php echo JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') ?></div>
<?php endif; ?>
