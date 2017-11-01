<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_1_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_REMAINDER_1_LBL'),
		'field' => '<input type="number" name="colour_sample_remainder_1" id="colour_sample_remainder_1" class="form-control"'
			. ' value="' . $this->config->get('COLOUR_SAMPLE_REMAINDER_1') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_2_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_REMAINDER_2_LBL'),
		'field' => '<input type="number" name="colour_sample_remainder_2" id="colour_sample_remainder_2" class="form-control"'
			. ' value="' . $this->config->get('COLOUR_SAMPLE_REMAINDER_2') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_3_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_REMAINDER_3_LBL'),
		'field' => '<input type="number" name="colour_sample_remainder_3" id="colour_sample_remainder_3" class="form-control"'
			. ' value="' . $this->config->get('COLOUR_SAMPLE_REMAINDER_3') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_COLOUR_COUPON_DURATION_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COLOUR_COUPON_DURATION_LBL'),
		'field' => '<input type="number" name="colour_coupon_duration" id="colour_coupon_duration" class="form-control"'
			. ' value="' . $this->config->get('COLOUR_COUPON_DURATION') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_COLOUR_DISCOUNT_PERCENTAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COLOUR_DISCOUNT_PERCENTAGE_LBL'),
		'line'  => false,
		'field' => '<input type="number" name="colour_discount_percentage" id="colour_discount_percentage" class="form-control"'
			. ' value="' . $this->config->get('COLOUR_DISCOUNT_PERCENTAGE') . '" />'
	)
);
?>
    <div class="hidden">
		<?php
		echo RedshopLayoutHelper::render(
			'config.config',
			array(
				'title' => JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_DAYS_LBL'),
				'desc'  => JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_DAYS'),
				'field' => '<input type="number" name="colour_sample_days" id="colour_sample_days" class="form-control"'
					. ' value="' . $this->config->get('COLOUR_SAMPLE_DAYS') . '" />'
			)
		);
		?>
    </div>
<?php
