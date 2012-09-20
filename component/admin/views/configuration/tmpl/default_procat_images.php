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
$cat_path="/components/com_redshop/assets/images/category/";
$prod_path="/components/com_redshop/assets/images/product/";
$slimbox_img_path="/components/com_redshop/assets/images/slimbox/";
?>
<table class="admintable" id="measurement">
<tr><td class="config_param"><?php echo JText::_( 'DEFAULT_IMAGES' ); ?></td></tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_CATEGORY_DEFAULT_IMAGE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATEGORY_DEFAULT_IMAGE' ); ?>">
		<?php echo JText::_ ( 'CATEGORY_DEFAULT_IMAGE_LBL' );?>:</span></td>
		<td>
		<div>
		<div>
			<input class="text_area" type="file" name="categoryImg" id="categoryImg" size="40" />
			<input type="hidden" name="category_default_image" id="category_default_image" value="<?php echo CATEGORY_DEFAULT_IMAGE;?>" />
		</div>

		<?php  if(is_file ( JPATH_ROOT . '/components/com_redshop/assets/images/category/' . CATEGORY_DEFAULT_IMAGE)) {?>
		<div id="catdiv">
		<a class="modal" href="<?php
				echo $url . '/components/com_redshop/assets/images/category/' . CATEGORY_DEFAULT_IMAGE;
				?>"
			title="<?php
				echo CATEGORY_DEFAULT_IMAGE;
				?>"
			rel="{handler: 'image', size: {}}"><img height="40" width="40"
			alt="<?php
				echo CATEGORY_DEFAULT_IMAGE;
				?>"
			src="<?php
				echo $url . '/components/com_redshop/assets/images/category/' . CATEGORY_DEFAULT_IMAGE;
				?>" /></a> <a class="remove_link" href="#123"   onclick="delimg('<?php echo CATEGORY_DEFAULT_IMAGE?>','catdiv','<?php echo $cat_path?>');">Remove File</a></div>
		<?php
			}
			?>
		</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'PRODUCT_DEFAULT_IMAGE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_PRODUCT_DEFAULT_IMAGE_LBL' ); ?>">
		<?php echo JText::_ ( 'PRODUCT_DEFAULT_IMAGE_LBL' );?>:</span></td>
		<td>

		<div>
		<div>
		<input class="text_area" type="file" name="productImg" id="productImg" size="40" />
		<input type="hidden" name="product_default_image" id="product_default_image" value="<?php echo PRODUCT_DEFAULT_IMAGE;?>" />

		</div>
			<?php
			if (is_file ( JPATH_ROOT . '/components/com_redshop/assets/images/product/' . PRODUCT_DEFAULT_IMAGE)) {
				?>
		<div id ="proddiv"><a class="modal"
			href="<?php
				echo $url . '/components/com_redshop/assets/images/product/' . PRODUCT_DEFAULT_IMAGE;
				?>"
			title="<?php
				echo PRODUCT_DEFAULT_IMAGE;
				?>"
			rel="{handler: 'image', size: {}}"><img height="40" width="40"
			alt="<?php
				echo PRODUCT_DEFAULT_IMAGE;
				?>"
			src="<?php
				echo $url . '/components/com_redshop/assets/images/product/' .PRODUCT_DEFAULT_IMAGE;
				?>" /></a><a class="remove_link" href="#123"   onclick="delimg('<?php echo PRODUCT_DEFAULT_IMAGE?>','proddiv','<?php echo $prod_path?>');">Remove File</a></div>
		<?php
			}
			?>
		</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_WATERMARK_IMAGE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_WATERMARK_IMAGE' ); ?>">
	<?php echo JText::_ ( 'WATERMARK_IMAGE_LBL' ); ?>:</span></td>
		<td>
		<div>
		<div>

		<input class="text_area" type="file" name="watermarkImg" id="watermarkImg" size="40" />
		<input type="hidden" name="watermark_image" id="watermark_image" value="<?php echo WATERMARK_IMAGE;?>" />

		</div>
			<?php
			if (is_file ( JPATH_ROOT . '/components/com_redshop/assets/images/product/' .WATERMARK_IMAGE)) {
				?>
		<div id="prodwaterdiv"><a class="modal"
			href="<?php
				echo $url . '/components/com_redshop/assets/images/product/' .WATERMARK_IMAGE;
				?>"
			title="<?php
				echo WATERMARK_IMAGE;
				?>"
			rel="{handler: 'image', size: {}}"><img height="40" width="40"
			alt="<?php
				echo PRODUCT_DEFAULT_IMAGE;
				?>"
			src="<?php
				echo $url . '/components/com_redshop/assets/images/product/' . WATERMARK_IMAGE;
				?>" /></a><a class="remove_link" href="#123"   onclick="delimg('<?php echo WATERMARK_IMAGE?>','prodwaterdiv','<?php echo $prod_path?>');">Remove File</a></div>
		<?php
			}
			?>
		</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_PRODUCT_OUTOFSTOCK_IMAGE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_PRODUCT_OUTOFSTOCK_IMAGE' ); ?>">
