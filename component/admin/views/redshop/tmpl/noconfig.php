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
defined ( '_JEXEC' ) or die ( 'restricted access' );

$configroot = JPATH_COMPONENT.DS.'helpers';
$configpath = $configroot.DS.'redshop.cfg.php';

$distconfigpath = $configroot.DS.'wizard'.DS.'redshop.cfg.dist.php';

JError::raiseWarning(21,JText::_('CONFIGURATION_FILE_IS_NOT_EXIST'));

if(!is_readable($configroot)){
	JError::raiseWarning(21,JText::_('CONFIGURATION_FILE_IS_NOT_READABLE'));
}

if(!is_writable($configroot)){
	JError::raiseWarning(21,JText::_('CONFIGURATION_FILE_IS_NOT_WRITABLE'));
	echo "<div>".JText::_('PLEASE_CHECK_DIRECTORY_PERMISSON')."</div>";
}else{
	$createConfig = JRequest::getCMD('create','');
	if(isset($createConfig)){
		JFile::copy($distconfigpath,$configpath);
	}
	?>
	<form name="getconfig" action="index.php?option=com_redshop" method="post">
	<table>
		<tr>
			<td><?php echo JText::_('GET_BACK_CONFIG_FILE');?></td>
			<td><input type="image" src="components/com_redshop/assets/images/icon-48-settings.png" name="create" value="<?php echo JText::_('CREATE_CONFIG');?>"></td>
		</tr>
	</table>
	</form>
	<?php
}
?>