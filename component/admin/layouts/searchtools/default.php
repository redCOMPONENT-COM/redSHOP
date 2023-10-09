<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$data = $displayData;

// Receive overridable options
$data['options'] = !empty($data['options']) ? $data['options'] : array();

// Set some basic options
$customOptions = array(
    'filtersHidden'       => isset($data['options']['filtersHidden']) ? $data['options']['filtersHidden'] : empty($data['view']->activeFilters),
    'defaultLimit'        => isset($data['options']['defaultLimit']) ? $data['options']['defaultLimit'] : Factory::getApplication(
    )->get('list_limit', 20),
    'searchFieldSelector' => '#filter_search',
    'orderFieldSelector'  => '#list_fullordering',
    'totalResults'        => isset($data['options']['totalResults']) ? $data['options']['totalResults'] : -1,
    'noResultsText'       => isset($data['options']['noResultsText']) ? $data['options']['noResultsText'] : Text::_(
        'JGLOBAL_NO_MATCHING_RESULTS'
    ),
);

$data['options'] = array_merge($customOptions, $data['options']);

$formSelector = !empty($data['options']['formSelector']) ? $data['options']['formSelector'] : '#adminForm';

// Load search tools
HtmlHelper::_('searchtools.form', $formSelector, $data['options']);

$filtersClass = isset($data['view']->activeFilters) && $data['view']->activeFilters ? ' js-stools-container-filters-visible' : '';
$showFilter   = isset($data['options']['showFilter']) ? (boolean) $data['options']['showFilter'] : true;
?>
<div class="js-stools" role="search">
    <div class="js-stools-container-bar">
        <div class="btn-toolbar">
            <?php echo RedshopLayoutHelper::render('searchtools.default.bar', $data); ?>
            <?php echo RedshopLayoutHelper::render('searchtools.default.list', $data); ?>
        </div>
    </div>
    <!-- Filters div -->
    <?php if ($showFilter): ?>
        <div class="js-stools-container-filters clearfix<?php echo $filtersClass; ?>">
            <?php echo RedshopLayoutHelper::render('searchtools.default.filters', $data); ?>
        </div>
    <?php endif; ?>
</div>
<?php if ($data['options']['totalResults'] === 0): ?>
    <?php echo RedshopLayoutHelper::render('searchtools.default.noitems', $data); ?>
<?php endif; ?>