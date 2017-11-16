<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Renders available tags for CMC Mailchimp and redSHOP
 *
 * @package     RedSHOP.Plugins
 * @subpackage  Cmc_Integrate
 * @since       1.0.0
 */
class JFormFieldCmctags extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var     string
	 */
	public $type = 'Cmctags';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_cmc', JPATH_ADMINISTRATOR);

		$redSHOPTags = array(
			'shopper_group_id',
			'shopper_group_name',
			'shopper_group_customer_type',
			'shopper_group_portal',
			'shopper_group_categories',
			'shopper_group_url',
			'shopper_group_logo',
			'shopper_group_introtext',
			'shopper_group_desc',
			'parent_id',
			'default_shipping',
			'default_shipping_rate',
			'published',
			'shopper_group_cart_checkout_itemid',
			'shopper_group_cart_itemid',
			'shopper_group_quotation_mode',
			'show_price_without_vat',
			'tax_group_id',
			'apply_product_price_vat',
			'show_price',
			'use_as_catalog',
			'is_logged_in',
			'shopper_group_manufactures',
			'users_info_id',
			'user_id',
			'user_email',
			'address_type',
			'firstname',
			'lastname',
			'vat_number',
			'tax_exempt',
			'country_code',
			'address',
			'city',
			'state_code',
			'zipcode',
			'phone',
			'tax_exempt_approved',
			'approved',
			'is_company',
			'ean_number',
			'braintree_vault_number',
			'veis_vat_number',
			'veis_status',
			'company_name',
			'requesting_tax_exempt',
			'accept_terms_conditions'
		);

		sort($redSHOPTags);

		$document = JFactory::getDocument();

		$document->addScriptDeclaration(
			'(function($){
				$(document).ready(function(){
					$("#jform_params_listId").on("change", function(){
						$.post(
							"index.php?option=com_ajax&plugin=CmcIntegrateListSelect&group=redshop_user&format=json",
							{
								"listId" : $(this).val(),
								"' . JSession::getFormToken() . '" : 1
							},
							function(response) {
								$("#cmc_integrate_list_fields tbody tr").remove();
								
								if (response.length) {
									var data = $.parseJSON(response);
									var $tableBody = $("#cmc_integrate_list_fields tbody");
									
									for (i = 0; i < data.length; i++) {
										var $tr = $("<tr />");
										var item = data[i].tag;
										item = item.split(";");
										$tr.append("<td><span class=\"badge badge-info\">" + item[0] + "</span></td>");
										$tr.append("<td>" + item[1] + "</td>");
										$tr.append("<td>" + item[2] + "</td>");
										
										if (item[3] == 1) {
											$tr.append("<td><span class=\"icon-save\"></span></td>");
										} else {
											$tr.append("<td></td>");
										}
										
										$tr.appendTo($tableBody);
									}
								}
							}
						);
					});
					
					$("#jform_params_listId").trigger("change");
				});
			})(jQuery);'
		);

		$html = '<div class="row">
			<div class="span6">
				<fieldset><legend>' . JText::_('PLG_REDSHOP_USER_CMC_INTEGRATE_FIELDS_CMC') . '</legend>
					<table class="table table-striped" id="cmc_integrate_list_fields">
						<thead>
							<tr><th>Tag</th><th>Type</th><th>Name</th><th>Required</th></tr>
						</thead>
						<tbody></tbody>
					</table>
				</fieldset>
			</div>
			<div class="span6">
				<fieldset><legend>' . JText::_('PLG_REDSHOP_USER_CMC_INTEGRATE_FIELDS_REDSHOP') . '</legend>
				<ul class="nav nav-stacked">';

		foreach ($redSHOPTags as $tags)
		{
			$html .= '<li>' . $tags . '</li>';
		}

		$html .= '</ul></fieldset></div></div>';

		return $html;
	}
}
