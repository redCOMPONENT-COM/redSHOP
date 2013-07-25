<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
$producthelper = new producthelper;
JHTML::_('behavior.tooltip');
$editor = JFactory::getEditor();
JHTMLBehavior::modal();
$uri = JURI::getInstance();
$url = $uri->root();
$option = JRequest::getVar('option');
jimport('joomla.html.pane');

$container_id = JRequest::getVar('container_id', '', 'request', 'string');
$stockroom_id = JRequest::getVar('stockroom_id', '', 'request', 'string');
$now = JFactory::getDate();
$model = $this->getModel('product_detail');

?>
<script language="javascript" type="text/javascript">
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
		args = "dependent_tags=" + dependent_tags + "&product_id=" + product_id + "&type_id=" + type_id + "&tag_id=" + tag_id;
		var url = "index.php?tmpl=component&option=com_redproductfinder&controller=associations&task=savedependent&" + args;

		request.onreadystatechange = function () {
			if (request.readyState == 4) {
				alert(request.responseText);
			}
		}
		request.open("GET", url, true);
		request.send(null);
	}
	/*var dom = {};
	 dom.query = jQuery.noConflict();
	 jQuery.noConflict(true);*/

	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

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
			alert("<?php echo JText::_('COM_REDSHOP_PRODUCT_ITEM_MUST_HAVE_A_NAME', true ); ?>");
			return;
		} else if (form.product_number.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_PRODUCT_ITEM_MUST_HAVE_A_NUMBER', true ); ?>");
			return;
		} else if (form.product_category.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CATEGORY_MUST_SELECTED', true ); ?>");
			return;
		} else if (form.product_template.value == "0") {
			alert("<?php echo JText::_('COM_REDSHOP_TEMPLATE_MUST_SELECTED', true ); ?>");
			return;
		} else if (form.copy_attribute.length) {
			for (var i = 0; i < form.copy_attribute.length; i++) {
				if (form.copy_attribute[i].checked) {
					if (form.copy_attribute[i].value == "1" && form.attribute_set_id.value == '') {
						alert("<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SET_MUST_BE_SELECTED', true ); ?>");
						return;
					}
				}
			}
		}
		submitform(pressbutton);
	}

	function oprand_check(s) {
		var oprand = s.value;
		if (oprand != '+' && oprand != '-' && oprand != '=' && oprand != '*' && oprand != "/") {
			alert("<?php echo JText::_('COM_REDSHOP_WRONG_OPRAND', true ); ?>");

			s.value = "+";
		}
	}

	function hideDownloadLimit(val) {

		var downloaddiv = document.getElementById('download');
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
<?php
$showbuttons = JRequest::getCmd('showbuttons');
if ($showbuttons == 1)
{
	?>

	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('save');"> <?php echo JText::_('COM_REDSHOP_SAVE'); ?> </button>
			<button type="button"
			        onclick="window.parent.SqueezeBox.close();"> <?php echo JText::_('COM_REDSHOP_CANCEL'); ?> </button>
		</div>
		<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT'); ?></div>
	</fieldset>
<?php } ?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data" onSubmit="return selectAll_related(this.elements['related_product[]'],this);">
<?php
//Get JPaneTabs instance
$myTabs = JPane::getInstance('tabs', array('startOffset' => 0));
$output = '';

//Create Pane
$output .= $myTabs->startPane('pane');
//Create 1st Tab
echo $output .= $myTabs->startPanel(JText::_('COM_REDSHOP_PRODUCT_INFORMATION'), 'tab1');?>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_PRODUCT_INFORMATION'); ?></legend>
		<table class="admintable" border="0" width="100%">
			<tr valign="top">
				<td width="50%">
					<table>
						<tr>
							<td width="100" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?>:
							</td>
							<td><input class="text_area" type="text" name="product_name" id="product_name" size="32"
							           maxlength="250"
							           value="<?php echo htmlspecialchars($this->detail->product_name); ?>"/>
							</td>
							<td> <?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NAME'), JText::_('COM_REDSHOP_PRODUCT_NAME'), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td width="100" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_NUMBER'); ?>:
							</td>
							<td><input class="text_area" type="text" name="product_number" id="product_number" size="32"
							           maxlength="250" value="<?php echo $this->detail->product_number; ?>"/>
							</td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NUMBER'), JText::_('COM_REDSHOP_PRODUCT_NUMBER'), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'); ?>:
							</td>
							<td><?php echo $this->lists['product_template']; ?>  </td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_TEMPLATE'), JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
								:
							</td>
							<td><?php echo $this->lists['published'];?> </td>
						</tr>
						<tr>
							<td colspan="2">
								<hr/>
							</td>
						</tr>
						<tr>
							<td width="100" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PARENT_PRODUCT'); ?>:
							</td>
							<td><?php $list = $producthelper->getProductByID($this->detail->product_parent_id);
								$productname = "";
								if (count($list) > 0)
								{
									$productname = $list->product_name;
								}?>
								<input class="text_area" type="text" name="parent" id="parent" size="32" maxlength="250"
								       value="<?php echo $productname; ?>"/>
								<input class="text_area" type="hidden" name="product_parent_id" id="product_parent_id"
								       size="32" maxlength="250"
								       value="<?php echo $this->detail->product_parent_id; ?>"/>
							</td>
							<td> <?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PARENT_PRODUCT'), JText::_('COM_REDSHOP_PARENT_PRODUCT'), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_CATEGORY'); ?>:
							</td>
							<td><?php echo $this->lists['categories']; ?><?php //echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_CATEGORY' ), JText::_('COM_REDSHOP_PRODUCT_CATEGORY' ), 'tooltip.png', '', '', false); ?> </td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_CATEGORY' ), JText::_('COM_REDSHOP_PRODUCT_CATEGORY' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<hr/>
							</td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_MANUFACTURER' ); ?>:
							</td>
							<td><?php echo $this->lists['manufacturers']; ?>  </td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MANUFACTURER' ), JText::_('COM_REDSHOP_PRODUCT_MANUFACTURER' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SUPPLIER' ); ?>
								:
							</td>
							<td><?php echo $this->lists['supplier']; ?></td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SUPPLIER' ), JText::_('COM_REDSHOP_SUPPLIER' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<hr/>
							</td>
						</tr>
						<tr>
							<td style="font-weight:bold;"><?php echo JText::_( 'COM_REDSHOP_SHORT_DESCRIPTION' ); ?></td>
						</tr>
						<tr>
							<td valign="top"
							    colspan="2"><?php echo $editor->display("product_s_desc",$this->detail->product_s_desc,'$widthPx','$heightPx','100','20'); ?></td>
						</tr>
						<tr>
							<td style="font-weight:bold;"><?php echo JText::_( 'COM_REDSHOP_FULL_DESCRIPTION' ); ?></td>
						</tr>
						<tr>
							<td valign="top"
							    colspan="2"><?php echo $editor->display("product_desc",$this->detail->product_desc,'$widthPx','$heightPx','100','20'); ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<hr/>
							</td>
						</tr>
						<?php if ($this->detail->product_id > 0){

						$ItemData = $producthelper->getMenuInformation(0,0,'','product&pid='.$this->detail->product_id);
						$catidmain= $this->detail->first_selected_category_id;
						if(count($ItemData)>0){
						$pItemid = $ItemData->id;
						}else{
						$objhelper = new redhelper();
						$pItemid = $objhelper->getItemid($this->detail->product_id,$catidmain);

						}
						$link = JURI::root().'index.php?option='.$option.'&view=product&pid='.$this->detail->product_id.'&cid='.$catidmain.'&Itemid='.$pItemid;
						?>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_FRONTEND_LINK' ); ?>:
							</td>
							<td><a href="<?php echo $link; ?>" target="_black"><?php echo $link;?></a></td>
						</tr>
						<?php }?>

					</table>
				</td>
				<td width="50%" valign="top">
					<table>
						<tr>
							<td width="100" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE' ); ?>:
							</td>
							<td><input class="text_area" type="text" name="product_price" id="product_price" size="10"
							           maxlength="10" value="<?php echo $this->detail->product_price; ?>"/>
							</td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PRICE' ), JText::_('COM_REDSHOP_PRODUCT_PRICE' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td width="100" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_TAX_GROUP' );//echo JText::_('COM_REDSHOP_PRODUCT_TAX' ); ?>
								:
							</td>
							<td><?php echo $this->lists['product_tax_group_id'];//echo $this->lists['product_tax']; ?>  </td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_TAX' ), JText::_('COM_REDSHOP_PRODUCT_TAX_GROUP' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_MINIMUM_PER_PRODUCT_TOTAL_LBL' ); ?></td>
							<td><input class="text_area" type="text" name="minimum_per_product_total" id="minimum_per_product_total" size="10" maxlength="10" value="<?php echo $this->detail->minimum_per_product_total;?>" /></td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_PER_PRODUCT_TOTAL' ), JText::_('COM_REDSHOP_MINIMUM_PER_PRODUCT_TOTAL_LBL' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<hr/>
							</td>
						</tr>
						<tr>
							<td align="right" class="key"><?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE' ); ?>:
							</td>
							<td><input class="text_area" type="text" name="discount_price" id="discount_price" size="10"
							           maxlength="10" value="<?php echo $this->detail->discount_price; ?>"/>
							</td>
							<td>  <?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PRICE' ), JText::_('COM_REDSHOP_DISCOUNT_PRICE' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td align="right" class="key"><?php echo JText::_('COM_REDSHOP_DISCOUNT_START_DATE' ); ?>:
							</td>
							<td><?php    $sdate = "";
								if($this->detail->discount_stratdate)
								$sdate = date("d-m-Y",$this->detail->discount_stratdate);
								echo JHTML::_('calendar',$sdate , 'discount_stratdate', 'discount_stratdate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'15',  'maxlength'=>'19')); ?>
							</td>
						</tr>
						<tr>
							<td align="right" class="key"><?php echo JText::_('COM_REDSHOP_DISCOUNT_END_DATE' ); ?>:
							</td>
							<td><?php $edate = "";
								if($this->detail->discount_enddate)
								$edate = date("d-m-Y",$this->detail->discount_enddate);
								echo JHTML::_('calendar',$edate , 'discount_enddate', 'discount_enddate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'15',  'maxlength'=>'19')); ?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<hr/>
							</td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_ON_SALE' ); ?>:
							</td>
							<td><?php echo $this->lists['product_on_sale']; ?></td>
							<td><?php  echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ON_SALE' ), JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ON_SALE_LBL' ), 'tooltip.png', '', '', false);?> </td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_SPECIAL' ); ?>:
							</td>
							<td><?php echo $this->lists['product_special']; ?></td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_SPECIAL' ), JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_SPECIAL_LBL' ), 'tooltip.png', '', '', false);?> </td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_EXPIRED' ); ?>:
							</td>
							<td><?php echo $this->lists['expired']; ?> </td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRED' ), JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRED_LBL' ), 'tooltip.png', '', '', false);?></td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_NOT_FOR_SALE' ); ?>:
							</td>
							<td><?php echo $this->lists['not_for_sale'];?> </td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NOT_FOR_SALE' ), JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NOT_FOR_SALE_LBL' ), 'tooltip.png', '', '', false);?></td>
						</tr>

						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_PREORDER' ); ?>:
							</td>
							<td><?php echo $this->lists['preorder']; ?> <?php //echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREORDER' ), JText::_('COM_REDSHOP_PRODUCT_PREORDER' ), 'tooltip.png', '', '', false); ?> </td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREORDER' ), JText::_('COM_REDSHOP_PRODUCT_PREORDER' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<hr/>
							</td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_PRODUCT_QUANTITY_LBL' ); ?>:
							</td>
							<td><input class="text_area" type="text" name="min_order_product_quantity"
							           id="min_order_product_quantity" size="10" maxlength="10"
							           value="<?php echo $this->detail->min_order_product_quantity; ?>"/>
							</td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_ORDER_PRODUCT_QUANTITY' ), JText::_('COM_REDSHOP_MINIMUM_ORDER_PRODUCT_QUANTITY_LBL' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<tr>
							<td valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_MAXIMUM_ORDER_PRODUCT_QUANTITY_LBL' ); ?>:
							</td>
							<td><input class="text_area" type="text" name="max_order_product_quantity"
							           id="max_order_product_quantity" size="10" maxlength="10"
							           value="<?php echo @$this->detail->max_order_product_quantity; ?>"/>
							</td>
							<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MAXIMUM_ORDER_PRODUCT_QUANTITY' ), JText::_('COM_REDSHOP_TOOLTIP_MAXIMUM_ORDER_PRODUCT_QUANTITY' ), 'tooltip.png', '', '', false); ?></td>
						</tr>
						<?php if(ALLOW_PRE_ORDER){?>
						<tr>
							<td style="color: red;" valign="top" align="right"
							    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL' ); ?>:
							</td>
							<td><?php $availability_date = "";
								if($this->detail->product_availability_date)
								$availability_date = date("d-m-Y",$this->detail->product_availability_date);
								echo JHTML::_('calendar',$availability_date , 'product_availability_date', 'product_availability_date',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'15',  'maxlength'=>'19')); ?>
                <?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_AVAILABILITY_DATE' ), JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL' ), 'tooltip.png', '', '', false); ?> </td>
						</tr>
						<?php }?>
					</table>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<?php
