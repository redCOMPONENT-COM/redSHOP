<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTMLBehavior::modal();
JHTML::_('behavior.tooltip');
$editor        = JFactory::getEditor();
$uri           = JURI::getInstance();
$url           = $uri->root();
JHTML::_('behavior.calendar');
$producthelper = productHelper::getInstance();
JText::script('COM_REDSHOP_DELETE');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		console.log(pressbutton);
		var form = document.adminForm;
		if (pressbutton == 'category.cancel') {
			submitform(pressbutton);
			return;
		}
		if (jQuery('#jform_category_name').val() == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CATEGORY_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		}
		else if (parseInt(jQuery('#jform_products_per_page').val()) <= 0) {
			alert("<?php echo JText::_('COM_REDSHOP_PRODUCTS_PER_PAGE_MUST_BE_GREATER_THAN_ZERO', true ); ?>");
		}
		else if ((jQuery('#jform_category_template').val() == "0" || jQuery('#jform_category_template').val() == "" ) && !<?php echo Redshop::getConfig()->get('CATEGORY_TEMPLATE');?>) {
			alert("<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_TEMPLATE', true ); ?>");
		}
		else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($uri->toString()) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">

    <?php
		echo RedshopLayoutHelper::render(
			'component.full.tab.main',
			array(
				'view'    => $this,
				'tabMenu' => $this->tabmenu->getData('tab')->items,
			)
		);
	?>

	<div class="clr"></div>
	<?php echo $this->form->getInput('category_id'); ?>
	<input type="hidden" name="old_image" id="old_image" value="<?php echo $this->item->category_full_image ?>">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="category"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
