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
		'title' => JText::_('COM_REDSHOP_POST_DK_INTEGRATION_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_POST_DK_INTEGRATION_LBL'),
		'field' => $this->lists['postdk_integration']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_POST_DK_CUSTOMER_ID_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_POST_DK_CUSTOMER_ID_LBL'),
		'id'     => 'postdk_customer_no',
		'showOn' => 'postdk_integration:1',
		'field'  => '<input type="text" name="postdk_customer_no" id="postdk_customer_no"
            value="' . $this->config->get('POSTDK_CUSTOMER_NO') . '" class="form-control" />'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_POST_DK_PASSWORD_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_POST_DK_PASSWORD_LBL'),
		'id'     => 'postdk_customer_password',
		'showOn' => 'postdk_integration:1',
		'field'  => '<input type="password" name="postdk_customer_password" id="postdk_customer_password"
            value="' . $this->config->get('POSTDK_CUSTOMER_PASSWORD') . '" class="form-control" />'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_POSTDANMARK_ADDRESS_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_POSTDANMARK_ADDRESS_LBL'),
		'id'     => 'postdk_address',
		'showOn' => 'postdk_integration:1',
		'field'  => '<input type="text" name="postdk_address" id="postdk_address"
            value="' . $this->config->get('POSTDANMARK_ADDRESS') . '" class="form-control" />'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_POSTDANMARK_POSTALCODE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_POSTDANMARK_POSTALCODE_LBL'),
		'id'     => 'postdk_postalcode',
		'showOn' => 'postdk_integration:1',
		'field'  => '<input type="text" name="postdk_postalcode" id="postdk_postalcode"
            value="' . $this->config->get('POSTDANMARK_POSTALCODE') . '" class="form-control" />'
	)
);

$options   = array();
$options[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_POSTDANMARK_GENERATE_LABEL_MANUALLY'));
$options[] = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_POSTDANMARK_AUTO_GENERATE_LABEL'));

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_POSTDANMARK_AUTO_GENERATE_LABEL_LBL'),
		'desc'   => JText::_('COM_REDSHOP_POSTDANMARK_AUTO_GENERATE_LABEL_TOOLTIP_DESC'),
		'id'     => 'auto_generate_label',
		'showOn' => 'postdk_integration:1',
		'field'  => Jhtml::_(
				'select.genericlist',
				$options,
				'auto_generate_label',
				' class="disableBoostrapChosen form-control"',
				'value',
				'text',
				$this->config->get('AUTO_GENERATE_LABEL')
			)
			. '<div id="generate_label_on_status_wrapper" style="display: none; margin-top: 5px;">'
			. RedshopHelperOrder::getStatusList(
				'generate_label_on_status', $this->config->get('GENERATE_LABEL_ON_STATUS'), "class=\"form-control\" size=\"1\" "
			)
			. '</div>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_SHOW_PRODUCT_DETAIL_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRODUCT_DETAIL_LBL'),
		'id'     => 'show_product_detail',
		'showOn' => 'postdk_integration:1',
		'field'  => $this->lists['show_product_detail']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_ENABLE_TRACK_AND_TRACE_EMAIL_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_ENABLE_TRACK_AND_TRACE_EMAIL'),
		'id'     => 'webpack_enable_email_track',
		'showOn' => 'postdk_integration:1',
		'field'  => $this->lists['webpack_enable_email_track']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_ENABLE_SMS_FROM_WEBPACK_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SMS_FROM_WEBPACK_LBL'),
		'id'     => 'webpack_enable_sms',
		'showOn' => 'postdk_integration:1',
		'field'  => $this->lists['webpack_enable_sms']
	)
);
?>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('#auto_generate_label').on("change", function (e) {
                e.preventDefault();

                if ($(this).val() == 1) {
                    $("#generate_label_on_status_wrapper").show();
                } else {
                    $("#generate_label_on_status_wrapper").hide();
                }
            });

			<?php if ($this->config->get('AUTO_GENERATE_LABEL') == 1): ?>
            $("#generate_label_on_status_wrapper").show();
			<?php endif; ?>
        });
    })(jQuery);
</script>
