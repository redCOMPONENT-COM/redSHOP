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
// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'mail.php' );

class catalogModelcatalog extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_product = null; // product data
	var $_table_prefix = null;
	var $_template = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$this->setId((int)JRequest::getInt('pid',  0));

	}
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	/*function _buildQuery()
	{
		$layout = JRequest::getVar('layout','default');

		if ($layout == 'sample')
			$query = "SELECT * FROM  ".$this->_table_prefix."template WHERE template_section='product_sample '  and published = 1";
		else
			$query = "SELECT * FROM  ".$this->_table_prefix."template WHERE template_section='catalog '  and published = 1";

		return $query;
	}*/
	function catalog_request($data)
	{
		$row =& $this->getTable('catalog_request');
		$redshopMail = new redshopMail();
		$option = JRequest::getVar('option');

		$query = "SELECT * FROM  ".$this->_table_prefix."media WHERE media_section='catalog' and 	media_type='document' AND section_id = ".$data['catalog_id']."  and published = 1";

		$catalog_data = $this->_getList( $query );

		$attachment =array();

		for($p=0;$p<count($catalog_data);$p++)
		{
			$attachment[]=JPATH_SITE.DS.'components/'.$option.'/assets/document/catalog/'.$catalog_data[$p]->media_name;
		}
		////////////////////////// Send mail /////////////////////////////

		$mailbody = "";
		$subject = "";
		$mailbcc=NULL;
		$maildata = $redshopMail->getMailtemplate(0,"catalog");
		if(count($maildata)>0)
		{
			$maildata = $maildata[0];
			$mailbody = $maildata->mail_body;
			$subject = $maildata->mail_subject;
			if(trim($maildata->mail_bcc)!="")
			{
				$mailbcc= explode(",",$maildata->mail_bcc);
			}
		}
		$mailbody = str_replace("{name}",$data['name_2'],$mailbody);

		$config		= &JFactory::getConfig();

		$from		= $config->getValue('mailfrom');

		$fromname	= $config->getValue('fromname');


		//JUtility::sendMail($from, $fromname, $recipient, $subject, $message, true, null, null, $attachment);
		if(JUtility::sendMail($from, $fromname,$data["email_address"], $subject, $mailbody, $mode=1,NULL, $mailbcc,$attachment))
		{
		 	////////////////////////// Send mail /////////////////////////////

			$authorize	=& JFactory::getACL();
			$user		= JFactory::getUser(0);
			$username	= $user->get('username');
			$u['name'] = $data['name_2'];
			$u['email'] = $data['email_address'];
			$u['username'] = $data['email_address'];
			$better_token = uniqid(md5(rand()), true);
			$password = substr($better_token,0,10);
			$u['password'] = md5($password);
			$u['password2'] = md5($password);

			$newUsertype = 'Registered';
			$user->set('id', 0);
			$user->set('usertype', '');
			$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));

		     $user->bind($u) ;
			 $user->save() ;


			if (!$row->bind($data)) {

				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$row->email = $data["email_address"];
			$row->name = $data["name_2"];

			if (!$rw=$row->store()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return $rw;
		}else{
			return false;
		}


	}
	function catalog_sample_send($data)
	{
		$row =& $this->getTable('sample_request');

		$authorize	=& JFactory::getACL();


		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}



		if (!$rw=$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$usr		= JFactory::getUser(0);
		$username	= $usr->get('username');
		$u['name'] = $data['name'];
		$u['email'] = $data['email'];
		$u['username'] = $data['email'];
		$better_token = uniqid(md5(rand()), true);
		$password = substr($better_token,0,10);
		$u['password'] = md5($password);
		$u['password2'] = md5($password);

		$newUsertype = 'Registered';
		//$usr->set('id', 0);
		$usr->set('usertype', '');
		$usr->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));

	     $usr->bind($u) ;
		 $usr->save() ;

		return $row;
	}

	function getData()
	{
		$redTemplate = new Redtemplate();
		$layout = JRequest::getVar('layout','default');
		if (empty( $this->_data ))
		{
			if ($layout == 'sample')
			{
				$this->_data = $redTemplate->getTemplate("product_sample");
			}
			else
			{
				$this->_data = $redTemplate->getTemplate("catalog");
			}
		}
		return $this->_data;
	}
	function getTemplate()
	{
		$option = JRequest::getVar('option');

		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'helpers'.DS.'extra_field.php');
		$extra_field = new extra_field();
		$address_fields= $extra_field->list_all_field(9,0,'',1);

		$url= JURI::base();

		$tdata=$this->getData();

		$template_data=$tdata[0]->template_desc ;

		// catalog select box start

		$catalogsel = "";

		$catalog_select = array();

		$query = "SELECT catalog_id as value,catalog_name as text FROM ".$this->_table_prefix."catalog WHERE published = 1";

		$catalog = $this->_getList($query);

		$optionselect = array();
		$optionselect[]   	= JHTML::_('select.option', '0',JText::_('SELECT'));

		$catalog_select = array_merge($optionselect,$catalog);

		$catalogsel  = JHTML::_('select.genericlist',$catalog_select,  'catalog_id', 'class="inputbox" size="1" ' , 'value', 'text',  0 );

		$template_data =str_replace("{catalog_select}",$catalogsel,$template_data);
		// end

		$txt_name ='<input type="text" name="name_2" id="name" />';

		$template_data =str_replace("{name}",$txt_name,$template_data);

		$email_address ='<input type="text" name="email_address" id="email_address" />';

		$template_data =str_replace("{email_address}",$email_address,$template_data);

		$submit_button_catalog ="<input type=\"submit\" name=\"Send\" id=\"Send\" value=\"Send\" >";

		$template_data =str_replace("{submit_button_catalog}",$submit_button_catalog,$template_data);

	 	$query = "SELECT * FROM ".$this->_table_prefix."catalog_sample WHERE published = 1";

		$catalog_sample = $this->_getList($query);

		$saple_data="";

		for($k=0;$k<count($catalog_sample);$k++)
		{
			$saple_data .=$catalog_sample[$k]->sample_name."<br>";

			$query = "SELECT * FROM ".$this->_table_prefix."catalog_colour WHERE  sample_id =".$catalog_sample[$k]->sample_id;

			$catalog_colour = $this->_getList($query);

			$saple_data .="<table cellpadding='0' border='0' cellspacing='0'><tr>";
			$saple_check ="<tr>";
			for($c=0;$c<count($catalog_colour);$c++)
			{

			$saple_data .="<td style='padding-right:2px;'>";
			if($catalog_colour[$c]->is_image==1)
			$saple_data .="<img src='".$catalog_colour[$c]->code_image."' border='0'  width='27' height='27'/><br>";
			else
			$saple_data .='<div style="background-color:'.$catalog_colour[$c]->code_image.';width: 27px; height:27px; "></div> ';

			$saple_check .="<td><input type='checkbox' name='sample_code[]' value='".$catalog_colour[$c]->colour_id."' ></td>";
			$saple_data .="</td>";
			}
			$saple_check .="</tr>";
			$saple_data .="</tr>".$saple_check."<tr><td>&nbsp;</td></tr></table>";
		}

		$template_data =str_replace("{product_samples}",$saple_data,$template_data);

