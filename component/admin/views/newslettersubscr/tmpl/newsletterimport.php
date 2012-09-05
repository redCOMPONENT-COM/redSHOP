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

$editor =& JFactory::getEditor();

JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
JHTMLBehavior::modal();

?>
<script language="javascript" type="text/javascript">

function submitform(pressbutton){
var form = document.adminForm;

   if (pressbutton)
    {
	    form.task.value=pressbutton;

	}

	 if ((pressbutton=='importdata') || (pressbutton=='back') )
	 {
	  form.view.value="newslettersubscr";

	 }
	try {
		form.onsubmit();
		}
	catch(e){}

	form.submit();
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >

<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'IMPORT_NEWSLETTER_SUBS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_('COM_REDSHOP_SEPRATOR' ); ?>:
			</td>
			<td>
				<input type="text" name="separator" size="1" value="," />
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'NEWSLETTER_SELECT_NEWSLETTER' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['newsletters']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="deliverytime">
					<?php echo JText::_( 'NEWSLETTER_SUBSCR_EMAIL_IMPORT' ); ?>:
				</label>
			</td>
			<td>
			<input type="file" name="file" size="53" />
			<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_NEWSLETTER_BROWSE' ), JText::_( 'NEWSLETTER_BROWSE' ), 'tooltip.png', '', '', false); ?>
	 		</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="newslettersubscr" />
</form>