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

global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang, $database,
    $mosConfig_mailfrom, $mosConfig_fromname;
    define( '_JEXEC', 1 );
        /*** access Joomla's configuration file ***/
      $my_path = dirname(__FILE__);
        
        if( file_exists($my_path."/../../../../../../configuration.php")) {  
            $absolute_path = dirname( $my_path."/../../../../../../configuration.php" );
            require_once($my_path."/../../../../../../configuration.php");
        }
        elseif( file_exists($my_path."/../../configuration.php")){
            $absolute_path = dirname( $my_path."/../../configuration.php" );
            require_once($my_path."/../../configuration.php");
        }
        elseif( file_exists($my_path."/../../../configuration.php")){
            $absolute_path = dirname( $my_path."/../../../configuration.php" );
            require_once($my_path."/../../../configuration.php");
        }
        elseif( file_exists($my_path."/configuration.php")){
            $absolute_path = dirname( $my_path."/configuration.php" );
            require_once( $my_path."/configuration.php" );
        }
        else {
            die( "Joomla Configuration File not found!" );
        }
        
         $absolute_path = realpath( $absolute_path );
		
        // Set up the appropriate CMS framework
       
			define( '_JEXEC', 1 );
			define( 'JPATH_BASE', $absolute_path );
			define( 'DS', DIRECTORY_SEPARATOR );
			
			// Load the framework
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );

			// create the mainframe object
			$mainframe = & JFactory::getApplication( 'site' );
			
			// Initialize the framework
			$mainframe->initialise();
			
			// load system plugin group
			JPluginHelper::importPlugin( 'system' );
			
			// trigger the onBeforeStart events
			$mainframe->triggerEvent( 'onBeforeStart' );
			$lang =& JFactory::getLanguage();
			$mosConfig_lang = $GLOBALS['mosConfig_lang']          = strtolower( $lang->getBackwardLang() );
			// Adjust the live site path
			
		/*** END of Joomla config ***/  
			
			//$subscriber = @basename(urldecode($_REQUEST['subscriber']));
			//$newsletter = @basename(urldecode($_REQUEST['newsletter']));
			$tracker_id = @basename(urldecode($_REQUEST['tracker_id']));
			$db = JFactory::getDBO();
			$query = "UPDATE `#__redshop_newsletter_tracker` SET `read` = '1' WHERE tracker_id = '".$tracker_id."' ";
							
			$db->setQuery($query);
			$db->query();
			
			$uri =& JURI::getInstance();								
			$requesturl= $uri->toString();
			$url = parse_url($requesturl);
			
			$img = $url['scheme']."://".$url['host'].'/components/com_redshop/assets/images/spacer.gif';		
			header ("Content-type: image/gif");
			readfile($img);
			
							 
?>