echo $myTabs->endPanel();

echo $myTabs->startPanel(JText::_('COM_REDSHOP_PRODUCT_DATA'), 'tab2' );?>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_REDSHOP_PRODUCT_DATA' ); ?></legend>
	<table class="admintable" border="0" width="100%">
		<tr valign="top">
			<td width="50%">
				<table>

					<tr>
						<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_VOLUME' ); ?>
							:
						</td>
						<td><input class="text_area" type="text" name="product_volume" id="product_volume" size="10"
						           maxlength="10"
						           value="<?php echo $producthelper->redunitDecimal($this->detail->product_volume); ?>"/>
							<?php echo DEFAULT_VOLUME_UNIT; ?>3
						</td>
						<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_VOLUME' ), JText::_('COM_REDSHOP_PRODUCT_VOLUME' ), 'tooltip.png', '', '', false); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_LENGTH' ); ?>
							:
						</td>
						<td><input class="text_area" type="text" name="product_length" id="product_length" size="10"
						           maxlength="10"
						           value="<?php echo $producthelper->redunitDecimal($this->detail->product_length); ?>"/>
							<?php echo DEFAULT_VOLUME_UNIT; ?>  </td>
						<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_LENGTH' ), JText::_('COM_REDSHOP_PRODUCT_LENGTH' ), 'tooltip.png', '', '', false); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_WIDTH' ); ?>:
						</td>
						<td><input class="text_area" type="text" name="product_width" id="product_width" size="10"
						           maxlength="10"
						           value="<?php echo $producthelper->redunitDecimal($this->detail->product_width); ?>"/>
							<?php echo DEFAULT_VOLUME_UNIT; ?></td>
						<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_WIDTH' ), JText::_('COM_REDSHOP_PRODUCT_WIDTH' ), 'tooltip.png', '', '', false); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_HEIGHT' ); ?>
							:
						</td>
						<td><input class="text_area" type="text" name="product_height" id="product_height" size="10"
						           maxlength="10"
						           value="<?php echo $producthelper->redunitDecimal($this->detail->product_height); ?>"/>
							<?php echo DEFAULT_VOLUME_UNIT; ?></td>
						<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HEIGHT' ), JText::_('COM_REDSHOP_PRODUCT_HEIGHT' ), 'tooltip.png', '', '', false); ?></td>
					</tr>
					<tr>
						<td width="100" align="right"
						    class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_DIAMETER' ); ?>:
						</td>
						<td><input class="text_area" type="text" name="product_diameter" id="product_diameter" size="10"
						           maxlength="10"
						           value="<?php echo $producthelper->redunitDecimal($this->detail->product_diameter); ?>"/>
							<?php echo DEFAULT_VOLUME_UNIT; ?> <?php //echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DIAMETER' ), JText::_('COM_REDSHOP_PRODUCT_DIAMETER' ), 'tooltip.png', '', '', false); ?>
						</td>
						<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DIAMETER' ), JText::_('COM_REDSHOP_PRODUCT_DIAMETER' ), 'tooltip.png', '', '', false); ?></td>
					</tr>

					<tr>
						<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_WEIGHT_LBL' ); ?>:
						</td>
						<td><input class="text_area" type="text" name="weight" id="weight" size="10" maxlength="10"
						           value="<?php echo $producthelper->redunitDecimal($this->detail->weight); ?>"/>
							<?php echo DEFAULT_WEIGHT_UNIT; ?></td>
						<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_WEIGHT' ), JText::_('COM_REDSHOP_WEIGHT_LBL' ), 'tooltip.png', '', '', false); ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</fieldset>
