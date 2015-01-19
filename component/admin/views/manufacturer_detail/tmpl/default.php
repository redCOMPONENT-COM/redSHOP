<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

JHTMLBehavior::modal();

$editor = JFactory::getEditor();
$order_functions = new order_functions;
$plg_manufacturer = $order_functions->getparameters('plg_manucaturer_excluding_category');
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
echo JHtml::_('tabs.start', 'manufacturer-pane', array('startOffset' => 0));
echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_DETAILS'), 'detail-tab');
?>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_('COM_REDSHOP_NAME'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="manufacturer_name" id="manufacturer_name" size="32"
					       maxlength="250" value="<?php echo $this->detail->manufacturer_name; ?>"/>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="template">
						<?php echo JText::_('COM_REDSHOP_TEMPLATE'); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['template']; ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="template">
						<?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="manufacturer_email" id="manufacturer_email" size="32"
					       maxlength="250" value="<?php echo $this->detail->manufacturer_email; ?>"/>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="template">
						<?php echo JText::_('COM_REDSHOP_MANUFACTURER_URL'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="manufacturer_url" id="manufacturer_url" size="32"
					       maxlength="250" value="<?php echo $this->detail->manufacturer_url; ?>"/>
				</td>
			</tr>

			<tr>
				<td valign="top" align="right" class="key">
					<label for="product_per_page">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_PER_PAGE'); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="product_per_page" id="product_per_page" size="32"
					       maxlength="250" value="<?php echo $this->detail->product_per_page; ?>"/>
				</td>
			</tr>
			<?php    if (count($plg_manufacturer) > 0 && $plg_manufacturer[0]->enabled)
			{
				?>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="product_per_page">
							<?php echo JText::_('COM_REDSHOP_EXCLUDING_CATEGORY_LIST'); ?>:
						</label>
					</td>
					<td><?php echo $this->lists['excluding_category_list'];?></td>
				</tr>
			<?php }?>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
				</td>
				<td>
					<?php echo $this->lists['published']; ?>
				</td>
			</tr>

		</table>
	</fieldset>
</div>
<div class="col50">

</div>

<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>

		<table class="admintable">
			<tr>
				<td>
					<?php echo $editor->display("manufacturer_desc", $this->detail->manufacturer_desc, '$widthPx', '$heightPx', '100', '20');    ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<?php

if ($this->detail->manufacturer_id != 0)
{
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_PRODUCT_IMAGES'), 'images-tab');
	?>
	<table>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGE'); ?> :</td>
			<td><?php
				$model = $this->getModel('manufacturer_detail');
				$media_id = $model->getMediaId($this->detail->manufacturer_id);

				if ($media_id)
				{
					$mediaId = $media_id->media_id;
					$mediaName = $media_id->media_name;
				}
				else
				{
					$mediaId = 0;
					$mediaName = '';
				}

				$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=media_detail&cid[]=' . $mediaId . '&section_id=' . $this->detail->manufacturer_id . '&showbuttons=1&media_section=manufacturer&section_name=' . $this->detail->manufacturer_name);

				$image_path = RedShopHelperImages::getImagePath(
								$mediaName,
								'',
								'thumb',
								'manufacturer',
								MANUFACTURER_THUMB_WIDTH,
								MANUFACTURER_THUMB_HEIGHT,
								USE_IMAGE_SIZE_SWAPPING
							);
				?>
				<div class="button2-left">
					<div class="image"><a class="modal" title="Image" href="<?php echo $ilink; ?>"
					                      rel="{handler: 'iframe', size: {x: 950, y: 500}}"><?php echo JText::_('COM_REDSHOP_ADD_ADDITIONAL_IMAGES');?></a>
					</div>
				</div>

			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<div id="image_dis">
					<img src="<?php echo $image_path; ?>" id="image_display" border="0"/>
					<input type="hidden" name="product_image" id="product_image"/>
				</div>
			</td>
		</tr>
	</table>
	<?php
}

echo  JHtml::_('tabs.panel', JText::_('COM_REDSHOP_META_DATA_TAB'), 'meta-tag-tab');
?>
<table>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_PAGE_TITLE'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="pagetitle" id="pagetitle" size="75" maxlength="250"
			       value="<?php echo $this->detail->pagetitle; ?>"/>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_TITLE'), JText::_('COM_REDSHOP_PAGE_TITLE'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_PAGE_HEADING'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="pageheading" id="pageheading" size="75" maxlength="250"
			       value="<?php echo $this->detail->pageheading; ?>"/>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PAGE_HEADING'), JText::_('COM_REDSHOP_PAGE_HEADING'), 'tooltip.png', '', '', false); ?>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<?php echo JText::_('COM_REDSHOP_SEF_URL'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="sef_url" id="sef_url" size="75" maxlength="250"
			       value="<?php echo $this->detail->sef_url; ?>"/>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SEF_URL'), JText::_('COM_REDSHOP_SEF_URL'), 'tooltip.png', '', '', false); ?>
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

if ($this->lists['extra_field'] != "")
{
	echo  JHtml::_('tabs.panel', JText::_('COM_REDSHOP_EXTRA_FIELD'), 'extra-field-tab');
	?>
	<div class="col50">
	<?php
	echo $this->lists['extra_field'];
	?>
	</div><?php
}
else
{
	echo '<input type="hidden" name="noextra_field" value="1">';
}

echo JHtml::_('tabs.end');
?>
<div class="col50">

</div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->manufacturer_id; ?>"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="manufacturer_detail"/>
</form>


