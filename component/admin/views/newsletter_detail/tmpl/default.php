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
$model = $this->getModel('newsletter_detail');
$option = JRequest::getVar('option');
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		if (form.name.value == ""){
			alert( "<?php echo JText::_( 'NEWSLETTER_ITEM_MUST_HAVE_A_NAME', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" >

<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->detail->name;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'SUBJECT' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="subject" id="subject" size="75" maxlength="250" value="<?php echo $this->detail->subject;?>" />
				<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_SUBJECT' ), JText::_( 'SUBJECT' ), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="deliverytime">
					<?php echo JText::_( 'NEWSLETTER_BODY' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $editor->display("body",$this->detail->body,'$widthPx','$heightPx','100','20');	?>
			</td>
		</tr>
	 	<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'TEMPLATE' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['newsletter_template']; ?>&nbsp;
				<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_TEMPLATE' ), JText::_( 'TEMPLATE' ), 'tooltip.png', '', '', false); ?>
				<?php
				if($this->detail->template_id!=0)
				{
				?>
				<span style="width:10%;">
					<a class="modal" href="index3.php?option=<?php echo $option ?>&view=template_detail&task=edit&showbuttons=1&cid[]=<?php echo $this->detail->template_id; ?>" rel="{handler: 'iframe', size: {x: 900, y: 500}}">
						<?php echo JText::_( 'EDIT_TEMPLATE' ); ?>
					</a>
				</span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'PUBLISHED' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('NEWSLETTER_FIXED_TAGS'); ?></legend>
			<table class="admintable">
				<tr>
					<td><?php echo JText::_('NEWSLETTER_FIXED_TAGS_HINT'); ?></td>
				</tr>
			</table>
	</fieldset>
</div>
<?php
$tags = $model->getnewslettertexts();
if(count($tags)>0)
{
?>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('NEWSLETTER_TAGS_HINT'); ?></legend>
			<table class="admintable">
				<?php
					//Geeting the Text library texts for the newsletter section
					for($i=0;$i<count($tags);$i++)
					{
					?>
						<tr>
							<td width="100" align="right" class="key">
								<?php echo $tags[$i]->text_desc; ?>:
							</td>
							<td>
								<?php echo "{".$tags[$i]->text_name."}"; ?>
							</td>
						</tr>
		  	   <?php } ?>
			</table>
	</fieldset>
</div>
<?php } ?>
<div class="clr"></div>

<input type="hidden" name="cid[]" value="<?php echo $this->detail->newsletter_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="newsletter_detail" />
</form>