<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

JHtml::_('behavior.modal', 'a.joom-box');

$mediaId = JFactory::getApplication()->input->getInt('media_id');

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		if (pressbutton == 'save')
		{
			document.additionaladminForm.submit();
			return;
		}
	}

	function addNewRow(tableRef)
	{
		var tBody = document.getElementById(tableRef).getElementsByTagName('tbody')[0];
		var newTR = document.createElement('tr');
		var newTD = document.createElement('td');
		var newTD1 = document.createElement('td');

		newTD.innerHTML = '';
		newTD1.innerHTML = '<input type="file" name="downloadfile[]" value="" id="downloadfile[]" size="75"><input value="Delete" onclick="deleteRow(this)" class="button" type="button" />';
		newTR.appendChild(newTD);
		newTR.appendChild(newTD1);
		tBody.appendChild(newTR);
	}

	// Delete Poperty Element
	function deleteRow(r)
	{
		document.getElementById('admintable').deleteRow(r.parentNode.parentNode.rowIndex);
	}
</script>

<div class="container">
	<?php echo JText::_('COM_REDSHOP_ADDITIONAL_DOWNLOAD_FILES');?>
	<div style="float: right">
		<button type="button" onclick="Joomla.submitbutton('save');">
			<?php echo JText::_('COM_REDSHOP_SAVE');?>
		</button>
		<button
			type="button"
			onclick="window.parent.location.reload();window.parent.SqueezeBox.close();"
		>
			<?php echo JText::_('COM_REDSHOP_CANCEL');?>
		</button>
	</div>
</div>

<form
	action="index.php"
	method="post"
	name="additionaladminForm"
	id="additionaladminForm"
	enctype="multipart/form-data">
	<div class="col50">
		<fieldset class="adminform">
			<table class="admintable" border="0" width="100%" id="admintable">
				<tr>
					<td class="key">
						<?php echo JText::_('COM_REDSHOP_DOWNLOAD_FOLDER');?>
					</td>
					<td>
						<div class="button2-left">
							<div
								class="image"
								style="padding-top: 0px !important">
								<a class="joom-box" title="Image"
									href="index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs&fdownload=1"
									rel="{handler: 'iframe', size: {x: 950, y: 450}}">
									<?php echo JText::_('COM_REDSHOP_FILE'); ?>
								</a>
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
						<input
							type="button"
							name="addvalue"
							id="addvalue"
							class="button"
							Value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
							onclick="addNewRow('admintable');"
						/>
					</td>
				</tr>
			</table>

			<div>
				<h4><?php echo JText::_('COM_REDSHOP_FILES');?></h4>
				<table class="table table-striped table-hover" width="100%" id="admintable">
				<?php

				$additionalfiles = $this->getModel('media')->getAdditionalFiles($mediaId);
				$k = 0;

				for ($i = 0, $in = count($additionalfiles); $i < $in; $i++)
				{
					$filename = $additionalfiles[$i]->name;
					$fileId = $additionalfiles[$i]->id;

					$link = JURI::root() . "/components/com_redshop/assets/download/product/" . $filename;

					$link_delete = "index.php?tmpl=component&option=com_redshop&view=media&task=deleteAddtionalFiles&fileId=" . $fileId . "&media_id=" . $mediaId;

					$path = JPATH_ROOT . '/components/com_redshop/assets/download/product/' . $filename;

					$fileExt = strtolower(JFile::getExt($filename));

					?>
						<tr class="<?php echo "row$k"; ?>">
							<td width="70%">
								<?php if (JFile::exists($path)) : ?>
									<?php if ($fileExt == 'gif' || $fileExt == 'png' || $fileExt == 'jpg' || $fileExt == 'jpeg') : ?>
										<a href="<?php echo $link; ?>" class="joom-box" rel="{handler: 'image', size: {}}">
											<?php echo $filename;?>
										</a>
									<?php else: ?>
										<a href="<?php echo $link; ?>"><?php echo $filename;?></a>
									<?php endif; ?>
								<?php else: ?>
									<?php echo $filename; ?>
								<?php endif; ?>
							</td>
							<td>
								<a href="<?php echo $link_delete; ?>">
									<?php echo JText::_('COM_REDSHOP_DELETE');?>
								</a>
							</td>
						</tr>
					<?php
					$k = 1 - $k;
					$k++;
				}
				?>
				</table>
			</div>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="id" value=""/>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="media_id" value="<?php echo $mediaId; ?>"/>
	<input type="hidden" name="task" value="saveAdditionalFiles"/>
	<input type="hidden" name="view" value="media"/>
</form>

<script language="javascript">
	function jdownload_file(path, filename) {
		document.getElementById("selected_file").innerHTML = filename;
		document.getElementById("hdn_download_file_path").value = path;
		document.getElementById("hdn_download_file").value = filename;
	}
</script>
