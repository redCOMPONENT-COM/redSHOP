<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.html.html.tabs');

JHTMLBehavior::modal();
$url = JURI::getInstance()->root();

$container_id = $this->input->getInt('container_id', null);
$stockroom_id = $this->input->getInt('stockroom_id', null);
$now = JFactory::getDate();
$model = $this->getModel('product_detail');
$showbuttons = $this->input->getBool('showbuttons', false);

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
		var url = "index.php?tmpl=component&option=com_redproductfinder&controller=associations&task=savedependent&" + args;

		request.onreadystatechange = function () {
			if (request.readyState == 4) {
				alert(request.responseText);
			}
		};
		request.open("GET", url, true);
		request.send(null);
	}

	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	};

	submitbutton = function (pressbutton) {
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

<?php if ($showbuttons) : ?>
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
      enctype="multipart/form-data" onSubmit="return selectAll_related(this.elements['related_product[]'],this);">
<?php

// Tabs converted to JHtml Tabs instead of using deprecated JPane.
$optionsForTabs = array(
	'onActive' => 'function(title, description){
        description.setStyle("display", "block");
        title.addClass("open").removeClass("closed");
    }',
	'onBackground' => 'function(title, description){
        description.setStyle("display", "none");
        title.addClass("closed").removeClass("open");
    }',
	'startOffset' => 0,
	'useCookie' => true
);

// Start tabs.
echo JHtml::_('tabs.start', 'productTabs', $optionsForTabs);

// Tab1 - Product information tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_PRODUCT_INFORMATION'), 'productTab1');
?>
<fieldset class="adminform">
	<legend>
		<?php echo JText::_('COM_REDSHOP_PRODUCT_INFORMATION'); ?>
	</legend>
	<?php echo $this->loadTemplate('general_data'); ?>
</fieldset>
<?php if ($this->detail->product_type != 'product') : ?>
	<?php
	// Tab2 - Product information tab panel.
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_CHANGE_PRODUCT_TYPE_TAB'), 'productTab2');
	?>
	<fieldset class="adminform">
		<legend>
			<?php echo JText::_('COM_REDSHOP_CHANGE_PRODUCT_TYPE_TAB_DESC'); ?>
		</legend>
		<?php echo $this->loadTemplate('producttype'); ?>
	</fieldset>
<?php endif; ?>

<?php
// Tab3 - Custom fields tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_FIELDS'), 'productTab3');
?>
<?php if ($this->detail->product_template != 0) : ?>
	<fieldset class="adminform">
		<legend>
			<?php echo JText::_('COM_REDSHOP_FIELDS'); ?>
		</legend>
		<?php echo $this->loadTemplate('extrafield'); ?>
	</fieldset>
<?php endif; ?>

<?php
// Tab4 - Product images tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_PRODUCT_IMAGES'), 'productTab4');
?>
<fieldset class="adminform">
	<legend>
		<?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGES'); ?>
	</legend>
	<?php echo $this->loadTemplate('product_images'); ?>
</fieldset>

<?php
// Tab5 - Product attributes tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'), 'productTab5');
echo $this->loadTemplate('product_attribute');

// Tab6 - Product accessories tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_ACCESSORY_PRODUCT'), 'productTab6');
echo $this->loadTemplate('product_accessory');

// Tab7 - Related products tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_RELATED_PRODUCT'), 'productTab7');
echo $this->loadTemplate('related');
?>

<?php if ($this->CheckRedProductFinder > 0) : ?>
	<?php
		// Tab8 - redPRODUCTFINDER tab panel.
		echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_REDPRODUCTFINDER_ASSOCIATION'), 'productTab8');

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

	<table class="adminform">
		<tr>
			<td>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TAG_NAME_TIP'), JText::_('COM_REDSHOP_TAG_NAME'), 'tooltip.png', '', '', false); ?>
				<?php echo JText::_('COM_REDSHOP_TAG_NAME'); ?>
			</td>
			<td>
				<?php echo $this->lists['tags']; ?>
			</td>
		</tr>
	</table>
	<input type="hidden" name="association_id" value="<?php echo $accosiation_id; ?>"/>
	<input type="hidden" name="ordering" value="<?php echo $ordering; ?>"/>

<?php endif; ?>

