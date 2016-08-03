<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$image_path = $type . '/' . trim($image);

?>

<?php if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $image_path) && trim($image) != "") : ?>

<?php
	$thumbUrl = RedShopHelperImages::getImagePath(
		$image,
		'',
		'thumb',
		$type,
		200,
		200,
		USE_IMAGE_SIZE_SWAPPING
	);
?>
<div class="divimage">
	<img src="<?php echo $thumbUrl ?>" id="<?php echo $displayid ?>" border="0" width="200"/>

	<input type="checkbox" name="<?php echo $deleteid ?>" id="<?php echo $deleteid ?>" rel="noicheck" class="hidden">

	<div class="divimagebuttons">
		<span id="editbtn" aria-hidden="true" class="fa-stack fa-lg">
          <i class="fa fa-square fa-stack-2x"></i>
          <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
        </span>

        <span id="deletebtn"  aria-hidden="true" class="fa-stack fa-lg">
          <i class="fa fa-square fa-stack-2x"></i>
          <i class="fa fa-times fa-stack-1x fa-inverse"></i>
        </span>
	</div>
</div>
<?php endif; ?>

<div class="form-group">
	<input type="file" name="<?php echo $id ?>" id="<?php echo $id ?>" size="25" />
</div>

