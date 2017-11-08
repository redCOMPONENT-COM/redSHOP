<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');

$uri = JUri::root();

$app = JFactory::getApplication();
$selectedTabPosition = $app->getUserState('com_redshop.configuration.selectedTabPosition', 'general');

if ($app->input->getInt('dashboard', 0))
{
	$selectedTabPosition = 'dashboard';
}

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		// Find the position of selected tab
		var allTabsNames = document.querySelectorAll('.tabconfig a');
		var selectedTabName  = document.querySelectorAll('.tabconfig li.active a');

		for (var i=0; i < allTabsNames.length; i++) {
			if (selectedTabName[0].innerHTML === allTabsNames[i].innerHTML) {
				var selectedTabPosition =allTabsNames[i].getAttribute("aria-controls");
				break;
			}
		}

		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if (pressbutton == 'save' || pressbutton == 'apply') {
			if (pressbutton == 'save')
				form.selectedTabPosition.value = '';
			else
				form.selectedTabPosition.value = selectedTabPosition;

			var obj = form.economic_integration;

			var sel_discount_flag = false;

			if (radioGetCheckedValue(form.discount_enable) == 1 ||
				radioGetCheckedValue(form.coupons_enable) == 1 ||
				radioGetCheckedValue(form.vouchers_enable) == 1) {
				sel_discount_flag = true;
			}

			if (sel_discount_flag) {
				if (form.discount_type.value == "0" || form.discount_type.value == "") {
					alert("<?php
				echo JText::_('COM_REDSHOP_PLEASE_SELECT_DISCOUNT_TYPE' );
				?>");
					return false;
				}
			}

			for (i = 0; i < obj.length; i++) {
				if (form.economic_integration[i].value == 1 && form.economic_integration[i].checked) {
					if (form.default_economic_account_group.value == 0) {
						alert("<?php
					echo JText::_('COM_REDSHOP_SELECT_ECONOMIC_ACCOUNTING_GROUP' );
					?>");
						form.default_economic_account_group.focus();
						return false;
					}

					if (form.economic_invoice_draft.value == 2 && form.booking_order_status.value == '0') {
						alert("<?php echo JText::_('COM_REDSHOP_SELECT_BOOK_INVOICE_ORDER_STATUS' );?>");
						form.booking_order_status.focus();
						return false;
					}
				}
			}
			if (form.thousand_seperator.value == "'" || form.thousand_seperator.value == '"') {
				alert("<?php echo JText::_('COM_REDSHOP_INVALID_THOUSAND_SEPERATOR' );?>");
				form.thousand_seperator.value = '';
				form.thousand_seperator.focus();
				return false;
			}
			if (form.price_seperator.value == "'" || form.price_seperator.value == '"') {
				alert("<?php echo JText::_('COM_REDSHOP_INVALID_PRICE_SEPERATOR' );?>");
				form.price_seperator.value = '';
				form.price_seperator.focus();
				return false;
			}
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>
<script type="text/javascript">
    function rsConfigShowOn(fieldName, fieldValue, wrapperId)
    {
        (function ($) {
            var $input = $('input[name="' + fieldName + '"]');
            var $wrapper = $("#" + wrapperId);

            if ($input.length) {
                var inputType = $input.attr('type');
                var inputVal  = '';

                if (inputType == "radio") {
                    inputVal = $('input[name="' + fieldName + '"]:checked').val();
                }
                else {
                    inputVal = $input.val();
                }

                if (inputVal == fieldValue) {
                    $wrapper.show('normal', function(){
                        $(this).next("hr").show();
                    });
                }
                else {
                    $wrapper.hide('normal', function(){
                        $(this).next("hr").hide();
                    })
                }

                $input.on('change', function (event) {
                    if ($(this).val() == fieldValue) {
                        $wrapper.show('normal', function(){
                            $(this).next("hr").show();
                        });
                    }
                    else {
                        $wrapper.hide('normal', function(){
                            $(this).next("hr").hide();
                        })
                    }
                });
            }
        })(jQuery);
    }
</script>
<form action="<?php echo 'index.php?option=com_redshop'; ?>" method="post" name="adminForm" id="adminForm"
	  enctype="multipart/form-data">
	<?php
		echo RedshopLayoutHelper::render(
			'component.full.tab.main',
			array(
				'view'    => $this,
				'tabMenu' => $this->tabmenu->getData('tab')->items,
			)
		);
	?>
	<input type="hidden" name="backward_compatible_js" value="<?php echo $this->config->get('BACKWARD_COMPATIBLE_JS') ?>" />
	<input type="hidden" name="backward_compatible_php" value="<?php echo $this->config->get('BACKWARD_COMPATIBLE_PHP') ?>" />
	<input type="hidden" name="view" value="configuration"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="selectedTabPosition" value=""/>
	<input type="hidden" name="cid" value="1"/>
	<input type="hidden" name="option" value="com_redshop"/>
</form>
<script type="text/javascript">
	function cleardata() {

		if (request.readyState == 4) {
			var output = request.responseText;
			if (output == 0) {
				document.getElementById('responce_clear').style.color = "red";
				document.getElementById('responce_clear').innerHTML = "<?php echo JText::_('COM_REDSHOP_NO_DATA_DELETE' ); ?>";
			} else {
				document.getElementById('responce_clear').style.color = "green";
				document.getElementById('responce_clear').innerHTML = output + " <?php echo JText::_('COM_REDSHOP_RECORDS_DELETED');?>";
			}
		}
	}
	function getHTTPObject() {
		var xhr = false;
		if (window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e) {
				try {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e) {
					xhr = false;
				}
			}
		}
		return xhr;
	}
</script>
