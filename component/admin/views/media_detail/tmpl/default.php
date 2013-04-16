<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

$editor = JFactory::getEditor();

$post = JRequest::get('post');

JHTMLBehavior::modal();

jimport('joomla.filesystem.file');

$uri = JURI::getInstance();
$url = $uri->root();

$option = JRequest::getVar('option');

$showbuttons = JRequest::getVar('showbuttons');
$section_id = JRequest::getVar('section_id');
$section_name = JRequest::getVar('section_name');
$media_section = JRequest::getVar('media_section');

if ($showbuttons)
{
	?>
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('save');">
				<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
			</button>
			<button type="button" onclick="goback();">
				<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
			</button>
		</div>
		<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_MEDIA'); ?></div>
	</fieldset>
<?php
}
?>

	<script language="javascript" type="text/javascript">
		function goback() {
			history.go(-1);
		}

		Joomla.submitbutton = function (pressbutton) {
			submitbutton(pressbutton);
		}

		submitbutton = function (pressbutton) {

			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform(pressbutton);
				return;
			}

			if (form.bulk.value == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_BULK_OPTION', true ); ?>");
			} else if (form.media_type.value == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_MEDIA_TYPE', true ); ?>");
			} else if (form.media_section.value == 0) {
				alert("<?php echo JText::_('COM_REDSHOP_SELECT_MEDIA_SECTION_FIRST', true ); ?>");
			} else if (form.section_name.value == '' && form.media_section.value != 'media') {

				alert("<?php echo JText::_('COM_REDSHOP_TYPE_SECTION_NAME', true ); ?>");
			} else {
				submitform(pressbutton);
			}

		}
	</script>

	<form onsubmit="javascript:return false;" action="<?php echo JRoute::_($this->request_url) ?>" method="post"
	      name="adminForm" id="adminForm" enctype="multipart/form-data">

	<div class="col50" id="media_data">

		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_VALUE'); ?></legend>
			<?php
			if ($media_section != 'manufacturer' || $media_section != 'catalog')
			{
				if ($this->detail->media_id == 0)
				{
					?>
					<table>
						<tr>
							<td><span
									id="uploadbulk"><?php echo JText::_('COM_REDSHOP_YOU_WANT_TO_UPLOAD_ZIP_FILE');  ?>
									?</span></td>
							<td><span
									id="bulk"><?php echo $this->lists['bulk'];?></span>&nbsp;&nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_YOU_WANT_TO_UPLOAD_ZIP_FILE'), JText::_('COM_REDSHOP_YOU_WANT_TO_UPLOAD_ZIP_FILE'), 'tooltip.png', '', '', false); ?>
							</td>

						</tr>
					</table>
				<?php
				}
				else
				{
					echo '<input type="hidden" name="bulk" value="bulk">';
				}
			}
			else
			{
				echo '<span id="bulk" style="display:none;"><' . $this->lists['bulk'] . '</span>';
			}
			?>

			<fieldset id="bulk_field"
				<?php if ($this->detail->media_id == 0)
			{ ?>
				style="display: none;"
			<?php }?>
				>
				<table cellpadding="0" cellspacing="5" border="0" id="bulk_table">

					<tr>
						<th><?php echo JText::_('COM_REDSHOP_MEDIA_NAME'); ?></th>
						<td>
							<?php

							if ($this->detail->media_name)
							{

								$filetype = strtolower(JFile::getExt($this->detail->media_name));

								if ($filetype == 'png' || $filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif')
								{
									?>
									<a class="modal"
									   href="<?php echo $url . 'components/' . $option . '/assets/' . $this->detail->media_type . '/' . $this->detail->media_section . '/' . $this->detail->media_name; ?>"
									   title="<?php echo JText::_('COM_REDSHOP_VIEW_IMAGE'); ?>"
									   rel="{handler: 'image', size: {}}">
										<img
											src="<?php echo $url; ?>/components/com_redshop/helpers/thumb.php?filename=<?php echo $this->detail->media_section; ?>/<?php echo $this->detail->media_name; ?>&newxsize=<?php echo THUMB_WIDTH; ?>&newysize=<?php echo THUMB_HEIGHT; ?>"
											alt="image"/></a>
								<?php
								}
							}
							?>
						</td>

					</tr>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_MEDIA_NAME'); ?></td>
						<td>
							<?php if ($this->detail->media_id == 0)
							{ ?>
								<input type="file" name="bulkfile" id="bulkfile" size="75">

							<?php
							}
							else
							{
								?>
								<input type="file" name="file[]" id="file" size="75">
							<?php
							}
							?>

						</td>
					</tr>
				</table>
			</fieldset>
			<fieldset id="media_bank">
				<table>
					<tr>
						<td><?php echo JText::_('COM_REDSHOP_MEDIA_BANK');?></td>
						<td><?php $ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs'); ?>
							<div class="button2-left">
								<div class="image"><a class="modal" title="Image" href="<?php echo $ilink; ?>"
								                      rel="{handler: 'iframe', size: {x: 1050, y: 450}}">Image</a>
									<!--<a class="modal" title="Image" href="<?php echo $ilink;?>" rel="{handler: 'iframe', size: {x: 490, y: 400}}">Image</a>
		--></div>
							</div>
							<div id="image_dis">
								<img src="" id="image_display" style="display:none;" border="0" width="200"/>
								<input type="hidden" name="media_bank_image" id="media_bank_image"/>
							</div>
						</td>
					</tr>
					<?php if ($media_section == 'product')
					{ ?>
						<tr>
							<td><?php echo JText::_('COM_REDSHOP_DOWNLOAD_FOLDER');?></td>
							<td><?php $down_ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs&fdownload=1'); ?>
								<div class="button2-left">
									<div class="image"><a class="modal" title="Image" href="<?php echo $down_ilink; ?>"
									                      rel="{handler: 'iframe', size: {x: 950, y: 450}}"><?php echo JText::_('COM_REDSHOP_FILE'); ?></a>
									</div>
								</div>
								<div id='selected_file'></div>
								<input type="hidden" name="hdn_download_file" id="hdn_download_file"/>
								<input type="hidden" name="hdn_download_file_path" id="hdn_download_file_path"/>
							</td>
						</tr>
					<?php } ?>
				</table>
			</fieldset>
			<?php if ($this->detail->media_id == 0)
			{ ?>
				<fieldset id="extra_field">
					<table cellpadding="0" cellspacing="5" border="0" id="extra_table">

						<?php

						$k = 1;
						?>
						<tr>
							<td><?php echo JText::_('COM_REDSHOP_UPLOAD_FILE_FROM_COMPUTER'); ?></td>
							<td><input type="file" name="file[]" id="file" size="75">
								<input type="button" name="addvalue" id="addvalue" class="button"
								       Value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
								       onclick="addNewRow('extra_table');"/>
							</td>

						</tr>

					</table>
				</fieldset>
			<?php } ?>
		</fieldset>


	</div>

	<div class="clr"></div>
	<input type="hidden" value="<?php echo $k; ?>" name="total_extra" id="total_extra">
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->media_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="media_detail"/>
	<input type="hidden" name="oldmedia" value="<?php echo $this->detail->media_name; ?>"/>

	<div class="col50">

	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<?php
					if ($media_section != 'manufacturer')
					{
						?>
						<td valign="top" align="right" class="key">
							<label for="volume">
								<?php echo JText::_('COM_REDSHOP_MEDIA_TYPE'); ?>:
							</label>
						</td>
						<td>
							<?php echo $this->lists['type']; ?><input type="hidden" name="oldtype"
							                                          value="<?php echo $this->detail->media_type; ?>"/>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MEDIA_TYPE'), JText::_('COM_REDSHOP_MEDIA_TYPE'), 'tooltip.png', '', '', false); ?>
						</td>
					<?php
					}
					else
					{
						?>
						<td colspan="2"><input type="hidden" name="media_type" value="images"/><input type="hidden"
						                                                                              name="oldtype"
						                                                                              value="images"/>
						</td>
					<?php
					}
					?>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_MEDIA_ALTERNATE_TEXT'); ?>:
						</label>
					</td>
					<td><input type="text" value="<?php echo $this->detail->media_alternate_text; ?>"
					           name="media_alternate_text">
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MEDIA_ALTERNATE_TEXT'), JText::_('COM_REDSHOP_MEDIA_ALTERNATE_TEXT'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_MEDIA_SECTION'); ?>:
						</label>
					</td>
					<td>
						<?php
						if ($showbuttons)
						{
							?>
							<input type="text" value="<?php echo $media_section; ?>" disabled="disabled"><input
							type="hidden" name="media_section" value="<?php echo $media_section; ?>">
							<input type="hidden" name="set" value="">
						<?php
						}
						else
						{
							echo $this->lists['section'];
							if ($this->detail->media_id != 0) echo '<input type="hidden" name="media_section" value="' . $this->detail->media_section . '">';
						}
						?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MEDIA_SECTION'), JText::_('COM_REDSHOP_MEDIA_SECTION'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr id="product_tr">
					<td valign="top" align="right" class="key">
						<?php    echo JText::_('COM_REDSHOP_SECTION_NAME'); ?>
					</td>
					<td>
						<?php
						if ($showbuttons)
						{
							?>
							<input type="text" name="section_name" id="section_name"
							       value="<?php echo $section_name; ?>" disabled="disabled" size="75"/><input
							type="hidden" name="section_id" id="section_id" value="<?php echo $section_id; ?>"/><input
							type="hidden" name="section_name" id="section_name" value="<?php echo $section_name; ?>"
							size="75"/>
						<?php
						}
						else
						{
							$model = $this->getModel('media_detail');
							$data = $model->getSection($this->detail->section_id, $this->detail->media_section);
							?>
							<input type="text" onclick="return false;" name="section_name" id="section_name"
							       value="<?php if ($data) echo $data->name; ?>" size="75"/><input type="hidden"
							                                                                       name="section_id"
							                                                                       id="section_id"
							                                                                       width="150"
							                                                                       value="<?php if ($data) echo $data->id; ?>"/>
						<?php
						}
						echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SECTION_NAME'), JText::_('COM_REDSHOP_SECTION_NAME'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
					</td>
					<td>
						<?php echo $this->lists['published']; ?>
					</td>
				</tr>
			</table>
		</fieldset>

	</div>

	</form>

	<script type="text/javascript">
		function jimage_insert(main_path) {
			var path_url = "<?php echo $url;?>";
			if (main_path) {
				document.getElementById("image_display").style.display = "block";
				document.getElementById("media_bank_image").value = main_path;
				document.getElementById("image_display").src = path_url + main_path;
			}
			else {
				document.getElementById("media_bank_image").value = "";
				document.getElementById("image_display").src = "";
			}

		}
		function jdownload_file(path, filename) {
			if (document.getElementById("selected_file")) {
				document.getElementById("selected_file").innerHTML = filename;
			}
			if (document.getElementById("hdn_download_file_path")) {
				document.getElementById("hdn_download_file_path").value = path;
			}
			if (document.getElementById("hdn_download_file")) {
				document.getElementById("hdn_download_file").value = filename;
			}
			if (document.getElementById("media_type")) {
				document.getElementById("media_type").value = 'download';
			}
		}
	</script>

