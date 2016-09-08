<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$producthelper = productHelper::getInstance();

$config = Redconfiguration::getInstance();
$section = JRequest::getVar('section');
$view = JRequest::getVar('view');

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		submitform(pressbutton);
	}
</script>
<form name="adminForm" id="adminForm" method="post" action="index.php">
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="view" value="<?php echo $view ?>"/>
	<input type="hidden" name="section" value="<?php echo $section ?>"/>
	<input type="hidden" name="task" value=""/>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminlist table table-striped table-hover">
		<thead>
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_SECTION_PERMISSION')?></th>
			<th><?php echo JText::_('COM_REDSHOP_SEE_VIEW')?></th>
			<?php if ($section != 'statistic' && $section != 'configuration' && $section != 'wizard'): ?>
				<th><?php echo JText::_('COM_REDSHOP_ADD')?></th>
				<th><?php echo JText::_('COM_REDSHOP_EDIT')?></th>
				<th><?php echo JText::_('COM_REDSHOP_DELETE')?></th>
			<?php endif;?>
		</tr>
		</thead>
		<?php
		$i = 0;$k = 0;


		unset($this->groups['30']);
		unset($this->groups['29']);

		foreach ($this->groups as $groupValue => $groupName)
		{
			/*if( $groupValue < 23  ):
				continue;
			endif;*/
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php /*if( $groupValue >= 23 ):

              		   endif;*/
					echo strip_tags($groupName);
					?>

				</td>
				<?php if ($groupValue == 8)
				{ ?>
					<td align="center"><input type="checkbox" value="1"
					                          name="groupaccess_<?php echo $groupValue ?>[view]"
					                          id="access_section<?php echo $groupValue ?>" checked='checked' DISABLED/>
					</td>
					<?php if ($section != 'statistic' && $section != 'configuration' && $section != 'wizard'): ?>
					<td align="center"><input type="checkbox" value="1"
					                          name="groupaccess_<?php echo $groupValue ?>[add]"
					                          id="add<?php echo $groupValue ?>" checked='checked' DISABLED/></td>
					<td align="center"><input type="checkbox" value="1"
					                          name="groupaccess_<?php echo $groupValue ?>[edit]"
					                          id="edit<?php echo $groupValue ?>" checked='checked' DISABLED/></td>
					<td align="center"><input type="checkbox" value="1"
					                          name="groupaccess_<?php echo $groupValue ?>[delete]"
					                          id="delete<?php echo $groupValue ?>" checked='checked' DISABLED/></td>
				<?php endif; ?>
				<?php }
				else
				{ ?>
					<td align="center"><input type="checkbox" value="1"
					                          name="groupaccess_<?php echo $groupValue ?>[view]"
					                          id="access_section<?php echo $groupValue ?>" <?php if (@$this->accessmanager[$i]->view == 1)
						{
							echo $chk = "checked='checked'";
						}
						else
						{
							echo $chk = "";
						}?>  /></td>
					<?php if ($section != 'statistic' && $section != 'configuration' && $section != 'wizard'): ?>
					<td align="center"><input type="checkbox" value="1"
					                          name="groupaccess_<?php echo $groupValue ?>[add]"
					                          id="add<?php echo $groupValue ?>" <?php if (@$this->accessmanager[$i]->add == 1)
						{
							echo $chk = "checked='checked'";
						}
						else
						{
							echo $chk = "";
						}?>  /></td>
					<td align="center"><input type="checkbox" value="1"
					                          name="groupaccess_<?php echo $groupValue ?>[edit]"
					                          id="edit<?php echo $groupValue ?>" <?php if (@$this->accessmanager[$i]->edit == 1)
						{
							echo $chk = "checked='checked'";
						}
						else
						{
							echo $chk = "";
						}?>  /></td>
					<td align="center"><input type="checkbox" value="1"
					                          name="groupaccess_<?php echo $groupValue ?>[delete]"
					                          id="delete<?php echo $groupValue ?>" <?php if (@$this->accessmanager[$i]->delete == 1)
						{
							echo $chk = "checked='checked'";
						}
						else
						{
							echo $chk = "";
						}?>  /></td>
				<?php endif; ?>
				<?php }?>
			</tr>
			<?php
			$i++;
			$k++;
		}
		?>

	</table>
</form>
