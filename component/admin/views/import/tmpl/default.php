<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('redshopjquery.ui');
JHtml::script('com_redshop/jquery.iframe-transport.js', false, true);
JHtml::script('com_redshop/jquery.fileupload.js', false, true);
JHtml::script('com_redshop/admin.import.js', false, true);

$allowFileTypes      = explode(',', Redshop::getConfig()->get('IMPORT_FILE_MIME', 'text/csv,application/vnd.ms-excel'));
$allowMaxFileSize    = (int) Redshop::getConfig()->get('IMPORT_MAX_FILE_SIZE', 20000000);
$allowMinFileSize    = (int) Redshop::getConfig()->get('IMPORT_MIN_FILE_SIZE', 1);
$allowFileExtensions = array('.csv');//explode(',', Redshop::getConfig()->get('IMPORT_FILE_EXTENSION', '.csv'));
$encodings           = array();

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
	$encodings[] = JHtml::_('select.option', $char, $title);
}
?>

<?php if (empty($this->imports)): ?>
	<div class="alert alert-warning">
		<span class="close" data-dismiss="alert">×</span>
		<h4 class="alert-heading">
			<i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?>
		</h4>
		<div>
			<p><?php echo JText::_('COM_REDSHOP_IMPORT_WARNING_MISSING_PLUGIN') ?></p>
		</div>
	</div>
<?php else: ?>
	<script type="text/javascript">;
        var allowFileType = ["<?php echo implode('","', $allowFileTypes) ?>"];
        var allowFileExt = ["<?php echo implode('","', $allowFileExtensions) ?>"];
        var allowMaxFileSize = <?php echo $allowMaxFileSize ?>;
        var allowMinFileSize = <?php echo $allowMinFileSize ?>;
	</script>

	<form action="index.php?option=com_redshop&view=import" method="post" name="adminForm" id="adminForm">
		<div class="row">
			<div class="col-md-6">
				<!-- Step 1. Choose plugin -->
				<?php echo $this->loadTemplate('plugins'); ?>
				<!-- Step 1. End -->
			</div>
			<div class="col-md-6">
				<!-- Step 2. Config -->
				<?php echo $this->loadTemplate('configs'); ?>
				<!-- Step 2. End -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- Step 3. Process -->
				<?php echo $this->loadTemplate('process'); ?>
				<!-- Step 3. End -->
			</div>
		</div>

		<!-- Hidden field -->
		<?php echo JHtml::_('form.token') ?>
	</form>
<?php endif; ?>
