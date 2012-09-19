<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');

$fid = JRequest::getVar('fid','');
$fsec = JRequest::getVar('fsec','');
$folder = JRequest::getVar('folder','');
if ($folder == '')
	$thumb_path = JURI::root()."components/com_redshop/assets".DS."images"."/".$this->_tmp_img->path_relative;
else
	$thumb_path = JURI::root()."components/com_redshop/helpers/thumb.php?filename=".$this->_tmp_img->path_relative."&newxsize=".$this->_tmp_img->width_60."&newysize=".$this->_tmp_img->height_60;
?>
		<div class="imgOutline">
			<div class="imgTotal">
				<div align="center" class="imgBorder" style=''>
					<a class="img-preview" href="javascript:window.parent.jimage_insert('<?php echo 'components/com_redshop/assets'.DS.'images'.DS.$this->_tmp_img->path_relative; ?>','<?php echo $fid;?>','<?php echo $fsec;?>');window.parent.SqueezeBox.close();" title="<?php echo $this->_tmp_img->name; ?>" style="display: block; width: 100%; height: 100%">
						<div class="image"  >
							<img src="<?php echo $thumb_path; ?>" width="<?php echo $this->_tmp_img->width_60 ; ?>" height="<?php echo $this->_tmp_img->height_60; ?>" alt="<?php echo $this->_tmp_img->name; ?> - <?php echo redMediahelper::parseSize($this->_tmp_img->size); ?>" border="0" />
						</div></a>
				</div>
			</div>
			<div class="imginfoBorder">
				<a href="<?php echo JURI::root()."components/com_redshop/assets".DS."images"."/".$this->_tmp_img->path_relative; ?>" class="preview" onclick="window.parent.jimage_insert('<?php echo 'components/com_redshop/assets'.DS.'images'.DS.$this->_tmp_img->path_relative; ?>','<?php echo $fid;?>','<?php echo $fsec;?>');window.parent.SqueezeBox.close();"><?php echo $this->escape( substr( $this->_tmp_img->name, 0, 10 ) . ( strlen( $this->_tmp_img->name ) > 10 ? '...' : '')); ?></a>
			</div>
		</div>
