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

$fid = JRequest::getVar('fid');
$fsec = JRequest::getVar('fsec');

$link = "index3.php?option=com_redshop&amp;view=media&amp;layout=thumbs";
if (isset($fsec))
	$link .="&amp;fsec=".$fsec;
if (isset($fid))	
$link .="&amp;fid=".$fid;

$link .="&amp;folder=".$this->_tmp_folder->path_relative;

$fdownload = JRequest::getInt('fdownload');

$extra_arg = "";
if($fdownload)
	$extra_arg = "&fdownload=1"; 
?>
		<div class="imgOutline">
			<div class="imgTotal">
				<div align="center" class="imgBorder">
					<a href="<?php echo $link.$extra_arg;?>">
						<img src="components/com_redshop/assets/images/folder.png" width="100" height="100" border="0" /></a>
				</div>
			</div>			
			<div class="imginfoBorder">
				<a href="<?php echo $link.$extra_arg;?>"><?php echo substr( $this->_tmp_folder->name, 0, 10 ) . ( strlen( $this->_tmp_folder->name ) > 10 ? '...' : ''); ?></a>
			</div>
		</div>