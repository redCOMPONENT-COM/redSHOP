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
?>
<div class="col50">
<table width="100%" cellpadding="2" border="0" cellspacing="2">
	<tr>
		<td>
		<fieldset class="adminform"><legend><?php echo JText::_( 'COM_REDSHOP_NAVIGATOR_PRODUCT' ); ?></legend>
    <table class="admintable">
      <tr >
        <td VALIGN="TOP" class="key" align="center"><?php echo JText::_('COM_REDSHOP_PRODUCT_SOURCE' ); ?> <br />
          <br />          
        </td>
        <td><input style="width: 200px" type="text" id="navigator" value="" /></td>
      </tr>
      </table>
		</fieldset>
		<table width="100%" cellpadding="2" border="0" cellspacing="2">
      <tr>
        <td colspan="2">
        	<table  id="navigator_table" class="adminlist" border="0"   >
            <thead>
              <tr>
                <th  width="35%" align="left"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME' ); ?></th>
                <th  width="15%" align="left"><?php echo JText::_('COM_REDSHOP_DISPLAY_NAME' ); ?></th>
                <th width="15%" align="left"><?php echo JText::_('COM_REDSHOP_ORDERING' ); ?></th>
                <th  width="15%" align="left"><?php echo JText::_('COM_REDSHOP_DELETE' ); ?></th>
              </tr>
            </thead>
            <tbody>
<?php		$navigator_product=$this->lists['navigator_product'];
			for($f=0;$f<count($navigator_product);$f++)
			{
			?>
				<tr><td><?php echo $navigator_product[$f]->product_name." (".$navigator_product[$f]->product_number.")"?>
										<input type="hidden" value="<?php echo $navigator_product[$f]->child_product_id;?>" name="product_navigator[<?php echo $f;?>][child_product_id]">
						<input type="hidden" value="<?php echo $navigator_product[$f]->navigator_id;?>" name="product_navigator[<?php echo $f;?>][navigator_id]"></td>
					<td><input class="text_area" type="text" value="<?php echo $navigator_product[$f]->navigator_name;?>" name="product_navigator[<?php echo $f;?>][navigator_name]"></td>
					<td><input type="text" name="product_navigator[<?php echo $f;?>][ordering]" size="5" value="<?php echo $navigator_product[$f]->ordering;?>" class="text_area" style="text-align: center" /></td>
					<td><input value="Remove" onclick="deleteRow_navigator(this,<?php echo $navigator_product[$f]->navigator_id;?>,0,<?php echo $navigator_product[$f]->child_product_id;?>);" class="button" type="button"></td>
				</tr>
	<?php	}	?>
            </tbody>
          </table><input type="hidden" name="total_navigator" id="total_navigator" value="<?php echo $f; ?>" /></td>
      </tr>
    </table>   
    </table>
  </div>