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

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

class plgredshop_custom_viewsrs_custom_views_date extends JPlugin
{
	var $_table_prefix = null;
   /**
    * Constructor
    *
    * For php4 compatability we must not use the __constructor as a constructor for
    * plugins because func_get_args ( void ) returns a copy of all passed arguments
    * NOT references.  This causes problems with cross-referencing necessary for the
    * observer design pattern.
    */
	function plgredshop_custom_viewsrs_custom_views_date(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            
    }
   	
    /**
    * Plugin method with the same name as the event will be called automatically.
    */
 	function onSelectedDate()
    { 
    	$heading=JText::_('PRODUCT_ORDERED_DATE');
		$gobtn =JText::_('CUSTOMVIEW_GO');
    	$cur_date =date('d-m-Y');
    	$maindate =JRequest::getVar('maindate', $cur_date);
    ?>
    	<form action="index.php?option=com_redshop&view=customprint&printoption=<?php echo JRequest::getVar('printoption');?>&popup=1" method="post" name="adminForm" id="adminForm">

		<div class="col50">
		
			<table class="adminlist">
			<tr>
				<td valign="top" align="left" colspan='2'>
					<h1 >
						<?php echo $heading;?>
					</h1>
				</td>
			</tr>
			<tr>
				<td valign="top" align="left" colspan='2'>
					<?php echo JHTML::_('calendar',$maindate,'maindate', 'maindate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'15' ));?>	
				</td>
			</tr>
			<tr>
				<td valign="top" align="left" colspan='2'>
					<input type="submit" value="<?php echo $gobtn?>" name="printall" onclick="return submitbutton('printall');"/>	
				</td>
			</tr>		 
		</table>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('printoption');?>" />
	<input type="hidden" name="printoption" value="<?php echo JRequest::getVar('printoption');?>" />
	<input type="hidden" name="view" value="customprint" />
	
	</form>
    <?php  
    }
	
	function onSelectedDateValue()
    { 
    	$db = JFactory::getDBO();
    	$heading=JText::_('PRODUCT_ORDERED_DATE');
		$print= JText::_('PRINT');
    	$sel = "select o.*,fd.*,p.* from ".$this->_table_prefix."fields_data fd left outer join  ".$this->_table_prefix."order_item o on o.order_item_id=fd.itemid  left outer join  ".$this->_table_prefix."product p on o.product_id=p.product_id where fd.section=12 order by o.cdate desc";
    	$db->setQuery($sel);
		$params=$db->loadObjectList();
		$popup =JRequest::getVar('popup', 1);
		
		
    	?>
    	<style type="text/css">
			.checkout_attribute_title {
		  font-weight: bold;
		}
		</style>
		<form action="index2.php?json=1&tmpl=component&option=com_redshop&view=customprint&printoption=<?php echo JRequest::getVar('printoption');?>&popup=0" method="post" name="adminForm1" id="adminForm1">
    	<table bgcolor="#FE9695" align='left' width='60%' cellspacing='5' cellpadding='5'>
    		<tr >
				<td valign="top" align="left" >
					<h1 style="color: black;">
						<?php echo $heading;?>
					</h1>
				</td>
				<td valign="top" align="left" >
					
				</td>
			</tr>
			
			
    	</table>
    	<table  align='left' cellspacing='3' cellpadding='3' width='60%' border="0">
    		<tr>
				<td valign="top" colspan='3' align='right'>
					<?php if($popup==0)
					{?>
					<b>Date:</b> <?php echo JRequest::getVar('maindate');?>	 &nbsp;
					<input type="hidden" value="<?php echo $print ?>" name="printall"  onclick="javascript:window.print();"/>
					<?php } else {?>
					  &nbsp; 
					<input type="submit" value="<?php echo $print ?>" name="printall"  onclick="return printbutton('printall');"/>
					<?php } ?>
				</td>
			</tr>
			<?php 
			$main_cnt=0;
			
			if(count($params)>0)
			{
				for($r=0;$r<count($params);$r++)
				{
					if($params[$r]->data_txt==JRequest::getVar('maindate'))
					{
						
				?>
					<tr>
						<td valign="top"  align='left' style="padding-left: 80px;">
								<?php echo $params[$r]->product_name;?><br><?php echo $params[$r]->product_attribute;?>
						</td>
						<td valign="top" align='left' width='50%' >
								<?php echo $params[$r]->product_quantity;?>
						</td>
						
					</tr> 
				<?php 
					$main_cnt++;
					}
				}
			} 
			if($main_cnt==0){
			?>
				<tr>
					<td valign="top" align="center" colspan='3'>
							No Records
					</td>
				</tr>
			<?php 
			}
			?>
			
		</table>
		<table bgcolor="#fff" align='left' width='80%' cellspacing='2' cellpadding='2'> 
    		<tr >
				<td valign="top" align="left" colspan='2' >
					&nbsp;
				</td>
			</tr>
			<tr >
				<td valign="top" align="left" colspan='2' >
					&nbsp;
				</td>
			</tr>
			
    		</table>
		<table bgcolor="#FE9695" align='left' width='60%' cellspacing='5' cellpadding='5'> 
    		<tr >
				<td valign="top" align="left" colspan='2' >
					&nbsp;
				</td>
			</tr>
			<tr >
				<td valign="top" align="left" colspan='2' >
					&nbsp;
				</td>
			</tr>
			
    	</table>
    	
    	<div class="clr"></div>
		<input type="hidden" name="task" value="<?php echo JRequest::getVar('printoption');?>" />
		<input type="hidden" name="printoption" value="<?php echo JRequest::getVar('printoption');?>" />
		<input type="hidden" name="maindate" value="<?php echo JRequest::getVar('maindate');?>" />
		<input type="hidden" name="view" value="customprint" />
    	</form>
    <?php  
    }
	  
} ?>