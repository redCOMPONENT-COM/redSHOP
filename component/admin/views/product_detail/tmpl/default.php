<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

?>

<script type="text/javascript">

	function add_dependency(type_id, tag_id, product_id) {
		var request;
		request = getHTTPObject();
		var arry_sel = new Array();
		if (document.getElementById('sel_dep' + type_id + '_' + tag_id)) {
			var j = 0;
			var selVal = document.getElementById('sel_dep' + type_id + '_' + tag_id);
			for (var i = 0; i < selVal.options.length; i++)
				if (selVal.options[i].selected)
					arry_sel[j++] = selVal.options[i].value;
		}
		var dependent_tags = "";
		dependent_tags = arry_sel.join(",");
		if (document.getElementById('product_id'))
			product_id = document.getElementById('product_id').value;
		var args = "dependent_tags=" + dependent_tags + "&product_id=" + product_id + "&type_id=" + type_id + "&tag_id=" + tag_id;
		var url = "index.php?tmpl=component&option=com_redproductfinder&task=associations.savedependent&" + args;

		request.onreadystatechange = function () {
			if (request.readyState == 4) {
				alert(request.responseText);
			}
		};
		request.open("GET", url, true);
		request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		request.send(null);
	}

	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	};

	submitbutton = function (pressbutton) {

		// Find the position of selected tab
		var allTabsNames = document.querySelectorAll('.tabconfig a');
		var selectedTabName  = document.querySelectorAll('.tabconfig li.active a');

		for (var i=0; i < allTabsNames.length; i++) {
			if (selectedTabName[0].innerHTML === allTabsNames[i].innerHTML) {
				var selectedTabPosition =allTabsNames[i].getAttribute("aria-controls");
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

        if (!document.formvalidator.isValid(form))
        {
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
	}

	function hideDownloadLimit(val) {
		var downloadlimit = document.getElementById('download_limit');
		var downloaddays = document.getElementById('download_days');
		var downloadclock = document.getElementById('download_clock');

		if (val.value == 1) {

			downloadlimit.style.display = 'none';
			downloaddays.style.display = 'none';
			downloadclock.style.display = 'none';
		} else {

			downloadlimit.style.display = 'table-row';
			downloaddays.style.display = 'table-row';
			downloadclock.style.display = 'table-row';
		}

	}
</script>

<?php if ($this->input->getBool('showbuttons', false)) : ?>
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('save');"> <?php echo JText::_('COM_REDSHOP_SAVE'); ?> </button>
			<button type="button"
					onclick="window.parent.SqueezeBox.close();"> <?php echo JText::_('COM_REDSHOP_CANCEL'); ?> </button>
		</div>
		<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT'); ?></div>
	</fieldset>
<?php endif; ?>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" class="form-validate"
	  enctype="multipart/form-data">

	<?php
		echo RedshopLayoutHelper::render(
			'component.full.tab.main',
			array(
				'view'    => $this,
				'tabMenu' => $this->tabmenu->getData('tab')->items,
			)
		);

		// Echo plugin tabs.
		$this->dispatcher->trigger('onDisplayProductTabs', array($this->detail));
	?>

	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->product_id; ?>"/>
	<input type="hidden" name="product_id" id="product_id" value="<?php echo $this->detail->product_id; ?>"/>
	<input type="hidden" name="old_manufacturer_id" value="<?php echo $this->detail->manufacturer_id; ?>"/>
	<input type="hidden" name="old_image" id="old_image" value="<?php echo $this->detail->product_full_image; ?>">
	<input type="hidden" name="old_thumb_image" id="old_thumb_image" value="<?php echo $this->detail->product_thumb_image; ?>">
	<input type="hidden" name="product_back_full_image" id="product_back_full_image" value="<?php echo $this->detail->product_back_full_image; ?>">
	<input type="hidden" name="product_back_thumb_image" id="product_back_thumb_image" value="<?php echo $this->detail->product_back_thumb_image; ?>">
	<input type="hidden" name="product_preview_image" id="product_preview_image" value="<?php echo $this->detail->product_preview_image; ?>">
	<input type="hidden" name="product_preview_back_image" id="product_preview_back_image" value="<?php echo $this->detail->product_preview_back_image; ?>">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="section_id" value=""/>
	<input type="hidden" name="template_id" value=""/>
	<input type="hidden" name="visited" value="<?php echo $this->detail->visited ?>"/>
	<input type="hidden" name="view" value="product_detail"/>
	<input type="hidden" name="selectedTabPosition" value=""/>
</form>
<script type="text/javascript">

	function set_dynamic_field(tid, pid, sid) {
		var form = document.adminForm;
		form.template_id.value = tid;
		form.section_id.value = sid;
		form.task.value = "getDynamicFields";
		form.submit();
	}

	function changeProductDiv(product_type)
	{
		document.getElementById("div_file").style.display = "none";
		document.getElementById("div_subscription").style.display = "none";
		var opendiv = document.getElementById("div_" + product_type);
		opendiv.style.display = 'block';

		if (product_type == 'file')
		{
			document.getElementById("product_download1").checked = true;
		}
		else
		{
			document.getElementById("product_download1").checked = false;
		}
	}

	function showBox(div) {
		var opendiv = document.getElementById(div);

		if (opendiv.style.display == 'block') opendiv.style.display = 'none';
		else opendiv.style.display = 'block';
		return false;
	}


	function jimage_insert(main_path, fid, fsec) {

		var path_url = "<?php echo JURI::getInstance()->root();?>";
		var propimg;

		if (!fid && !fsec) {

			if (main_path) {
				var elImageDisplay = document.getElementById("image_display");

				// Make sure this el exists before apply
				if (elImageDisplay !== null)
				{
					elImageDisplay.style.display = "block";
					elImageDisplay.src = path_url + main_path;
				}
				else
				{
					// It's not exists than create and append it
					elImageDisplay = document.createElement('img');
					elImageDisplay.style.display = "block";
					elImageDisplay.src = path_url + main_path;
					jQuery('#product_image').parent().append(elImageDisplay);
				}
			}
			else {
				document.getElementById("product_image").value = "";
				document.getElementById("image_display").src = "";
			}
		} else {

			if (fsec == 'property') {
				if (main_path) {
					propimg = 'propertyImage' + fid;
					document.getElementById(propimg).style.display = "block";
					document.getElementById(propimg).width = "60";
					document.getElementById(propimg).heidth = "60";
					document.getElementById("propmainImage" + fid).value = main_path;
					document.getElementById(propimg).src = path_url + main_path;


				}
				else {
					document.getElementById("propmainImage" + fid).value = "";
					document.getElementById("propimg" + fid).src = "";
				}
			} else {
				if (main_path) {

					propimg = 'subpropertyImage' + fid;
					document.getElementById(propimg).style.display = "block";
					document.getElementById(propimg).width = "60";
					document.getElementById(propimg).heidth = "60";
					document.getElementById("subpropmainImage" + fid).value = main_path;
					document.getElementById(propimg).src = path_url + main_path;


				}
				else {
					document.getElementById("subpropmainImage" + fid).value = "";
					document.getElementById("propimg" + fid).src = "";
				}
			}
		}
	}
</script>
