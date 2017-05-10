<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$fid = JRequest::getVar('fid');
$fsec = JRequest::getVar('fsec');
$link = "index.php?tmpl=component&option=com_redshop&amp;view=media&amp;layout=thumbs";
if (isset($fsec))
	$link .= "&amp;fsec=" . $fsec;
if (isset($fid))
	$link .= "&amp;fid=" . $fid;

$link .= "&amp;folder=" . $this->state->parent;

$fdownload = JRequest::getInt('fdownload');
$extra_arg = "";
if ($fdownload)
	$extra_arg = "&fdownload=1";
?>
<div class="imgOutline">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a href="<?php echo $link . $extra_arg; ?>">
				<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>folderup_32.png" width="32" height="32" border="0"
				     alt=".."/></a>
		</div>
	</div>
	<div class="imginfoBorder">
		<a href="<?php echo $link . $extra_arg; ?>">..</a>
	</div>
</div>