<?php echo $myTabs->endPanel();
//Create fields Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_FIELDS'), 'tab3' );

if($this->detail->product_template!=0) ?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_REDSHOP_FIELDS' ); ?></legend>
	<?php    echo $this->loadTemplate('extrafield'); ?>
</fieldset>
<?php
echo  $myTabs->endPanel();
//Create 2nd Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_PRODUCT_IMAGES'), 'tab4' );
?>
<fieldset class="adminform">
<legend><?php echo JText::_( 'COM_REDSHOP_PRODUCT_IMAGES' ); ?></legend>
<table class="admintable">
<tr>
	<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGE' ); ?> :</td>
	<td valign="top"><input type="file" name="product_full_image" id="product_full_image" size="25">
		<?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_IMAGE' ), JText::_('COM_REDSHOP_PRODUCT_IMAGE' ), 'tooltip.png', '', '', false); ?>
	</td>

</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td valign="top"><?php $ilink = JRoute::_( 'index.php?tmpl=component&option=com_redshop&view=media&layout=thumbs' );    ?>
		<div class="button2-left">
			<div class="image"><a class="modal" title="Image" href="<?php echo $ilink; ?>"
			                      rel="{handler: 'iframe', size: {x: 900, y: 500}}">Image</a></div>
		</div>
		<?php

		if($this->detail->product_id > 0){
		$ilink    = JRoute::_( 'index.php?tmpl=component&option=com_redshop&view=media&section_id='.$this->detail->product_id.'&showbuttons=1&media_section=product' );
		} else {
		$ilink    = JRoute::_( 'index.php?tmpl=component&option=com_redshop&view=media&section_id='.$this->next_product.'&showbuttons=1&media_section=product' );
		}
		$image_path = 'product/'.trim($this->detail->product_full_image);    ?>
		<div class="button2-left">
			<div class="image"><a class="modal" title="Image" href="<?php echo $ilink; ?>"
			                      rel="{handler: 'iframe', size: {x: 950, y: 500}}"><?php echo JText::_('COM_REDSHOP_ADD_ADDITIONAL_IMAGES');?></a>
			</div>
		</div>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left">
		<?php
		$style_img = 'style="display: none;"';
		if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH.$image_path) && trim($this->detail->product_full_image)!="")
		{
		$style_img = 'style="display: block;"';
		}
		?>
		<div id="image_dis">
			<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH.$image_path; ?>"
			     id="image_display" <?php echo $style_img;?>  border="0" width="200"/>
			<input type="hidden" name="product_image" id="product_image"/>
		</div>

	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left"><?php
		if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH.$image_path)){    ?>
		<label><?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');?>
		</label><input type="checkbox" name="image_delete">
		<?php    }    ?>
	</td>
