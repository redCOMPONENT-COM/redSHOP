<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\Registry\Registry;

$data = $displayData;

// Receive overridable options
$data['options'] = !empty($data['options']) ? $data['options'] : array();

if (is_array($data['options']))
{
	$data['options'] = new Registry($data['options']);
}

// Options
$filterButton = $data['options']->get('filterButton', true);
$searchButton = $data['options']->get('searchButton', true);

$filters = $data['view']->filterForm->getGroup('filter');
?>

<?php if (!empty($filters['filter_search'])) : ?>
    <?php if ($searchButton): ?>
        <div class="col-xs-8 col-sm-7 col-md-8 col-lg-4">
            <div class="input-group stools-search-group">
                <?php echo $filters['filter_search']->input ?>
                <span class="input-group-btn">
                    <button type="submit" class="lc-button-search btn btn-default" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT') ?>">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>
	<?php endif; ?>
    <?php if ($filterButton) : ?>
        <div class="col-xs-4 col-sm-5 col-md-4 stools-filter-buttons">
            <button type="button" class="btn js-stools-btn-filter btn-default">
		        <i class="fa fa-filter"></i> <?php echo JText::_('JSEARCH_TOOLS'); ?>
            </button>
            <button type="button" class="btn btn-default reset js-stools-btn-clear" title="<?php echo JText::_('COM_REDSHOP_RESET') ?>">
                <i class="fa fa-close"></i> <?php echo JText::_('COM_REDSHOP_RESET') ?>
            </button>
        </div>
    <?php endif; ?>
<?php endif;
