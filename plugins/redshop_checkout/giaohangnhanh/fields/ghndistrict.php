<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Renders Giaohangnhanh district list
 *
 * @since  1.1
 */
class JFormFieldGhndistrict extends JFormFieldList
{
	/**
	 * A flexible category list that respects access controls
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'ghndistrict';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		JFactory::getDocument()->addScriptDeclaration("
			jQuery(document).ready(function(){
				ghnSelectDistrict(jQuery('#jform_params_city'));
			});
			function ghnSelectDistrict(el) {
				var city = jQuery(el).val();
				jQuery.ajax({
			        type: 'POST',
			        data: {city: city},
			        url: '" . JUri::root() . "index.php?option=com_ajax&plugin=GetGHNDistrict&group=redshop_checkout&format=raw',
			        success: function(data) {
			        	jQuery('select#jform_params_from_district_code').html('');
			        	jQuery('select#jform_params_from_district_code').append(data);
			   			jQuery('select#jform_params_from_district_code').val('" . $this->value . "');
			        	jQuery('select#jform_params_from_district_code').trigger('liszt:updated');
			        }
			    });
			}"
		);

		return parent::getInput();
	}
}
