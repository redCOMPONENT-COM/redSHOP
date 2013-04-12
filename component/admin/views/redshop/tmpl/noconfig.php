<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('restricted access');

$configroot = JPATH_COMPONENT . '/helpers';
$configpath = $configroot . '/redshop.cfg.php';

$distconfigpath = $configroot . '/wizard/redshop.cfg.dist.php';

JError::raiseWarning(21, JText::_('COM_REDSHOP_CONFIGURATION_FILE_IS_NOT_EXIST'));

if (!is_readable($configroot))
{
	JError::raiseWarning(21, JText::_('COM_REDSHOP_CONFIGURATION_FILE_IS_NOT_READABLE'));
}

if (!is_writable($configroot))
{
	JError::raiseWarning(21, JText::_('COM_REDSHOP_CONFIGURATION_FILE_IS_NOT_WRITABLE'));
	echo "<div>" . JText::_('COM_REDSHOP_PLEASE_CHECK_DIRECTORY_PERMISSON') . "</div>";
}
else
{
	$createConfig = JRequest::getCMD('create', '');
	if (isset($createConfig))
	{
		JFile::copy($distconfigpath, $configpath);
	}
	?>
	<form name="getconfig" id="getconfig" action="index.php?option=com_redshop" method="post">
		<table>
			<tr>
				<td><?php echo JText::_('COM_REDSHOP_GET_BACK_CONFIG_FILE');?></td>
				<td><input type="image" src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>icon-48-settings.png"
				           name="create" value="<?php echo JText::_('COM_REDSHOP_CREATE_CONFIG'); ?>"></td>
			</tr>
		</table>
	</form>
<?php
}
?>