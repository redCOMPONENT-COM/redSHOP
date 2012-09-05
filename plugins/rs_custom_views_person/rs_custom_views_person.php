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

class plgredshop_custom_viewsrs_custom_views_person extends JPlugin
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
	function plgredshop_custom_viewsrs_custom_views_person(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            
    }
   	
    /**
    * Plugin method with the same name as the event will be called automatically.
    */
 	function onSelectedPerson()
    { 
    	$heading=JText::_('PRODUCT_ORDERED_PERSON');
		$gobtn= JText::_('CUSTOMVIEW_GO');
    	$option = JRequest::getVar('option');
    	$document = & JFactory::getDocument();
    	$document->addStyleSheet (JURI::base(). 'components/'.$option.'/assets/css/search.css' );
		$document->addScript (JURI::base().'components/'.$option.'/assets/js/search.js');
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
					<input type="button" value="<?php echo $gobtn?>" name="printall" onclick="return submitbutton('printall');"/>	
				</td>
			</tr>		 
		</table>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('printoption');?>" />
	<input type="hidden" name="printoption" value="<?php echo JRequest::getVar('printoption');?>" />
	<input type="hidden" name="view" value="customprint" />
	
	</form>
	<script>
		
		var useroptions = {
    			script:"index3.php?&option=com_redshop&view=search&plgcustomview=1&iscompany=0&json=true&",
    			varname:"input",
    			json:true,
    			shownoresults:true,
    			callback: function (obj)
    			{
    				document.getElementById('user_id').value = obj.id;    				
    			}
    		};
    		
    		var as_json = new bsn.AutoSuggest('searchusernames', useroptions);
		</script>
    <?php  
    }
	
	function onSelectedPersonValue()
    { 
    	$db = JFactory::getDBO();
    	$heading=JText::_('PRODUCT_ORDERED_PERSON');
    	$print=JText::_('PRINT');
    	$time = JRequest::getVar('maindate');
    	$popup =JRequest::getVar('popup', 1);
    	
    	//$dateStart = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time));
	//	$dateEnd = mktime(23,59,59,date('m',$time),date('d',$time),date('Y',$time));
							
    	
    	$sel = "select o.*,p.*,u.*,fd.*,GROUP_CONCAT( CONCAT_WS(' x ',product_quantity,product_name),product_attribute SEPARATOR ' - ' ) as nq from ".$this->_table_prefix."order_item o left outer join ".$this->_table_prefix."product p on o.product_id=p.product_id left outer join ".$this->_table_prefix."users_info u on u.users_info_id=o.user_info_id left outer join  ".$this->_table_prefix."fields_data fd on o.order_item_id=fd.itemid where data_txt = '".$time."' and fd.section =12 group by  o.user_info_id order by u.firstname asc";
    	$db->setQuery($sel);
		$params=$db->loadObjectList();
		
    	?>
  		<style type="text/css">
			.checkout_attribute_title {
		  font-weight: bold;
		}
		
		table.customviewperson {
	width: 70%;
	border-spacing: 1px;
	background-color: #e7e7e7;
	color: #666;
}

table.customviewperson td,
table.customviewperson th { padding: 4px; }

table.customviewperson thead th {
	text-align: center;
	background: #f0f0f0;
	color: #666;
	border-bottom: 1px solid #999;
	border-left: 1px solid #fff;
}

table.customviewperson thead a:hover { text-decoration: none; }

table.customviewperson thead th img { vertical-align: middle; }

table.customviewperson tbody th { font-weight: bold; }

table.customviewperson tbody tr			{ background-color: #fff;  text-align: left; }
table.customviewperson tbody tr.row1 	{ background: #f9f9f9; border-top: 1px solid #fff; }

table.customviewperson tbody tr.row0:hover td,
table.customviewperson tbody tr.row1:hover td  { background-color: #ffd ; }

table.customviewperson tbody tr td 	   { height: 25px; background: #fff; border: 1px solid #fff; }
table.customviewperson tbody tr.row1 td { background: #f9f9f9; border-top: 1px solid #FFF; }

table.customviewperson tfoot tr { text-align: center;  color: #333; }
table.customviewperson tfoot td,
table.customviewperson tfoot th { background-color: #f3f3f3; border-top: 1px solid #999; text-align: center; }

table.customviewperson td.order 		{ text-align: center; white-space: nowrap; }
table.customviewperson td.order span { float: left; display: block; width: 20px; text-align: center; }

table.customviewperson .pagination { display:table; padding:0;  margin:0 auto;	 }
		
		</style>
		<form action="index2.php?json=1&tmpl=component&option=com_redshop&view=customprint&printoption=<?php echo JRequest::getVar('printoption');?>&popup=0" method="post" name="adminForm1" id="adminForm1">
		<table bgcolor="#FFF196" align='left' width='80%' cellspacing='5' cellpadding='5'>
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
    	<table  align='left' cellspacing='3' cellpadding='3' width='80%'>
    		<tr>
				<td valign="top" colspan='3' align='right'>
					<?php if($popup==0)
					{?>
					
					<b>Date:</b> <?php echo JRequest::getVar('maindate');?>	 &nbsp;
					<input type="hidden" value="<?php echo $print ?>" name="printall"  onclick="javascript:window.print();"/>
					<?php } else {?>
					 
					<input type="submit" value="<?php echo $print ?>" name="printall"  onclick="return printbutton('printall');"/>
					<?php } ?>
					<br></br><br></br> 
				</td>
			</tr>
			
    	</table>
    	<table   align='left'  class='customviewperson'>
    		
			<tr>
				<th valign="top" align="left" class="title" >
						<b><?php echo JText::_('NAME');?></b>
				</th>
				<th valign="top" align="left" class="title" >
						<b><?php echo JText::_('USER_COMPANY');?></b>
				</th>
				<th valign="top" align="left" class="title">
						<b><?php echo JText::_('ORDER');?></b>
				</th>
			</tr>
			
			<?php 
			$main_cnt=0;
			
			if(count($params)>0)
			{
				for($r=0;$r<count($params);$r++)
				{

					$sel_shopper_group_name = "select shopper_group_name from ".$this->_table_prefix."shopper_group where shopper_group_id='".$params[$r]->shopper_group_id."'";
			    	$db->setQuery($sel_shopper_group_name);
					$params_shopper_group_name=$db->loadObjectList();
				
				?>
					<tr>
						<td valign="top"  align='left' >
								<?php echo $params[$r]->firstname;?>&nbsp;<?php echo $params[$r]->lastname;?>
						</td>
						<td valign="top"  align='left' >
								<?php if($params[$i]->company_name =="") { echo "-";} else { echo $params[$i]->company_name;}?>
						</td>
						<td valign="top" align='left' width='60%' >
								<?php echo $params[$r]->nq;?>	
						</td>
						
					</tr> 
				<?php 
					$main_cnt++;
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
		<table bgcolor="#FFF196" align='left' width='80%' cellspacing='5' cellpadding='5'> 
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
    	</form>	
    <?php  
    }
	  
} ?>