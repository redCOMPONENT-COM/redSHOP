<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTMLBehavior::modal();

$app = JFactory::getApplication();
$selectedTabPosition = $app->getUserState('com_redshop.product_detail.selectedTabPosition', 'general_data');

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

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<div class="row">
		<div class="col-sm-2">
			<div class="box">
				<div class="box-body no-padding">
					<ul class="tabconfig nav nav-pills nav-stacked" role="tablist">
						<li role="presentation" class="<?php echo ($selectedTabPosition == 'general_data') ? 'active' : '' ?>">
							<a href="#general_data" aria-controls="general_data" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_INFORMATION', true) ?>
							</a>
						</li>

						<?php if ($this->detail->product_type != 'product' && !empty($this->detail->product_type)) : ?>
						<li role="presentation" class="<?php echo ($selectedTabPosition == 'producttype') ? 'active' : '' ?>">
							<a href="#producttype" aria-controls="producttype" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CHANGE_PRODUCT_TYPE_TAB', true) ?>
							</a>
						</li>
						<?php endif; ?>

						<li role="presentation" class="<?php echo ($selectedTabPosition == 'extrafield') ? 'active' : '' ?>">
							<a href="#extrafield" aria-controls="extrafield" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_FIELDS', true) ?>
							</a>
						</li>

						<li role="presentation" class="<?php echo ($selectedTabPosition == 'product_images') ? 'active' : '' ?>">
							<a href="#product_images" aria-controls="product_images" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGES', true) ?>
							</a>
						</li>

						<li role="presentation" class="<?php echo ($selectedTabPosition == 'product_attribute') ? 'active' : '' ?>">
							<a href="#product_attribute" aria-controls="product_attribute" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES', true) ?>
							</a>
						</li>

						<li role="presentation" class="<?php echo ($selectedTabPosition == 'product_accessory') ? 'active' : '' ?>">
							<a href="#product_accessory" aria-controls="product_accessory" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT', true) ?>
							</a>
						</li>

						<li role="presentation" class="<?php echo ($selectedTabPosition == 'related') ? 'active' : '' ?>">
							<a href="#related" aria-controls="related" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT', true) ?>
							</a>
						</li>

						<?php if ($this->CheckRedProductFinder > 0) : ?>
						<li role="presentation" class="<?php echo ($selectedTabPosition == 'productfinder') ? 'active' : '' ?>">
							<a href="#productfinder" aria-controls="productfinder" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_REDPRODUCTFINDER_ASSOCIATION', true) ?>
							</a>
						</li>
						<?php endif; ?>

						<li role="presentation" class="<?php echo ($selectedTabPosition == 'product_meta_data') ? 'active' : '' ?>">
							<a href="#product_meta_data" aria-controls="product_meta_data" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_META_DATA_TAB', true) ?>
							</a>
						</li>

						<?php if (USE_STOCKROOM == 1) : ?>
						<li role="presentation" class="<?php echo ($selectedTabPosition == 'productstockroom') ? 'active' : '' ?>">
							<a href="#productstockroom" aria-controls="productstockroom" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_STOCKROOM_TAB', true) ?>
							</a>
						</li>
						<?php endif; ?>

						<li role="presentation" class="<?php echo ($selectedTabPosition == 'calculator') ? 'active' : '' ?>">
							<a href="#calculator" aria-controls="calculator" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_DISCOUNT_CALCULATOR', true) ?>
							</a>
						</li>

						<li role="presentation" class="<?php echo ($selectedTabPosition == 'economic_settings') ? 'active' : '' ?>">
							<a href="#economic_settings" aria-controls="economic_settings" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_ECONOMIC_SETTINGS', true) ?>
							</a>
						</li>

					</ul>
				</div>
			</div>
		</div>

		<div class="col-sm-10">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'general_data') ? 'active' : '' ?>" id="general_data">
					<?php echo $this->loadTemplate('general_data'); ?>
				</div>

				<?php if ($this->detail->product_type != 'product' && !empty($this->detail->product_type)) : ?>
				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'producttype') ? 'active' : '' ?>" id="producttype">
					<?php echo $this->loadTemplate('producttype'); ?>
				</div>
				<?php endif; ?>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'extrafield') ? 'active' : '' ?>" id="extrafield">
					<?php echo $this->loadTemplate('extrafield'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'product_images') ? 'active' : '' ?>" id="product_images">
					<?php echo $this->loadTemplate('product_images'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'product_attribute') ? 'active' : '' ?>" id="product_attribute">
					<?php echo $this->loadTemplate('product_attribute'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'product_accessory') ? 'active' : '' ?>" id="product_accessory">
					<?php echo $this->loadTemplate('product_accessory'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'related') ? 'active' : '' ?>" id="related">
					<?php echo $this->loadTemplate('related'); ?>
				</div>

				<?php if ($this->CheckRedProductFinder > 0) : ?>
				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'productfinder') ? 'active' : '' ?>" id="productfinder">
					<?php
					if(count($this->getassociation) == 0)
					{
						$accosiation_id = 0;
						$ordering = 1;
					}
					else
					{
						$accosiation_id = $this->getassociation->id;
						$ordering = $this->getassociation->ordering;
					}
					?>
					<table class="admintable table">
						<tr>
							<td>
								<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TAG_NAME_TIP'), JText::_('COM_REDSHOP_TAG_NAME'), 'tooltip.png', '', '', false); ?>
								<?php echo JText::_('COM_REDSHOP_TAG_NAME'); ?>
							</td>
							<td>
								<?php echo $this->lists['tags']; ?>
							</td>
						</tr>
					</table>
					<input type="hidden" name="association_id" value="<?php echo $accosiation_id; ?>"/>
					<input type="hidden" name="ordering" value="<?php echo $ordering; ?>"/>
				</div>
				<?php endif; ?>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'product_meta_data') ? 'active' : '' ?>" id="product_meta_data">
					<?php echo $this->loadTemplate('product_meta_data'); ?>
				</div>

				<?php if (USE_STOCKROOM == 1) : ?>
				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'productstockroom') ? 'active' : '' ?>" id="productstockroom">
					<?php echo $this->loadTemplate('productstockroom'); ?>
				</div>
				<?php endif; ?>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'calculator') ? 'active' : '' ?>" id="calculator">
					<?php echo $this->loadTemplate('calculator'); ?>
				</div>

				<div role="tabpanel" class="tab-pane <?php echo ($selectedTabPosition == 'economic_settings') ? 'active' : '' ?>" id="economic_settings">
					<?php echo $this->loadTemplate('economic_settings'); ?>
				</div>

			</div>
		</div>
	</div>

	<?php
	// Echo plugin tabs.
		$this->dispatcher->trigger('onDisplayProductTabs', array($this->detail));
	?>

	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->product_id; ?>"/>
	<input type="hidden" name="product_id" id="product_id" value="<?php echo $this->detail->product_id; ?>"/>
	<input type="hidden" name="old_manufacturer_id" value="<?php echo $this->detail->manufacturer_id; ?>"/>
	<input type="hidden" name="old_image" id="old_image" value="<?php echo $this->detail->product_full_image; ?>">
	<input type="hidden" name="old_thumb_image" id="old_thumb_image"
	       value="<?php echo $this->detail->product_thumb_image; ?>">
	<input type="hidden" name="product_back_full_image" id="product_back_full_image"
	       value="<?php echo $this->detail->product_back_full_image; ?>">
	<input type="hidden" name="product_back_thumb_image" id="product_back_thumb_image"
	       value="<?php echo $this->detail->product_back_thumb_image; ?>">
	<input type="hidden" name="product_preview_image" id="product_preview_image"
	       value="<?php echo $this->detail->product_preview_image; ?>">
	<input type="hidden" name="product_preview_back_image" id="product_preview_back_image"
	       value="<?php echo $this->detail->product_preview_back_image; ?>">
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
				document.getElementById("image_display").style.display = "block";
				document.getElementById("product_image").value = main_path;
				document.getElementById("image_display").src = path_url + main_path;
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
