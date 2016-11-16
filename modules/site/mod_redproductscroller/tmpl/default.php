<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<?php if ($ScrollCSSOverride == 'yes'): ?>
	<?php $txt_size = $ScrollTextSize . 'px'; ?>
	<?php $margin   = $ScrollMargin . 'px'; ?>
	<?php echo $params->get('pretext', ""); ?>
	<div style="text-align:<?php echo $ScrollAlign ?>;background-color:<?php echo $ScrollBGColor ?>; width:<?php echo $ScrollWidth ?>px;
           margin <?php echo $margin . ' ' . $margin . ' ' . $margin . ' ' . $margin; ?>" >
   			<marquee behavior="<?php echo $ScrollBehavior ?>"
	            direction="<?php echo $ScrollDirection ?>"
	            height="<?php echo $ScrollHeight ?>"
	            width="<?php echo $ScrollWidth ?>"
	            scrollamount="<?php echo $ScrollAmount ?>"
	            scrolldelay="<?php echo $ScrollDelay ?>"
	            truespeed="true" onmouseover="this.stop()" onmouseout="this.start()"
	            style="text-align:<?php echo $ScrollTextAlign ?>; color:<?php echo $ScrollTextColor ?>; font-weight:<?php echo $ScrollTextWeight ?>; font-size: <?php echo $txt_size; ?>px">
<?php else: ?>
	<div style="width:<?php echo $ScrollWidth ?>px; text-align:<?php echo $ScrollAlign ?>">
	<marquee behavior="<?php echo $ScrollBehavior ?>"
            direction="<?php echo $ScrollDirection ?>"
            height="<?php echo $ScrollHeight ?>"
            width="<?php echo $ScrollWidth ?>"
            scrollamount="<?php echo $ScrollAmount ?>"
            scrolldelay="<?php echo $ScrollDelay ?>"
            truespeed="true" onmouseover="this.stop()" onmouseout="this.start()">
<?php endif; ?>

<?php if (($ScrollDirection == 'left') || ($ScrollDirection == 'right')): ?>
	<table><tr>
<?php endif ?>

<?php $i = 0; ?>

<?php foreach ($rows as $row): ?>
	<?php if (($ScrollDirection == 'left') || ($ScrollDirection == 'right')): ?>
		<td style="vertical-align:top;padding: 2px 5px 2px 5px;"><table width="<?php echo $boxwidth ?>">
	<?php endif; ?>

	<?php $category_id    = $producthelper->getCategoryProduct($row->product_id); ?>
	<?php $ItemData       = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id); ?>
	<?php require JModuleHelper::getLayoutPath('mod_redproductscroller', $params->get('layout', 'products')); ?>

	<?php if (($ScrollDirection == 'left') || ($ScrollDirection == 'right')): ?>
		</table></td>;
	<?php else: ?>
		<?php for ($i = 0; $i < $ScrollLineCharTimes; $i++): ?>
			<?php echo $ScrollLineChar; ?>
		<?php endfor; ?>
	<?php endif; ?>

	<?php $i++; ?>
<?php endforeach; ?>

<?php if (($ScrollDirection == 'left') || ($ScrollDirection == 'right')): ?>
	</tr></table>
<?php endif; ?>

	</marquee>
</div>
