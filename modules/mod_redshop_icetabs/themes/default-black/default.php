<?php
/**
 * IceTabs Module for Joomla 1.6 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2011 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/icetabs.html
 * @Support 	http://www.icetheme.com/Forums/IceTabs/
 *
 */
?> 

<div id="icetabs<?php echo $module->id; ?>" class="ice-tabs-black<?php echo $params->get('moduleclass_sfx','');?> <?php echo $class;?>-sl-black clearfix" style="height:<?php echo $moduleHeight;?>; width:<?php echo $moduleWidth;?>">
	<?php if($class && $class != 'ice-bottom') : ?>
		<?php require(dirname(__FILE__) . DS . '_navigator.php');?>
	<?php endif; ?>
	<div class="ice-main-wapper" style="height:<?php echo (int)$params->get('main_height',300);?>px;width:<?php echo (int)$params->get('main_width',650);?>px;<?php echo $maincss;?>">
		<?php $i = 0; foreach($list as $row): ?>
			<div class="ice-main-item" <?php echo (!$i) ? 'style="display:block;"' : ''?>> 
				<?php echo modIceTabsHelper::renderItem($row, $params);?>
			</div> 
		<?php $i++; endforeach; ?>
		<?php if($params->get('display_button', '')): ?>
			<div class="ice-buttons-control">
				<div class="ice-previous"><?php echo JText::_('Previous');?></div>
				<div class="ice-next"><?php echo JText::_('Next');?></div>
			</div>    
		<?php endif; ?>
	</div>
	<?php if($class && $class == 'ice-bottom') : ?>
    	<?php require(dirname(__FILE__) . DS . '_navigator.php');?>
    <?php endif; ?>
 </div> 