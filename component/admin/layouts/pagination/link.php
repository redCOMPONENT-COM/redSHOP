<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/** @var JPaginationObject $item */
$item = $displayData['data'];

$display = $item->text;

switch ((string) $item->text)
{
	// Check for "Start" item
	case JText::_('JLIB_HTML_START') :
		$icon = 'icon-fast-backward icon-first';
		break;

	// Check for "Prev" item
	case $item->text === JText::_('JPREV') :
		$item->text = JText::_('JPREVIOUS');
		$icon = 'icon-step-backward icon-previous';
		break;

	// Check for "Prev" item
	case $item->text == JText::_('LIB_REDCORE_PREVIOUS_10') :
		$icon = "icon-backward";
		break;

	// Check for "Next" item
	case JText::_('JNEXT') :
		$icon = 'icon-step-forward icon-next';
		break;

	// Check for "Next" item
	case JText::_('LIB_REDCORE_NEXT_10') :
		$icon = "icon-forward";
		break;

	// Check for "End" item
	case JText::_('JLIB_HTML_END') :
		$icon = 'icon-fast-forward icon-last';
		break;

	default:
		$icon = null;
		break;
}

if ($icon !== null)
{
	$display = '<i class="' . $icon . '"></i>';
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

	$onClick = '';
	$href    = trim($item->link);

	// Still using javascript approach in backend
	if (JFactory::getApplication()->isAdmin())
	{
		$onClick = 'onclick="document.adminForm.' . $item->prefix . $limit
			. '; Joomla.submitform(document.forms[\'' . $item->formName . '\'].task.value, document.forms[\'' . $item->formName . '\']);return false;"';
		$href    = '#';
	}
}
else
{
	$class = (property_exists($item, 'active') && $item->active) ? 'active' : 'disabled';
}
?>
<?php if ($displayData['active']) : ?>
	<li>
		<a
			<?php echo !empty($cssClasses) ? 'class="' . implode(' ', $cssClasses) . '"' : ''; ?>
			<?php echo $title; ?>
			<?php echo $onClick; ?>
			href="<?php echo $href; ?>"
		>
			<?php echo $display; ?>
		</a>
	</li>
<?php else : ?>
	<li class="<?php echo $class; ?>">
		<span><?php echo $display; ?></span>
	</li>
<?php endif;
