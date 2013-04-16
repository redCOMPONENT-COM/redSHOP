<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$editor = JFactory::getEditor();
JHTMLBehavior::modal();
$uri = JURI::getInstance();
$url = $uri->root();
JHTML::_('behavior.calendar');
jimport('joomla.html.pane');
$objhelper = new redhelper();
$producthelper = new producthelper();?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		if (form.category_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CATEGORY_ITEM_MUST_HAVE_A_NAME', true ); ?>");
		}
		else if ((form.category_template.value == "0" || form.category_template.value == "" ) && !<?php echo CATEGORY_TEMPLATE;?>) {
			alert("<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_TEMPLATE', true ); ?>");
		}
		else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
<?php
//Get JPaneTabs instance
$myTabs = JPane::getInstance('tabs', array('startOffset' => 0));
$output = '';

//Create Pane
$output .= $myTabs->startPane('pane');
//Create 1st Tab
echo $output .= $myTabs->startPanel(JText::_('COM_REDSHOP_CATEGORY_INFORMATION'), 'tab1');?>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><label
						for="name"><?php echo JText::_('COM_REDSHOP_CATEGORY_NAME'); ?>:</label></td>
				<td><input class="text_area" type="text" name="category_name" id="category_name" size="32"
				           maxlength="250"
				           value="<?php echo $this->detail->category_name; ?>"/><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_NAME'), JText::_('COM_REDSHOP_CATEGORY_NAME'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_CATEGORY_PARENT'); ?>:</td>
				<td><?php echo $this->lists['categories']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_PARENT'), JText::_('COM_REDSHOP_CATEGORY_PARENT'), 'tooltip.png', '', '', false); ?></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
				<td><?php echo $this->lists['published']; ?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label
						for="name"><?php echo JText::_('COM_REDSHOP_SHOW_PRODUCT_PER_PAGE'); ?>:</label></td>
				<td><input class="text_area" type="text" name="products_per_page" id="products_per_page" size="32"
				           maxlength="250"
				           value="<?php echo $this->detail->products_per_page; ?>"/><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRODUCT_PER_PAGE'), JText::_('COM_REDSHOP_CATEGORY_NAME'), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_CATEGORY_TEMPLATE'); ?>:
				</td>
				<td><?php echo $this->lists['category_template']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_TEMPLATE'), JText::_('COM_REDSHOP_CATEGORY_TEMPLATE'), 'tooltip.png', '', '', false); ?></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_CATEGORY_MORE_TEMPLATE'); ?>
					:
				</td>
				<td><?php echo $this->lists['category_more_template']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_MORE_TEMPLATE'), JText::_('COM_REDSHOP_CATEGORY_MORE_TEMPLATE'), 'tooltip.png', '', '', false); ?></td>
			</tr>
			<tr>
				<td valign="top" align="right"
				    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY'); ?>:
				</td>
				<td><?php echo $this->lists['compare_template_id']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY_LABEL'), JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_COMPARE_TEMPLATE_FOR_CATEGORY'), 'tooltip.png', '', '', false); ?></td>
			</tr>

			<?php if ($this->detail->category_id > 0)
			{
				$catid = $this->detail->category_id;

				$Itemid = $objhelper->getCategoryItemid($catid);

				if ($Itemid != '')
				{
					$cItemid = $Itemid;
				}
				else
				{
					$cItemid = '';
				}

				//echo "<pre>";print_r($Itemid);exit;
				?>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_FRONTEND_LINK'); ?>:
					</td>
					<td>
						<a href="<?php echo JURI::root() . 'index.php?option=com_redshop&view=category&layout=detail&Itemid=' . $cItemid . '&cid=' . $this->detail->category_id; ?>"
						   target="_black"><?php echo JURI::root() . 'index.php?option=com_redshop&view=category&layout=detail&Itemid=' . $cItemid . '&cid=' . $this->detail->category_id;?></a>
					</td>
				</tr>
			<?php }?>

		</table>
	</fieldset>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_SHORT_DESCRIPTION'); ?></legend>
			<table class="admintable">
				<tr>
					<td><?php echo $editor->display("category_short_description", $this->detail->category_short_description, '$widthPx', '$heightPx', '100', '20');    ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>
			<table class="admintable">
				<tr>
					<td><?php echo $editor->display("category_description", $this->detail->category_description, '$widthPx', '$heightPx', '100', '20');    ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
