<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

/**
 * Layout variables
 * -----------------
 * @var   string                $displayData  Date format
 * @var   string                $format       Date format
 * @var   int                   $firstDay     First day config.
 * @var   boolean               $autoApply    Auto-apply when select date.
 * @var   JFormFieldRdaterange  $field        Field object.
 * @var   string                $value        Field value.
 * @var   string                $class        Field class.
 * @var   boolean               $showButton   Show calendar button or not.
 * @var   string                $onChange     On change js function.
 * @var   string                $phpFormat    Date format in PHP.
 */

extract($displayData);

if (!empty($value))
{
	$dateRange = explode('-', $value);
	$startDate = (!empty($dateRange[0])) ? date($phpFormat, $dateRange[0]) : '';
	$endDate   = (!empty($dateRange[1])) ? date($phpFormat, $dateRange[1]) : '';
}

?>

<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			var options_<?php echo $field->id ?> = {};

			options_<?php echo $field->id ?>.locale = {
				format: '<?php echo $format ?>',
				separator: ' - ',
				applyLabel: 'Apply',
				cancelLabel: 'Cancel',
				fromLabel: 'From',
				toLabel: 'To',
				customRangeLabel: 'Custom',
				daysOfWeek: [
					'<?php echo JText::_('COM_REDSHOP_SUN') ?>',
					'<?php echo JText::_('COM_REDSHOP_MON') ?>',
					'<?php echo JText::_('COM_REDSHOP_TUE') ?>',
					'<?php echo JText::_('COM_REDSHOP_WED') ?>',
					'<?php echo JText::_('COM_REDSHOP_THU') ?>',
					'<?php echo JText::_('COM_REDSHOP_FRI') ?>',
					'<?php echo JText::_('COM_REDSHOP_SAT') ?>'
				],
				monthNames: [
					'<?php echo JText::_('COM_REDSHOP_MONTH_JANUARY') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_FEBRUARY') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_MARCH') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_APRIL') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_MAY') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_JUNE') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_JULY') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_AUGUST') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_SEPTEMBER') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_OCTOBER') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_NOVEMBER') ?>',
					'<?php echo JText::_('COM_REDSHOP_MONTH_DECEMBER') ?>'
				],
				firstDay: <?php echo $firstDay ?>
			};

			<?php if (!empty($this->filterStartDate)): ?>
			options_<?php echo $field->id ?>.startDate = $('input[name="filter[start_date]').val();
			<?php endif; ?>
			<?php if (!empty($this->filterEndDate)): ?>
			options_<?php echo $field->id ?>.endDate = $('input[name="filter[end_date]"]').val();
			<?php endif; ?>

			options_<?php echo $field->id ?>.autoApply = <?php echo ($autoApply) ? 'true' : 'false'; ?>;
			options_<?php echo $field->id ?>.ranges = {
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_TODAY') ?>': [moment(), moment()],
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_YESTERDAY') ?>': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_THIS_WEEK') ?>': [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_LAST_WEEK') ?>': [
					moment().subtract(1, 'weeks').startOf('isoWeek'), moment().subtract(1, 'weeks').endOf('isoWeek')
				],
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_THIS_MONTH') ?>': [moment().startOf('month'), moment().endOf('month')],
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_LAST_MONTH') ?>': [
					moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')
				],
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_THIS_YEAR') ?>': [moment().startOf('year'), moment().endOf('year')],
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_LAST_YEAR') ?>': [
					moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')
				],
				'<?php echo JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', 7) ?>': [moment().subtract(6, 'days'), moment()],
				'<?php echo JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', 15) ?>': [moment().subtract(14, 'days'), moment()],
				'<?php echo JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', 30) ?>': [moment().subtract(29, 'days'), moment()],
				'<?php echo JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', 60) ?>': [moment().subtract(59, 'days'), moment()],
				'<?php echo JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', 90) ?>': [moment().subtract(89, 'days'), moment()]
			};

			<?php if (!empty($startDate)): ?>
            options_<?php echo $field->id ?>.startDate = "<?php echo $startDate ?>";
            <?php endif; ?>
			<?php if (!empty($endDate)): ?>
            options_<?php echo $field->id ?>.endDate = "<?php echo $endDate ?>";
			<?php endif; ?>

			options_<?php echo $field->id ?>.linkedCalendars = true;
			options_<?php echo $field->id ?>.autoUpdateInput = true;
			options_<?php echo $field->id ?>.showDropdowns = true;
			options_<?php echo $field->id ?>.alwaysShowCalendars = true;

			$('#<?php echo $field->id ?>').daterangepicker(options_<?php echo $field->id ?>, function(start, end, label){
				$('#<?php echo $field->id ?>_input').val(moment(start).unix() + '-' + moment(end).unix());
				<?php if (!empty($onChange)): ?>
				<?php echo $onChange ?>
				<?php endif; ?>
			});

			<?php if ($showButton): ?>
			$('#date_range_<?php echo $field->id ?>_btn').click(function(e){
				e.preventDefault();
				$('#<?php echo $field->id ?>').click();
			});
			<?php endif; ?>
		});
	})(jQuery);
</script>

<div class="<?php echo $field->id ?>-date-range" id="<?php echo $field->id ?>_wrapper">
	<div class="input-append">
		<input id="<?php echo $field->id ?>" class="<?php echo $class ?>" type="text" autocomplete="false" />
		<input type="hidden" value="<?php echo $value ?>" name="<?php echo $field->name ?>" id="<?php echo $field->id ?>_input" />
		<?php if ($showButton): ?>
		<button class="btn btn-success" id="date_range_<?php echo $field->id ?>_btn"><i class="fa fa-calendar"></i></button>
		<?php endif; ?>
	</div>
</div>
