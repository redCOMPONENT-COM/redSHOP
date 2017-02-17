<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

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
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		if (form.category_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CATEGORY_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		}
		else if ((form.category_template.value == "0" || form.category_template.value == "" ) && !<?php echo Redshop::getConfig()->get('CATEGORY_TEMPLATE');?>) {
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
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->category_id; ?>"/>
	<input type="hidden" name="old_image" id="old_image" value="<?php echo $this->detail->category_full_image ?>">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="category_detail"/>
</form>

<script type="text/javascript" language="javascript">

	/*  Media Bank */

	function jimage_insert(main_path) {
		var path_url = "<?php echo $url;?>";
		if (main_path) {
			if (document.getElementById("image_display") == null) {
				var img = new Image();
				img.id = "image_display";
				document.getElementById("image_dis").appendChild(img);
			}
			document.getElementById("category_image").value = main_path;
			document.getElementById("image_display").src = path_url + main_path;
		}
	}

</script>
