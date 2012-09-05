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
 
class JInstallerShipping extends JObject
{
 
	function __construct(&$parent)
	{
		$this->parent =& $parent;
	}

 
	function install()
	{
		// Get a database connector object
		$db =& $this->parent->getDBO();

		// Get the extension manifest object
		$manifest =& $this->parent->getManifest();
		$this->manifest =& $manifest->document;
 
		// Set the extensions name
		$name =& $this->manifest->getElementByPath('name');
		$name = JFilterInput::clean($name->data(), 'string');
		$this->set('name', $name);
		
 

		// Get the component description
		$description = & $this->manifest->getElementByPath('description');
		if (is_a($description, 'JSimpleXMLElement')) {
			$this->parent->set('message', $description->data());
		} else {
			$this->parent->set('message', '' );
		}

		/*
		 * Backward Compatability
		 * @todo Deprecate in future version
		 */
		$type = $this->manifest->attributes('type');

		// Set the installation path
		$element =& $this->manifest->getElementByPath('files');
		if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
			$files =& $element->children(); 
			foreach ($files as $file) {
				if ($file->attributes($type)) {
					$pname = $file->attributes($type);
					break;
				}
			}
		}
		
		$shipping_class = $this->manifest->getElementByPath('shipping_class');
	 	if (is_a($shipping_class, 'JSimpleXMLElement')) {
			$this->set('shipping_class', $shipping_class->data());
			$shipping_class = $shipping_class->data();
		} else {
			$this->set('shipping_class', '' );
		}
		
		 $shipping_method_code = $this->manifest->getElementByPath('shipping_method_code');
	 	if (is_a($shipping_method_code, 'JSimpleXMLElement')) {
			$this->set('shipping_method_code', $shipping_method_code->data());
			$shipping_method_code = $shipping_method_code->data();
		} else {
			$this->set('shipping_method_code', '' );
		}
		 
		 
 
		 
		if (!empty ($pname) && !empty($shipping_class)) {  
			$this->parent->setPath('extension_root',JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'shippings');
		} else {
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('No plugin file or class name specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the plugin directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * If we created the plugin directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		if ($this->parent->parseFiles($element, -1,$pname) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

	  /**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Check to see if a plugin by the same name is already installed
		 $query = 'SELECT `shipping_id`' .
				' FROM `#__'.TABLE_PREFIX.'_shipping_method`' .
				' WHERE plugin = "'.$pname.'" OR shipping_class = '.$db->Quote($shipping_class) 
				 ;
				 
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$shipping_id = $db->loadResult();

		// Was there a module already installed with the same name?
		if ($shipping_id) {

			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Plugin').' "'.$pname.'" '.JText::_('already exists!'));
				return false;
			}

		} else {
			//$row =& JTable::getTable('shipping_detail'); 
			$row =& JTable::getInstance('shipping_detail', 'Table');
	  		$row->shipping_name = $this->get('name');
			$row->shipping_class = $this->get('shipping_class');
			$row->shipping_method_code = $this->get('shipping_method_code');
			$row->published = 1;			 
		 	$row->params = $this->parent->getParams();
 			$row->plugin = $pname;
		   if (!$row->store()) {
				// Install failed, roll back changes
			 $this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			 	return false;
			 }

			 	$this->parent->pushStep(array ('type' => 'plugin', 'shipping_id' => $row->shipping_id));
		}

 
		if (!$this->parent->copyManifest(-1)) {
			// Install failed, rollback changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Could not copy setup file'));
			return false;
		}
		return true;
	}
 
	function uninstall($id, $clientId )
	{  
		// Initialize variables
		$row	= null;
		$retval = true;
		$db		=& $this->parent->getDBO();

		$row =& JTable::getInstance('shipping_detail', 'Table'); 
		if ( !$row->load((int) $clientId) ) {
			JError::raiseWarning(100, JText::_('ERRORUNKOWNEXTENSION'));
			return false;
		}
 
		// Set the plugin root path
		$this->parent->setPath('extension_root',JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'shippings');

		 $manifestFile = JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'shippings'.DS.$row->plugin.'.xml';
		if (file_exists($manifestFile))
		{
			$xml =& JFactory::getXMLParser('Simple');

			// If we cannot load the xml file return null
			if (!$xml->loadFile($manifestFile)) {
				JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.JText::_('Could not load manifest file'));
				return false;
			}

			 
			$root =& $xml->document;
			if ($root->name() != 'install' && $root->name() != 'mosinstall') {
				JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.JText::_('Invalid manifest file'));
				return false;
			}

			 
			JFile::delete($manifestFile);
 
		} else {
			JError::raiseWarning(100, 'Plugin Uninstall: Manifest File invalid or not found');
			//return false;
		}

		// Now we will no longer need the plugin object, so lets delete it
		$row->delete($row->shipping_id);
		

		// If the folder is empty, let's delete it
		$files = JFolder::files($this->parent->getPath('extension_root').DS.$row->plugin);
		
	 
		 $this->parent->getPath('extension_root').DS.$row->plugin; 
		//if (!count($files)) {
			JFolder::delete($this->parent->getPath('extension_root').DS.$row->plugin);
		//}
		unset ($row);
		return $retval;
	}
 
	function _rollback_plugin($arg)
	{
		// Get database connector object
		$db =& $this->parent->getDBO();

		// Remove the entry from the #__plugins table
		$query = 'DELETE' .
				' FROM `#__'.TABLE_PREFIX.'_shipping_method`' .
				' WHERE shipping_id='.(int)$arg['shipping_id'];
		$db->setQuery($query);
		return ($db->query() !== false);
	}
}