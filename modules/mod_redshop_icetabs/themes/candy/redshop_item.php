<div class="ice-description">
  
    <h3 class="ice-title">
            <a <?php echo $target;?>  href="<?php echo $row->link;?>" title="<?php echo $row->title;?>"><?php echo $row->title;?></a>
    </h3>
      
   <div class="ice-imagearea">
   
   <p><?php echo $row->product_thumb_image; ?></p>
   
    <div class="ice-addtocart">
    	
    	<form action="<?php echo $row->addtocart_link?>" method="post">
    <input type="hidden" name="option" value="com_redshop" />
    <input type="hidden" name="view" value="cart" />
    
    <input type="hidden" name="task" value="add" />
    <input type="hidden" name="prod_id" value="<?php echo $row->product_id; ?>" />
    <input type="hidden" name="product_id" value="<?php echo $row->product_id; ?>" />
    <input type="hidden" name="quantity" value="1" />
    <input type="submit" class="greenbutton" value="<?php echo JText::_('ADD_TO_CART') ?>" title="<?php echo JText::_('ADD_TO_CART') ?>" />
    </form>
    </div>
    
    </div>
    
     <div class="ice-productdesc">
     <?php echo $row->description;	?>
	</div>
 
    
    
 </div>