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
$editor = JFactory::getEditor();

$editor = JFactory::getEditor();
$uri = JURI::getInstance();
$url = $uri->root();

?>
<script type="text/javascript" language="javascript">var J = jQuery.noConflict();</script>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.catalog_name.value == 1) {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_CATALOG_NAME', true ); ?>");
			return false;
		}
		else {
			submitform(pressbutton);
		}
	}
</script>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_DETAIL'); ?></legend>

	<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">

		<div class="col50">

			<table class="admintable table">
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_CATALOG_NAME'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="catalog_name" id="catalog_name" size="75"
						       maxlength="250" value="<?php echo $this->detail->catalog_name; ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
					</td>
					<td><?php echo $this->lists['published']; ?>
					</td>
				</tr>

			</table>

		</div>

		<div class="clr"></div>
		<input type="hidden" value="0" name="total_extra" id="total_extra">
		<input type="hidden" name="cid[]" value="<?php echo $this->detail->catalog_id; ?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="view" value="catalog_detail"/>
		<input type="hidden" name="layout" value="detail"/>
	</form>
</fieldset>
