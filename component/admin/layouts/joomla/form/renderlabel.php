<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * ---------------------
 *
 * @var string  $text        The label text
 * @var string  $description An optional description to use in a tooltip
 * @var string  $for         The id of the input this label is for
 * @var boolean $required    True if a required field
 * @var array   $classes     A list of classes
 * @var string  $position    The tooltip position. Bottom for alias
 */

extract($displayData);

$classes = array_filter((array) $classes);
$id      = $for . '-lbl';
$title   = '';

// If required, there's a class for that.
if ($required)
{
	$classes[] = 'required';
}
?>
<label id="<?php echo $id ?>" for="<?php echo $for ?>" class="control-label text-right <?php echo implode(' ', $classes) ?>"<?php echo $title ?>
	<?php echo $position ?> style="font-size: 110%">
    <strong><?php echo $text ?></strong>
    <?php if (!$required) : ?><small class="text-muted">-&nbsp;<?php echo JText::_('COM_REDSHOP_FIELD_OPTIONAL') ?></small><?php endif; ?>
</label>
<div class="help-block">
    <small><i><?php echo trim($description) ?></i></small>
</div>
