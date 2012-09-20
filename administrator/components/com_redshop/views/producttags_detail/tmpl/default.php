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

JHTML::_('behavior.tooltip');

$now	=& JFactory::getDate();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
	submitbutton(pressbutton);
	}

submitbutton = function(pressbutton) 
	{
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
	
		if (form.tags_name.value == ""){
			alert( "<?php echo JText::_('COM_REDSHOP_TAGS_NAME_MUST_FILLED', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DETAILS' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_('COM_REDSHOP_TAGS_NAME' ); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="tags_name" id="tags_name" size="32" maxlength="250" value="<?php echo $this->detail->tags_name;?>" />
					<?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_TAGS_NAME' ), JText::_('COM_REDSHOP_TAGS_NAME' ), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>						 
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_PUBLISHED' ); ?>:
				</td>
				<td>
					<?php echo $this->lists['published']; ?>
				</td>
			</tr>			
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->tags_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="tags_counter" value="<?php echo $this->detail->tags_counter; ?>" />
<input type="hidden" name="view" value="producttags_detail" />
</form>