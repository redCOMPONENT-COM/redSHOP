<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$fid = JRequest::getVar('fid', '');
$fsec = JRequest::getVar('fsec', '');
$folder = JRequest::getVar('folder', '');
if ($folder == '')
	$thumb_path = JURI::root() . "components/com_redshop/assets/images/" . $this->_tmp_img->path_relative;
else
	$thumb_path = JURI::root() . "components/com_redshop/helpers/thumb.php?filename=" . $this->_tmp_img->path_relative . "&newxsize=" . $this->_tmp_img->width_60 . "&newysize=" . $this->_tmp_img->height_60;
?>
<div class="imgOutline">
	<div class="imgTotal">
		<div align="center" class="imgBorder" style=''>
			<a class="img-preview"
			   href="javascript:window.parent.jimage_insert('<?php echo 'components/com_redshop/assets/images/' . $this->_tmp_img->path_relative; ?>','<?php echo $fid; ?>','<?php echo $fsec; ?>');window.parent.SqueezeBox.close();"
			   title="<?php echo $this->_tmp_img->name; ?>" style="display: block; width: 100%; height: 100%">
				<div class="image">
					<img src="<?php echo $thumb_path; ?>" width="<?php echo $this->_tmp_img->width_60; ?>"
					     height="<?php echo $this->_tmp_img->height_60; ?>"
					     alt="<?php echo $this->_tmp_img->name; ?> - <?php echo redMediahelper::parseSize($this->_tmp_img->size); ?>"
					     border="0"/>
				</div>
			</a>
		</div>
	</div>
	<div class="imginfoBorder">
		<a href="<?php echo JURI::root() . "components/com_redshop/assets/images/" . $this->_tmp_img->path_relative; ?>"
		   class="preview"
		   onclick="window.parent.jimage_insert('<?php echo 'components/com_redshop/assets/images/' . $this->_tmp_img->path_relative; ?>','<?php echo $fid; ?>','<?php echo $fsec; ?>');window.parent.SqueezeBox.close();"><?php echo $this->escape(substr($this->_tmp_img->name, 0, 10) . (strlen($this->_tmp_img->name) > 10 ? '...' : '')); ?></a>
	</div>
</div>
