<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');

class RedshopModelZip_import extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_table = null;

	/** @var object JTable object */
	public $_url = null;

	public function getData()
	{
		$app = JFactory::getApplication();

		$this->getzipfilenames();

		$this->install();
		session_unregister("filename");
		session_unregister("zipno");
		$msg = JText::_('COM_REDSHOP_REDSHOP_REMOTLY_UPDATED');
		$app->redirect(JURI::base() . 'index.php?option=com_redshop', $msg);
	}

	public function getzipfilescount()
	{
		$url = Redshop::getConfig()->get('REMOTE_UPDATE_DOMAIN_URL') . "index.php?option=com_reviews&domainname=" . JUri::getInstance()->toString(array('host'));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		preg_match_all("#<span id='zip'>(.*?)</span>#is", $content, $out);
		$content = trim($out[0][0]);
		$response = curl_getinfo($ch);
		print_r($response);
		JFactory::getApplication()->close();
		curl_close($ch);
		$x = count(explode(",", $content));

		return $x;
	}

	public function getzipfilenames()
	{
		$user = JFactory::getUser();
		$url = Redshop::getConfig()->get('REMOTE_UPDATE_DOMAIN_URL') . "index.php?option=com_remoteupdate&view=getcomponent&redusername=" .
			$user->username . "&reddomain=" . JURI::base() . "";

		$ch = curl_init();

		// Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// Set the URL
		curl_setopt($ch, CURLOPT_URL, $url);

		// Execute the fetch
		$data = curl_exec($ch);

		//Close the connection
		curl_close($ch);
		ob_clean();
		$fp = fopen(JPATH_ROOT . '/tmp/com_jcomments_new.zip', 'w');
		fwrite($fp, $data);
		fclose($fp);

		$filename = JURI::base() . '/tmp/com_jcomments_new.zip';
		$_SESSION['filename'][0] = $filename;
	}

	// Related product sync
	public function install()
	{
		$app = JFactory::getApplication();
		$fileType = "url";
		switch ($fileType)
		{
			case 'url':
				$package = $this->_getPackageFromUrl();
				break;

			default:
				$this->setState('message', 'No Install Type Found');

				return false;
				break;
		}

		// Was the package unpacked?
		if (!$package)
		{
			$this->setState('message', 'Unable to find install package');
			$msg = JText::_('COM_REDSHOP_REDSHOP_REMOTELY_UPDATED');
			$app->redirect(JURI::base() . "index.php?option=com_redshop", $msg);
		}

		// Get an installer instance
		$installer = JInstaller::getInstance();

		// Install the package
		if (!$installer->install($package['dir']))
		{
			?>
			<script type="text/javascript" language="javascript">
				window.location = "index.php?option=com_redshop&view=zip_import&msg=err";
			</script>
		<?php
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_REDSHOP_REMOTELY_UPDATED');
			$app->redirect(JURI::base() . "index.php?option=com_redshop", $msg);

		}

		// Set some model state values
		$app->enqueueMessage($msg);
		$this->setState('name', $installer->get('name'));
		$this->setState('result', $result);
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));

		// Cleanup the install files
		if (!JFile::exists($package['packagefile']))
		{
			$config = JFactory::getConfig();
			$package['packagefile'] = $config->get('tmp_path') . '/' . $package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
		?>
		<script type='text/javascript' language='javascript'>
			window.location = "index.php?option=com_redshop&view=zip_import&msg=suc";
		</script>
		<?php
		return $result;
	}


	/**
	 * Install an extension from a URL
	 *
	 * @static
	 * @return boolean True on success
	 * @since 1.5
	 */
	public function _getPackageFromUrl()
	{
		// Get the URL of the package to install
		$url = trim(strip_tags(str_replace('administrator//', '', $_SESSION['filename'][0])));

		// Did you give us a URL?

		if (!$url)
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_PLEASE_ENTER_A_URL'));
			?>
			<script type='text/javascript' language='javascript'>
				window.location = "index.php?option=com_redshop&view=zip_import&msg=err";
			</script>
		<?php
		}

		// Download the package at the URL given
		$p_file = JInstallerHelper::downloadPackage(trim(strip_tags($url)));


		// Was the package downloaded?
		if (!$p_file)
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_INVALID_URL'));

			?>
			<script type='text/javascript' language='javascript'>
				//window.location="index.php?option=com_redshop&view=zip_import&msg=err";
			</script>
		<?php
		}

		$config = JFactory::getConfig();
		$tmp_dest = $config->get('tmp_path');

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest . '/' . $p_file);

		?>
		<script type='text/javascript' language='javascript'>
			//window.location="index.php?option=com_redshop&view=zip_import";
		</script>
		<?php
		return $package;
	}


	public function _getPackageFromFolder()
	{
		$p_dir = JFactory::getApplication()->input->getString('install_directory');
		$p_dir = JPath::clean($p_dir);

		// Did you give us a valid directory?
		if (!is_dir($p_dir))
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_PLEASE_ENTER_A_PACKAGE_DIRECTORY'));

			return false;
		}

		// Detect the package type
		$type = JInstallerHelper::detectType($p_dir);

		// Did you give us a valid package?
		if (!$type)
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('COM_REDSHOP_PATH_DOES_NOT_HAVE_A_VALID_PACKAGE'));

			return false;
		}

		$package['packagefile'] = null;
		$package['extractdir'] = null;
		$package['dir'] = $p_dir;
		$package['type'] = $type;

		return $package;
	}
}
