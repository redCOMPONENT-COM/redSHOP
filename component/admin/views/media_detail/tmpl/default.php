<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.joom-box');

$editor = JFactory::getEditor();
$jinput = JFactory::getApplication()->input;
$post = $jinput->post->getArray();

jimport('joomla.filesystem.file');

$url = JUri::root();

$showbuttons   = $jinput->get('showbuttons');
$section_id    = $jinput->get('section_id');
$section_name  = $jinput->get('section_name');
$media_section = $jinput->get('media_section');
$k = 0;

JFactory::getDocument()->addScriptDeclaration('
(function ($) {
	$(document).ready(function () {
		$("#media_section").on("change", function(){
			$("#section_id").select2("val","");
		});
		var media_type = $("select[name=media_type]").val();

		if (media_type == "youtube"){
			$("#youtube-wrapper").show();
			$("#media_data").hide();
		}
		else{
			$("#youtube-wrapper").hide();
			$("#media_data").show();
		}

		$("select[name=media_type]").on("change", function(){
			var value = $(this).val();
			if (value == "youtube"){
				$("#youtube-wrapper").show();
				$("#media_data").hide();
			}
			else{
				$("#youtube-wrapper").hide();
				$("#media_data").show();
			}
		});
	});
})(jQuery);
function jimage_insert(main_path) {
	var path_url = "' . $url . '";
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
	document.getElementById("selected_file").innerHTML = filename;
	document.getElementById("hdn_download_file_path").value = path;
	document.getElementById("hdn_download_file").value = filename;
}
');

if ($showbuttons)
{
	?>
    <fieldset>
        <div style="float: right">
            <button type="button" class="btn btn-small" onclick="submitbutton('save');">
				<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
            </button>
            <button type="button" class="btn btn-small" onclick="goback();">
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

    /**
     * Cancel submit and also alert with message
     *
     * @param message
     * @returns {boolean}
     */
    function cancelSubmit(message) {
        alert(message);
        return false;
    }

    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        var mediaSection = '<?php echo $media_section;?>';

        if (pressbutton == 'cancel') {
            Joomla.submitform(pressbutton);
            return;
        }

        // Upload zip images
        if (form.bulk.value == 0) {
            return cancelSubmit('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_BULK_OPTION', true); ?>');
        }
        else {
            // None zip images
            switch (form.media_type.value) {
                case 'youtube':
                    break;
                default:
                    <?php $input = JFactory::getApplication()->input; ?>
                    <?php $checkCid = $input->get('cid', []); ?>
                    var checkCid = '<?php echo count($checkCid)? 'true': 'false'; ?>';

                    if (checkCid == 'false' && form.file.value == '' && form.media_bank_image.value == '')
                    {
                        return cancelSubmit('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_FILE', true); ?>');   
                    }

                    if (mediaSection == 'product') {
                        if (form.hdn_download_file.value == '' && form.file == '') {
                            return cancelSubmit('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_FILE', true); ?>');
                        }
                    }
                    // Make sure media type is selected
                    if (form.media_type.value == 0) {
                        return cancelSubmit('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_MEDIA_TYPE', true); ?>');
                    }
                    // Make sure section is selected
                    if (form.media_section.value == 0) {
                        return cancelSubmit('<?php echo JText::_('COM_REDSHOP_SELECT_MEDIA_SECTION_FIRST', true); ?>');
                    }

                    if (form.section_id.value == '' && form.media_section.value != 'media') {
                        return cancelSubmit('<?php echo JText::_('COM_REDSHOP_TYPE_SECTION_NAME', true); ?>');
                    }
            }
            // Have done now submit it
            Joomla.submitform(pressbutton);
        }
    }
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12" id="media_data">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_REDSHOP_VALUE') ?></legend>
		        <?php if ($media_section != 'manufacturer' || $media_section != 'catalog'):
			        if ($this->detail->media_id == 0)
			        {
				        ?>
                        <table>
                            <tr>
                                <td><span
                                            id="uploadbulk"><?php echo JText::_('COM_REDSHOP_YOU_WANT_TO_UPLOAD_ZIP_FILE'); ?>
                                        ?</span></td>
                                <td><span
                                            id="bulk"><?php echo $this->lists['bulk']; ?></span>&nbsp;&nbsp;&nbsp;<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_YOU_WANT_TO_UPLOAD_ZIP_FILE'), JText::_('COM_REDSHOP_YOU_WANT_TO_UPLOAD_ZIP_FILE'), 'tooltip.png', '', '', false); ?>
                                </td>

                            </tr>
                        </table>
				        <?php
			        }
			        else
			        {
				        echo '<input type="hidden" name="bulk" value="bulk">';
			        }
			        ?>
		        <?php else: ?>
			        <span id="bulk" style="display:none;"><?php echo $this->lists['bulk'] ?></span>
		        <?php endif; ?>

                <fieldset id="bulk_field"
			        <?php if ($this->detail->media_id == 0)
			        { ?>
                        style="display: none;"
			        <?php } ?>
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
								        $thumbUrl = RedShopHelperImages::getImagePath(
									        $this->detail->media_name,
									        '',
									        'thumb',
									        $this->detail->media_section,
									        Redshop::getConfig()->get('THUMB_WIDTH'),
									        Redshop::getConfig()->get('THUMB_HEIGHT'),
									        Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								        );
								        ?>
								        <?php if ($thumbUrl): ?>
                                            <a class="joom-box btn btn-primary"
                                               href="<?php echo $url . 'components/com_redshop/assets/' . $this->detail->media_type . '/' . $this->detail->media_section . '/' . $this->detail->media_name; ?>"
                                               title="<?php echo JText::_('COM_REDSHOP_VIEW_IMAGE'); ?>"
                                               rel="{handler: 'image', size: {}}">
                                                <img
                                                        src="<?php echo $thumbUrl; ?>"
                                                        alt="image"/>
                                            </a>
							            <?php else: ?>
                                            <small><?php echo $this->detail->media_name; ?></small>
                                            <input type="hidden" name="file[]" id="file" value="<?php echo $this->detail->media_name;?>">
							            <?php endif; ?>
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
                            <td width="2%"><?php $ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs'); ?>
                                <div class="button2-left">
                                    <div class="image">
                                        <a class="joom-box btn btn-primary"
                                           title="Image" href="<?php echo $ilink; ?>"
                                           rel="{handler: 'iframe', size: {x: 1050, y: 450}}">
									        <?php echo JText::_('COM_REDSHOP_IMAGE'); ?>
                                        </a>
                                    </div>
                                </div>
                                <div id="image_dis">
                                    <img src="" id="image_display" style="display:none;" border="0" width="200"/>
                                    <input type="hidden" name="media_bank_image" id="media_bank_image"/>
                                </div>
                            </td>
                            <td><?php echo JText::_('COM_REDSHOP_MEDIA_BANK'); ?></td>
                        </tr>
				        <?php if ($media_section == 'product') : ?>
                            <tr>
                                <td width="2%">
							        <?php $down_ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs&fdownload=1'); ?>
                                    <div class="button2-left">
                                        <div class="image">
                                            <a class="joom-box btn btn-primary"
                                               title="Image"
                                               href="<?php echo $down_ilink; ?>"
                                               rel="{handler: 'iframe', size: {x: 950, y: 450}}">
										        <?php echo JText::_('COM_REDSHOP_FILE'); ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div id='selected_file'></div>
                                    <input type="hidden" name="hdn_download_file" id="hdn_download_file"/>
                                    <input type="hidden" name="hdn_download_file_path" id="hdn_download_file_path"/>
                                </td>
                                <td><?php echo JText::_('COM_REDSHOP_DOWNLOAD_FOLDER'); ?></td>
                            </tr>
				        <?php endif; ?>
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
							        <?php if ($media_section != 'manufacturer'): ?>
                                        <input type="button" name="addvalue" id="addvalue" class="button btn btn-primary"
                                               Value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>"
                                               onclick="addNewRow('extra_table');"/>
							        <?php endif; ?>
                                </td>

                            </tr>

                        </table>
                    </fieldset>
		        <?php } ?>
            </fieldset>
        </div>
    </div>

    <div class="clr"></div>
    <input type="hidden" value="<?php echo isset($k) ? $k : ''; ?>" name="total_extra" id="total_extra">
    <input type="hidden" name="cid[]" value="<?php echo $this->detail->media_id; ?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="view" value="media_detail"/>
    <input type="hidden" name="oldmedia" value="<?php echo $this->detail->media_name; ?>"/>

    <div class="col50">

    </div>
    <div class="col50">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

            <table class="admintable table">
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
                <tr id="youtube-wrapper">
                    <td valign="top" align="right" class="key">
                        <label for="volume">
							<?php echo JText::_('COM_REDSHOP_MEDIA_YOUTUBE_ID'); ?>:
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo $this->detail->media_name; ?>"
                               name="youtube_id">
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MEDIA_YOUTUBE_ID'), JText::_('COM_REDSHOP_MEDIA_YOUTUBE_ID'), 'tooltip.png', '', '', false); ?>
                    </td>
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
                            <input type="hidden" name="set" value="">
                            <input type="hidden" name="media_section" value="<?php echo $media_section; ?>">
							<?php
						}
						elseif ($this->detail->media_id != 0)
						{
							echo '<input type="hidden" name="media_section" id="media_section" value="' . $this->detail->media_section . '">';
						}

						echo $this->lists['section'];
						?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MEDIA_SECTION'), JText::_('COM_REDSHOP_MEDIA_SECTION'), 'tooltip.png', '', '', false); ?>
                    </td>
                </tr>
                <tr id="product_tr">
                    <td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_SECTION_NAME'); ?>
                    </td>
                    <td>
						<?php
						$sectionValue   = new stdClass;
						$sectionIdName  = 'section_id';
						$listAttributes = array();
						$model          = $this->getModel('media_detail');

						if ($showbuttons)
						{
							if ($section_name)
							{
								$sectionValue->text = $section_name;
							}
							else
							{
								if ($data = $model->getSection($section_id, $media_section))
								{
									$sectionValue->text = $data->name;
								}
							}

							$sectionValue->value = $section_id;
							$sectionIdName       = 'disabled_section_id';
							$listAttributes      = array('disabled' => 'disabled');
							echo '<input type="hidden" name="section_id" id="section_id" value="' . $section_id . '" />';
						}
						else
						{
							if ($data = $model->getSection($this->detail->section_id, $this->detail->media_section))
							{
								$sectionValue->text  = $data->name;
								$sectionValue->value = $data->id;
							}
						}

						echo JHtml::_('redshopselect.search', $sectionValue,
							$sectionIdName,
							array(
								'select2.ajaxOptions' => array('typeField' => ', media_section:$(\'#media_section\').val()'),
								'select2.options'     => array('placeholder' => JText::_('COM_REDSHOP_SECTION_NAME'), 'minimumInputLength' => 0),
								'list.attr'           => $listAttributes
							)
						);
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
