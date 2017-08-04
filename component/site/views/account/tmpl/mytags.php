<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$url = JURI::base();

// Get product helper
$extra_data = productHelper::getInstance();

$Itemid = JRequest::getInt('Itemid');
$tagid  = JRequest::getInt('tagid');
$edit   = JRequest::getInt('edit');

$model = $this->getModel('account');
$user  = JFactory::getUser();

$pagetitle = JText::_('COM_REDSHOP_MY_TAGS');

if ($this->params->get('show_page_heading', 1))
{
	?>
	<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $pagetitle; ?>
	</h1>
<?php
}
?>
<?php
if ($user->id != 0)
{
	if (isset($tagid))
	{
		if (isset($edit))
		{
			$link = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $Itemid);

			?>
			<div>&nbsp;</div>
			<form id="tags_name" name="tags_name" action="<?php echo $link; ?>" method="post">
				<table border="0" cellpadding="5" cellspacing="0" width="100%" class="adminlist">
					<tr>
						<td width="10%"><?php echo JText::_('COM_REDSHOP_TAG_NAME');?></td>
						<td width="70%"><input type="text" name="tags_name"
						                       value="<?php echo $model->getMytag($tagid); ?>" id="tags_name"
						                       size="50"/></td>
						<td width="20%">
							<input type="submit" class="button btn btn-primary" name="tags_submit"
							       value="<?php echo JText::_('COM_REDSHOP_EDIT_TAG'); ?>"/>
							<input type="hidden" name="tags_id" value="<?php echo $tagid; ?>"/>
							<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
							<input type="hidden" name="task" value="editTag"/>
							<input type="hidden" name="view" value="account"/>
						</td>
					</tr>
				</table>
			</form>
			<div>&nbsp;</div>
			<div>
				<a href="<?php echo 'index.php?option=com_redshop&view=account&layout=mytags&tagid=' . $tagid . '&Itemid=' . $Itemid; ?>"
				   title="<?php echo JText::_('COM_REDSHOP_BACK_TO_TAG_LIST'); ?>">
					<?php echo JText::_('COM_REDSHOP_BACK_TO_TAG_LIST');?></a>
			</div>
		<?php
		}
		else
		{
			$MyTags = $model->getMyDetail();

			$link_edit   = 'index.php?option=com_redshop&view=account&layout=mytags&edit=1&tagid=' . $tagid . '&Itemid=' . $Itemid;
			$link_remove = 'index.php?option=com_redshop&view=account&layout=mytags&remove=1&tagid=' . $tagid . '&Itemid=' . $Itemid;
			?>
			<!-- tag detail-->

			<table border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
					<td align="right">
						<span><a href="<?php echo $link_edit; ?>" title=""
						         style="text-decoration: none;"><?php echo JText::_('COM_REDSHOP_EDIT_TAG');?></a>&nbsp;|</span>
						<span><a href="<?php echo $link_remove; ?>" title=""
						         style="text-decoration: none;"><?php echo JText::_('COM_REDSHOP_REMOVE_TAG');?></a></span>
					</td>
				</tr>
			</table>
			<div>&nbsp;</div>
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr valign="top">
					<td width="40%">
						<?php
						$i = 0;

						if (count($MyTags) > 0)
						{
							foreach ($MyTags as $row)
							{
								$data_add   = '<div style="float:left;width:' . (Redshop::getConfig()->get('THUMB_WIDTH') + 50) . 'px;height:' . (Redshop::getConfig()->get('THUMB_HEIGHT') + 70) . 'px;text-align:center;">';
								$thum_image = "";

								$pname = $row->product_name;

								$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid);

								if ($row->product_full_image)
								{
									$thumbUrl = RedShopHelperImages::getImagePath(
													$row->product_full_image,
													'',
													'thumb',
													'product',
													Redshop::getConfig()->get('THUMB_WIDTH'),
													Redshop::getConfig()->get('THUMB_HEIGHT'),
													Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
												);
									$thum_image = "<div style='width:" . Redshop::getConfig()->get('THUMB_WIDTH') . "px;height:" . Redshop::getConfig()->get('THUMB_HEIGHT') . "px;margin-left:20px;' ><a href='" . $link . "' title=''><img src='" . $thumbUrl . "'></a></div>";
									$data_add .= $thum_image;
								}

								$pname = "<div ><a href='" . $link . "' >" . $pname . "</a></div>";
								$data_add .= $pname;

								// For attribute price count
								$price_add = '<span id="pr_price">' . $extra_data->getProductFormattedPrice($row->product_price) . '</span>';

								$tax_amount = $extra_data->getProductTax($row->product_id);

								if ($tax_amount == 0)
									$data_add .= '<div>' . $price_add . '</div>';
								else
									$data_add .= '<div>' . $extra_data->getProductFormattedPrice($tax_amount) . '</div>';

								// Start cart

								($tax_amount == 0) ? $product_price = $row->product_price : $product_price = $tax_amount;

								$data_add .= "<div><form name='addtocartscroll" . $i . "' id='addtocartscroll" . $i . "' action='' method='post' >
										<input type='hidden'   value='" . $row->product_id . "' name='product_id'>
										<input type='hidden'   value='cart' name='view'>
										<input type='hidden'   value='add' name='task'>
										<input type='hidden'   name='product_price' value='" . $product_price . "'>
										<input type='hidden' name='quantity' id='quantity" . $row->product_id . "'  value='1'>
										<span onclick='document.addtocartscroll" . $i . ".submit();' align='center' style='background-color: #" . Redshop::getConfig()->get('ADDTOCART_BACKGROUND') . ";cursor:pointer;'><span style='cursor: pointer;' >" . JText::_('COM_REDSHOP_ADD_TO_CART') . "</span></span>
										</form>
										</div>";
								$i++;
								$data_add .= '</div>';
								echo $data_add;
							}
						}
						else
						{
							echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_TAGS') . "</div>";
						}

						?>
					</td>
				</tr>
			</table>
			<div>&nbsp;</div>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
						<div>
							<a href="<?php echo 'index.php?option=com_redshop&view=account&layout=mytags&Itemid=' . $Itemid; ?>"
							   title="<?php echo JText::_('COM_REDSHOP_BACK_TO_TAG_LIST'); ?>">
								<?php echo JText::_('COM_REDSHOP_BACK_TO_TAG_LIST');?></a>
						</div>
					</td>
					<td valign="top" align="center">
						<?php echo $this->pagination->getPagesLinks(); ?>
						<br/><br/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" colspan="2">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</td>
				</tr>
			</table>
			<div>&nbsp;</div>
		<?php
		}
	}
	else
	{
		$MyTags = $model->getMyDetail();
		?>
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_AVAILABLE_TAGS')?></legend>
			<table border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td><?php
if (count($MyTags) != 0)
						{
							foreach ($MyTags as $MyTag)
							{
								?>
								<a href="<?php echo 'index.php?option=com_redshop&view=account&layout=mytags&tagid=' . $MyTag->tags_id . '&Itemid=' . $Itemid; ?>"
								   style="text-decoration: none;"><span
										style="font-size: <?php echo $MyTag->tags_counter + 15; ?>px;">
										<?php echo $MyTag->tags_name; ?></span></a>
							<?php
							}
						}
						?>
					</td>
				</tr>
			</table>
		</fieldset>
		<div>&nbsp;</div>
		<div><a href="<?php echo 'index.php?option=com_redshop&view=account&Itemid=' . $Itemid; ?>"
		        title="<?php echo JText::_('COM_REDSHOP_BACK_TO_MYACCOUNT'); ?>">
				<?php echo JText::_('COM_REDSHOP_BACK_TO_MYACCOUNT');?></a>
		</div>
	<?php
	}
}