</div>
<?php
echo $myTabs->endPanel();
//Create 2nd Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_CATEGORY_IMAGES'), 'tab2');
?>
<div class="col50"></div>
<table class="adminform">
	<tr>
		<td valign="top" align="right" class="key" width="100"><?php echo JText::_('COM_REDSHOP_CATEGORY_IMAGE'); ?>:
		</td>
		<td>
			<input type="file" name="category_full_image" id="category_full_image" size="30">
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_IMAGE'), JText::_('COM_REDSHOP_CATEGORY_IMAGE'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><?php $ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs');
			$image_path = REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . $this->detail->category_full_image;    ?>
			<div class="button2-left">
				<div class="image"><a class="modal" title="Image" href="<?php echo $ilink; ?>"
				                      rel="{handler: 'iframe', size: {x: 490, y: 400}}">Image</a></div>
			</div>
			<div id="image_dis">
				<img src="<?php echo $image_path; ?>"
				     id="image_display" <?php if ($this->detail->category_full_image == "") echo "style='display:none;'" ?>
				     border="0" width="200"/>
				<input type="hidden" name="category_image" id="category_image"/>
			</div>
		</td>
	</tr>
	<?php
	if ($this->detail->category_full_image != "")
	{
		if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $this->detail->category_full_image))
		{
			?>
			<tr>
				<td>&nbsp;</td>
				<td>
					<?php    echo '<input type="checkbox" name="image_delete" ><label>';
					echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');
					echo '</label>';
					?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<a class="modal"
					   href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>category/<?php echo $this->detail->category_full_image ?>"
					   title="" rel="{handler: 'image', size: {}}"><img
							src="<?php echo $url ?>/components/com_redshop/helpers/thumb.php?filename=category/<?php echo $this->detail->category_full_image ?>&newxsize=<?php echo THUMB_WIDTH; ?>&newysize=<?php echo THUMB_HEIGHT; ?>"></a>
				</td>
			</tr>
		<?php
		}
	} ?>
	<tr>
		<td valign="top" align="right" class="key"
		    width="100"><?php echo JText::_('COM_REDSHOP_CATEGORY_BACK_IMAGE'); ?>:
		</td>
		<td>
			<input type="file" name="category_back_full_image" id="category_back_full_image" size="30">
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CATEGORY_BACK_IMAGE'), JText::_('COM_REDSHOP_CATEGORY_BACK_IMAGE'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php
			$image_back_path = REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . $this->detail->category_back_full_image;    ?>
			<div id="image_dis">
				<img src="<?php echo $image_back_path; ?>" id="image_display" style="display:block;" border="0"
				     width="200"/>
			</div>
		</td>
	</tr>

	<?php
	if ($this->detail->category_back_full_image != "")
	{
		if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $this->detail->category_back_full_image))
		{
			?>
			<tr>
				<td>&nbsp;</td>
				<td>
					<?php    echo '<input type="checkbox" name="image_back_delete" ><label>';
					echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');
					echo '</label>';
					?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>&nbsp;</td>
			</tr>
		<?php
		}
	} ?>
