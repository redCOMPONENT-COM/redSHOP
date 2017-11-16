<?php
/**
 * @package     Aesir
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Aesir\Core\Helper\AssetHelper;

JHtml::_('jquery.framework');
AssetHelper::load('chosen.destroy.min.js', 'reditem');
AssetHelper::load('select2.min.css', 'com_reditem');
AssetHelper::load('select2.min.js', 'com_reditem');

extract($displayData);

/**
 * Layout variables
 * ==================================
 * @var  boolean                    $autocomplete  Auto-complete flag.
 * @var  boolean                    $autofocus     Auto-focus flag.
 * @var  string                     $class         Class of input.
 * @var  boolean                    $disabled      Disabled input or not.
 * @var  SimpleXMLElement           $element       Element xml.
 * @var  ReditemEntityField         $field         Field entity class.
 * @var  string                     $group         Group the field belongs to. <fields> section in form XML.
 * @var  boolean                    $hidden        Is this field hidden in the form?
 * @var  boolean                    $hiddenLabel   Display field label?
 * @var  string                     $hint          Placeholder for the field.
 * @var  string                     $id            DOM id of the field.
 * @var  string                     $label         Label of the field.
 * @var  string                     $text          Label of the field. Here for B/C.
 * @var  boolean                    $multiple      Does this field support multiple values?
 * @var  string                     $name          DOM name of this field.
 * @var  string                     $onchange      Onchange attribute for the field.
 * @var  string                     $onclick       Onclick attribute for the field.
 * @var  string                     $pattern       Pattern (Reg Ex) of value of the form field.
 * @var  boolean                    $readonly      Is this field read only?
 * @var  boolean                    $repeat        Allows extensions to duplicate elements.
 * @var  boolean                    $required      Is this field required?
 * @var  integer                    $size          Size attribute of the input.
 * @var  boolean                    $spellcheck    Spellcheck state for the form field.
 * @var  string                     $validate      Validation rules to apply.
 * @var  string                     $value         Value attribute of the field.
 * @var  string                     $position      Position of the tooltips
 * @var  string                     $classes       CSS classes to apply to the field
 * @var  string                     $for           For attribute for labels
 * @var  array                      $attribs       Array of attributes for this field.
 * @var  string                     $fieldcode     Code of this custom field
 * @var  integer                    $fieldId       Custom field identifier
 * @var  string                     $fieldClass    Class of the custom field
 * @var  \Joomla\Registry\Registry  $config        Optional configuration for the custom field
 * @var  array                      $data          Related items data
 * @var  string                     $attributes    Attributes for this field in string.
 */

$values = (array) $value;
$selectName = $name;

if ($multiple)
{
	$selectName = $id . '-selector';

	// Depends on jQuery UI
	JHtml::_('rjquery.ui', array('core', 'sortable'));

	AssetHelper::load('script.min.js', 'plg_aesir_field_item_related');
	JFactory::getDocument()->addScriptDeclaration('
		(function($){
			$(document).ready(function(){
				$("#' . $id . '").chosenDestroy().SortableSelect2({
					allowClear: true,
					sortable : {
						tolerance   : "pointer"
					},
					ss2 : {
						selected : ' . json_encode($values) . '
					}
				});
			});
		})(jQuery);
	');
}

$readOnly = ((string) $readonly === 'true' || (string) $readonly === '1');
?>
<?php if (!$readOnly) : ?>
	<input type="hidden" name="<?=$name; ?>" value=""/>
<?php endif; ?>
<div class="reditem_customfield_item_related">
	<select class="select2 test form-control" name="<?=$selectName?>" data-name="<?php echo $name; ?>" id="<?=$id?>" <?php echo $attributes ?>>
		<option></option>
		<?php if (!empty($data)): ?>
			<?php foreach ($data as $key => $item): ?>
				<?php if (isset($item['text'])) : ?>
					<?php
					$selected = ($item['selected']) ? 'selected="selected"' : '';
					?>
					<option value="<?=$item['value']?>" <?php echo $selected; ?>><?=$item['text']?></option>
				<?php else : ?>
					<optgroup label="<?php echo $key ?>">
						<?php foreach ($item as $option): ?>
							<?php
							$selected = ($option['selected']) ? 'selected="selected"' : '';
							?>
							<option value="<?=$option['value']?>" <?php echo $selected; ?>><?=$option['text']?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</select>
	<?php if ($multiple) : ?>
		<div class="js-ss2-values-container">
			<?php foreach ($values as $value): ?>
				<?php
					$value = htmlspecialchars(trim($value), ENT_COMPAT, 'UTF-8');
				?>
				<input type="hidden" name="<?=$name; ?>" value="<?=$value?>"/>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
