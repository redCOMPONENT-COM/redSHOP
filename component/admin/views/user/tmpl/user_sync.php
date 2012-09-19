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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<div id="element-box">
<div class="t">
<div class="t">
<div class="t"></div>
</div>

</div>
<div class="m">
<div>
<div>
<div>
<?php 
if($this->sync_user){
	echo '<font color=green>';
	echo JText::_("COM_REDSHOP_ADDED");
	echo ' '.$this->sync_user.' ';
	echo JText::_("COM_REDSHOP_YES_SYNC");
	echo '.</font>';
}else{
	echo '<font color=green>';
	echo JText::_('COM_REDSHOP_NO_SYNC');
	echo '!</font>';
}
?>
</div>
</div>
</div>
<div class="clr"></div>
</div>
<div class="b">
<div class="b">
<div class="b"></div>

</div>
</div>
</div>
