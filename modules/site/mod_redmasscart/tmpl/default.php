<?php
/**
 * @copyright Copyright (C) 2008 redCOMPONENT.com. All rights reserved.
 * @license   can be read in this package of software in the file license.txt or
 *            read on http://redcomponent.com/license.txt
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 */

// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
$u = JFactory::getURI();
?>
<div id="cartmod">
	<form action="" method="post" name="masscart" id="masscart">
		<div id="redcart">
			<label for="numbercart">
				<?php if ($params->get('title') != "")
				{
					echo $params->get('title');
				}
				else
				{
					echo JText::_('MOD_REDMASSCART_PRODUCT_NUMBER');
				}
				?>
			<?php echo JHTML::_('tooltip', $params->get('info')); ?></label>
			<textarea id="numbercart" name="numbercart"
			          style="width:<?php echo $params->get('textwidth'); ?>px; height:<?php echo $params->get('textheight'); ?>px;"
			          class="inputbox_numbercart"></textarea>

		</div>
		<?php
		if ($params->get('chk_quantity') == "1")
		{
			?>
			<div id="productQuantity">
				<label for="mod_quantity"><?php  echo JText::_('MOD_REDMASSCART_PRODUCT_QUANTITY');  ?></label>
				<input type="text" id="mod_quantity" class="span12" name="mod_quantity" size="2"/>
			</div>
		<?php } ?>
		<br/>
		<input type="submit" name="Submit" class="button_cart btn" value="<?php echo $params->get('cartbtntitle'); ?>"/>
		<input type="hidden" name="option" value="com_redshop"/>
		<input type="hidden" name="view" value="cart"/>
		<input type="hidden" name="task" value="redmasscart"/>
		<input type="hidden" name="rurl" value="<?php echo base64_encode($u->toString()); ?>"/>
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
