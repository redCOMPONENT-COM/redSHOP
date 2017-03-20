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

?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.manufacturer_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_MANUFACTURER_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		} else if (parseInt(form.product_per_page.value) <= 0) {
			alert("<?php echo JText::_('COM_REDSHOP_PRODUCTS_PER_PAGE_MUST_BE_GREATER_THAN_ZERO', true ); ?>");
		} else if (form.manufacturer_url.value != "") {
			if (!form.manufacturer_url.value.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/)) {
				alert("<?php echo JText::_('COM_REDSHOP_ENTER_VALID_MANUFACTURER_URL', true); ?>");
			} else {
				submitform(pressbutton);
			}
		} else {
			submitform(pressbutton);
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">

	<?php
		echo RedshopLayoutHelper::render(
			'component.full.tab.main',
			array(
				'view'    => $this,
				'tabMenu' => $this->tabmenu->getData('tab')->items,
			)
		);
	?>

	<input type="hidden" name="cid[]" value="<?php echo $this->detail->manufacturer_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="manufacturer_detail"/>
</form>


