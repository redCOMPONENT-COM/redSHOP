<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
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
	'ISO-8859-1'  => 'Western European, Latin-1',
	'ISO-8859-5'  => 'Little used cyrillic charset (Latin/Cyrillic)',
	'ISO-8859-15' => 'Western European, Latin-9. Adds the Euro sign, French and Finnish letters missing in Latin-1 (ISO-8859-1).',
	'UTF-8'       => 'ASCII compatible multi-byte 8-bit Unicode',
	'cp866'       => 'DOS-specific Cyrillic charset',
	'cp1251'      => 'Windows-specific Cyrillic charset',
	'cp1252'      => 'Windows specific charset for Western European',
	'KOI8-R'      => 'Russian',
	'BIG5'        => 'Traditional Chinese, mainly used in Taiwan',
	'GB2312'      => 'Simplified Chinese, national standard character set',
	'BIG5-HKSCS'  => 'Big5 with Hong Kong extensions, Traditional Chinese',
	'Shift_JIS'   => 'Japanese',
	'EUC-JP'      => 'Japanese',
	'MacRoman'    => 'Charset that was used by Mac OS'
);

// Creating JOption for JSelect box.
foreach ($characterSets as $char => $name)
{
	$title = '(' . $char . ') ' . $name;
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
	<table class="adminList">
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
		<?php foreach ($data as $value => $text): ?>
		<tr>
			<td>
				<input
					type="radio"
					value="<?php echo $value; ?>"
					id="import<?php echo $value; ?>"
					name="import"
				>
				<label
					class="radiobtn"
					id="import<?php echo $value; ?>-lbl"
					for="import<?php echo $value; ?>"
				>
			    <?php echo JText::_($text); ?>
			    </label>
			</td>
			<td>
				<input type="file" name="importfile<?php echo $value; ?>" size="75"/>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<input type="hidden" name="view" value="import"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>
