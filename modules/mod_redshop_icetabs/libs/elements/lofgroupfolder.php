<?php 
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage
 * @copyright	Copyright(C) JAN 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @license		GNU General Public License version 2
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.parameter');
/**
 * Get a collection of categories
 */
class JFormFieldLofgroupfolder extends JFormField
{	
	/*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'lofgroupfolder';
	
	/**
	 * fetch Element 
	 */
	function getInput()
	{
		jimport('joomla.filesystem.folder');
		// path to images directory
		$path		= JPATH_ROOT.DS.$this->element['directory'];
		$filter		= $this->element['filter'];
		$exclude	= $this->element['exclude'];
		$folders	= JFolder::folders($path, $filter);
		$options 	= array();
		$form 		= array();
		$mparams 	= $this->getModuleInfo();
		$tmpp 		= new JRegistry;
		
		$tmpp->loadJSON($mparams);
		$i = 1;
		
		foreach($folders as $key=> $folder)
		{
			if(!empty($exclude))
			{
				if(preg_match(chr(1) . $exclude . chr(1), $folder))
				{
					//continue;
				}
			}
			$options[] 	= JHTML::_('select.option', $folder, $folder);
			$formName 	= "jform".$i;
			$tmp 	= $this->renderForm($folder, $mparams, $formName);
			$tmpn 	= $this->fieldname.'_'.$folder.'_status';
		//	echo '<pre>'.print_r($tmpp,1); die;
			$value = $tmpp->get($this->fieldname.'_'.$folder.'_status', 0);
			if($tmp)
			{
				$input	= '<input type="hidden" class="lof-status" value="'.$value.'" id="'.$tmpn.'" name="jfrom[params]['.$name.'_'.$folder.'_status]">';
				$f 		= '<div class="lof-status '.($key%2 ==0?'lof-even':'lof-odd').'">'.$input.'<span class="lof-label">'.ucfirst($folder).'</span>'."</div>";	
				$f 		.= '<fieldset class="'.$tmpn.' '.($key%2 ==0?'lof-even':'lof-odd').' ">'.$tmp.'</fieldset>';
				$form[] = $f;
				$i++;
			}
		}
	
		if(!$this->element['hide_none'])
		{
			array_unshift($options, JHTML::_('select.option', '-1', '- '.JText::_('Do not use').' -'));
		}

		if(!$this->element['hide_default'])
		{
			array_unshift($options, JHTML::_('select.option', '', '- '.JText::_('Use Default Theme').' -'));
		}
		
		if(!defined('ADD_MEDIA_CONTROL'))
		{
			define('ADD_MEDIA_CONTROL', 1);
			$uri = str_replace(DS,"/",str_replace(JPATH_SITE, JURI::base(), dirname(__FILE__)));
			$uri = str_replace("/administrator", "", $uri);

			JHTML::stylesheet($uri."/media/style.css");
			JHTML::script($uri."/media/script.js");
		}
		//echo "<pre>";
	//	print_r($form);die();
		return implode('',$form);
		//return JHTML::_('select.genericlist',  $options, ''.$this->name.'[]', 'class="inputbox"', 'value', 'text', $value, $this->name).implode('',$form);
	}
	
	function getModuleInfo()
	{
		$moduleId =(int)JRequest::getVar('id')?(int)JRequest::getVar('id'):JRequest::getVar('extension_id');
		//get module as an object
		$moduleId  = is_array($moduleId)?$moduleId[0]:$moduleId ;
		//$db =& JFactory::getDBO(); 
		//$db->setQuery("SELECT * FROM #__plugins WHERE id='$moduleId' "); 
		//$obj = $db->loadObject();
		$table = JTable::getInstance("Extension", "JTable");
		// Attempt to load the row.
		$return = $table->load($moduleId);
		return $table->params;
	}
	/**
	 * render paramters form
	 *
	 * @return string
	 */
	function renderForm($theme, $params='', $fileName='params')
	{
		// look up configuration file which build-in this plugin or the tempate used.
		$path =(dirname(dirname(__FILE__))).DS.'social'.DS.$theme.DS.'params.xml';
		if(file_exists($path))
		{
			$options = array("control"=>"jform");
			$tmpp = new JRegistry;
			$tmpp->loadJSON($params);

			$paramsForm = &JForm::getInstance($fileName, $path, $options);
			$paramsForm->bind($tmpp);
			$content = $this->loadFormView($paramsForm);
			return $content;
		}
		
		return '';
	}
	function loadFormView($paramsForm)
	{
		$_body = JResponse::getBody();
		ob_start();
		
		$fieldSets = $paramsForm->getFieldsets('params');
		foreach($fieldSets as $name => $fieldSet) :
				if(isset($fieldSet->description) && trim($fieldSet->description)) :
					echo '<p class="tip">'.JText::_($fieldSet->description).'</p>';
				endif;
				
			$hidden_fields = ''; 
			foreach($paramsForm->getFieldset($name) as $field) : 
			 if(!$field->hidden) : ?>
				<li>
					<?php echo $paramsForm->getLabel($field->fieldname,$field->group); ?>
					<?php echo $paramsForm->getInput($field->fieldname,$field->group); ?>
				</li>
				<?php else : $hidden_fields.= $paramsForm->getInput($field->fieldname,$field->group); 
			 endif;
				 endforeach; 
			 echo $hidden_fields; 
		endforeach;
		
		$content = ob_get_clean();
		JResponse::setBody($_body);
		return $content;
	}
}