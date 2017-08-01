<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.framework');

$url = JUri::base();
$input = JFactory::getApplication()->input;
$wishlists = $this->wishlists;
$productId = $input->getInt('product_id', 0);
$hasWishlist = ($productId && count($wishlists) > 0) ? true : false;
$Itemid = $input->getInt('Itemid', 0);
?>
<div class="divnewwishlist">
<?php if ($hasWishlist && Redshop::getConfig()->get('WISHLIST_LIST')) : ?>
	<label>
		<input type="checkbox" name="chkNewwishlist" id="chkNewwishlist"
		   onchange="changeDiv(this);" />
		<?php echo JText::_('COM_REDSHOP_CREATE_NEW_WISHLIST'); ?>
	</label>
<?php endif;
?>
<div id="newwishlist" style="display:<?php echo $hasWishlist ? 'none' : 'block'; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
		<?php $pagetitle = JText ::_('COM_REDSHOP_CREATE_NEWWISHLIST'); ?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $pagetitle; ?>
		</h1>
	<?php endif; ?>
	<form name="newwishlistForm" method="post" action="">
		<div class="row">
			<div class="col-sm-4 span4">
				<label for="txtWishlistname"><?php echo JText::_('COM_REDSHOP_WISHLIST_NAME');?>:</label>
			</div>
			<div class="col-sm-8 span8">
				<input type="input" class="form-control" name="txtWishlistname" id="txtWishlistname" />
			</div>
		</div>
		<hr />
		<div class="row wishlistbtn">
			<div class="col-sm-12 span12">
			<input type="button" class="btn btn-primary" value="<?php echo JText::_('COM_REDSHOP_CREATE_SAVE'); ?>"
						   onclick="checkValidation()"/>&nbsp;
			<?php if (JFactory::getApplication()->input->getInt('loginwishlist') == 1) : ?>
				<?php
				$mywishlist_link = JRoute::_('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=' . $Itemid);
				?>
				<a href="<?PHP echo $mywishlist_link; ?>">
					<input type="button" class="btn btn-primary" value="<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>"/>
				</a>
			<?php else : ?>
				<input type="button" class="btn" value="<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>"
					   onclick="window.parent.SqueezeBox.close();"/>
			<?php endif; ?>
			</div>
		</div>
		<input type="hidden" name="<?php echo JSession::getFormToken() ?>" value="1" />
		<input type="hidden" name="product_id" value="<?php echo $input->getInt('product_id', 0) ?>" />
		<input type="hidden" name="attribute_id" value="<?php echo $input->getRaw('attribute_id', '') ?>" />
		<input type="hidden" name="property_id" value="<?php echo $input->getRaw('property_id', '') ?>" />
		<input type="hidden" name="subattribute_id" value="<?php echo $input->getRaw('subattribute_id', '') ?>" />
		<input type="hidden" name="view" value="wishlist" />
		<input type="hidden" name="option" value="com_redshop" />
		<input type="hidden" name="task" value="createsave" />
	</form>
</div>
<?php if ($hasWishlist) : ?>
	<div id="wishlist">
		<?php if ($this->params->get('show_page_heading', 1)): ?>
			<?php $pagetitle = JText::_('COM_REDSHOP_MY_WISHLIST'); ?>
			<br/>
			<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
				<?php echo $pagetitle; ?>
			</h1>
			<div>&nbsp;</div>
		<?php endif; ?>
		<form name="adminForm" id="adminForm" method="post" action="">
			<div class="table-responsive">
				<table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
					<thead>
					<tr>
						<th width="1" align="center">
							<?php echo JText::_('COM_REDSHOP_NUM'); ?>
						</th>
						<th width="1" class="title" align="center">
							<?php echo JHtml::_('redshopgrid.checkall'); ?>
						</th>
						<th class="title" width="auto">
							<?php echo JText::_('COM_REDSHOP_WISHLIST_NAME'); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$k = 0;
					$firstId = 0;

					foreach ($wishlists as $wishlist)
					{
						$i = 0;

						if ($i == 0)
						{
							$firstId = $wishlist->wishlist_id;
						}

						?>
						<tr class="<?php echo "row$i"; ?>">
							<td align="center">
								<?php echo ($i + 1); ?>
							</td>
							<td align="center">
								<?php echo JHTML::_('grid.id', $i, $wishlist->wishlist_id, false, 'wishlist_id'); ?>
							</td>
							<td>
								<?php echo $wishlist->wishlist_name; ?>
							</td>
						</tr>
						<?php
						$i++;
					}
					?>
					<tr>
						<td colspan="3" align="center">
							<input type="button" class="btn btn-primary" value="<?php echo JText::_('COM_REDSHOP_ADD_TO_WISHLIST'); ?>"
								   onclick="submitform();"/>&nbsp;
							<input type="button" class="btn" value="<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>"
								   onclick="window.parent.SqueezeBox.close();"/>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<input type="hidden" name="<?php echo JSession::getFormToken() ?>" value="1" />
            <input type="hidden" name="product_id" value="<?php echo $input->getInt('product_id', 0) ?>" />
			<input type="hidden" name="attribute_id" value="<?php echo $input->getRaw('attribute_id', '') ?>" />
			<input type="hidden" name="property_id" value="<?php echo $input->getRaw('property_id', '') ?>" />
			<input type="hidden" name="subattribute_id" value="<?php echo $input->getRaw('subattribute_id', '') ?>" />
			<input type="hidden" name="view" value="wishlist" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="option" value="com_redshop" />
			<input type="hidden" name="task" value="savewishlist" />
		</form>
	</div>

	<script language="javascript" type="text/javascript">
		function submitform() {
			if (document.adminForm.boxchecked.value == '0')
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_WISHLIST')?>");
			else
				document.adminForm.submit();
		}
		function changeDiv(element) {
			if (element.checked) {
				document.getElementById('newwishlist').style.display = 'block';
				document.getElementById('wishlist').style.display = 'none';
			}
			else {
				document.getElementById('newwishlist').style.display = 'none';
				document.getElementById('wishlist').style.display = 'block';
			}
		}

		<?php if (!Redshop::getConfig()->get('WISHLIST_LIST')) : ?>
			document.getElementsByName('checkall-toggle')[0].click();
			submitform();
		<?php endif; ?>
	</script>
<?php endif; ?>
<script language="javascript" type="text/javascript">
	function checkValidation() {
		if (trim(document.newwishlistForm.txtWishlistname.value) == "")
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_WISHLIST_NAME')?>");
		else
			document.newwishlistForm.submit();
	}
</script>
</div>