<?php
echo JText::_ ( 'PRODUCT_OUTOFSTOCK_IMAGE_LBL' );
?>:
</td>
		<td>
		<div>
		<div>
		<input class="text_area" type="file" name="productoutofstockImg" id="categoryImg" size="40" />
		<input type="hidden" name="product_outofstock_image" id="product_outofstock_image" value="<?php echo PRODUCT_OUTOFSTOCK_IMAGE;?>" />
		</div>
			<?php
			if (is_file ( JPATH_ROOT . '/components/com_redshop/assets/images/product/' . PRODUCT_OUTOFSTOCK_IMAGE)) {
				?>
		<div id="prodstockdiv"><a class="modal"
			href="<?php
				echo $url . '/components/com_redshop/assets/images/product/' . PRODUCT_OUTOFSTOCK_IMAGE;
				?>"
			title="<?php
				echo PRODUCT_OUTOFSTOCK_IMAGE;
				?>"
			rel="{handler: 'image', size: {}}"><img height="40" width="40"
			alt="<?php
				echo PRODUCT_OUTOFSTOCK_IMAGE;
				?>"
			src="<?php
				echo $url . '/components/com_redshop/assets/images/product/' . PRODUCT_OUTOFSTOCK_IMAGE;
				?>" /></a> <a class="remove_link" href="#123"   onclick="delimg('<?php echo PRODUCT_OUTOFSTOCK_IMAGE?>','prodstockdiv','<?php echo $prod_path?>');">Remove File</a>
				</div>
		<?php
			}
			?>
		</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE' ); ?>">
<?php
echo JText::_ ( 'PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE_LBL' );
?>:
</span></td>
		<td>
		<div>
			<div>
				<input class="text_area" type="file" name="imgslimbox" id="imgslimbox" size="40" />
				<input type="hidden" name="product_detail_lighbox_close_button_image" id="product_detail_lighbox_close_button_image" value="<?php echo PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>" />
				<?php
				$slimbox_image_path=JPATH_ROOT . '/components/com_redshop/assets/images/slimbox/' . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE;
				?>
			</div>
			<?php  if(is_file ($slimbox_image_path)) {?>
		<div id="slimboximglinkdiv">

				<div><a class="modal" href="<?php echo $url . '/components/com_redshop/assets/images/slimbox/' . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>" title="<?php echo PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>" rel="{handler: 'image', size: {}}"><img alt="<?php echo PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>" src="<?php echo $url . '/components/com_redshop/assets/images/slimbox/' . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE; ?>" /></a>
				<a class="remove_link" href="#123"   onclick="delimg('<?php echo PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE?>','slimboximglinkdiv','<?php echo $slimbox_img_path; ?>');">Remove File</a></span>
				</div>
			</div><?php } ?>
		</div>
		</td>
	</tr>

</table>