</table>
<?php
echo $myTabs->endPanel();
//Create 3rd Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_META_DATA_TAB'), 'tab3');
?>
<table>
	<tr>
		<td align="right" class="key"><?php echo JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL'); ?>:
		</td>
		<td><?php echo $this->lists['append_to_global_seo']; ?>
		</td>
		<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_APPEND_TO_GLOBAL_SEO_LBL'), JText::_('COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL'), 'tooltip.png', '', '', false); ?></td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_PAGE_TITLE'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="pagetitle" id="pagetitle" size="75"
			       value="<?php echo $this->detail->pagetitle; ?>"/>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_TITLE'), JText::_('COM_REDSHOP_PAGE_TITLE'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_PAGE_HEADING'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="pageheading" id="pageheading" size="75"
			       value="<?php echo $this->detail->pageheading; ?>"/>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_HEADING'), JText::_('COM_REDSHOP_PAGE_HEADING'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_SEF_URL'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="sef_url" id="sef_url" size="75"
			       value="<?php echo $this->detail->sef_url; ?>"/>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SEF_URL'), JText::_('COM_REDSHOP_SEF_URL'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_CANONICAL_URL_PRODUCT'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="canonical_url" id="canonical_url" size="75"
			       value="<?php echo $this->detail->canonical_url; ?>"/>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CANONICAL_URL_PRODUCT'), JText::_('COM_REDSHOP_CANONICAL_URL_PRODUCT'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_META_KEYWORDS'); ?>:
		</td>
		<td>
			<textarea class="text_area" type="text" name="metakey" id="metakey" rows="4"
			          cols="40"/><?php echo $this->detail->metakey; ?></textarea>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_KEYWORDS'), JText::_('COM_REDSHOP_META_KEYWORDS'), 'tooltip.png', '', '', false); ?>

		</td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_META_DESCRIPTION'); ?>:
		</td>
		<td>
			<textarea class="text_area" type="text" name="metadesc" id="metadesc" rows="4"
			          cols="40"/><?php echo $this->detail->metadesc; ?></textarea>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_DESCRIPTION'), JText::_('COM_REDSHOP_META_DESCRIPTION'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_META_LANG_SETTING'); ?>:
		</td>
		<td>
			<textarea class="text_area" type="text" name="metalanguage_setting" id="metalanguage_setting" rows="4"
			          cols="40"/><?php echo $this->detail->metalanguage_setting; ?></textarea>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_LANG_SETTING'), JText::_('COM_REDSHOP_META_LANG_SETTING'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_META_ROBOT_INFO'); ?>:
		</td>
		<td>
			<textarea class="text_area" type="text" name="metarobot_info" id="metarobot_info" rows="4"
			          cols="40"/><?php echo $this->detail->metarobot_info; ?></textarea>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_ROBOT_INFO'), JText::_('COM_REDSHOP_META_ROBOT_INFO'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
</table>
<?php
echo $myTabs->endPanel();
/*
if($this->lists['extra_field']!="")
{
	echo  $myTabs->startPanel(JText::_('COM_REDSHOP_EXTRA_FIELD'), 'tab4' );
?>
<div class="col50">
<?php
	echo $this->lists['extra_field'];
?>
</div>
<?php
}*/


echo  $myTabs->startPanel(JText::_('COM_REDSHOP_FIELDS'), 'tab4');

?>
<table class="admintable">
	<tr>
		<td colspan="2">
			<div id="dynamic_field"></div>
		</td>
	</tr>