<?php
// Tab9 - Meta data tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_META_DATA_TAB'), 'productTab9');
?>
<fieldset class="adminform">
	<legend>
		<?php echo JText::_('COM_REDSHOP_META_DATA_TAB'); ?>
	</legend>

	<table class="admintable">

		<tr>
			<td class="key">
				<label for="append_to_global_seo">
					<?php echo JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL'); ?>
				</label>
			</td>
			<td>
				<?php echo $this->lists['append_to_global_seo']; ?>
			</td>
			<td>
				<?php
					echo JHTML::tooltip(
									JText::_('COM_REDSHOP_TOOLTIP_APPEND_TO_GLOBAL_SEO_LBL'),
									JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL'),
									'tooltip.png',
									'',
									'',
									false
					);
				?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="pagetitle">
					<?php echo JText::_('COM_REDSHOP_PAGE_TITLE'); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="pagetitle" id="pagetitle" size="75" value="<?php echo htmlspecialchars($this->detail->pagetitle); ?>"/>
			</td>
			<td>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_TITLE'), JText::_('COM_REDSHOP_PAGE_TITLE'), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="pageheading">
					<?php echo JText::_('COM_REDSHOP_PAGE_HEADING'); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="pageheading" id="pageheading" size="75" value="<?php echo $this->detail->pageheading; ?>"/>
			</td>
			<td>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_HEADING'), JText::_('COM_REDSHOP_PAGE_HEADING'), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="sef_url">
					<?php echo JText::_('COM_REDSHOP_SEF_URL'); ?>
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="sef_url" id="sef_url" size="75" value="<?php echo $this->detail->sef_url; ?>"/>
			</td>
			<td>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SEF_URL'), JText::_('COM_REDSHOP_SEF_URL'), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="canonical_url">
					<?php echo JText::_('COM_REDSHOP_CANONICAL_URL_PRODUCT'); ?>
				</label>
			</td>
			<td>
				<input class="text_area"
					   type="text"
					   name="canonical_url"
					   id="canonical_url"
					   size="75"
					   value="<?php echo isset($this->detail->canonical_url) ? $this->detail->canonical_url : ""; ?>"
					/>
			</td>
			<td>
				<?php
					echo JHTML::tooltip(
									JText::_('COM_REDSHOP_TOOLTIP_CANONICAL_URL_PRODUCT'),
									JText::_('COM_REDSHOP_CANONICAL_URL_PRODUCT'),
									'tooltip.png',
									'',
									'',
									false
				);
				?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="cat_in_sefurl">
					<?php echo JText::_('COM_REDSHOP_SELECT_CATEGORY_TO_USEIN_SEF'); ?>
				</label>
			</td>
			<td>
				<?php echo $this->lists['cat_in_sefurl']; ?>
			</td>
			<td>
				<?php
					echo JHTML::tooltip(
									JText::_('COM_REDSHOP_TOOLTIP_SELECT_CATEGORY_TO_USEIN_SEF'),
									JText::_('COM_REDSHOP_SELECT_CATEGORY_TO_USEIN_SEF'),
									'tooltip.png',
									'',
									'',
									false
					);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<hr/>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="metakey">
					<?php echo JText::_('COM_REDSHOP_META_KEYWORDS'); ?>
				</label>
			</td>
			<td>
				<textarea class="text_area" name="metakey" id="metakey" rows="4" cols="40"><?php echo $this->detail->metakey; ?></textarea>
			</td>
			<td>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_KEYWORDS'), JText::_('COM_REDSHOP_META_KEYWORDS'), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="metadesc">
					<?php echo JText::_('COM_REDSHOP_META_DESCRIPTION'); ?>
				</label>
			</td>
			<td>
				<textarea class="text_area" name="metadesc" id="metadesc" rows="4" cols="40"><?php echo htmlspecialchars($this->detail->metadesc); ?></textarea>
			</td>
			<td>
				<?php
					echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_DESCRIPTION'), JText::_('COM_REDSHOP_META_DESCRIPTION'), 'tooltip.png', '', '', false);
				?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="metalanguage_setting">
					<?php echo JText::_('COM_REDSHOP_META_LANG_SETTING'); ?>
				</label>
			</td>
			<td>
				<textarea class="text_area"
						  name="metalanguage_setting"
						  id="metalanguage_setting"
						  rows="4"
						  cols="40"><?php echo $this->detail->metalanguage_setting; ?></textarea>
			</td>
			<td>
				<?php
					echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_LANG_SETTING'), JText::_('COM_REDSHOP_META_LANG_SETTING'), 'tooltip.png', '', '', false);
				?>
			</td>
		</tr>

		<tr>
			<td class="key">
				<label for="metarobot_info">
					<?php echo JText::_('COM_REDSHOP_META_ROBOT_INFO'); ?>
				</label>
			</td>
			<td>
				<textarea class="text_area" name="metarobot_info" id="metarobot_info" rows="4" cols="40"><?php echo $this->detail->metarobot_info; ?></textarea>
			</td>
			<td>
				<?php
					echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_ROBOT_INFO'), JText::_('COM_REDSHOP_META_ROBOT_INFO'), 'tooltip.png', '', '', false);
				?>
			</td>
		</tr>

	</table>

