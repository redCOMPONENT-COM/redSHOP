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

<div class="ice-navigator-wrapper">
    <!-- NAVIGATOR -->
      <div class="ice-navigator-outer" style="width:<?php echo $moduleWidth;?>; height:<?php echo $navheight;?>px">
            <ul class="ice-navigator">
            <?php foreach($list as $row):?>
				<li style="width:<?php echo $navwidth;?>px; height:<?php echo $navheight;?>px">
					<div><?php echo $row->thumbnail;?>
						<h4 class="ice-title"><?php echo substr($row->title, 0, (int) $params->get('title_max_chars',100)) ;?></h4>
					</div>
				</li>
             <?php endforeach; ?> 		
            </ul>
      </div>
 	<!-- END NAVIGATOR //-->
</div>