</tr>
<tr>
	<td colspan="2">
		<hr/>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_THUMB_IMAGE' ); ?> :</td>
	<td valign="top"><input type="file" name="product_thumb_image" id="product_thumb_image" size="25">
		<?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_THUMB_IMAGE' ), JText::_('COM_REDSHOP_PRODUCT_THUMB_IMAGE' ), 'tooltip.png', '', '', false); ?>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left">
		<div id="dynamic_field1"></div>
		<?php
		$image_path = 'product/'.trim($this->detail->product_thumb_image);

		if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH.$image_path ) && trim($this->detail->product_thumb_image)!=""){
		?>
		<div id="image_dis">
			<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH.$image_path; ?>" id="thumb_image_display"/>
		</div>
		<?php    }    ?>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left"><?php
		if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH.$image_path)){    ?>
		<label><?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_THUMB_IMAGE');?></label><input type="checkbox"
		                                                                                      name="thumb_image_delete">
		<?php    }    ?>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_BACK_IMAGE' ); ?> :</td>
	<td valign="top"><input type="file" name="product_back_full_image" id="product_back_full_image" size="25">
		<?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_BACK_IMAGE' ), JText::_('COM_REDSHOP_PRODUCT_BACK_IMAGE' ), 'tooltip.png', '', '', false); ?>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left">
		<?php
		$back_image_path = 'product/'.trim($this->detail->product_back_full_image);


		if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH.$back_image_path) && trim($this->detail->product_back_full_image)!=""){

		?>
		<div id="image_dis">
			<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH.$back_image_path; ?>" id="back_image_display" border="0"
			     width="200"/>

		</div>
		<?php    }    ?>
	</td>
