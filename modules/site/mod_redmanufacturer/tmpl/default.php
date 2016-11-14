<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('redshopjquery.flexslider', '#redManufSlider_' . $module->id . ' .flexslider',
	array(
		'minItems' => $scrollBehavior,
		'move' => $scrollAuto,
		'itemWidth' => ($scrollWidth / $scrollBehavior) + 5,
		'animation' => 'slide',
		'itemMargin' => 5,
		'animationSpeed' => $scrollDelay,
		'controlNav' => (boolean) $controlNav,
		'directionNav' => (boolean) $directionNav
	)
);

$document = JFactory::getDocument();
$document->addStyleDeclaration('
#redManufSlider_' . $module->id . ' .flexslider{
	max-width: ' . $scrollWidth . 'px;
}
#redManufSlider_' . $module->id . ' .slides li{
	overflow: hidden;
	margin-right: 5px;
}
#redManufSlider_' . $module->id . ' .flexslider{
	margin-bottom: 40px;
}
#redManufSlider_' . $module->id . ' .slideImage, #redManufSlider_' . $module->id . ' .slideTitle{
	text-align: center;
	margin-bottom: 5px;
}
#redManufSlider_' . $module->id . ' .slideImage img{
	width: auto !important;
	height: auto !important;
	display: inline;
	max-width: 100%;
}
');
?>
<div class="redManufSlider" id="redManufSlider_<?php echo $module->id; ?>">
<?php if ($preText): ?>
<div class="preText">
	<?php echo $preText ?>
</div>
<?php endif; ?>
<?php if ($list): ?>
<div class="flexslider">
	<ul class="slides">
		<?php foreach ($list as $slide): ?>
			<?php $thumbUrl = RedShopHelperImages::getImagePath(
				$slide->media_name,
				'',
				'thumb',
				'manufacturer',
				$ImageWidth,
				$ImageHeight,
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			); ?>

			<?php $link = JRoute::_(
				'index.php?option=com_redshop&view=manufacturers&layout='
				. $PageLink . '&mid=' . $slide->manufacturer_id . '&Itemid=' . $slide->item_id, false
			); ?>

			<?php $title = $slide->manufacturer_name; ?>
		<li>
			<?php if ($showImage): ?>
				<div class="slideImage">
					<img src="<?php echo $thumbUrl; ?>" />
				</div>
			<?php endif; ?>
			<?php if ($showProductName): ?>
				<div class="slideTitle">
				<?php if ($showLinkOnProductName): ?>
					<a href="<?php echo $link; ?>">
				<?php endif; ?>
			<?php echo $title; ?>
				<?php if ($showLinkOnProductName): ?>
					</a>
				<?php endif; ?>
				</div>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
</div>
