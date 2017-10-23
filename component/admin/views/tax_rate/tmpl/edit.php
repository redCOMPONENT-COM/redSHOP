<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this));
?>

<script type="text/javascript">
	function updateState(countryValue)
	{
		(function($){
			var url = 'index.php?option=com_redshop&view=state&task=ajaxGetState';

			$.ajax({
				url: url,
				data: {
					country: countryValue,
					"<?php echo JSession::getFormToken() ?>": 1
				},
				method: "POST",
				cache: false
			})
			.success(function(data){
				if ($("#jform_tax_state").length)
				{
					$("#jform_tax_state").parent().html(data);
					$("#jform_tax_state").select2({width:"auto", dropdownAutoWidth:"auto"});
				}
				else if ($("#rs_state_jformtax_state").length)
				{
					$("#rs_state_jformtax_state").parent().html(data);
					$("#rs_state_jformtax_state").select2({width:"auto", dropdownAutoWidth:"auto"});
				}
			});
		})(jQuery);
	}
</script>
