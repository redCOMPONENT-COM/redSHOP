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
jimport('joomla.html.pane');
//$editor =& JFactory::getEditor();
?>

<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	submitbutton(pressbutton);
	}

submitbutton = function(pressbutton)
{
	var form = document.adminForm;
	if(pressbutton=='shipping_rate')
	{
		form.view.value="shipping_rate";
	}
	if (pressbutton == 'cancel')
	{
		submitform( pressbutton );
		return;
	}

	if (form.name.value == ""){
		alert( "<?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_MUST_HAVE_A_NAME', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<?php
//Get JPaneTabs instance
$myTabs = JPane::getInstance('tabs', array('startOffset'=>0));
$output = $myTabs->startPane( 'pane' );
//Create 1st Tab
echo $output .= $myTabs->startPanel(JText::_('COM_REDSHOP_DETAILS'), 'tab1' );
?>
	<table class="admintable" width="100%">
	<tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_NAME' ); ?>:
			</label>
		</td>
		<td>
			<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->detail->name;?>"  />
		</td>
	</tr>
	 <tr>
		<td width="100" align="right" class="key">
			<label for="name">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_CLASS' ); ?>:
			</label>
		</td>
		<td><?php echo $this->detail->element;?></td>
	</tr>
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
<?php
echo $myTabs->endPanel();

//Create 2nd Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_CONFIG'), 'tab2' );
?>
<div align="left"><?php
	JPluginHelper::importPlugin('redshop_shipping');
	$dispatcher= JDispatcher::getInstance();
	$payment = $dispatcher->trigger('onShowconfig',array($this->detail));
?>
</div>
<?php
echo $myTabs->endPanel();

echo $myTabs->endPane();
?>
<div class="clr"></div>

<input type="hidden" name="element" value="<?php echo $this->detail->element; ?>" />
<input type="hidden" name="extension_id" value="<?php echo $this->detail->extension_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="shipping_detail" />
<input type="hidden" name="plugin" value="<?php echo $this->detail->folder; ?>" />
</form>
