<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

require_once 'components/com_redshop/views/configuration/view.html.php';

/**
 * Class RedshopViewWizard
 */
class RedshopViewWizard extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		// Try to get temporary wizard config from config
		$session = JFactory::getSession();
		$wizardConfig = $session->get('redshop.wizard', false);

		// If it's not created than use distribute config file
		if ($wizardConfig === false)
		{
			$config = JPATH_BASE . '/components/com_redshop/config/config.dist.php';

			if (JFile::exists($config))
			{
				// Only load this file if this class is not declared
				if (!class_exists('RedshopConfig'))
				{
					require_once $config;
				}

				$distConfig = new RedshopConfig;
				$wizardConfig = get_object_vars($distConfig);
			}
			else
			{
				$wizardConfig = array();
			}

			// Save back to session
			$session->set('redshop.wizard', $wizardConfig);
		}

		// Set to view variable to use in tmpl files
		$this->temparray = $wizardConfig;

		$uri         = JFactory::getURI();
		$db          = JFactory::getDbo();
		$redhelper   = redhelper::getInstance();
		$config      = Redconfiguration::getInstance();
		$extra_field = extra_field::getInstance();
		$model       = $this->getModel();
		$document    = JFactory::getDocument();

		$document->setTitle(JText::_('COM_REDSHOP_CONFIG'));
		$document->addScript('components/com_redshop/assets/js/validation.js');
		JHtml::stylesheet('com_redshop/redshop.css', array(), true);
		$document->addStyleSheet('components/com_redshop/assets/css/wizard.css');

		// Shop country
		$q = "SELECT  country_3_code as value,country_name as text,country_jtext from #__redshop_country ORDER BY country_name ASC";
		$db->setQuery($q);
		$countries = $db->loadObjectList();
		$countries = RedshopHelperUtility::convertLanguageString($countries);

		$lists['shop_country'] = JHTML::_('select.genericlist', $countries, 'shop_country',
			'class="inputbox" size="1" ', 'value', 'text', $this->temparray['SHOP_COUNTRY']
		);

		// Shipping Country Start
		$lists['default_shipping_country'] = JHTML::_('select.genericlist', $countries, 'default_shipping_country',
			'class="inputbox" size="1" ', 'value', 'text', $this->temparray['DEFAULT_SHIPPING_COUNTRY']
		);

		// Date Formats
		$default_dateformat = $config->getDateFormat();
		$lists['default_dateformat'] = JHTML::_('select.genericlist', $default_dateformat, 'default_dateformat',
			'class="inputbox" ', 'value', 'text', $this->temparray['DEFAULT_DATEFORMAT']
		);

		// Country lists
		$country_list = explode(',', $this->temparray['COUNTRY_LIST']);
		$lists['country_list'] = JHTML::_('select.genericlist', $countries, 'country_list[]',
			'class="inputbox disableBootstrapChosen" multiple="multiple" size="5"', 'value', 'text', $country_list
		);

		// Invoice mail enable
		$lists['invoice_mail_enable'] = JHTML::_('redshopselect.booleanlist', 'invoice_mail_enable',
			'class="inputbox" onchange="enableInvoice(this.value);"', $this->temparray['INVOICE_MAIL_ENABLE']
		);

		// Invoice mail send type
		$invoice_mail_send_option = array();
		$invoice_mail_send_option[0] = new stdClass;
		$invoice_mail_send_option[0]->value = 0;
		$invoice_mail_send_option[0]->text = JText::_('COM_REDSHOP_SELECT');

		$invoice_mail_send_option[1] = new stdClass;
		$invoice_mail_send_option[1]->value = 1;
		$invoice_mail_send_option[1]->text = JText::_('COM_REDSHOP_ADMINISTRATOR');

		$invoice_mail_send_option[2] = new stdClass;
		$invoice_mail_send_option[2]->value = 2;
		$invoice_mail_send_option[2]->text = JText::_('COM_REDSHOP_CUSTOMER');

		$invoice_mail_send_option[3] = new stdClass;
		$invoice_mail_send_option[3]->value = 3;
		$invoice_mail_send_option[3]->text = JText::_('COM_REDSHOP_BOTH');

		$lists['invoice_mail_send_option'] = JHTML::_('select.genericlist', $invoice_mail_send_option, 'invoice_mail_send_option',
			'class="inputbox" ', 'value', 'text', $this->temparray['INVOICE_MAIL_SEND_OPTION']
		);

		// Registration methods
		$register_methods = array();
		$register_methods[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_REGISTER_WITH_ACCOUNT_CREATION'));
		$register_methods[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_REGISTER_WITHOUT_ACCOUNT_CREATION'));
		$register_methods[] = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_REGISTER_ACCOUNT_OPTIONAL'));
		$register_methods[] = JHTML::_('select.option', '3', JText::_('COM_REDSHOP_REGISTER_ACCOUNT_SILENT'));
		$lists['register_method'] = JHTML::_('select.genericlist', $register_methods, 'register_method',
			'class="inputbox" id="register_method"', 'value', 'text', $this->temparray['REGISTER_METHOD']
		);
		unset($register_methods);

		// Currency data
		$currency_data = $model->getCurrency();
		$lists['currency_data'] = JHTML::_('select.genericlist', $currency_data, 'currency_code',
			'class="inputbox" size="1" ', 'value', 'text', $this->temparray['CURRENCY_CODE']
		);

		// Discount
		$discount_type = array();

		$discount_type[0] = new stdClass;
		$discount_type[0]->value = 0;
		$discount_type[0]->text = JText::_('COM_REDSHOP_SELECT');

		$discount_type[1] = new stdClass;
		$discount_type[1]->value = 1;
		$discount_type[1]->text = JText::_('COM_REDSHOP_DISCOUNT_OR_VOUCHER_OR_COUPON');

		$discount_type[2] = new stdClass;
		$discount_type[2]->value = 2;
		$discount_type[2]->text = JText::_('COM_REDSHOP_DISCOUNT_VOUCHER_OR_COUPON');

		$discount_type[3] = new stdClass;
		$discount_type[3]->value = 3;
		$discount_type[3]->text = JText::_('COM_REDSHOP_DISCOUNT_VOUCHER_COUPON');

		$discount_type[4] = new stdClass;
		$discount_type[4]->value = 4;
		$discount_type[4]->text = JText::_('COM_REDSHOP_DISCOUNT_VOUCHER_COUPON_MULTIPLE');

		$lists['discount_type'] = JHTML::_('select.genericlist', $discount_type, 'discount_type',
			'class="inputbox" ', 'value', 'text', $this->temparray['DISCOUNT_TYPE']
		);
		$lists['discount_enable'] = JHTML::_('redshopselect.booleanlist', 'discount_enable', 'class="inputbox" ', $this->temparray['DISCOUNT_ENABLE']);
		$lists['coupons_enable'] = JHTML::_('redshopselect.booleanlist', 'coupons_enable', 'class="inputbox" ', $this->temparray['COUPONS_ENABLE']);
		$lists['vouchers_enable'] = JHTML::_('redshopselect.booleanlist', 'vouchers_enable', 'class="inputbox" ', $this->temparray['VOUCHERS_ENABLE']);

		// Discount after Shipping
		$shipping_after = $this->temparray['SHIPPING_AFTER'];
		$lists['shipping_after'] = $extra_field->rs_booleanlist('shipping_after', 'class="inputbox"', $shipping_after,
			$yes = JText::_('COM_REDSHOP_TOTAL'), $no = JText::_('COM_REDSHOP_SUBTOTAL_LBL'), '', 'total', 'subtotal'
		);

		// Vat Country
		$tmp = array();
		$tmp[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$default_vat_country = @array_merge($tmp, $countries);
		$lists['default_vat_country'] = JHTML::_('select.genericlist', $default_vat_country, 'default_vat_country',
			'class="inputbox" onchange="changeStateList();"', 'value', 'text', $this->temparray['DEFAULT_VAT_COUNTRY']
		);

		// VAT States
		$country_list_name = 'default_vat_country';
		$state_list_name = 'default_vat_state';
		$selected_country_code = $this->temparray['DEFAULT_VAT_COUNTRY'];
		$selected_state_code = $this->temparray['DEFAULT_VAT_STATE'];

		if (empty($selected_state_code))
		{
			$selected_state_code = "originalPos";
		}
		else
		{
			$selected_state_code = "'" . $selected_state_code . "'";
		}

		$db->setQuery("SELECT c.id, c.country_3_code, s.state_name, s.state_2_code
						FROM #__redshop_country c
						LEFT JOIN #__redshop_state s
						ON c.id=s.country_id OR s.country_id IS NULL
						ORDER BY c.id, s.state_name");

		$states = $db->loadObjectList();

		// Build the State lists for each Country
		$script = "<script>";
		$script .= "var originalOrder = '1';\n";
		$script .= "var originalPos = '$selected_country_code';\n";
		$script .= "var states = new Array();	// array in the format [key,value,text]\n";
		$i = 0;
		$prev_country = '';

		for ($j = 0, $jn = count($states); $j < $jn; $j++)
		{
			$state = $states[$j];

			$country_3_code = $state->country_3_code;

			if ($state->state_name)
			{
				if ($prev_country != $country_3_code)
				{
					$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','',' -= " . JText::_("COM_REDSHOP_SELECT") . " =-' );\n";
				}

				$prev_country = $country_3_code;

				$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','" . $state->state_2_code . "','" . addslashes(JText::_($state->state_name)) . "' );\n";
			}
			else
			{
				$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','','" . JText::_("COM_REDSHOP_NONE") . "' );\n";
			}
		}

		$script .= "
		function changeStateList() {
		  var selected_country = null;

		  	//for (var i=0; i<document.installform.default_vat_country.length; i++){

		  		var selind = document.installform." . $country_list_name . ".selectedIndex;

		  		selected_country = document.installform." . $country_list_name . "[selind].value;
			//}

		  VATchangeDynaList('" . $state_list_name . "',states,selected_country, originalPos, originalOrder);

			if(window.jQuery){
				jQuery(\"#" . $state_list_name . "\").trigger(\"liszt:updated\");
			}
	 	}
		writeDynaList( 'class=\"inputbox\" name=\"$state_list_name\" size=\"1\" id=\"$state_list_name\"', states, originalPos, originalPos, $selected_state_code );

		function VATchangeDynaList( listname, source, key, orig_key, orig_val ) {
			var list = eval( 'document.installform.' + listname );

			// empty the list
			for (i in list.options.length) {
				list.options[i] = null;
			}
			i = 0;
			for (x in source) {
				if (source[x][0] == key) {
					opt = new Option();
					opt.value = source[x][1];
					opt.text = source[x][2];

					if ((orig_key == key && orig_val == opt.value) || i == 0) {
						opt.selected = true;
					}
					list.options[i++] = opt;
				}
			}
			list.length = i;
		}

		</script>";
		$lists['default_vat_state'] = $script;

		$lists['apply_vat_on_discount'] = JHTML::_('redshopselect.booleanlist', 'apply_vat_on_discount',
			'class="inputbox" size="1"', $this->temparray['APPLY_VAT_ON_DISCOUNT']
		);

		$calculate_vat_on = $this->temparray['CALCULATE_VAT_ON'];
		$lists['calculate_vat_on'] = $extra_field->rs_booleanlist('calculate_vat_on',
			'class="inputbox"', $calculate_vat_on,
			$yes = JText::_('COM_REDSHOP_BILLING_ADDRESS_LBL'),
			$no = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_LBL'), '', 'BT', 'ST'
		);

		$this->taxrates = $this->get('TaxRates');

		$this->lists       = $lists;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