</table>
<?php
echo $myTabs->endPanel();
//Create 6th Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_ACCESSORY_PRODUCT'), 'tab5');
?>
<div class="col50">
	<table class="admintable">
		<tr>
			<td VALIGN="TOP" class="key" align="center"><?php echo JText::_('COM_REDSHOP_PRODUCT_SOURCE'); ?> <br/>
				<br/>
				<input style="width: 200px" type="text" id="input" value=""/>
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2">
				<table id="accessory_table" class="adminlist" border="0">
					<thead>
					<tr>
						<th width="400"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
						<th width="75"><?php echo JText::_('COM_REDSHOP_PRODUCT_NORMAIL_PRICE'); ?></th>
						<th width="50"><?php echo JText::_('COM_REDSHOP_OPRAND'); ?></th>
						<th width="75"><?php echo JText::_('COM_REDSHOP_ADDED_VALUE'); ?></th>
						<th width="15%"><?php echo JText::_('COM_REDSHOP_ORDERING'); ?></th>
						<!-- <th width="15%" ><?php echo JText::_('COM_REDSHOP_DEFAULT_SELECTED' ); ?></th>-->
						<th width="50"><?php echo JText::_('COM_REDSHOP_DELETE'); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php        $accessory_product = $this->lists['categroy_accessory_product'];
					for ($f = 0; $f < count($accessory_product); $f++)
					{
						$accessory_main_price = 0;
						if ($accessory_product[$f]->product_id && $accessory_product[$f]->accessory_id)
						{
							$accessory_main_price = $producthelper->getAccessoryPrice($accessory_product[$f]->product_id, $accessory_product[$f]->newaccessory_price, $accessory_product[$f]->accessory_main_price, 1);
						}
						$checked = ($accessory_product[$f]->setdefault_selected) ? "checked" : "";    ?>
						<tr>
							<td><?php echo $accessory_product[$f]->product_name;?>
								<input type="hidden" value="<?php echo $accessory_product[$f]->child_product_id; ?>"
								       name="product_accessory[<?php echo $f; ?>][child_product_id]">
								<input type="hidden" value="<?php echo $accessory_product[$f]->accessory_id; ?>"
								       name="product_accessory[<?php echo $f; ?>][accessory_id]"></td>
							<td><?php echo $accessory_main_price[1];?></td>
							<td><input size="1" maxlength="1" class="text_area" type="text"
							           value="<?php echo $accessory_product[$f]->oprand; ?>"
							           onchange="javascript:oprand_check(this);"
							           name="product_accessory[<?php echo $f; ?>][oprand]"></td>
							<td><input size="5" class="text_area" type="text"
							           value="<?php echo $accessory_product[$f]->accessory_price; ?>"
							           name="product_accessory[<?php echo $f; ?>][accessory_price]"></td>
							<td><input type="text" name="product_accessory[<?php echo $f; ?>][ordering]" size="5"
							           value="<?php echo $accessory_product[$f]->ordering; ?>" class="text_area"
							           style="text-align: center"/></td>
							<!-- <td><input value="1" class="button" type="checkbox" name="product_accessory[<?php echo $f;?>][setdefault_selected]" <?php echo $checked;?>></td>-->
							<td><input value="X"
							           onclick="deleteRow_accessory(this,<?php echo $accessory_product[$f]->accessory_id; ?>,<?php echo $accessory_product[$f]->category_id; ?>,<?php echo $accessory_product[$f]->child_product_id ?>);"
							           class="button" type="button"></td>
						</tr>
					<?php }    ?>
					</tbody>
				</table>
				<input type="hidden" name="total_accessory" id="total_accessory" value="<?php echo $f; ?>"/></td>
		</tr>
	</table>
</div>
<?php

echo $myTabs->endPanel();
echo $myTabs->endPane(); ?>
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
			document.getElementById("image_display").style.display = "block";
			document.getElementById("category_image").value = main_path;
			document.getElementById("image_display").src = path_url + main_path;
		}
		else {
			document.getElementById("category_image").value = "";
			document.getElementById("image_display").src = "";
		}

	}

</script>

<?php
if ($this->detail->category_template != '0')
{
	?>
	<script language="javascript" type="text/javascript">

		select_dynamic_field(<?php echo $this->detail->category_template;?>, <?php echo $this->detail->category_id;?>, '2');
	</script>
<?php
}
?>


<script language="javascript" type="text/javascript">
	//------------------ Accessory Product ------------------------

	var options = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&product_id=0&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			document.getElementById('input').value = "";
			create_table_accessory(obj.value, obj.id, obj.price);
		}
	};

	var as_json = new bsn.AutoSuggest('input', options);

	//------------- End Of Accessory Product --------------------------
</script>
