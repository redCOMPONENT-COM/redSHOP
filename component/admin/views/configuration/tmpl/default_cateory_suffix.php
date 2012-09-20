<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$uri = & JURI::getInstance ();
$url = $uri->root ();
$link_path="/components/com_redshop/assets/images/";
?>
<table class="admintable">
<tr><td class="config_param"><?php echo JText::_( 'CATEGORY_SUFFIXES' ); ?></td></tr>
<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_RETURN_TO_CATEGORY_PREFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_RETURN_TO_CATEGORY_PREFIX' ); ?>">
     <?php echo JText::_ ( 'RETURN_TO_CATEGORY_PREFIX' );?>:</span></td>
		<td>
		<input type="text" name="return_to_category_prefix" id="return_to_category_prefix" value="<?php echo DAFULT_RETURN_TO_CATEGORY_PREFIX;?>" />
		</td>
	</tr>
	<!-- next-previous link-->
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DAFULT_PREVIOUS_PREFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DAFULT_PREVIOUS' ); ?>">
     <?php echo JText::_ ( 'DAFULT_PREVIOUS_PREFIX_LBL' );?>:</span></td>
		<td>
		<input type="text" name="default_previous_prefix" id="default_previous_prefix" value="<?php echo DAFULT_PREVIOUS_LINK_PREFIX;?>" />
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
	<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DAFULT_NEXT_SUFFIX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DAFULT_NEXT_SUFFIX' ); ?>">
     <?php echo JText::_ ( 'DAFULT_NEXT_SUFFIX_LBL' ); ?>:</span></td>
		<td>
		<input type="text" name="default_next_suffix" id="default_next_suffix" value="<?php echo DAFULT_NEXT_LINK_SUFFIX;?>" />
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_CUSTOM_PREVIOUS_LINK_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CUSTOM_PREVIOUS_LINK' ); ?>">
        <?php echo JText::_ ( 'CUSTOM_PREVIOUS_LINK' );?>:</span></td>
		<td>
		<input type="text" name="custom_previous_link" id="custom_previous_link" value="<?php echo CUSTOM_PREVIOUS_LINK_FIND;?>" />
		</td>
	</tr>

	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_CUSTOM_NEXT_LINK_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CUSTOM_NEXT_LINK' ); ?>">
        <?php echo JText::_ ( 'CUSTOM_NEXT_LINK' );?>:</span></td>
		<td>
		<input type="text" name="custom_next_link" id="custom_next_link" value="<?php echo CUSTOM_NEXT_LINK_FIND;?>" />
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_IMAGE_PREVIOUS_LINK_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_IMAGE_PREVIOUS_LINK' ); ?>">
        <?php echo JText::_ ( 'IMAGE_PREVIOUS_LINK' );?>:</span></td>
		<td>
		<div>
		<div>
		<input class="text_area" type="file" name="imgpre" id="imgpre" size="40" />
		<input type="hidden" name="image_previous_link" id="image_previous_link" value="<?php echo IMAGE_PREVIOUS_LINK_FIND;?>" />
		</div>
<?php  if(is_file ( JPATH_ROOT . '/components/com_redshop/assets/images/' . IMAGE_PREVIOUS_LINK_FIND)) {?>
		<div id="prvlinkdiv">
		<a class="modal" href="<?php echo $url . '/components/com_redshop/assets/images/' . IMAGE_PREVIOUS_LINK_FIND; ?>" title="<?php echo IMAGE_PREVIOUS_LINK_FIND;?>" rel="{handler: 'image', size: {}}"><img alt="<?php echo IMAGE_PREVIOUS_LINK_FIND; ?>" src="<?php echo $url . '/components/com_redshop/assets/images/' . IMAGE_PREVIOUS_LINK_FIND; ?>" /></a>
		<a class="remove_link" href="#123" onclick="delimg('<?php echo IMAGE_PREVIOUS_LINK_FIND?>','prvlinkdiv','<?php echo $link_path?>');">Remove File</a>
		</div>
		<?php } ?>
</div>
		</td>
	</tr>

	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_IMAGE_NEXT_LINK_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_IMAGE_NEXT_LINK' ); ?>">
<?php
echo JText::_ ( 'IMAGE_NEXT_LINK' );
?>:
</span></td>
		<td>
		<div>
			<div>
				<input class="text_area" type="file" name="imgnext" id="imgnext" size="40" />
				<input type="hidden" name="image_next_link" id="image_next_link" value="<?php echo IMAGE_NEXT_LINK_FIND; ?>" />
			</div>
			<?php  if(is_file ( JPATH_ROOT . '/components/com_redshop/assets/images/' . IMAGE_NEXT_LINK_FIND)) {?>
		<div id="nxtlinkdiv">
				<a class="modal" href="<?php echo $url . '/components/com_redshop/assets/images/' . IMAGE_NEXT_LINK_FIND; ?>" title="<?php echo IMAGE_NEXT_LINK_FIND; ?>" rel="{handler: 'image', size: {}}"><img alt="<?php echo IMAGE_NEXT_LINK_FIND; ?>" src="<?php echo $url . '/components/com_redshop/assets/images/' . IMAGE_NEXT_LINK_FIND; ?>" /></a>
				<a class="remove_link" href="#123"   onclick="delimg('<?php echo IMAGE_NEXT_LINK_FIND?>','nxtlinkdiv','<?php echo $link_path?>');">Remove File</a>
			</div><?php } ?>
		</div>
		</td>
	</tr>


	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DEFAULT_NP_LINK_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DEFAULT_NP_LINK' ); ?>">
		<label for="name"><?php echo JText::_ ( 'DEFAULT_NP_LINK_LBL' ); ?></label></span>
		</td>
		<td><?php echo $this->lists ['next_previous_link'];?></td>
	</tr>
	<!-- next-previous link End-->

</table>