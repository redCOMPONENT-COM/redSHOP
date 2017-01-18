<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$data = array(
	// 'categories'                 => 'COM_REDSHOP_IMPORT_CATEGORIES',
	// 'products'                   => 'COM_REDSHOP_IMPORT_PRODUCTS',
	'attributes'                 => 'COM_REDSHOP_IMPORT_ATTRIBUTES',
	// 'manufacturer'               => 'COM_REDSHOP_IMPORT_MANUFACTURER',
	'related_product'            => 'COM_REDSHOP_IMPORT_RELATED_PRODUCTS',
	'fields'                     => 'COM_REDSHOP_IMPORT_FIELDS',
	'users'                      => 'COM_REDSHOP_IMPORT_USERS',
	'shipping_address'           => 'COM_REDSHOP_IMPORT_SHIPPING_ADDRESS',
	'shopperGroupProductPrice'   => 'COM_REDSHOP_IMPORT_SHOPPER_GROUP_PRODUCT_SPECIFIC_PRICE',
	'shopperGroupAttributePrice' => 'COM_REDSHOP_IMPORT_SHOPPER_GROUP_ATTRIBUTE_SPECIFIC_PRICE',
	'product_stockroom_data'     => 'COM_REDSHOP_PRODUCT_STOCKROOM_DATA'
);

// Defines encoding used in import
$characterSets = array(
	'ISO-8859-1'  => 'COM_REDSHOP_IMPORT_CHARS_ISO88591',
	'ISO-8859-5'  => 'COM_REDSHOP_IMPORT_CHARS_ISO88595',
	'ISO-8859-15' => 'COM_REDSHOP_IMPORT_CHARS_ISO885915',
	'UTF-8'       => 'COM_REDSHOP_IMPORT_CHARS_UTF8',
	'cp866'       => 'COM_REDSHOP_IMPORT_CHARS_CP866',
	'cp1251'      => 'COM_REDSHOP_IMPORT_CHARS_CP1251',
	'cp1252'      => 'COM_REDSHOP_IMPORT_CHARS_CP1252',
	'KOI8-R'      => 'COM_REDSHOP_IMPORT_CHARS_KOI8R',
	'BIG5'        => 'COM_REDSHOP_IMPORT_CHARS_BIG5',
	'GB2312'      => 'COM_REDSHOP_IMPORT_CHARS_GB2312',
	'BIG5-HKSCS'  => 'COM_REDSHOP_IMPORT_CHARS_BIG5HKSCS',
	'Shift_JIS'   => 'COM_REDSHOP_IMPORT_CHARS_SHIFTJIS',
	'EUC-JP'      => 'COM_REDSHOP_IMPORT_CHARS_EUCJP',
	'MacRoman'    => 'COM_REDSHOP_IMPORT_CHARS_MACROMAN'
);

// Creating JOption for JSelect box.
foreach ($characterSets as $char => $name)
{
	$title       = sprintf(JText::_($name), $char);
	$encodings[] = JHTML::_('select.option', $char, $title);
}

?>
<script type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton)
		{
			form.task.value = pressbutton;
		}

		if (form.separator.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CSV_COLUMN_SEPARATOR_NOT_NULL', true) ?>");

			return false;
		}

		submitform(pressbutton);
	}
</script>
<?php if (!empty($this->result)): ?>
	<p><?php echo $this->result; ?></p>
	<hr />
<?php endif; ?>
<form action="index.php?option=com_redshop&view=import" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<fieldset class="adminform form-vertical">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_IMPORT_CONFIGURATION') ?></h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label class="control-label"><?php echo JText::_('COM_REDSHOP_SEPRATOR') ?></label>
							<input class="form-control" type="text" name="separator" maxlength="1" size="1" value=","/>
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo JText::_('COM_REDSHOP_IMPORT_ENCODING') ?></label>
							<?php echo JHTML::_(
								'select.genericlist',
								$encodings,
								'encoding',
								'class="form-control disableBootstrapChosen"',
								'value',
								'text',
								'UTF-8'
								);
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_IMPORT_SELECT_TYPE') ?></h3>
					</div>
					<div class="box-body">
						<table class="adminlist table table-hover" id="table-importtype">
							<?php foreach ($data as $value => $text): ?>
								<tr class="disabled">
									<td width="30%">
										<label class="lbl-select-type form-control no-border radio-inline" style="background: transparent;">
											<strong><?php echo JText::_($text) ?></strong>
											<input type="radio" value="<?php echo $value ?>" id="import<?php echo $value ?>" name="import" class="hidden" />
										</label>
									</td>
									<td>
										<div class="input-group input-upload-file hidden">
											<label class="input-group-btn">
												<span class="btn btn-primary">Browse&hellip;<i class="fa fa-folder"></i>
													<input type="file" style="display: none;" name="importfile<?php echo $value ?>" />
												</span>
											</label>
											<input type="text" class="form-control readonly input-lg" readonly />
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<?php echo JHtml::_('form.token') ?>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>

<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$("#encoding").select2();

			$("#table-importtype tr label.lbl-select-type").click(function(event){
				event.preventDefault();

				$("#table-importtype tr.active input[type='radio']:checked").prop("checked", false);
				$("#table-importtype tr.active .input-upload-file").addClass("hidden");
				$("#table-importtype tr.active").removeClass("active").addClass("disabled");

				var $tr = $(this).parent().parent();

				// Add class active for row.
				$tr.addClass("active").removeClass("disabled");

				// Produce checked for radio button.
				$tr.find("input[type='radio']").prop("checked", true);

				// Display upload button.
				$tr.find(".input-upload-file").removeClass("hidden");
			});

			// We can attach the `fileselect` event to all file inputs on the page
			$("#table-importtype input[type='file']").on("change", function(){
				var input = $(this);
				var numFiles = input.get(0).files ? input.get(0).files.length : 1;
				var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
				input.trigger('fileselect', [numFiles, label]);
			});

			// We can watch for our custom `fileselect` event like this
			$(':file').on('fileselect', function(event, numFiles, label) {

				var input = $(this).parents('.input-group').find(':text'),
					log = numFiles > 1 ? numFiles + ' files selected' : label;

				if( input.length ) {
					input.val(log);
				} else {
					if( log ) alert(log);
				}

			});
		});
	})(jQuery);
</script>
