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
 * @var  array  $options Field option parameters
 * @var  string $label   The html code for the label (not required if $options['hiddenLabel'] is true)
 * @var  string $input   The input field html code
 *
 */

extract($displayData);

if (!empty($options['showonEnabled']))
{
	JHtml::_('jquery.framework');
	JHtml::_('script', 'jui/cms.js', false, true);
}

$class = empty($options['class']) ? "" : " " . $options['class'];
$rel   = empty($options['rel']) ? "" : " " . $options['rel'];
?>
<?php if (!empty($label) || !empty($input)) : ?>
    <div class="form-group row-fluid <?php echo $class ?>"<?php echo $rel ?>>
		<?php if (empty($options['hiddenLabel'])) : ?>
            <div class="col-md-3"><?php echo $label ?></div>
            <div class="col-md-9"><?php echo $input ?></div>
		<?php else: ?>
            <div class="col-md-12"><?php echo $input ?></div>
		<?php endif; ?>
    </div>
<?php endif ?>