</tr>
<?php
if(is_file(REDSHOP_FRONT_IMAGES_RELPATH.$back_image_path)){    ?>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left">
		<input type="checkbox"
		       name="back_image_delete"><label><?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');?>
		</label>

	</td>
</tr>
<?php    }    ?>
<tr>
	<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_BACK_THUMB_IMAGE' ); ?> :</td>
	<td valign="top"><input type="file" name="product_back_thumb_image" id="product_back_thumb_image" size="25">
		<?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_BACK_THUMB_IMAGE' ), JText::_('COM_REDSHOP_PRODUCT_BACK_THUMB_IMAGE' ), 'tooltip.png', '', '', false); ?>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left"><?php
		$back_thumb_image_path = 'product/'.trim($this->detail->product_back_thumb_image);

		if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH.$image_path ) && trim($this->detail->product_back_thumb_image)!=""){


		?>
		<div id="image_dis">
			<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH.$back_thumb_image_path; ?>"
			     id="thumb_back_image_display"/>
		</div>
		<?php    }    ?>
	</td>
</tr>
<?php
if(is_file(REDSHOP_FRONT_IMAGES_RELPATH.$back_thumb_image_path)){    ?>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left">
		<input type="checkbox"
		       name="back_thumb_image_delete"><label><?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_THUMB_IMAGE');?>
		</label>
	</td>
</tr>
<?php    }    ?>

