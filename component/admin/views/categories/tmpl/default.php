<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render('view.list', array('data' => $this));
?>
<script language="javascript" type="text/javascript">
	function AssignTemplate() {
		var form = jQuery('#adminForm');
		var templatevalue = jQuery('select#filter_category_template').val();
		var boxchecked = jQuery('input[name="boxchecked"]').val();
		if (boxchecked == 0) {
			jQuery('select#filter_category_template').val(0);
			alert('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_CATEGORY');?>');

		} else {
			jQuery('input[name="task"]').val('assignTemplate');

			if (confirm("<?php echo JText::_('COM_REDSHOP_SURE_WANT_TO_ASSIGN_TEMPLATE');?>")) {
				form.submit();
			} else {
				jQuery('select#filter_category_template').val(0);
				return false;
			}
		}
	}
</script>
