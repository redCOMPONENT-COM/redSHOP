<div id="icetabs<?php echo $module->id; ?>" class="ice-slideshow-black<?php echo $params->get('moduleclass_sfx','');?> <?php echo $class;?>-sl-black" style="height:<?php echo $moduleHeight;?>; width:<?php echo $moduleWidth;?>">
	<?php if( $class && $class != 'ice-bottom' ) : ?>
    	<?php require( dirname(__FILE__) . DS . '_navigator.php' );?>
    <?php endif; ?>
 <!-- MAIN CONTENT --> 
  <div class="ice-main-wapper" style="height:<?php echo (int)$params->get('main_height',300);?>px;width:<?php echo (int)$params->get('main_width',650);?>px;">
 		<?php foreach( $list as $row ): ?>
  		<div class="ice-main-item"> 
        	 <?php echo modIceTabsHelper::renderItem( $row, $params  );?>
        </div> 
   		<?php endforeach; ?>
        
         <div class="ice-buttons-control">
            <div class="ice-previous"><?php echo JText::_('Previous');?></div>
            <div class="ice-next"><?php echo JText::_('Next');?></div>
    	</div>    
    
  </div>
  <?php if( $params->get('display_button', '') ): ?>
  <?php endif; ?>
<!-- END MAIN CONTENT -->
	<?php if( $class && $class == 'ice-bottom' ) : ?>
    	<?php require( dirname(__FILE__) . DS . '_navigator.php' );?>
    <?php endif; ?> 
 </div> 
