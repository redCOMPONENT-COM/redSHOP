<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
extract($displayData);
?>
<script type="text/javascript">
	function getShippingDistrict(infoId)
	{
		jQuery('select[name="rs_kerry_city"]').on('change', function(){
	    	var id = jQuery(this).val();
	    	jQuery.ajax({
		        type: "POST",
		        data: {city: id, id: infoId},
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetKerryDistrict&group=redshop_checkout&format=raw'; ?>",
		        success: function(data) {
		        	jQuery('select[name="rs_kerry_district"]').html('');
		        	jQuery('#s2id_rs_kerry_district .select2-chosen').html('Select District');
		        	jQuery('select[name="rs_kerry_district"]').append(data);
		        	jQuery('select[name="rs_kerry_district"]').trigger("change");
		        }
		    });
	    });
	}

	function getShippingWard(infoId)
	{
		jQuery('select[name="rs_kerry_district"]').on('change', function(){
	    	var id = jQuery(this).val();
	    	jQuery.ajax({
		        type: "POST",
		        data: {district: id, id: infoId},
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetKerryWard&group=redshop_checkout&format=raw'; ?>",
		        success: function(data) {
		        	jQuery('select[name="rs_kerry_ward"]').html('');
		        	jQuery('#s2id_rs_kerry_ward .select2-chosen').html('Select Ward');
		        	jQuery('select[name="rs_kerry_ward"]').append(data);
		        	jQuery('select[name="rs_kerry_ward"]').trigger("change");
		        }
		    });
	    });
	}

	function getBillingDistrict(infoId)
	{
		jQuery('select[name="rs_kerry_billing_city"]').on('change', function(){
	    	var id = jQuery(this).val();
	    	if (id){
	    		var city = jQuery(this).find('option:selected').text();
	    		jQuery('input[name="city"]').val(city);
	    	}
	    	jQuery.ajax({
		        type: "POST",
		        data: {city: id, id: infoId},
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetKerryDistrict&group=redshop_checkout&format=raw'; ?>",
		        success: function(data) {
		        	jQuery('select[name="rs_kerry_billing_district"]').html('');
		        	jQuery('#s2id_rs_kerry_billing_district .select2-chosen').html('Select District');
		        	jQuery('select[name="rs_kerry_billing_district"]').append(data);
		        	jQuery('select[name="rs_kerry_billing_district"]').trigger("change");
		        }
		    });
	    });
	}

	function getBillingWard(infoId)
	{
		jQuery('select[name="rs_kerry_billing_district"]').on('change', function(){
	    	var id = jQuery(this).val();
	    	jQuery.ajax({
		        type: "POST",
		        data: {district: id, id: infoId},
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetKerryWard&group=redshop_checkout&format=raw'; ?>",
		        success: function(data) {
		        	jQuery('select[name="rs_kerry_billing_ward"]').html('');
		        	jQuery('#s2id_rs_kerry_billing_ward .select2-chosen').html('Select Ward');
		        	jQuery('select[name="rs_kerry_billing_ward"]').append(data);
		        	jQuery('select[name="rs_kerry_billing_ward"]').trigger("change");
		        }
		    });
	    });
	}

	jQuery(document).ready(function(){
		jQuery(document).on("AfterGetBillingTemplate", function(){
			var infoId = <?php echo $id; ?>;
			jQuery('input[name="zipcode"]').val('<?php echo $zipcode; ?>');
		    getShippingDistrict(infoId);
		    getShippingWard(infoId);
		    jQuery.ajax({
		        type: "POST",
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetKerryCity&group=redshop_checkout&format=raw'; ?>",
		        data: {id: infoId},
		        success: function(data) {
		        	jQuery('select[name="rs_kerry_city"]').append(data).trigger("change");
		        	jQuery('select[name="rs_kerry_billing_city"]').append(data);
		        	getBillingDistrict(infoId);
		    		getBillingWard(infoId);
		    		jQuery('select[name="rs_kerry_billing_city"]').append(data).trigger('change');
		        }
		    });	
		});
		jQuery(document).trigger("AfterGetBillingTemplate");
	});
</script>
