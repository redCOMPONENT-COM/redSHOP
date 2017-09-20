<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

/**
 * $displayData extract
 *
 * @param   object  $subProperties        Subproperty
 * @param   string  $commonId             Common ID
 * @param   string  $productId            Product ID
 * @param   string  $subPropertyId        Subproperty ID
 * @param   string  $accessoryId          Accessory ID
 * @param   string  $relatedProductId     Related product ID
 * @param   int     $selectedSubProperty  Subproperty is selected
 * @param   array   $subPropertyArray     Subproperty array
 * @param   float   $width                Image width
 * @param   float   $height               Image height
 */
extract($displayData);
$atth = 50;
$attw = 50;

if (Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT'))
{
	$atth = Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT');
}

if (Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH'))
{
	$attw = Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH');
}
?>

<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<a class="leftButton" id="FirstButton" href="javascript:isFlowers<?php echo $commonId; ?>.scrollReverse();"></a>
		</td>

		<td>
			<div id="isFlowersFrame<?php echo $commonId; ?>" name="isFlowersFrame<?php echo $commonId; ?>" style="margin: 0px; padding: 0px;position: relative; overflow: hidden;">
				<div id="isFlowersImageRow<?php echo $commonId; ?>" name="isFlowersImageRow<?php echo $commonId; ?>" style="position: absolute; top: 0px;left: 0px;">
					<script type="text/javascript">
						var isFlowers<?php echo $commonId; ?> = new ImageScroller("isFlowersFrame<?php echo $commonId; ?>", "isFlowersImageRow<?php echo $commonId; ?>");
						<?php foreach ($subProperties as $key => $subProperty): ?>
							<?php
								$borderStyle = ($selectedSubProperty == $subProperty->value) ? " 1px solid " : "";
								$thumbUrl = RedShopHelperImages::getImagePath(
									$subProperty->subattribute_color_image,
									'',
									'thumb',
									'subcolor',
									$width,
									$height,
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);
							?>
							isFlowers<?php echo $commonId; ?>.addThumbnail("<?php echo $thumbUrl; ?>", "javascript:isFlowers<?php echo $commonId; ?>.scrollImageCenter('<?php echo $key; ?>');setSubpropImage('<?php echo $productId; ?>', '<?php echo $subPropertyId; ?>', '<?php echo $subProperty->value; ?>');calculateTotalPrice('<?php echo $productId; ?>', '<?php echo $relatedProductId; ?>);displayAdditionalImage('<?php echo $productId; ?>', '<?php echo $accessoryId; ?>, '<?php echo $relatedProductId; ?>', '<?php echo $subPropertyId . '_subpropimg_' . $subproperty->value; ?>');", "<?php echo $borderStyle; ?>");
						<?php endforeach; ?>
						isFlowers<?php echo $commonId; ?>.setThumbnailHeight(<?php echo $atth; ?>);
						isFlowers<?php echo $commonId; ?>.setThumbnailWidth(<?php echo $attw; ?>);
						isFlowers<?php echo $commonId; ?>.setThumbnailPadding(5);
						isFlowers<?php echo $commonId; ?>.setScrollType(0);
						isFlowers<?php echo $commonId; ?>.enableThumbBorder(false);
						isFlowers<?php echo $commonId; ?>.setClickOpenType(1);
						isFlowers<?php echo $commonId; ?>.setThumbsShown(<?php echo Redshop::getConfig()->get('NOOF_SUBATTRIB_THUMB_FOR_SCROLLER'); ?>);
						isFlowers<?php echo $commonId; ?>.setNumOfImageToScroll(1);
						isFlowers<?php echo $commonId; ?>.renderScroller();
					</script>
					<div id="divsubimgscroll<?php echo $commonId; ?>" style="display: none"><?php echo implode('#_#', $subPropertyArray) ?></div>
				</div>
			</div>
		</td>
		<td>
			<a class='rightButton' id="FirstButton" href="javascript:isFlowers<?php echo $commonId; ?>.scrollForward();"></a>
		</td>
	</tr>
</table>
