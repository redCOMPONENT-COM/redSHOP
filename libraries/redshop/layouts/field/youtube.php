<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

extract($displayData);

?>
<iframe
	width="<?php echo $width ?>"
	height="<?php echo $height ?>"
	src="https://www.youtube.com/embed/<?php echo $id ?>"
	frameborder="<?php echo $border ?>">	
</iframe>
