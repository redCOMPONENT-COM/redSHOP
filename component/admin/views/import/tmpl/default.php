<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$data = array(
	'categories'                 => 'COM_REDSHOP_IMPORT_CATEGORIES',
	'products'                   => 'COM_REDSHOP_IMPORT_PRODUCTS',
	'attributes'                 => 'COM_REDSHOP_IMPORT_ATTRIBUTES',
	'manufacturer'               => 'COM_REDSHOP_IMPORT_MANUFACTURER',
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

		if (form.separator.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CSV_COLUMN_SEPARATOR_NOT_NULL', true ); ?>");
			return false;
		}
		else {
			submitform(pressbutton);
		}
	}
</script>
<h1><?php echo JText::_('COM_REDSHOP_DATA_IMPORT'); ?></h1>
<p>
<?php echo $this->result; ?>
</p>
<form
	action="index.php?option=com_redshop"
	method="post"
	name="adminForm"
	id="adminForm"
    enctype="multipart/form-data"
>
	<table class="adminlist table table-striped">
		<tr>
			<td colspan="2">
				<?php echo JText::_('COM_REDSHOP_SEPRATOR');?>
				<input type="text" name="separator" maxlength="1" size="1" value=","/>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php
				echo JText::_('COM_REDSHOP_IMPORT_ENCODING');
				echo JHTML::_(
						'select.genericlist',
						$encodings,
						'encoding',
						'class="inputbox"',
						'value',
						'text',
						'UTF-8'
					);
				?>
			</td>
		</tr>
		<?php $i = 0; ?>
		<?php foreach ($data as $value => $text): ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td width="30%">
				<label class="radio inline">
				<input
					type="radio"
					value="<?php echo $value; ?>"
					id="import<?php echo $value; ?>"
					name="import"
				>
					<?php echo JText::_($text); ?>
				</label>
			</td>
			<td>
				<input type="file" name="importfile<?php echo $value; ?>" size="75"/>
			</td>
		</tr>
		<?php $i++; ?>
		<?php endforeach; ?>
	</table>
	<input type="hidden" name="view" value="import"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>