<tr>
	<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE' ); ?> :</td>
	<td valign="top"><input type="file" name="product_preview_image" id="product_preview_image" size="25">
		<?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_IMAGE' ), JText::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE' ), 'tooltip.png', '', '', false); ?>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left"><?php
		$product_preview_image = 'product/'.trim($this->detail->product_preview_image);

		if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH.$product_preview_image ) && trim($this->detail->product_preview_image)!=""){


		?>
		<div id="image_dis">
			<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH.$product_preview_image; ?>" id="preview_image_display"/>
		</div>
		<?php    }    ?>
	</td>
</tr>
<?php
if(is_file(REDSHOP_FRONT_IMAGES_RELPATH.$product_preview_image)){    ?>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left">
		<input type="checkbox"
		       name="preview_image_delete"><label><?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');?>
		</label>
	</td>
</tr>
<?php    }    ?>
<tr>
	<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_PREVIEW_BACK_IMAGE' ); ?> :</td>
	<td valign="top"><input type="file" name="product_preview_back_image" id="product_preview_back_image" size="25">
		<?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_BACK_IMAGE' ), JText::_('COM_REDSHOP_PRODUCT_PREVIEW_BACK_IMAGE' ), 'tooltip.png', '', '', false); ?>
	</td>
</tr>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left"><?php
		$product_preview_back_image = 'product/'.trim($this->detail->product_preview_back_image);

		if(file_exists(REDSHOP_FRONT_IMAGES_RELPATH.$product_preview_back_image )  && trim($this->detail->product_preview_back_image)!=""){


		?>
		<div id="image_dis">
			<img src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH.$product_preview_back_image; ?>"
			     id="preview_back_image_display"/>
		</div>
		<?php    }    ?>
	</td>
</tr>
<?php
if(is_file(REDSHOP_FRONT_IMAGES_RELPATH.$product_preview_back_image)){    ?>
<tr>
	<td valign="top" align="right" class="key">&nbsp;</td>
	<td align="left">
		<input type="checkbox"
		       name="preview_back_image_delete"><label><?php echo JText::_('COM_REDSHOP_DELETE_CURRENT_IMAGE');?>
		</label>
	</td>
</tr>
<?php    }    ?>
</table>
</fieldset>
<?php
echo  $myTabs->endPanel();
//Create 4th Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'), 'tab5' );
echo $this->loadTemplate('product_attribute');
echo $myTabs->endPanel();
//Create 6th Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_ACCESSORY_PRODUCT'), 'tab6' );
echo $this->loadTemplate('product_accessory');
echo $myTabs->endPanel();
//Create 6th Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_RELATED_PRODUCT'), 'tab6' );
?>
<div class="col50"><?php echo $this->loadTemplate('related');?></div>
<?php
if($this->CheckRedProductFinder > 0){
echo $myTabs->endPanel();
//Create 7th Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_REDPRODUCTFINDER_ASSOCIATION'), 'tab7' );

if(count($this->getassociation) == 0){

$accosiation_id = 0;
$ordering = 1;
}else{

$accosiation_id = $this->getassociation->id;
$ordering = $this->getassociation->ordering;
}
?>
<div class="col50">
	<table class="adminform">
		<tr>
			<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TAG_NAME_TIP'), JText::_('COM_REDSHOP_TAG_NAME'), 'tooltip.png', '', '', false); ?> <?php echo JText::_('COM_REDSHOP_TAG_NAME'); ?> </td>
			<td><?php echo $this->lists['tags']; ?> </td>
		</tr>
	</table>
	<input type="hidden" name="association_id" value="<?php echo $accosiation_id; ?>"/>
	<input type="hidden" name="ordering" value="<?php echo $ordering; ?>"/>
</div>
<?php
}

