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

$link .="&amp;folder=".$this->state->parent;

$fdownload = JRequest::getInt('fdownload');
$extra_arg = "";
if($fdownload)
	$extra_arg = "&fdownload=1";
?>
		<div class="imgOutline">
			<div class="imgTotal">
				<div align="center" class="imgBorder">
					<a href="<?php echo $link.$extra_arg; ?>">
						<img src="components/com_redshop/assets/images/folderup_32.png" width="32" height="32" border="0" alt=".." /></a>
				</div>
			</div>
			<div class="imginfoBorder">
				<a href="<?php echo $link.$extra_arg; ?>" >..</a>
			</div>
		</div>
