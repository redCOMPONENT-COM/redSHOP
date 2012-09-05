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

$option = JRequest::getVar('option');
JHTML::_('behavior.tooltip');
$editor =& JFactory::getEditor();
JHTML::_('behavior.modal');
 
$editor =& JFactory::getEditor();
$uri =& JURI::getInstance();
$url= $uri->root();

?>
<script type="text/javascript" language="javascript">var J=jQuery.noConflict();</script>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {		
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		
		if (form.catalog_name.value==1)
		{
			alert( "<?php echo JText::_('ENTER_CATALOG_NAME', true ); ?>" );
			return false;
		} 
		else 
		{
			submitform(pressbutton);
		}
	}
</script>
<fieldset class="adminform">
<legend><?php echo JText::_( 'DETAIL' ); ?></legend>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">

<div class="col50">
	
		<table class="admintable">
		<tr>
			<td valign="top" align="right" class="key">
				<label for="volume">
					<?php echo JText::_( 'CATALOG_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="catalog_name" id="catalog_name" size="75" maxlength="250" value="<?php echo $this->detail->catalog_name;?>" />
			</td>
		</tr>		 
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'PUBLISHED' ); ?>:
			</td>
			<td><?php echo $this->lists['published']; ?>
			</td>
		</tr>
				
	</table>
	
</div>
	 
<div class="clr"></div>
<input type="hidden" value="0" name="total_extra" id="total_extra">
<input type="hidden" name="cid[]" value="<?php echo $this->detail->catalog_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="catalog_detail" />
<input type="hidden" name="layout" value="detail" />
</form>
</fieldset>