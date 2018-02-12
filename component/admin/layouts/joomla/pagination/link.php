<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/** @var JPaginationObject $item */
$item = $displayData['data'];

$display = $item->text;

switch ((string) $item->text)
{
	// Check for "Start" item
	case JText::_('JLIB_HTML_START'):
		$icon = 'fa fa-fast-backward';
		break;

	// Check for "Prev" item
	case $item->text === JText::_('JPREV'):
		$item->text = JText::_('JPREVIOUS');
		$icon       = 'fa fa-backward';
		break;

	// Check for "Next" item
	case JText::_('JNEXT'):
		$icon = 'fa fa-forward';
		break;

	// Check for "End" item
	case JText::_('JLIB_HTML_END'):
		$icon = 'fa fa-fast-forward';
		break;

	default:
		$icon = null;
		break;
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

	$onClick = 'document.adminForm.' . $item->prefix . 'limitstart.value=' . ($item->base > 0 ? $item->base : '0') . '; Joomla.submitform();return false;';
}
else
{
	$class = (property_exists($item, 'active') && $item->active) ? 'active' : 'disabled';
}
?>
<?php if ($displayData['active']) : ?>
    <a class="<?php echo implode(' ', $cssClasses) ?> btn btn-default" <?php echo $title ?> href="#" onclick="<?php echo $onClick ?>">
        <?php if (!is_null($icon)): ?>
            <i class="<?php echo $icon ?>"></i>
        <?php else: ?>
            <?php echo $display ?>
        <?php endif; ?>
    </a>
<?php else : ?>
    <button type="button" disabled class="btn btn-default btn-disabled <?php echo is_null($icon) ? 'text-red' : '' ?>">
	    <?php if (!is_null($icon)): ?>
            <i class="<?php echo $icon ?>"></i>
	    <?php else: ?>
		    <?php echo $display ?>
	    <?php endif; ?>
    </button>
<?php endif;
