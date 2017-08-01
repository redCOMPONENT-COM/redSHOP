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
<script type="text/javascript">
    var xmlhttp = null;

    function GetXmlHttpObject() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            return new XMLHttpRequest();
        }

        if (window.ActiveXObject) {
            // code for IE6, IE5
            return new ActiveXObject("Microsoft.XMLHTTP");
        }

        return null;
    }

    function resetOrderId() {
        if (!confirm("<?php echo Jtext::_('COM_REDSHOP_CONFIRM_ORDER_ID_RESET');?> ")) {
            return false;
        }
        else {
            xmlhttp = GetXmlHttpObject();
            if (xmlhttp == null) {
                alert("Your browser does not support XMLHTTP!");
                return;
            }
            var url = 'index.php?option=com_redshop&view=configuration&task=resetOrderId&sid=' + Math.random();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4) {
                    alert("<?php echo JText::_('COM_REDSHOP_SUCCESSFULLY_RESET_ORDER_ID');?>");
                }
            }
            xmlhttp.open("GET", url, true);
            xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xmlhttp.send(null);
        }
    }
</script>
<div class="row adminform">
    <div class="col-sm-6">
        <div class="box box-primary form-vertical">
            <div class="box-header with-border">
                <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_ORDER_MAIN_SETTINGS') ?></h3>
            </div>
            <div class="box-body">
				<?php
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ORDER_ID_RESET_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ORDER_ID_RESET_LBL'),
						'field' => '<a class="btn btn-success" onclick="javascript:resetOrderId();"
                            title="' . JText::_('COM_REDSHOP_ORDER_ID_RESET_LBL') . '">' . JText::_('COM_REDSHOP_ORDER_ID_RESET') . '</a>'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_LBL'),
						'desc'  => JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_LBL'),
						'field' => $this->lists['order_mail_after']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_INVOICE_MAIL_ENABLE_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_INVOICE_MAIL_ENABLE'),
						'field' => $this->lists['invoice_mail_enable']
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title'  => JText::_('COM_REDSHOP_INVOICE_MAIL_SEND_OPTION_LBL'),
						'desc'   => JText::_('COM_REDSHOP_TOOLTIP_INVOICE_MAIL_SEND_OPTION'),
						'field'  => $this->lists['invoice_mail_send_option'],
						'id'     => 'invoice_mail_send_option',
						'showOn' => 'invoice_mail_enable:1'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_TOOLTIP_SEND_MAIL_TO_CUSTOMER_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SEND_MAIL_TO_CUSTOMER'),
						'field' => $this->lists['send_mail_to_customer'],
						'line'  => false
					)
				);
				?>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="box box-primary form-vertical">
            <div class="box-header with-border">
                <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_ORDER_INVOICE_SETTINGS') ?></h3>
            </div>
            <div class="box-body">
				<?php
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_FIRST_INVOICE_NUMBER_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_FIRST_INVOICE_NUMBER_LBL'),
						'field' => '<input type="text" name="first_invoice_number" id="first_invoice_number" class="form-control"
                            value="' . $this->config->get('FIRST_INVOICE_NUMBER') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_ORDER_NUMBER_TEMPLATE_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ORDER_NUMBER_TEMPLATE'),
						'field' => '<input type="text" name="invoice_number_template" id="invoice_number_template" class="form-control"
                            value="' . $this->config->get('INVOICE_NUMBER_TEMPLATE') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_INVOICE_NUMBER_TEMPLATE_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_INVOICE_NUMBER_TEMPLATE'),
						'field' => '<input type="text" name="real_invoice_number_template" id="real_invoice_number_template" class="form-control"
                            value="' . $this->config->get('REAL_INVOICE_NUMBER_TEMPLATE') . '" />'
					)
				);
				echo RedshopLayoutHelper::render(
					'config.config',
					array(
						'title' => JText::_('COM_REDSHOP_INVOICE_NUMBER_FOR_FREE_ORDER_LBL'),
						'desc'  => JText::_('COM_REDSHOP_TOOLTIP_INVOICE_NUMBER_FOR_FREE_ORDER_LBL'),
						'field' => JHtml::_(
							'redshopselect.booleanlist',
							'invoice_number_for_free_order',
							'',
							$this->config->get('INVOICE_NUMBER_FOR_FREE_ORDER')
						),
						'line'  => false
					)
				);
				?>
            </div>
        </div>
    </div>
</div>
