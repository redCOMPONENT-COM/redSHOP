<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$url = JURI::base();
$option = JRequest::getVar('option');
$wishlist = $this->wishlist;
$product_id = JRequest::getInt('product_id');
$flage = ($product_id && count($wishlist) > 0) ? true : false;
$Itemid = JRequest::getVar('Itemid');
?>
<?php if ($flage) : ?>
	<input type="checkbox" name="chkNewwishlist" id="chkNewwishlist"
	       onchange="changeDiv(this);"/><?php echo JText::_('COM_REDSHOP_CREATE_NEW_WISHLIST'); ?>
<?php endif;
?>
<div id="newwishlist" style="display:<?php echo $flage ? 'none' : 'block'; ?>">
	<?php
	if ($this->params->get('show_page_heading', 1))
	{
		$pagetitle = JText ::_('COM_REDSHOP_CREATE_NEWWISHLIST');
		?>
		<br/>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $pagetitle; ?>
		</h1>
		<div>&nbsp;</div>
	<?php
	}
	?>
	<form name="newwishlistForm" method="post" action="">
		<table>
			<tr>
				<td>
					<label for="txtWishlistname"><?php echo JText::_('COM_REDSHOP_WISHLIST_NAME');?> : </label>
				</td>
				<td>
					<input type="input" name="txtWishlistname" id="txtWishlistname"/>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" value="<?php echo JText::_('COM_REDSHOP_CREATE_SAVE'); ?>"
					       onclick="checkValidation()"/>&nbsp;
					<?php
					if (JRequest::getVar('loginwishlist') == 1) : ?>
						$mywishlist_link = JRoute::_('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=' . $Itemid);
						?>
						<a href="<?PHP echo $mywishlist_link; ?>"><input type="button"
						                                                 value="<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>"/></a>
					<?php else : ?>
						<input type="button" value="<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>"
						       onclick="window.parent.SqueezeBox.close();"/>
					<?php endif; ?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="view" value="wishlist"/>
		<input type="hidden" name="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="task" value="createsave"/>
	</form>
</div>
<?php
if ($flage) : ?>
	<div id="wishlist">
		<?php
		if ($this->params->get('show_page_heading', 1))
		{
			$pagetitle = JText::_('COM_REDSHOP_MY_WISHLIST');
			?>
			<br/>
			<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
				<?php echo $pagetitle; ?>
			</h1>
			<div>&nbsp;</div>
		<?php
		}
		?>
		<form name="adminForm" method="post" action="">
			<table class="adminlist">
				<thead>
				<tr>
					<th width="5%" align="center">
						<?php echo JText::_('COM_REDSHOP_NUM'); ?>
					</th>
					<th width="5%" class="title" align="center">
						<input type="checkbox" name="toggle" value=""
						       onclick="checkAll(<?php echo count($wishlist); ?>);"/>
					</th>
					<th class="title" width="30%">
						<?php echo JText::_('COM_REDSHOP_WISHLIST_NAME'); ?>
					</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$k = 0;

				for ($i = 0, $n = count($wishlist); $i < $n; $i++)
				{
					$row = $wishlist[$i];
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php echo ($i + 1); ?>
						</td>
						<td align="center">
							<?php echo JHTML::_('grid.id', $i, $row->wishlist_id); ?>
						</td>
						<td>
							<?php echo $row->wishlist_name; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				<tr>
					<td colspan="3" align="center">
						<input type="button" value="<?php echo JText::_('COM_REDSHOP_ADD_TO_WISHLIST'); ?>"
						       onclick="submitform();"/>&nbsp;
						<input type="button" value="<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>"
						       onclick="window.parent.SqueezeBox.close();"/>
					</td>
				</tr>
				</tbody>
			</table>
			<input type="hidden" name="view" value="wishlist"/>
			<input type="hidden" name="boxchecked" value=""/>
			<input type="hidden" name="option" value="<?php echo $option; ?>"/>
			<input type="hidden" name="task" value="savewishlist"/>
		</form>
	</div>

	<script language="javascript" type="text/javascript">
		function submitform() {
			if (!document.adminForm.boxchecked.value)
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