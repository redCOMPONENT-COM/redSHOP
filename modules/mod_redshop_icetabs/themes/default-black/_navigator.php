<div class="ice-navigator-wrapper clearfix">
    <!-- NAVIGATOR -->
      <div class="ice-navigator-outer">
            <ul class="ice-navigator">
            <?php foreach( $list as $row ):?>
                <li><div><?php echo $row->thumbnail;?>
					<h4 class="ice-title"><?php echo $row->title; ?></h4>
                 </div></li>
             <?php endforeach; ?> 		
            </ul>
      </div>
 	<!-- END NAVIGATOR //-->
</div>    