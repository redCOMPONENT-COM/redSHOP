<?php 
/**
 * $ModDesc
 * 
 * @version   $Id: $file.php $Revision
 * @package   modules
 * @subpackage  $Subpackage.
 * @copyright Copyright(C) November 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */
 
// no direct access
defined('_JEXEC') or die;
/**
 * Get a collection of categories
 */
class JFormFieldLofspacer extends JFormField
{
	
	/*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'Lofspacer'; 	
	
	/**
	 * fetch Element 
	 */
	protected function getInput()
	{
		if(!defined('_LOFSPACER'))
		{
			define('_LOFSPACER', 1);
			$uri = str_replace(DS,"/",str_replace( JPATH_SITE, JURI::base(), dirname(__FILE__) ));
			$uri = str_replace("://", "_LOFHOLDER", $uri);
			$uri = str_replace("//","/", $uri);
			$uri = str_replace("_LOFHOLDER", "://", $uri);
			$uri = str_replace("/administrator", "", $uri);
			JHTML::stylesheet($uri.'/media/form.css');
		}
		$text = (string)$this->element['text']?(string)$this->element['text']:'';
		return '<div class="lof-header">'.JText::_($text).'</div>';
	}		
}