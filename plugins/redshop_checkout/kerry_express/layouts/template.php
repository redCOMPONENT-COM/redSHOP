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
	jQuery(document).ready(function(){
		var infoId = <?php echo $id; ?>;
		jQuery.ajax({
	        type: "POST",
	        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetKerryCity&group=redshop_checkout&format=raw'; ?>",
	        data: {id: infoId},
	        success: function(data) {
	        	jQuery('select#rs_kerry_city').append(data);
	        	jQuery("select#rs_kerry_city").trigger("change");
	        }
	    });

	    jQuery('select#rs_kerry_city').on('change', function(){
	    	var id = jQuery(this).val();
	    	jQuery.ajax({
		        type: "POST",
		        data: {city: id, id: infoId},
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetKerryDistrict&group=redshop_checkout&format=raw'; ?>",
		        success: function(data) {
		        	jQuery('select#rs_kerry_district').html('');
		        	jQuery('#s2id_rs_kerry_district .select2-chosen').html('Select District');
		        	jQuery('select#rs_kerry_district').append(data);
		        	jQuery("select#rs_kerry_district").trigger("change");
		        }
		    });
	    })

	    jQuery('select#rs_kerry_district').on('change', function(){
	    	var id = jQuery(this).val();
	    	jQuery.ajax({
		        type: "POST",
		        data: {district: id, id: infoId},
		        url: "<?php echo JUri::root() . 'index.php?option=com_ajax&plugin=GetKerryWard&group=redshop_checkout&format=raw'; ?>",
		        success: function(data) {
		        	jQuery('select#rs_kerry_ward').html('');
		        	jQuery('#s2id_rs_kerry_ward .select2-chosen').html('Select Ward');
		        	jQuery('select#rs_kerry_ward').append(data);
		        	jQuery("select#rs_kerry_ward").trigger("change");
		        }
		    });
	    })
	});
</script>
