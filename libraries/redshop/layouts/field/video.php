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

<div class="media-wrapper">
    <video id="<?php echo $id ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" preload="<?php echo $preload ?>">
        <source src="<?php echo $source ?>" type="<?php echo $mimetype ?>">
        <?php if (count($subtitles) > 0): ?>
        	<?php foreach ($subtitles as $k => $v): ?>
        		<track srclang="$k" kind="subtitles" src="$v">
        	<?php endforeach ?>
    	<?php endif ?>
    </video>
</div>
