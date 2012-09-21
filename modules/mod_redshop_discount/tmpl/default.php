<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php' );
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();

?>
<div class="mod_discount_main">
	<div class="mod_discount_title"><?php echo JText::_("COM_REDSHOP_DISCOUNT_DETAIL");?></div>
	<div class="mod_discount_main_value">
	<?php
	for($i=(count($data)-1);$i>=0;$i--){
		for ($j=0;$j<=$i;$j++){
			?>
			<div class="mod_discount_spacer"><?php echo "&nbsp;&nbsp;";?></div>
			<?php
		}
			$data[$i]->condition == '1' ? $cond = '-' : ($data[$i]->condition == '3' ? $cond = '+' : $cond = '');
			$amount = $data[$i]->amount;
			$data[$i]->discount_type == '1' ? $disc = '%' : $disc = REDCURRENCY_SYMBOL;
			$discount_amount = $data[$i]->discount_amount;
	?>
		<div class="mod_discount_value"><?php echo $cond."&nbsp;".$amount."&nbsp; = ".$discount_amount."&nbsp;".$disc; ?></div>
	<?php
	}
	?>
	</div>
</div>