<?php
/**
 * IceTabs Module for Joomla 1.6 By IceTheme
 *
 *
 * @copyright      Copyright (C) 2008 - 2011 IceTheme.com. All rights reserved.
 * @license        GNU General Public License version 2
 *
 * @Website    http://www.icetheme.com/Joomla-Extensions/icetabs.html
 * @Support    http://www.icetheme.com/Forums/IceTabs/
 *
 */
?>
<div class="ice-description">
	<h3 class="ice-title">
		<?php if ($params->get('show_readmore', '0'))
		{ ?>
			<a <?php echo $target;?>  href="<?php echo $row->link; ?>"
			                          title="<?php echo $row->title; ?>"><?php echo $row->title;?></a>
		<?php }
		else
		{ ?>
			<?php echo $row->title; ?>
		<?php }?>
	</h3>

	<?php if ($params->get('show_readmore', '0'))
	{ ?>
		<a class="ice-readmore" <?php echo $target;?>  href="<?php echo $row->link; ?>"
		   title="<?php echo $row->title; ?>">
			<?php echo $row->mainImage; ?>
		</a>
	<?php }
	else
	{ ?>
		<?php echo $row->mainImage; ?>
	<?php }?>

	<?php echo $row->description;?>


	<?php if ($params->get('show_readmore', 1)): ?>
		<a class="ice-readmore" <?php echo $target;?>  href="<?php echo $row->link; ?>"
		   title="<?php echo $row->title; ?>">
			<?php echo JText::_('Read more...');?>
		</a>
	<?php endif; ?>

	<?php if ($params->get('group') == "redshop"): ?>
		<div class="ice-addtocart">
			<form action="<?php echo $row->addtocart_link ?>" method="post">
				<input type="hidden" name="option" value="com_redshop"/>
				<input type="hidden" name="task" value="add"/>
				<input type="hidden" name="view" value="cart"/>
				<input type="hidden" name="prod_id" value="<?php echo $row->product_id; ?>"/>
				<input type="hidden" name="product_id" value="<?php echo $row->product_id; ?>"/>
				<input type="hidden" name="quantity" value="1"/>
				<input type="submit" class="greenbutton" value="<?php echo JText::_('COM_REDSHOP_ADD_TO_CART') ?>"
				       title="<?php echo JText::_('COM_REDSHOP_ADD_TO_CART') ?>"/>
			</form>
		</div>
	<?php endif; ?>
</div>
