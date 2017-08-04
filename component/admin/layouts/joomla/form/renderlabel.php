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
 *    $text         : (string)  The label text
 *    $description  : (string)  An optional description to use in a tooltip
 *    $for          : (string)  The id of the input this label is for
 *    $required     : (boolean) True if a required field
 *    $classes      : (array)   A list of classes
 *    $position     : (string)  The tooltip position. Bottom for alias
 */

$text     = $displayData['text'];
$desc     = $displayData['description'];
$for      = $displayData['for'];
$req      = $displayData['required'];
$classes  = array_filter((array) $displayData['classes']);
$position = $displayData['position'];

$id    = $for . '-lbl';
$title = '';

// If a description is specified, use it to build a tooltip.
if (!empty($desc))
{
	JHtml::_('redshopjquery.popover');
	$classes[] = 'hasPopover';
	$title     = ' title="' . trim($text) . '" data-content="' . trim($desc) . '"';
}

// If required, there's a class for that.
if ($req)
{
	$classes[] = 'required';
}

?>
<label id="<?php echo $id ?>" for="<?php echo $for ?>"
       class="col-md-2 control-label <?php echo implode(' ', $classes) ?>"<?php echo $title ?><?php echo $position ?>>
	<?php echo $text ?><?php if ($req) : ?><span class="star text-danger">&#160;*</span><?php endif; ?>
</label>