<?php
if ($this->detail->media_id == 0)
{
	?>
	<script type="text/javascript">

		function select_type(type) {
			var value = type.value;

			if (value != 'media') {
				document.getElementById('product_tr').style.display = 'table-row';
				var options = {
					script: "index.php?tmpl=component&&option=com_redshop&view=search&media_section=" + value + "&json=true&",
					varname: "input",
					json: true,
					shownoresults: false,
					callback: function (obj) {
						document.getElementById('section_id').value = obj.id;
						return false;
					}
				};
				var as_json = new bsn.AutoSuggest('section_name', options);
			} else {
				try {
					document.getElementById('product_tr').style.display = 'none';

				} catch (ex) {
					document.getElementById('product_tr').style.display = 'table-row';
				}
				document.getElementById('section_name').value = '';
			}

		}
	</script>
<?php
}else
{
	?>
	<script type="text/javascript">

		var options = {
			script: "index.php?tmpl=component&&option=com_redshop&view=search&media_section=<?php echo $this->detail->media_section;?>&json=true&",
			varname: "input",
			json: true,
			shownoresults: false,
			callback: function (obj) {
				document.getElementById("section_id").value = obj.id;
				return false;
			}

		};


		var as_json = new bsn.AutoSuggest('section_name', options);

	</script>

<?php
}
?>