</fieldset>

<?php if (USE_STOCKROOM == 1) : ?>
	<?php
	// Tab10 - Stockroom tab panel.
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_STOCKROOM_TAB'), 'productTab10');
	?>
	<div class="col50">
		<?php echo $this->loadTemplate('productstockroom')?>
	</div>
<?php endif; ?>

<?php
// Tab11 - Discount calculator tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_DISCOUNT_CALCULATOR'), 'productTab11');
?>
<div class="col50">
	<?php echo $this->loadTemplate('calculator')?>
</div>

<?php
// Tab12 - Economic tab panel.
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_ECONOMIC_SETTINGS'), 'productTab12');
?>
<fieldset class="adminform">
	<legend>
		<?php echo JText::_('COM_REDSHOP_ECONOMIC_SETTINGS'); ?>
	</legend>

	<table class="admintable" border="0">

		<tr>
			<td>

				<table>

					<tr>
						<td class="key">
							<label for="accountgroup_id">
								<?php echo JText::_('COM_REDSHOP_ECONOMIC_ACCOUNTGROUP_LBL'); ?>
							</label>
						</td>
						<td>
							<?php echo $this->lists['accountgroup_id'];?>
						</td>
						<td>
							<?php
								echo JHTML::tooltip(
												JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_ACCOUNTGROUP_LBL'),
												JText::_('COM_REDSHOP_ECONOMIC_ACCOUNTGROUP_LBL'),
												'tooltip.png',
												'',
												'',
												false
								);
							?>
						</td>
					</tr>

					<tr>
						<td class="key">
							<label for="quantity_selectbox_value">
								<?php echo JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'); ?>
							</label>
						</td>
						<td>
							<input class="text_area"
								   type="text"
								   name="quantity_selectbox_value"
								   id="quantity_selectbox_value"
								   size="10"
								   value="<?php echo $this->lists['QUANTITY_SELECTBOX_VALUE']; ?>"
								/>
						</td>
						<td>
							<?php
								echo JHTML::tooltip(
												JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'),
												JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'),
												'tooltip.png',
												'',
												'',
												false
								);
							?>
						</td>
					</tr>

					<tr>
						<td colspan="2">
							<hr/>
						</td>
					</tr>

				</table>

			</td>
		</tr>

	</table>

</fieldset>

<?php
// End tabs.
echo JHtml::_('tabs.end');
?>

<div class="clr"></div>

<?php if ($stockroom_id && USE_CONTAINER == 1) : ?>
	<input type="hidden" name="stockroom_id" value="<?php echo $stockroom_id; ?>"/>
<?php endif; ?>

<?php if ($container_id) : ?>
	<input type="hidden" name="container_id" value="<?php echo $container_id; ?>"/>
<?php else : ?>
	<input type="hidden" name="container_id" value="" />
<?php endif; ?>

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

		var path_url = "<?php echo $url;?>";
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
	// Parent Product Search

	var options = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&product_id=<?php echo $this->detail->product_id;?>&parent=1&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			document.getElementById('product_parent_id').value = obj.id;
		}
	};

	var as_json = new bsn.AutoSuggest('parent', options);

	// End Of Parent Product Search


	// ------------------ Accessory Product ------------------------

	options = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&product_id=<?php echo $this->detail->product_id;?>&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			document.getElementById('input').value = "";
			create_table_accessory(obj.value, obj.id, obj.price);
		}
	};

	as_json = new bsn.AutoSuggest('input', options);

	//------------- End Of Accessory Product --------------------------
	//-------------- Related Product ----------------------------------

	var related = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&product_id=<?php echo $this->detail->product_id;?>&related=1&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			var selTo = document.adminForm.related_product;
			var chk_add = 1;
			for (var i = 0; i < selTo.options.length; i++) {
				if (selTo.options[i].value == obj.id) {
					chk_add = 0;
				}
			}
			if (chk_add == 1) {
				var newOption = new Option(obj.value, obj.id);
				selTo.options[selTo.options.length] = newOption;
			}
		}
	};

	as_json = new bsn.AutoSuggest('relat', related);

	//-------------- End Related Product --------------------------

</script>