echo $myTabs->endPanel();

//Create 3rd Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_META_DATA_TAB'), 'tab5' );
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_REDSHOP_META_DATA_TAB' ); ?></legend>
	<table class="admintable">
		<tr>
			<td align="right" class="key"><?php echo JText::_( 'COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL' ); ?>:
			</td>
			<td><?php echo $this->lists['append_to_global_seo']; ?>
			</td>
			<td><?php echo JHTML::tooltip( JText::_( 'COM_REDSHOP_TOOLTIP_APPEND_TO_GLOBAL_SEO_LBL' ), JText::_( 'COM_REDSHOP_APPEND_TO_GLOBAL_SEO_LBL' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
		<tr>
			<td align="right" class="key"><?php echo JText::_('COM_REDSHOP_PAGE_TITLE' ); ?>:</td>
			<td><input class="text_area" type="text" name="pagetitle" id="pagetitle" size="75"
			           value="<?php echo htmlspecialchars($this->detail->pagetitle); ?>"/>
			</td>
			<td><?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_PAGE_TITLE' ), JText::_('COM_REDSHOP_PAGE_TITLE' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
		<tr>
			<td align="right" class="key"><?php echo JText::_('COM_REDSHOP_PAGE_HEADING' ); ?>:</td>
			<td><input class="text_area" type="text" name="pageheading" id="pageheading" size="75"
			           value="<?php echo $this->detail->pageheading; ?>"/>
			</td>
			<td><?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_PAGE_HEADING' ), JText::_('COM_REDSHOP_PAGE_HEADING' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
		<tr>
			<td align="right" class="key"><?php echo JText::_('COM_REDSHOP_SEF_URL' ); ?>:</td>
			<td><input class="text_area" type="text" name="sef_url" id="sef_url" size="75"
			           value="<?php echo $this->detail->sef_url; ?>"/>
			</td>
			<td><?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_SEF_URL' ), JText::_('COM_REDSHOP_SEF_URL' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
		<tr>
			<td align="right" class="key"><?php echo JText::_( 'COM_REDSHOP_CANONICAL_URL_PRODUCT' ); ?>:</td>
			<td><input class="text_area" type="text" name="canonical_url" id="canonical_url" size="75"
			           value="<?php echo isset($this->detail->canonical_url) ? $this->detail->canonical_url : ""; ?>"/>
			</td>
			<td><?php echo JHTML::tooltip( JText::_( 'COM_REDSHOP_TOOLTIP_CANONICAL_URL_PRODUCT' ), JText::_( 'COM_REDSHOP_CANONICAL_URL_PRODUCT' ), 'tooltip.png', '', '', false); ?> </td>
		</tr>
		<tr>
			<td align="right" class="key"><?php echo JText::_('COM_REDSHOP_SELECT_CATEGORY_TO_USEIN_SEF' ); ?>:</td>
			<td><?php echo $this->lists['cat_in_sefurl']; ?>  </td>
			<td><?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_SELECT_CATEGORY_TO_USEIN_SEF' ), JText::_('COM_REDSHOP_SELECT_CATEGORY_TO_USEIN_SEF' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
		<tr>
			<td colspan="2">
				<hr/>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_META_KEYWORDS' ); ?>:</td>
			<td><textarea class="text_area" type="text" name="metakey" id="metakey" rows="4"
			              cols="40"/><?php echo $this->detail->metakey; ?></textarea>
			</td>
			<td><?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_META_KEYWORDS' ), JText::_('COM_REDSHOP_META_KEYWORDS' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_META_DESCRIPTION' ); ?>:</td>
			<td><textarea class="text_area" type="text" name="metadesc" id="metadesc" rows="4"
			              cols="40"/><?php echo htmlspecialchars($this->detail->metadesc); ?></textarea>
			</td>
			<td> <?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_META_DESCRIPTION' ), JText::_('COM_REDSHOP_META_DESCRIPTION' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_META_LANG_SETTING' ); ?>:</td>
			<td><textarea class="text_area" type="text" name="metalanguage_setting" id="metalanguage_setting" rows="4"
			              cols="40"/><?php echo $this->detail->metalanguage_setting; ?></textarea>
			</td>
			<td><?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_META_LANG_SETTING' ), JText::_('COM_REDSHOP_META_LANG_SETTING' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_META_ROBOT_INFO' ); ?>:</td>
			<td><textarea class="text_area" type="text" name="metarobot_info" id="metarobot_info" rows="4"
			              cols="40"/><?php echo $this->detail->metarobot_info; ?></textarea>
			</td>
			<td> <?php echo JHTML::tooltip( JText::_('COM_REDSHOP_TOOLTIP_META_ROBOT_INFO' ), JText::_('COM_REDSHOP_META_ROBOT_INFO' ), 'tooltip.png', '', '', false); ?></td>
		</tr>
	</table>
