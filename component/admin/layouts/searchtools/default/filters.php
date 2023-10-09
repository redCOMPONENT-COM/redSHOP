<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;

$data = $displayData;

// Load the form filters
$filters = $data['view']->filterForm->getGroup('filter');

foreach ($filters as $field) {
    if ($showonstring = $field->getAttribute('showon')) {
        $showonarr = array();

        foreach (preg_split('%\[AND\]|\[OR\]%', $showonstring) as $showonfield) {
            $showon      = explode(':', $showonfield, 2);
            $showonarr[] = array(
                'field'  => $showon[0],
                'values' => explode(',', $showon[1]),
                'op'     => (
                    preg_match(
                        '%\[(AND|OR)\]' . $showonfield . '%',
                        $showonstring,
                        $matches
                    )
                ) ? $matches[1] : ''
            );
        }

        $data['view']->filterForm->setFieldAttribute(
            $field->fieldname,
            'dataShowOn',
            json_encode($showonarr),
            $field->group
        );
    }
}

?>
<?php if ($filters) : ?>
    <?php foreach ($filters as $fieldName => $field) : ?>
        <?php if ($fieldName !== 'filter_search') : ?>
            <?php $dataShowOn = ''; ?>
            <?php if ($field->showon) : ?>
                <?php HtmlHelper::_('redshopjquery.framework'); ?>
                <?php HtmlHelper::script('system/showon.min.js', ['version' => 'auto', 'relative' => true]); ?>
                <?php $dataShowOn = " data-showon='" . json_encode(FormHelper::parseShowOnConditions($field->showon, $field->formControl, $field->group)) . "'"; ?>
            <?php endif; ?>
            <div class="js-stools-field-filter"<?php echo $dataShowOn; ?>>
                <span class="visually-hidden"><?php echo $field->label; ?></span>
                <?php echo $field->input; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>