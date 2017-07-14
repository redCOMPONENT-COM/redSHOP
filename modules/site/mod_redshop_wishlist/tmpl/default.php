<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_wishlist
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$user   = JFactory::getUser();
$Itemid = RedshopHelperUtility::getItemId();
?>

<div class="mod_redshop_wishlist <?php echo $moduleClassSuffix ?>">
	<?php if ($user->guest && !empty($wishList)): ?>
        <a href="<?php echo JRoute::_('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=' . $Itemid) ?>">
			<?php echo JText::_('COM_REDSHOP_VIEW_WISHLIST') ?>
        </a>
	<?php elseif (!$user->guest && !empty($wishList)): ?>
        <a href="<?php echo JRoute::_('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=' . $Itemid) ?>">
			<?php echo JText::_('COM_REDSHOP_VIEW_WISHLIST') ?>
        </a>
	<?php else: ?>
        <div><?php echo JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') ?></div>
	<?php endif; ?>
</div>

