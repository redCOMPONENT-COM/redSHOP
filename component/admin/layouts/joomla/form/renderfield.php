<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

/**
 * Layout variables
 * ---------------------
 *    $options         : (array)  Optional parameters
 *    $label           : (string) The html code for the label (not required if $options['hiddenLabel'] is true)
 *    $input           : (string) The input field html code
 */

if (!empty($displayData['options']['showonEnabled'])) {
    HtmlHelper::_('redshopjquery.framework');
    HtmlHelper::script('system/showon.min.js', ['version' => 'auto', 'relative' => true]);
}
/*
$class = empty($displayData['options']['class']) ? "" : " " . $displayData['options']['class'];
$rel   = empty($displayData['options']['rel']) ? "" : " " . $displayData['options']['rel'];
?>
<?php if (!empty($displayData['label']) || !empty($displayData['input'])): ?>
    <div class="form-group row-fluid <?php echo $class; ?>" <?php echo $rel; ?>>
        <?php if (empty($displayData['options']['hiddenLabel'])): ?>
            <?php echo $displayData['label']; ?>
        <?php endif; ?>
        <div class="col-md-10">
            <?php echo $displayData['input']; ?>
        </div>
    </div>
<?php endif ?>
*/
$class           = empty($displayData['options']['class']) ? '' : ' ' . $displayData['options']['class'];
$rel             = empty($displayData['options']['rel']) ? '' : ' ' . $displayData['options']['rel'];
$id              = ($displayData['id'] ?? $displayData['name']) . '-desc';
$hideLabel       = !empty($displayData['options']['hiddenLabel']);
$hideDescription = empty($displayData['options']['hiddenDescription']) ? false : $displayData['options']['hiddenDescription'];
$descClass       = ($displayData['options']['descClass'] ?? '') ?: (!empty($displayData['options']['inlineHelp']) ? 'hide-aware-inline-help d-none' : '');

if (!empty($parentclass)) {
    $class .= ' ' . $parentclass;
}

?>
<div class="control-group<?php echo $class; ?>"<?php echo $rel; ?>>
    <?php if ($hideLabel) : ?>
        <div class="visually-hidden"><?php echo $displayData['label']; ?></div>
    <?php else : ?>
        <div class="control-label"><?php echo $displayData['label']; ?></div>
    <?php endif; ?>
    <div class="controls">
        <?php echo $displayData['input']; ?>
        <?php if (!$hideDescription && !empty($description)) : ?>
            <div id="<?php echo $id; ?>" class="<?php echo $descClass ?>">
                <small class="form-text">
                    <?php echo $description; ?>
                </small>
            </div>
        <?php endif; ?>
    </div>
</div>