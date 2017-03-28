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
JHtml::_('behavior.modal', 'a.joom-box');

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

		if (form.sample_name.value == 1) {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_SAMPLE_NAME', true ); ?>");
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
							<?php echo JText::_('COM_REDSHOP_SAMPLE_NAME'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="sample_name" id="sample_name" size="75"
						       maxlength="250" value="<?php echo $this->detail->sample_name; ?>"/>
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


		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_CATALOG_COLOUR'); ?></legend>


			<table cellpadding="0" cellspacing="5" border="0">
				<tr>
					<th><?php echo JText::_('COM_REDSHOP_COLOUR_CODE'); ?> : <input class="inputbox" type="text"
					                                                                name="color_code_1"
					                                                                id="color_code_1"/>
					</th>
					<td>
						<div id="colorSelector">
							<div style="background-color: #0000ff"></div>
						</div>
					</td>
					<TD><?php echo JText::_('COM_REDSHOP_OR'); ?></TD>
					<td><?php echo JText::_('COM_REDSHOP_COLOUR_IMAGE'); ?> :</td>
					<td><?php $ilink = JRoute::_('index.php?option=com_media&view=images&tmpl=component&e_name=text');  ?>
						<div class="button2-left">
							<div class="image"><a class="joom-box" title="Image" href="<?php echo $ilink; ?>"
							                      rel="{handler: 'iframe', size: {x: 570, y: 400}}">Image</a></div>
						</div>
					</td>
					<th>
						<input type="button" name="addvalue" id="addvalue" class="button"
						       Value="<?php echo JText::_('COM_REDSHOP_ADD_COLOR'); ?>"
						       onclick="addNewcolor('extra_table');"/>
						<input type="hidden" name="catalog_image" id="catalog_image"/>
					</th>
					<th>
						<div id="image_dis">
							<img src="" id="image_display" style="display:none;" border="0"/>
						</div>
					</th>
				</tr>

			</table>
			<table cellpadding="0" cellspacing="5" border="0" id="extra_table">
				<tr>
					<th><?php echo JText::_('COM_REDSHOP_COLOUR_IMAGE'); ?></th>
					<th><?php echo JText::_('COM_REDSHOP_DELETE'); ?></th>
				</tr>
				<?php
				for ($j = 0; $j < count($this->lists['color_data']); $j++)
				{

					echo'<tr>';
					if ($this->lists['color_data'][$j]->is_image == 0)
						echo '<td><input type="hidden" name="is_image[]" value="0" id="is_image[]"><div style=" width:100px:height:100px;background-color:' . $this->lists['color_data'][$j]->code_image . ';">&nbsp;</div></td>';
					else
						echo '<td><input type="hidden" name="is_image[]" value="1" id="is_image[]"><img src="' . $url . $this->lists['color_data'][$j]->code_image . '" border="0" /></td>';

					echo '<td><input type="hidden" name="code_image[]" value="' . $this->lists['color_data'][$j]->code_image . '" id="code_image[]"><input value="Delete" onclick="deletecolor(this)" class="button" type="button" /><input type="hidden" name="colour_id[]" id="colour_id[]"></td>';
					echo '</tr>';

				}
				?>
			</table>
		</fieldset>

		<div class="clr"></div>
		<input type="hidden" value="0" name="total_extra" id="total_extra">
		<input type="hidden" name="cid[]" value="<?php echo $this->detail->sample_id; ?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="view" value="sample_detail"/>
	</form>
</fieldset>
<script>
	function jInsertEditorText(text, editor) {

		if (text) {
			var path_url = "<?php echo $url;?>";
			var fpath = text.split('<img src="');
			var path = fpath[1].split('"');
			document.getElementById("catalog_image").value = path[0];
			main_path = path_url + path[0];
			document.getElementById("image_display").style.display = "block";
			document.getElementById("image_display").src = main_path;
		}
		else {
			document.getElementById("catalog_image").value = "";
			document.getElementById("image_display").src = "";
		}

	}
</script>
