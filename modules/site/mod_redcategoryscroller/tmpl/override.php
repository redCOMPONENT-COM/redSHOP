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

<?php echo $this->params->get('pretext', ""); ?>

<div style='text-align:"<?php echo $this->ScrollAlign ?>";
			background-color: "<?php echo $this->ScrollBGColor ?>";
			width: "<?php echo $this->ScrollWidth?>"px;
			margin-top: <?php echo $margin ?>;
			margin-right: <?php echo $margin ?>;
			margin-bottom: <?php echo $margin ?>;
			margin-left: $margin; ?>'>
    <marquee 
    	behavior="<?php echo $this->ScrollBehavior ?>"
		direction="<?php echo $this->ScrollDirection ?>"
		height="<?php echo $this->ScrollHeight ?>"
		width="<?php echo $this->ScrollWidth ?>"
		scrollamount="<?php echo $this->ScrollAmount ?>"
		scrolldelay="<?php echo $this->ScrollDelay ?>"
		truespeed="true"
		onmouseover="this.stop()"
		onmouseout="this.start()"
		style="	text-align: <?php echo $this->ScrollTextAlign ?>;
				color: <?php echo $this->ScrollTextColor ?>;
				font-weight: <?php echo $this->ScrollTextWeight ?>;
				font-size: <?php echo $txt_size ?>px" >
		<?php require_once JModuleHelper::getLayoutPath('mod_redcategoryscroller', $this->params->get('layout', 'common')); ?>
	</marquee>
</div>
