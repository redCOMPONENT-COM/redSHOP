<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$fdownload = JRequest::getInt('fdownload');
$extra_link = "";
if ($fdownload)
	$extra_link = "href=\"javascript:window.parent.jdownload_file('" . $this->_tmp_doc->path_relative . "','" . $this->_tmp_doc->name . "');window.parent.SqueezeBox.close();\"";
?>
<div class="imgOutline">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a style="display: block; width: 100%; height: 100%" <?php echo $extra_link; ?> >
				<img border="0" src="<?php echo $this->_tmp_doc->icon_32 ?>"
				     alt="<?php echo $this->_tmp_doc->name; ?>"/></a>
		</div>
	</div>
	<div class="imginfoBorder">
		<?php echo $this->_tmp_doc->name; ?>
	</div>
</div>
