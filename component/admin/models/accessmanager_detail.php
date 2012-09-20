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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'mail.php' );

class accessmanager_detailModelaccessmanager_detail extends JModel
{
	var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

	}
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}



	function getaccessmanager(){
		$section = JRequest::getVar('section');
		$query = "SELECT a.* FROM ".$this->_table_prefix."accessmanager AS a "
			."WHERE a.section_name='".$section."'";
		$this->_db->setQuery($query);
	 	$this->_data = $this->_db->loadObjectList();
		return $this->_data;
	}

	/**
	 * Method to store the information
	 *
	 * @access public
	 * @return boolean
	 */
	function store($data)
	{
		$acl		= JFactory::getACL();
		$aclGroups = $acl->sort_groups();
		$groups = $acl->format_groups( $aclGroups,'html',28 );
		$check_section = $this->checksection($data['section']);

		unset($groups ['30']);
		unset($groups ['29']);

		if($check_section ==0){
			if(count($groups)){
				foreach($groups as $groupValue => $groupName)
				{
					if( $groupValue < 23 ):
						continue;
					endif;
					$row =& $this->getTable('accessmanager_detail');
					$row->gid = $groupValue;
					$row->section_name = $data['section'];
				 	$row->view = $data['groupaccess_'.$groupValue]['view'];
					$row->add = $data['groupaccess_'.$groupValue]['add'];
					$row->edit = $data['groupaccess_'.$groupValue]['edit'];
					$row->delete = $data['groupaccess_'.$groupValue]['delete'];




					 if ($row->check()) {

						if (!$row->store()) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}

					  }else {
						$this->setError($this->_db->getErrorMsg());
						return false;
					  }



					  // added for stock room
				       if($row->section_name =='stockroom')
						{

							$row1 =& $this->getTable('accessmanager_detail');
							$row1->gid = $groupValue;
							$row1->section_name = "stockroom_detail";
						 	$row1->view = $data['groupaccess_'.$groupValue]['view'];
							$row1->add = $data['groupaccess_'.$groupValue]['add'];
							$row1->edit = $data['groupaccess_'.$groupValue]['edit'];
							$row1->delete = $data['groupaccess_'.$groupValue]['delete'];
		                    if($row->view ==1 && $row->add ==1)
		                    {
				                     if ($row1->check()) {

										if (!$row1->store()) {
											$this->setError($this->_db->getErrorMsg());
											return false;
										}
								 	 }

		                    } else {

                                  	$row1->view = NULL;


		                          if ($row1->check()) {

										if (!$row1->store()) {
											$this->setError($this->_db->getErrorMsg());
											return false;
										}
								 	 }

		                    }


						    $row_amt =& $this->getTable('accessmanager_detail');
							$row_amt->gid = $groupValue;
							$row_amt->section_name = "stockroom_listing";
						 	$row_amt->view = $data['groupaccess_'.$groupValue]['view'];
							$row_amt->add = $data['groupaccess_'.$groupValue]['add'];
							$row_amt->edit = $data['groupaccess_'.$groupValue]['edit'];
							$row_amt->delete = $data['groupaccess_'.$groupValue]['delete'];
		                    if($row->view ==1 && $row->edit ==1)
		                    {
				                     if ($row_amt->check()) {

										if (!$row_amt->store()) {
											$this->setError($this->_db->getErrorMsg());
											return false;
										}
								 	 }

		                    } else {

                                  	$row_amt->view = NULL;


		                          if ($row_amt->check()) {

										if (!$row_amt->store()) {
											$this->setError($this->_db->getErrorMsg());
											return false;
										}
								 	 }

		                    }



		                    // stockrrom image

					    	$row_img =& $this->getTable('accessmanager_detail');
							$row_img->gid = $groupValue;
							$row_img->section_name = "stockimage";
						 	$row_img->view = $data['groupaccess_'.$groupValue]['view'];
							$row_img->add = $data['groupaccess_'.$groupValue]['add'];
							$row_img->edit = $data['groupaccess_'.$groupValue]['edit'];
							$row_img->delete = $data['groupaccess_'.$groupValue]['delete'];
		                    if($row->view ==1 && $row->edit ==1)
		                    {
				                     if ($row_img->check()) {

										if (!$row_img->store()) {
											$this->setError($this->_db->getErrorMsg());
											return false;
										}
								 	 }

		                    } else {

                                  	$row_img->view = NULL;
                                  	$row_img->add = NULL;


		                          if ($row_img->check()) {

										if (!$row_img->store()) {
											$this->setError($this->_db->getErrorMsg());
											return false;
										}
								 	 }

		                    }


							$row_imgd =& $this->getTable('accessmanager_detail');
							$row_imgd->gid = $groupValue;
							$row_imgd->section_name = "stockimage_detail";
						 	$row_imgd->view = $data['groupaccess_'.$groupValue]['view'];
							$row_imgd->add = $data['groupaccess_'.$groupValue]['add'];
							$row_imgd->edit = $data['groupaccess_'.$groupValue]['edit'];
							$row_imgd->delete = $data['groupaccess_'.$groupValue]['delete'];
		                    if($row_img->view ==1 && $row_img->add ==1)
		                    {
				                     if ($row_imgd->check()) {

										if (!$row_imgd->store()) {
											$this->setError($this->_db->getErrorMsg());
											return false;
										}
								 	 }

		                    } else {

                                  	$row_imgd->view = NULL;
                                  	$row_imgd->add = NULL;


		                          if ($row_imgd->check()) {

										if (!$row_imgd->store()) {
											$this->setError($this->_db->getErrorMsg());
											return false;
										}
								 	 }

		                    }




						}
					  // End


	 			}
			}
		}else{
			foreach($groups as $groupValue => $groupName)
			{
				if( $groupValue < 23 ):
					continue;
				endif;

				$row->gid = $groupValue;
				$row->section_name = $data['section'];
			 	$row->view = $data['groupaccess_'.$groupValue]['view'];
				$row->add = $data['groupaccess_'.$groupValue]['add'];
				$row->edit = $data['groupaccess_'.$groupValue]['edit'];
				$row->delete = $data['groupaccess_'.$groupValue]['delete'];

				if($row->section_name =='stockroom')
				{

					$child_section= "stockroom_detail";
                    if($row->view ==1 && $row->add ==1)
                    {
                      	$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$row->view."',`add` = '".$row->add."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$child_section."' AND `gid` = '".$row->gid."'";
						$this->_db->setQuery($query);
						$this->_db->Query();

                    } else {
                        $child_view =NULL;
                    	$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$child_view."',`add` = '".$row->add."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$child_section."' AND `gid` = '".$row->gid."'";
						$this->_db->setQuery($query);
						$this->_db->Query();

                    }



				    $child_section1= "stockroom_listing";
                    if($row->view ==1 && $row->edit ==1)
                    {
                      	$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$row->view."',`add` = '".$row->add."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$child_section1."' AND `gid` = '".$row->gid."'";
						$this->_db->setQuery($query);
						$this->_db->Query();

                    } else {
                        $child_view1 =NULL;
                    	$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$child_view1."',`add` = '".$row->add."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$child_section1."' AND `gid` = '".$row->gid."'";
						$this->_db->setQuery($query);
						$this->_db->Query();

                    }

				    $child_section2= "stockimage";
                    if($row->view ==1 && $row->edit ==1)
                    {
                      	$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$row->view."',`add` = '".$row->add."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$child_section2."' AND `gid` = '".$row->gid."'";
						$this->_db->setQuery($query);
						$this->_db->Query();

                    } else {
                        $child_view2 =NULL;
                        $child_add2 =NULL;
                    	$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$child_view2."',`add` = '".$child_add2."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$child_section2."' AND `gid` = '".$row->gid."'";
						$this->_db->setQuery($query);
						$this->_db->Query();

                    }

				   $child_section3= "stockimage_detail";
                    if($row->view ==1 && $row->edit ==1)
                    {
                      	$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$row->view."',`add` = '".$row->add."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$child_section3."' AND `gid` = '".$row->gid."'";
						$this->_db->setQuery($query);
						$this->_db->Query();

                    } else {
                        $child_view1 =NULL;
                    	$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$child_view1."',`add` = '".$row->add."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$child_section3."' AND `gid` = '".$row->gid."'";
						$this->_db->setQuery($query);
						$this->_db->Query();

                    }

				}

				$query = "UPDATE ".$this->_table_prefix."accessmanager SET `view` = '".$row->view."',`add` = '".$row->add."',`edit` = '".$row->edit."',`delete` = '".$row->delete."'"
				         ." WHERE `section_name` = '".$row->section_name."' AND `gid` = '".$row->gid."'";
				$this->_db->setQuery($query);
				$this->_db->Query();
 			}

		}

		return $row;
	}

	/**
	 * Method to get section
	 *
	 * @access public
	 * @return boolean
	 */
	function checksection($section){
		$db = JFactory::getDBO();
		$query= " SELECT count(*) FROM ".$this->_table_prefix."accessmanager "
				. "WHERE `section_name` = '".$section."'";
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}


}	?>