</fieldset>
<?php
echo $myTabs->endPanel();

//Create 7th Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_CHANGE_PRODUCT_TYPE'), 'tab7' );

?>
<div class="col50">
	<table class="adminform">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_PRODUCT_TYPE'); ?> </td>
			<td><?php echo $this->lists['product_type']; ?> <?php echo JHTML::tooltip(JText::_('COM_REDSHOP_PRODUCT_TYPE_TIP'), JText::_('COM_REDSHOP_PRODUCT_TYPE'), 'tooltip.png', '', '', false); ?></td>
		</tr>
	</table>
	<?php echo $this->loadTemplate('producttype')?>
</div>
<?php

echo $myTabs->endPanel();

if ( USE_STOCKROOM == 1)
{
//Create 8th Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_STOCKROOM_TAB'), 'tab8' );
?>
<div class="col50">
	<?php echo $this->loadTemplate('productstockroom')?>
</div>
<?php
echo  $myTabs->endPanel();

}
//Create 9th Tab
echo  $myTabs->startPanel(JText::_('COM_REDSHOP_DISCOUNT_CALCULATOR'), 'tab9' );
?>
<div class="col50">
	<?php echo $this->loadTemplate('calculator')?>
</div>
<?php
echo  $myTabs->endPanel();

echo $myTabs->startPanel(JText::_('COM_REDSHOP_ECONOMIC_SETTINGS'), 'tab10' );?>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_REDSHOP_ECONOMIC_SETTINGS' ); ?></legend>
	<table class="admintable" border="0" width="100%">
		<tr valign="top">
			<td width="50%">
				<table>
					<tr>
						<td valign="top" align="right"
						    class="key"><?php echo JText::_('COM_REDSHOP_ECONOMIC_ACCOUNTGROUP_LBL' ); ?>:
						</td>
						<td><?php echo $this->lists['accountgroup_id'];?>
						</td>
						<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_ACCOUNTGROUP_LBL' ), JText::_('COM_REDSHOP_ECONOMIC_ACCOUNTGROUP_LBL' ), 'tooltip.png', '', '', false); ?></td>
					</tr>
					<tr>
						<td valign="top" align="right"
						    class="key"><?php echo JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL' ); ?>:
						</td>
						<td><input class="text_area" type="text" name="quantity_selectbox_value"
						           id="quantity_selectbox_value" size="10"
						           value="<?php echo $this->lists['QUANTITY_SELECTBOX_VALUE']; ?>"/>
						</td>
						<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL' ), JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL' ), 'tooltip.png', '', '', false); ?></td>
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
<?php echo $myTabs->endPanel();



//End Pane
echo $myTabs->endPane();
?>
	<div class="clr"></div>
<?php
if($stockroom_id && USE_CONTAINER == 1) {
?>
<input type="hidden" name="stockroom_id" value="<?php echo $stockroom_id; ?>"/>
<?php
}

if($container_id) {
?>
<input type="hidden" name="container_id" value="<?php echo $container_id; ?>"/>
<?php }else { echo '<input type="hidden" name="container_id" value="" />';} ?>
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
					var propimg = 'propertyImage' + fid;
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

					var propimg = 'subpropertyImage' + fid;
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

	var options = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&product_id=<?php echo $this->detail->product_id;?>&",
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

	var as_json = new bsn.AutoSuggest('relat', related);

	//-------------- End Related Product ---------------------------


</script>
