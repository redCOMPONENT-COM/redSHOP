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
	<?php if ($searchButton) : ?>
		<div class="filterItem">
			<div class="btn-wrapper input-append">
				<?php echo $filters['filter_search']->input; ?>
				<input type="submit" class="btn" value="<?php echo JText::_('JSEARCH_FILTER_SUBMIT') ?>" />
                <input type="button" class="btn reset js-stools-btn-clear" value="<?php echo JText::_('COM_REDSHOP_RESET');?>" />
			</div>
		</div>
		<?php if ($filterButton) : ?>
			<div class="btn-wrapper hidden-phone">
				<button type="button" class="btn js-stools-btn-filter btn-large btn-primary">
					<?php echo JText::_('JSEARCH_TOOLS');?> <span class="caret"></span>
				</button>
			</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endif;
