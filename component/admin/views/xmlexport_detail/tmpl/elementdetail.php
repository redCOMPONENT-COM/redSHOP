<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');

$jinput        = JFactory::getApplication()->input;
$section_type  = $jinput->get('section_type');
$parentsection = $jinput->get('parentsection');
$model         = $this->getModel('xmlexport_detail');
$uri           = JURI::getInstance();
$url           = $uri->root(); ?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (form.element_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_XMLEXPORT_CHILD_ELEMENT_NAME', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url); ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<div class="col50">
		<table class="admintable table">
			<tr>
				<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_XMLEXPORT_ELEMENT_NAME'); ?>
					:
				</td>
				<td><input type="text" name="element_name" id="element_name" value="<?php echo $this->childname; ?>"/>
				</td>
			</tr>
			<tr>
				<th><?php echo JText::_('COM_REDSHOP_FIELD_NAME'); ?></th>
				<th><?php echo JText::_('COM_REDSHOP_XMLEXPORT_FILE_TAG_NAME'); ?></th>
			</tr>
			<?php    for ($i = 0; $i < count($this->columns); $i++)
			{
				?>
				<tr>
					<td width="100" align="right" class="key"><?php echo $this->columns[$i]->Field; ?>:</td>
					<td><input type="text" name="<?php echo $this->columns[$i]->Field; ?>"
					           id="<?php echo $this->columns[$i]->Field; ?>"
					           value="<?php echo $this->colvalue[$i]; ?>"/></td>
				</tr>
			<?php }    ?>
			<tr>
				<td width="100" align="right" class="key">&nbsp;</td>
				<td><input type="submit" name="btnsubmit" id="btnsubmit"
				           value="<?php echo JText::_('COM_REDSHOP_SAVE'); ?>"/></td>
			</tr>
		</table>
	</div>

	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->xmlexport_id; ?>"/>
	<input type="hidden" name="xmlexport_id" id="xmlexport_id" value="<?php echo $this->detail->xmlexport_id; ?>"/>
	<input type="hidden" name="task" id="task" value="setChildElement"/>
	<input type="hidden" name="section_type" id="section_type" value="<?php echo $section_type; ?>"/>
	<input type="hidden" name="parentsection" id="parentsection" value="<?php echo $parentsection; ?>"/>
	<input type="hidden" name="view" value="xmlexport_detail"/>
</form>
