<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHtml::_('redshopjquery.ui');

/**
 * $displayData extract
 *
 * @var   array  $displayData     Extra field data
 * @var   array  $rowData         Extra field data
 * @var   object $rowData         Extra field data
 * @var   string $extraFieldLabel Extra field label
 * @var   string $required        Extra field required
 * @var   string $requiredLabel   Extra field required label
 * @var   string $errorMsg        Extra field error message
 * @var   array  $dataTxt         Extra field data text
 * @var   object $dataValue       Extra field data
 */
extract($displayData);
?>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("a#add_<?php echo $rowData->name ?>").on('click', function (e) {
                e.preventDefault();
                var extra_field_name     = '<?php echo $rowData->name ?>';
                var html_acceptor        = $('#html_' + extra_field_name);
                var total_elm            = html_acceptor.children('div').length + 1;
                var extra_field_doc_html = '<div id="div_' + extra_field_name + total_elm + '" class="ui-helper-clearfix well">';
                extra_field_doc_html += '<input type="text" value="" id="text_' + extra_field_name + total_elm + '" errormsg="" reqlbl="" name="text_' + extra_field_name + '[]">';
                extra_field_doc_html += '<input type="file" id="file_' + extra_field_name + total_elm + '" name="' + extra_field_name + '[]" class="">';
                extra_field_doc_html += '<a class="btn btn-danger pull-left" href="#" class="rsDocumentDelete" title="' + extra_field_name + '" id="remove_' + extra_field_name + total_elm + '"><?php echo JText::_('COM_REDSHOP_DELETE') ?></a>';
                extra_field_doc_html += '</div>';

                html_acceptor.append(extra_field_doc_html);
                $('#div_' + extra_field_name + total_elm).effect('highlight');
            });

            $('#html_<?php echo $rowData->name ?>').on('click', 'a.rsDocumentDelete', function (e) {
                e.preventDefault();
                $(this).parent('div').effect('highlight', {}, 500, function () {
                    $(this).remove();
                });
            });
        });
    })(jQuery);
</script>
<td valign="top" width="100" align="right" class="key">
	<?php echo $extraFieldLabel; ?>
</td>
<td>
    <a href="#" class="btn btn-success" title="<?php echo $rowData->name; ?>" id="add_<?php echo $rowData->name; ?>">
        <i class="fa fa-plus-circle"></i>&nbsp;<?php echo JText::_('COM_REDSHOP_ADD'); ?>
    </a>
    <br />
    <div id="html_<?php echo $rowData->name; ?>">
		<?php if (!empty($dataTxt)) : ?>
			<?php $idx = 0; ?>
			<?php foreach ($dataTxt as $text => $value) : ?>
				<?php $idx++; ?>
                <div id="div_<?php echo $rowData->name . $idx; ?>" class="well">
                    <input type="text" name="text_<?php echo $rowData->name; ?>[]"
                           id="text_<?php echo $rowData->name . $idx; ?>" value="<?php echo $text; ?>"
						<?php echo $required; ?>
						<?php echo $requiredLabel; ?>
						<?php echo $errorMsg; ?> />
                    &nbsp;
                    <input type="file" name="<?php echo $rowData->name; ?>[]" id="<?php echo $rowData->name . $idx; ?>"
                           class="<?php echo $rowData->class; ?>"/>
					<?php $destinationPrefix = REDSHOP_FRONT_DOCUMENT_ABSPATH . 'extrafields/'; ?>
					<?php $destinationPrefixAbsolute = REDSHOP_FRONT_DOCUMENT_RELPATH . 'extrafields/'; ?>
					<?php $destinationPrefixDel = '/components/com_redshop/assets/document/extrafields/'; ?>
					<?php $mediaImage = $destinationPrefixAbsolute . $value; ?>

					<?php if (JFile::exists($mediaImage)): ?>
						<?php $mediaImage = $destinationPrefix . $value; ?>
						<?php $mediaType = strtolower(JFile::getExt($value)); ?>
						<?php if ($mediaType == 'jpg' || $mediaType == 'jpeg' || $mediaType == 'png' || $mediaType == 'gif') : ?>
                            <div id="docdiv<?php echo $idx; ?>">
                                <img style="max-width: 200px; max-height: 200px;" src="<?php echo $mediaImage; ?>" border="0">
                                <a href="#" class="btn btn-danger" onclick="delimg('<?php echo $value ?>',
                                'div_<?php echo $rowData->name . $idx; ?>', '<?php echo $destinationPrefixDel; ?>',
                                '<?php echo $dataValue->data_id . ':document'; ?>');">
                                    <i class="fa fa-times"></i>&nbsp;<?php echo JText::_('COM_REDSHOP_REMOVE_FILE'); ?>
                                </a>
                                &nbsp;
                                <input type="hidden" name="<?php echo $rowData->name; ?>[]"
                                        id="<?php echo $rowData->name; ?>" class="<?php echo $rowData->class; ?>"
                                        value="<?php echo $value; ?>" />
                            </div>
						<?php else: ?>
                            <div id="docdiv<?php echo $idx; ?>">
                                <a href="<?php echo $mediaImage; ?>" target="_blank"><?php echo $value; ?></a>
                                <a href="#" class="btn btn-danger"
                                   onclick="delimg('<?php echo $value ?>', 'div_<?php echo $rowData->name . $idx; ?>', '<?php echo $destinationPrefixDel; ?>', '<?php echo $dataValue->data_id . ':document'; ?>');">
                                    <i class="fa fa-times"></i>&nbsp;<?php echo JText::_('COM_REDSHOP_REMOVE_FILE'); ?>
                                </a>
                                <input type="hidden" name="<?php echo $rowData->name; ?>[]"
                                        id="<?php echo $rowData->name; ?>" class="<?php echo $rowData->class; ?>"
                                        value="<?php echo $value; ?>" />
                            </div>
						<?php endif; ?>
					<?php else: ?>
						<?php echo JText::_('COM_REDSHOP_FILE_NOT_EXIST') ?>
					<?php endif; ?>
                </div>
			<?php endforeach; ?>
		<?php endif; ?>
    </div>
</td>
