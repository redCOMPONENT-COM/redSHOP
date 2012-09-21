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
$fdownload = JRequest::getInt('fdownload');
$extra_link = "";
if($fdownload)
	$extra_link = "href=\"javascript:window.parent.jdownload_file('".$this->_tmp_doc->path_relative."','".$this->_tmp_doc->name."');window.parent.SqueezeBox.close();\"";
?>
		<div class="imgOutline">
			<div class="imgTotal">
				<div align="center" class="imgBorder">
				 	<a style="display: block; width: 100%; height: 100%" <?php echo $extra_link; ?> >
						<img border="0" src="<?php echo $this->_tmp_doc->icon_32 ?>" alt="<?php echo $this->_tmp_doc->name; ?>" /></a>
				</div>
			</div>
			<div class="imginfoBorder">
				<?php echo $this->_tmp_doc->name; ?>
			</div>
		</div>
