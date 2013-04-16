<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

JHTMLBehavior::modal();

jimport('joomla.filesystem.file');

$uri = JURI::getInstance();
$url = $uri->root();

$option = JRequest::getVar('option');

$model = $this->getModel('media');

$media_id = JRequest::getInt('media_id');

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.additionaladminForm;

		if (pressbutton == 'save') {
			form.submit();
			return;
		}

	}

	function addNewRow(tableRef) {

		var myTable = document.getElementById(tableRef);
		var tBody = myTable.getElementsByTagName('tbody')[0];
		var newTR = document.createElement('tr');
		var newTD = document.createElement('td');
		var newTD1 = document.createElement('td');

		newTD.innerHTML = '';
		newTD1.innerHTML = '<input type="file" name="downloadfile[]" value="" id="downloadfile[]" size="75"><input value="Delete" onclick="deleteRow(this)" class="button" type="button" />';
		newTR.appendChild(newTD);
		newTR.appendChild(newTD1);
		tBody.appendChild(newTR);

	}


	//******************************** Delete Poperty Element ******************

	function deleteRow(r) {
		var i = r.parentNode.parentNode.rowIndex;
		document.getElementById('admintable').deleteRow(i);
	}
</script>

<fieldset class="adminform">
	<div style="float: right">
		<button type="button" onclick="submitbutton('save');">
			<?php
			echo JText::_('COM_REDSHOP_SAVE');
			?>
		</button>
		<button type="button"
		        onclick="window.parent.SqueezeBox.close();">
			<?php
			echo JText::_('COM_REDSHOP_CANCEL');
			?>
		</button>
	</div>
	<div class="configuration"><?php
		echo JText::_('COM_REDSHOP_ADDITIONAL_DOWNLOAD_FILES');
		?></div>
</fieldset>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="additionaladminForm" id="additionaladminForm"
      enctype="multipart/form-data">
	<div class="col50">
		<fieldset class="adminform">
			<table class="admintable" border="0" width="100%" id="admintable">
				<tr>
					<td class="key"><?php echo JText::_('COM_REDSHOP_DOWNLOAD_FOLDER');?></td>
					<td><?php $down_ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs&fdownload=1'); ?>
						<div class="button2-left">
							<div class="image" style="padding-top: 0px !important"><a class="modal" title="Image"
							                                                          href="<?php echo $down_ilink; ?>"
							                                                          rel="{handler: 'iframe', size: {x: 950, y: 450}}"><?php echo JText::_('COM_REDSHOP_FILE'); ?></a>
							</div>
						</div>
						<div id='selected_file'></div>
						<input type="hidden" name="hdn_download_file" id="hdn_download_file"/>
						<input type="hidden" name="hdn_download_file_path" id="hdn_download_file_path"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="name"><?php echo JText::_('COM_REDSHOP_ADDITIONAL_FILES'); ?>:</label>
					</td>
					<td>
						<input type="file" name="downloadfile[]" id="downloadfile[]" value="" size="75"/>
						<input type="button" name="addvalue" id="addvalue" class="button"
						       Value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>" onclick="addNewRow('admintable');"/>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="id" value=""/>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="media_id" value="<?php echo $media_id; ?>"/>
	<input type="hidden" name="task" value="saveAdditionalFiles"/>
	<input type="hidden" name="view" value="media"/>
</form>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_FILES');?></legend>
	<?php

	$additionalfiles = $model->getAdditionalFiles($media_id);
	$k = 0;
	for ($i = 0; $i < count($additionalfiles); $i++)
	{

		$filename = $additionalfiles[$i]->name;
		$fileId = $additionalfiles[$i]->id;

		$link = JURI::root() . "/components/com_redshop/assets/download/product/" . $filename;

		$link_delete = "index.php?tmpl=component&option=com_redshop&view=media&task=deleteAddtionalFiles&fileId=" . $fileId . "&media_id=" . $media_id;

		$path = JPATH_ROOT . '/components/com_redshop/assets/download/product/' . $filename;

		$fileExt = strtolower(JFile::getExt($filename));

		?>
		<table class="adminlist" border="0" width="100%" id="admintable">
			<tr class="<?php
			echo "row$k";
			?>">
				<td width="70%">
					<?php
					if (is_file($path))
					{

						if ($fileExt == 'gif' || $fileExt == 'png' || $fileExt == 'jpg' || $fileExt == 'jpeg')
						{
							?>
							<a href="<?php echo $link; ?>" class="modal"
							   rel="{handler: 'image', size: {}}"><?php echo $filename;?></a>
						<?php
						}
						else
						{
							?>
							<a href="<?php echo $link; ?>"><?php echo $filename;?></a>
						<?php
						}
					}
					else
					{
						echo $filename;
					}?>
				</td>
				<td><a href="<?php echo $link_delete; ?>"><?php echo JText::_('COM_REDSHOP_DELETE');?></a></td>
			</tr>
		</table>
		<?php
		$k = 1 - $k;
		$k++;
	}

	?>
</fieldset>
<script language="javascript">
	function jdownload_file(path, filename) {
		document.getElementById("selected_file").innerHTML = filename;
		document.getElementById("hdn_download_file_path").value = path;
		document.getElementById("hdn_download_file").value = filename;
	}
</script>
