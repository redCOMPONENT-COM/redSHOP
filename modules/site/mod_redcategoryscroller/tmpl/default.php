<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<div style="width: <?php echo $this->ScrollWidth ?>px;
			text-align: <?php echo $this->ScrollAlign ?>">
    <marquee 
    	behavior="<?php echo $this->ScrollBehavior ?>"
		direction="<?php echo $this->ScrollDirection ?>"
		height="<?php echo $this->ScrollHeight ?>"
		width="<?php echo $this->ScrollWidth ?>"
		scrollamount="<?php echo $this->ScrollAmount ?>"
		scrolldelay="<?php echo $this->ScrollDelay ?>"
        truespeed="true"
		onmouseover="this.stop()"
		onmouseout="this.start()">
		<?php require_once JModuleHelper::getLayoutPath('mod_redcategoryscroller', $this->params->get('layout', 'common')); ?>
	</marquee>
</div>
