<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$data = (object) $displayData;

$attributes = array();

$attributes['id']            = $data->id;
$attributes['class']         = $data->element['class'] ? (string) $data->element['class'] : null;
$attributes['size']          = $data->element['size'] ? (int) $data->element['size'] : null;
$attributes['multiple']      = $data->multiple ? 'multiple' : null;
$attributes['required']      = $data->required ? 'required' : null;
$attributes['aria-required'] = $data->required ? 'true' : null;
$attributes['autofocus']     = $data->autofocus ? ' autofocus' : null;
$attributes['onchange']      = $data->element['onchange'] ? (string) $data->element['onchange'] : null;

if ((string) $data->element['readonly'] == 'true' || (string) $data->element['disabled'] == 'true')
{
	$attributes['disabled'] = 'disabled';
}

$renderedAttributes = RedshopHelperUtility::toAttributes($attributes);

$readOnly = ((string) $data->element['readonly'] == 'true');

// If it's readonly the select will have no name
$selectName = $readOnly ? '' : $data->name;
?>
<div <?php echo $renderedAttributes; ?>>
	<?php if ($data->options) : ?>
		<?php foreach ($data->options as $i => $option) : ?>
			<?php
			// Input field attributes
			$inputAttributes = array();
			$inputAttributes['checked']  = ((string) $option->value == (string) $data->value) ? 'checked' : null;
			$inputAttributes['class']    = !empty($option->class) ? $option->class : null;
			$inputAttributes['class']   .= !empty($data->element['input-class']) ? (string) $data->element['input-class'] : null;
			$inputAttributes['disabled'] = (!empty($option->disable) || ($readOnly && !$checked)) ? 'disabled' : null;
			$inputAttributes['onclick']  = !empty($option->onclick) ? $option->onclick : null;
			$inputAttributes['onchange'] = !empty($option->onchange) ? $option->onchange : null;
			$inputAttributes['required'] = !empty($attributes['required']) ? 'required' : null;

			// Label attributes
			$labelAttributes = array();
			$labelAttributes['class'] = !empty($data->element['label-class']) ? (string) $data->element['label-class'] : null;
			?>
			<label for="<?php echo $data->id . $i; ?>" <?php echo RedshopHelperUtility::toAttributes($labelAttributes);?> >
				<input
					type="radio"
					id="<?php echo $data->id . $i; ?>"
					name="<?php echo $data->name; ?>"
					value="<?php echo htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8'); ?>"
					<?php echo RedshopHelperUtility::toAttributes($inputAttributes); ?>
				/>
				<?php echo JText::alt($option->text, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $data->name)); ?>
			</label>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
