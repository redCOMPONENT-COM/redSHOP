<?php 
	global $VM_LANG, $ps_product; 
?>
<div class="ice-description">
  
    <h3 class="ice-title">
            <a <?php echo $target;?>  href="<?php echo $row->link;?>" title="<?php echo $row->title;?>"><?php echo $row->title;?></a>
    </h3>
      
   <div class="ice-vmimagearea">
   
   <p><?php echo ps_product::image_tag( $row->product_thumb_image, "alt=\"".$row->product_name."\""); ?></p>
     	<span class="ice-pprice">
	<?php 
		global $iceps_product ;
		if( !$iceps_product ) {
			$iceps_product = new ps_product();
		}
		echo $iceps_product->show_price($row->product_id, true, false);
	?>
    </span>
   
    <?php if( !trim( ps_product::product_has_attributes($row->product_id, true)) ) :  ?>
     <div class="ice-addtocart">
   <form action="<?php echo $row->addtocart_link?>" method="post" name="addtocart" id="addtocart<?php echo $row->product_id; ?>" onsubmit="handleAddToCart( this.id );return false;" >
    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="page" value="shop.cart" />
    <input type="hidden" name="Itemid" value="<?php echo ps_session::getShopItemid(); ?>" />
    <input type="hidden" name="func" value="cartAdd" />
    <input type="hidden" name="prod_id" value="<?php echo $row->product_id; ?>" />
    <input type="hidden" name="product_id" value="<?php echo $row->product_id; ?>" />
    <input type="hidden" name="quantity" value="1" />
    <input type="hidden" name="set_price[]" value="" />
    <input type="hidden" name="adjust_price[]" value="" />
    <input type="hidden" name="master_product[]" value="" />
    <button type="submit" class="addtocart_button_module"><span class="round"><span><?php echo $VM_LANG->_('PHPSHOP_CART_ADD_TO')  ?></span></span></button>
    </form>
    </div>
    
    </div>
    <?php endif; ?>
    
     <div class="ice-vmproductdesc">
     <?php echo $row->description;	?>
	</div>
 
    
    
 </div>