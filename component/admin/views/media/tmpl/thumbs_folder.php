<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$jinput = JFactory::getApplication()->input;

$fid = $jinput->get('fid');
$fsec = $jinput->get('fsec');

$link = "index.php?tmpl=component&option=com_redshop&amp;view=media&amp;layout=thumbs";
if (isset($fsec))
	$link .= "&amp;fsec=" . $fsec;
if (isset($fid))
	$link .= "&amp;fid=" . $fid;

$link .= "&amp;folder=" . $this->_tmp_folder->path_relative;

$fdownload = $jinput->getInt('fdownload');

$extra_arg = "";
if ($fdownload)
	$extra_arg = "&fdownload=1";
?>
<div class="imgOutline">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a href="<?php echo $link . $extra_arg; ?>">
				<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>folder.png" width="100" height="100"
				     border="0"/></a>
		</div>
	</div>
	<div class="imginfoBorder">
		<a href="<?php echo $link . $extra_arg; ?>"><?php echo substr($this->_tmp_folder->name, 0, 10) . (strlen($this->_tmp_folder->name) > 10 ? '...' : ''); ?></a>
	</div>
</div>
