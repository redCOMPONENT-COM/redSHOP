<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
    /*-- @TODO Move all these scripts to js file instead. About validate should use OOP library instead. */
    /*submitbutton = function (pressbutton) {

     // Find the position of selected tab
     var allTabsNames = document.querySelectorAll('.tabconfig a');
     var selectedTabName = document.querySelectorAll('.tabconfig li.active a');

     for (var i = 0; i < allTabsNames.length; i++) {
     if (selectedTabName[0].innerHTML === allTabsNames[i].innerHTML) {
     var selectedTabPosition = allTabsNames[i].getAttribute("aria-controls");
     break;
     }
     }

     var form = document.adminForm;

     function parseDate(date) {
     var parts = date.split("-");
     return new Date(parts[2], parts[1] - 1, parts[0]);
     }

     if (pressbutton == 'cancel') {
     submitform(pressbutton);
     return;
     }

     if (pressbutton == 'prices') {
     document.adminForm.view.value = 'prices';
     submitform(pressbutton);
     return;
     }
     if (pressbutton == 'wrapper') {
     document.adminForm.view.value = 'wrapper';
     submitform(pressbutton);
     return;
     }

     if (pressbutton == 'save')
     form.selectedTabPosition.value = '';
     else
     form.selectedTabPosition.value = selectedTabPosition;

     if (form.product_name.value == "") {
     alert("<?php echo JText::_('COM_REDSHOP_PRODUCT_ITEM_MUST_HAVE_A_NAME', true); ?>");
     return;
     } else if (form.product_number.value == "") {
     alert("<?php echo JText::_('COM_REDSHOP_PRODUCT_ITEM_MUST_HAVE_A_NUMBER', true); ?>");
     return;
     } else if (form.product_category.value == "") {
     alert("<?php echo JText::_('COM_REDSHOP_CATEGORY_MUST_SELECTED', true); ?>");
     return;
     } else if (form.product_template.value == "0") {
     alert("<?php echo JText::_('COM_REDSHOP_TEMPLATE_MUST_SELECTED', true); ?>");
     return;
     } else if (parseFloat(form.discount_price.value) >= parseFloat(form.product_price.value)) {
     alert("<?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE_MUST_BE_LESS_THAN_PRICE', true); ?>");
     return;
     } else if (parseDate(form.discount_stratdate.value) > parseDate(form.discount_enddate.value)) {
     alert("<?php echo JText::_('COM_REDSHOP_DISCOUNT_START_DATE_END_DATE_CONDITION', true); ?>");
     return;
     } else if ((parseInt(form.min_order_product_quantity.value) > parseInt(form.max_order_product_quantity.value)) && parseInt(form.max_order_product_quantity.value) > 0) {
     alert("<?php echo JText::_('COM_REDSHOP_MINIMUM_QUANTITY_PER_ORDER_MUST_BE_LESS_THAN_MAXIMUM_QUANTITY_PER_ORDER', true); ?>");
     return;
     } else if (form.copy_attribute.length) {
     for (var i = 0; i < form.copy_attribute.length; i++) {
     if (form.copy_attribute[i].checked) {
     if (form.copy_attribute[i].value == "1" && form.attribute_set_id.value == '') {
     alert("<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SET_MUST_BE_SELECTED', true); ?>");
     return;
     }
     }
     }
     }

     if (!document.formvalidator.isValid(form)) {
     return false;
     }

     submitform(pressbutton);
     };

     function oprand_check(s) {
     var oprand = s.value;
     if (oprand != '+' && oprand != '-' && oprand != '=' && oprand != '*' && oprand != "/") {
     alert("<?php echo JText::_('COM_REDSHOP_WRONG_OPRAND', true); ?>");

     s.value = "+";
     }
     }*/

</script>
<?php $jinput = JFactory::getApplication()->input; ?>
<?php if ($jinput->getBool('showbuttons', false)) : ?>
    <fieldset>
        <div style="float: right">
            <button type="button" onclick="submitbutton('save');"> <?php echo JText::_('COM_REDSHOP_SAVE'); ?> </button>
            <button type="button"
                    onclick="window.parent.SqueezeBox.close();"> <?php echo JText::_('COM_REDSHOP_CANCEL'); ?> </button>
        </div>
        <div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT'); ?></div>
    </fieldset>
<?php endif; ?>
<form action="index.php?option=com_redshop&task=product.edit&id=<?php echo (int) $this->item->id; ?>"
      method="post"
      name="adminForm"
      id="adminForm"
      class="form-validate"
      enctype="multipart/form-data"
>
	<?php
	echo RedshopLayoutHelper::render(
		'component.full.tab.main',
		array(
			'view'    => $this,
			'tabMenu' => $this->tabmenu->getData('tab')->items,
		)
	);

	// Echo plugin tabs.
	$this->dispatcher->trigger('onDisplayProductTabs', array($this->item));
	?>

    <div class="clr"></div>

    <fieldset>
        <input type="hidden" name="id" id="id"
               value="<?php echo (int) $this->item->id; ?>"/>
        <input type="hidden" name="old_manufacturer_id"
               value="<?php echo $this->item->manufacturer_id; ?>"/>
        <input type="hidden" name="old_image" id="old_image"
               value="<?php echo $this->item->product_full_image; ?>">
        <input type="hidden" name="old_thumb_image" id="old_thumb_image"
               value="<?php echo $this->item->product_thumb_image; ?>">
        <input type="hidden" name="product_back_full_image" id="product_back_full_image"
               value="<?php echo $this->item->product_back_full_image; ?>">
        <input type="hidden" name="product_back_thumb_image" id="product_back_thumb_image"
               value="<?php echo $this->item->product_back_thumb_image; ?>">
        <input type="hidden" name="product_preview_image" id="product_preview_image"
               value="<?php echo $this->item->product_preview_image; ?>">
        <input type="hidden" name="product_preview_back_image" id="product_preview_back_image"
               value="<?php echo $this->item->product_preview_back_image; ?>">
        <input type="hidden" name="task"
               value=""/>
        <input type="hidden" name="section_id"
               value=""/>
        <input type="hidden" name="template_id"
               value=""/>
        <input type="hidden" name="hits"
               value="<?php echo (int) $this->item->hits ?>"/>
        <input type="hidden" name="view"
               value="product"/>
        <input type="hidden" name="selectedTabPosition"
               value=""/>
    </fieldset>
</form>
