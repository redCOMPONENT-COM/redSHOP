<div class="ice-description">
    <h3 class="ice-title">
            <a <?php echo $target;?>  href="<?php echo $row->link;?>" title="<?php echo $row->title;?>"><?php echo $row->title;?></a>
    </h3>
    <?php echo $row->description;?>
    <a class="ice-readmore" <?php echo $target;?>  href="<?php echo $row->link;?>" title="<?php echo $row->title;?>"><span class="round"><span><?php echo JText::_('Read more...');?></span></span></a>
 </div>