<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<div class="form-group row-fluid">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible" role="alert">
            <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?></h4>
			<?php echo JText::_('COM_REDSHOP_ECONOMIC_NOTE_LBL'); ?>
        </div>
    </div>
</div>

<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ECONOMIC_INTEGRATION_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_INTEGRATION_LBL'),
		'field' => $this->lists['economic_integration']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_ECONOMIC_CHOICE_OF_BOOK_INVOICE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_CHOICE_OF_BOOK_INVOICE_LBL'),
		'id'     => 'economic_invoice_draft',
		'showOn' => 'economic_integration:1',
		'field'  => $this->lists['economic_invoice_draft']
			. '<div id="booking_order_status" style="display: none; margin-top: 5px;">'
			. RedshopHelperOrder::getStatusList('booking_order_status', $this->config->get('BOOKING_ORDER_STATUS'), 'class="form-control"')
			. '</div>'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_ECONOMIC_BOOK_INVOICE_NUMBER_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_BOOK_INVOICE_NUMBER_LBL'),
		'id'     => 'economic_book_invoice_number',
		'showOn' => 'economic_integration:1',
		'field'  => $this->lists['economic_book_invoice_number']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_DEFAULT_ECONMOMIC_ACCOUNT_GROUP_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_ECONMOMIC_ACCOUNT_GROUP_LBL'),
		'id'     => 'default_economic_account_group',
		'showOn' => 'economic_integration:1',
		'field'  => $this->lists['default_economic_account_group']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL'),
		'id'     => 'attribute_as_product_in_economic',
		'showOn' => 'economic_integration:1',
		'field'  => $this->lists['attribute_as_product_in_economic']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_DETAIL_ERROR_MESSAGE_ON_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_DETAIL_ERROR_MESSAGE_ON'),
		'id'     => 'detail_error_message_on',
		'showOn' => 'economic_integration:1',
		'field'  => $this->lists['detail_error_message_on']
	)
);
?>
<div class="form-group row-fluid">
    <div class="col-md-12">
        <p><?php echo JText::_('COM_REDSHOP_CONFIG_ECONOMIC_DESCRIPTION_IMG') ?></p>
		<?php
		echo str_replace(
			'e-conomic',
			'<a href="http://www.e-conomic.dk?opendocument&ReferralID=63" target="_blank">e-conomic</a>',
			JText::_('COM_REDSHOP_CONFIG_ECONOMIC_DESCRIPTION')
		)
		?>
    </div>
</div>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('#economic_invoice_draft').on("change", function (e) {
                e.preventDefault();

                if ($(this).val() == 2) {
                    $("#booking_order_status").show();
                } else {
                    $("#booking_order_status").hide();
                }
            });

			<?php if ($this->config->get('ECONOMIC_INVOICE_DRAFT') == 2): ?>
            $("#booking_order_status").show();
			<?php endif; ?>
        });
    })(jQuery);
</script>