//		$myfield ="<table class='admintable' border='0'><tr><td align='right'>".JText::_('Name');
//		$myfield .="</td><td><input type=\"text\" name=\"sample_name\" id=\"sample_name\" /></td></tr><tr><td align='right'>".JText::_('Eail');
//		$myfield .="</td><td><input type=\"text\" name=\"sample_email\" id=\"sample_email\" /></td></tr>";
//		$myfield .=$address_fields;
//		$myfield .='</table>';

		$myfield ="<table class='admintable' border='0'>";
		$myfield .=$address_fields;
		$myfield .='</table>';

		$template_data =str_replace("{address_fields}",$myfield,$template_data);

		$submit_button_sample ="<input type=\"submit\" name=\"Send\" id=\"Send\" value=\"Send\" >";

		$template_data =str_replace("{submit_button_sample}",$submit_button_sample,$template_data);


		return $template_data;


	}
	function NewsLetter_subscribe($data){


		$query = "SELECT subscription_id from ".$this->_table_prefix."newsletter_subscription WHERE email = '".$data['email']."' and newsletter_id = '".DEFAULT_NEWSLETTER."'";
		$data_count = $this->_getListCount($query);

		if (isset($data['newsletter_signup']) && $data_count == 0){

			$query = "INSERT into ".$this->_table_prefix."newsletter_subscription
					(`subscription_id`,`user_id`,`date`,`newsletter_id`,`name`,`email`,`published`)
					VALUES ('','0','".$data['registerdate']."','".DEFAULT_NEWSLETTER."','".$data['name']."','".$data['email']."','1')";

			$this->_db->setQuery($query);
			$this->_db->Query();
		}

		return true;
	}
}