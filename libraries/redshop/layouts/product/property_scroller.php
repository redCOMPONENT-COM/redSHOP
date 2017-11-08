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
 * @param   object  $attribute         Attribute
 * @param   object  $properties        Property
 * @param   string  $commonId          Common ID
 * @param   string  $productId         Product ID
 * @param   string  $propertyId        Property ID
 * @param   string  $accessoryId       Accessory ID
 * @param   string  $relatedProductId  Related product ID
 * @param   int     $selectedProperty  Property is selected
 * @param   float   $width             Image width
 * @param   float   $height            Image height
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

<table cellpadding="5" cellspacing="5">
	<tr>
		<td>
			<a class="leftButton" id="FirstButton" href="javascript:isFlowers<?php echo $commonId; ?>.scrollReverse();"></a>
		</td>
		<td>
			<div id="isFlowersFrame<?php echo $commonId; ?>" name="isFlowersFrame<?php echo $commonId; ?>" style="margin: 0px; padding: 0px;position: relative; overflow: hidden;">
				<div id="isFlowersImageRow<?php echo $commonId; ?>" name="isFlowersImageRow<?php echo $commonId; ?>" style="position: absolute; top: 0px;left: 0px;">
					<script type="text/javascript">
						var isFlowers<?php echo $commonId; ?> = new ImageScroller("isFlowersFrame<?php echo $commonId; ?>", "isFlowersImageRow<?php echo $commonId; ?>");
						<?php foreach ($properties as $key => $property): ?>
							<?php
								$borderStyle = ($selectedProperty == $property->value) ? " 1px solid " : "";
								$thumbUrl = RedShopHelperImages::getImagePath(
									$property->property_image,
									'',
									'thumb',
									'product_attributes',
									$width,
									$height,
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);
							?>
							isFlowers<?php echo $commonId; ?>.addThumbnail("<?php echo $thumbUrl; ?>", "javascript:isFlowers<?php echo $commonId; ?>.scrollImageCenter('<?php echo $key; ?>');setPropImage('<?php echo $productId; ?>', '<?php echo $propertyId; ?>', '<?php echo $property->value; ?>');changePropertyDropdown('<?php echo $productId; ?>', '<?php echo $accessoryId; ?>', '<?php echo $relatedProductId; ?>', '<?php echo $attribute->value; ?>', '<?php echo $property->value; ?>', '<?php echo $width; ?>', '<?php echo $height; ?>');", "<?php echo $property->text; ?>", "", "<?php echo $propertyId; ?>_propimg_<?php echo $property->value; ?>", "<?php echo $borderStyle; ?>");
						<?php endforeach; ?>
						isFlowers<?php echo $commonId; ?>.setThumbnailHeight(<?php echo $atth; ?>);
						isFlowers<?php echo $commonId; ?>.setThumbnailWidth(<?php echo $attw; ?>);
						isFlowers<?php echo $commonId; ?>.setThumbnailPadding(5);
						isFlowers<?php echo $commonId; ?>.setScrollType(0);
						isFlowers<?php echo $commonId; ?>.enableThumbBorder(false);
						isFlowers<?php echo $commonId; ?>.setClickOpenType(1);
						isFlowers<?php echo $commonId; ?>.setThumbsShown(<?php echo Redshop::getConfig()->get('NOOF_THUMB_FOR_SCROLLER'); ?>);
						isFlowers<?php echo $commonId; ?>.setNumOfImageToScroll(1);
						isFlowers<?php echo $commonId; ?>.renderScroller();
					</script>
				</div>
			</div>
		</td>
		<td>
			<a class='rightButton' id="FirstButton" href="javascript:isFlowers<?php echo $commonId; ?>.scrollForward();"></a>
		</td>
	</tr>
</table>
