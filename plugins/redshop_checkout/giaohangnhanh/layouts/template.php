<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
extract($displayData);
?>
<script type="text/javascript">
	function getShippingDistrict(infoId)
	{
		jQuery('select[name="rs_ghn_city"]').on('change', function(){
	    	var id = jQuery(this).val();
	    	if (id){
	    		var city = jQuery(this).find('option:selected').text();
	    		jQuery('input[name="city_ST"]').val(city);
	    	}
	    	jQuery.ajax({
		        type: "POST",
		        data: {city: id, id: infoId},
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetGHNDistrict&group=redshop_checkout&format=raw'; ?>",
		        success: function(data) {
		        	jQuery('#s2id_rs_ghn_district .select2-chosen').html('Select District');
		        	jQuery('select[name="rs_ghn_district"]').html(data).trigger("change");
		        }
		    });
	    });
	}

	function getBillingDistrict(infoId)
	{
		jQuery('select[name="rs_ghn_billing_city"]').on('change', function(){
	    	var id = jQuery(this).val();
	    	if (id){
	    		var city = jQuery(this).find('option:selected').text();
	    		jQuery('input[name="city"]').val(city);
	    	}
	    	jQuery.ajax({
		        type: "POST",
		        data: {city: id, id: infoId},
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetGHNDistrict&group=redshop_checkout&format=raw'; ?>",
		        success: function(data) {
		        	jQuery('#s2id_rs_ghn_billing_district .select2-chosen').html('Select District');
		        	jQuery('select[name="rs_ghn_billing_district"]').html(data).trigger("change");
		        }
		    });
	    });
	}

	jQuery(document).ready(function(){
		jQuery(document).on("AfterGetBillingTemplate", function(){
			var infoId = <?php echo $id; ?>;
			jQuery('input[name="zipcode"]').val('<?php echo $zipcode; ?>');
			jQuery('input[name="zipcode_ST"]').val('<?php echo $zipcode; ?>');
			getShippingDistrict(infoId);
			jQuery.ajax({
		        type: "POST",
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetGHNCity&group=redshop_checkout&format=raw'; ?>",
		        data: {id: infoId},
		        success: function(data) {
		        	jQuery('select[name="rs_ghn_city"]').html(data).trigger("change");
		        	jQuery('select[name="rs_ghn_billing_city"]').html(data);
		        	getBillingDistrict(infoId);
		        	jQuery('select[name="rs_ghn_billing_city"]').trigger("change");
		        }
		    });	
		});
		jQuery(document).trigger("AfterGetBillingTemplate");
	});
</script>
