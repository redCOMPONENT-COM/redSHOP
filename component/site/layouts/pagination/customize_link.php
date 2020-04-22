<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$item = $displayData['data'];

$display = $item->text;

switch ((string) $item->text)
{
	// Check for "Start" item
	case JText::_('JLIB_HTML_START') :
		$icon = "pagenav icon-backward icon-first";
		$text = JText::_('JLIB_HTML_START');
		$class = "pagination-start";
		break;

	// Check for "Prev" item
	case $item->text == JText::_('JPREV') :
		$item->text = JText::_('JPREVIOUS');
		$icon = "pagenav icon-step-backward icon-previous";
		$text = JText::_('JPREVIOUS');
		$class = "pagination-prev";
		break;

	// Check for "Next" item
	case JText::_('JNEXT') :
		$icon = "icon-step-forward icon-next";
		$text = JText::_('JNEXT');
		$class = "pagination-next";
		break;

	// Check for "End" item
	case JText::_('JLIB_HTML_END') :
		$icon = "icon-forward icon-last";
		$text = JText::_('JLIB_HTML_END');
		$class = "pagination-end";
		break;

	default:
		$icon = null;
		break;
}

if ($icon !== null)
{
	$display = $text;
}

if ($displayData['active'])
{
	if ($item->base > 0)
	{
		$limit = 'limitstart.value=' . $item->base;
	}
	else
	{
		$limit = 'limitstart.value=0';
	}

	$cssClasses = array();

	$title = '';

	if (!is_numeric($item->text))
	{
		JHtml::_('bootstrap.tooltip');
		$cssClasses[] = 'hasTooltip';
		$title = ' title="' . $item->text . '" ';
	}

	$onClick = 'pagination(' . ($item->base > 0 ? $item->base : '0') . ');';
}
else
{
	$class = (property_exists($item, 'active') && $item->active) ? 'pagenav' : $class;
}
?>
<?php if ($displayData['active']) : ?>
	<li>
		<a class="<?php echo implode(' ', $cssClasses); ?> page" <?php echo $title; ?> href="javascript:void(0);" onclick="<?php echo $onClick; ?>">
			<?php echo $display; ?>
		</a>
	</li>
<?php else : ?>
	<li class="<?php echo $class; ?>">
		<span><?php echo $display; ?></span>
	</li>
<?php endif;
