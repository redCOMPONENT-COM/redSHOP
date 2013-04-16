<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class SubInstaller extends JObject
{

	/**
	 * var &object JInstaller
	 *
	 * Reference to the currently running
	 * main installer.
	 */
	public $_mainSource = null;

	/**
	 * var &object JDatabase
	 *
	 * Reference to the database of the
	 * currently running main installer.
	 */
	public $_mainDb = null;

	/**
	 * var &object JApplication
	 *
	 * Reference to the current application.
	 */
	public $_app = null;

	/**
	 * var array of &object stdClass
	 *
	 * Stores each successfully installed sub extension
	 * in case a rollback has to be performed later.
	 */
	public $_rollback = array();

	/**
	 * var &object JSimpleXMLElement
	 *
	 * Reference to you <subinstall> section in the
	 * main manifest.
	 */
	public $_mysection = null;

	/**
	 * var int
	 *
	 * Our current mode:
	 *   0 = performing uninstall
	 *   1 = performing install
	 */
	public $_mode = 0;

	/**
	 * var int
	 *
	 * Flag to prevent recursion in our
	 * _abort method (If _abort is called from
	 * inside _abort).
	 */
	public $_inabort = 0;

	/**
	 * Constructor
	 *
	 * @access protected
	 **/
	public function __construct()
	{
		// Since we are running from within the current installation,
		// we can use getInstance() to fetch the current installer and thus get access to our own manifest.
		$parent = JInstaller::getInstance();
		$manifest = $parent->getManifest();
		$this->_mainDb =& $parent->getDBO();
		$this->_mainSource = $parent->getPath('source');
		$this->_mysection =& $manifest->document->getElementByPath('subinstall');
		$this->_app = JFactory::getApplication();
	}

	/**
	 * Pushes a message into the main application
	 * message queue.
	 *
	 * @param msg   string   The Message to display.
	 * @param mtype string The message type (message, warning ...)
	 */
	public function _msg($msg, $mtype = 'message')
	{
		if (!empty($msg))
		{
			$this->_app->enqueueMessage('SubInstall: ' . $msg, $mtype);
		}
	}

	/**
	 * Fetches the ID of an extension from the database.
	 *
	 * @param e    object The extension object who's ID should be fetched.
	 * @param core int The value of the iscore field of the extension.
	 *
	 * @return int The ID of the extension, or null if not found.
	 */
	public function _getExtID(&$e, $core)
	{
		if (!is_object($e))
		{
			return load;
		}

		$query = null;

		switch ($e->type)
		{
			case 'module':
				$name = $e->name;

				if (strncmp($name, 'mod_', 4) != 0)
				{
					$name = 'mod_' . $name;
				}

				$query = 'SELECT extension_id FROM #__extensions WHERE name = "' .
					$name . '" AND protected = ' . $core . ' AND client_id = ' .
					$e->client . ' GROUP BY module';
				break;
			case 'plugin':
				$query = 'SELECT extension_id as id FROM #__extensions WHERE element = "' .
					$e->name . '" AND protected = ' . $core . ' AND client_id = ' .
					$e->client . ' AND folder = "' . $e->folder . '" GROUP BY folder, element';
				break;
			case 'component':
				$name = $e->name;

				if (strncmp($name, 'com_', 4) != 0)
				{
					$name = 'com_' . $name;
				}

				$query = 'SELECT extension_id FROM #__extensions WHERE `option` = "' .
					$name . '" AND protected = ' . $core;
				break;
			case 'template':
				// First, check if the template is active. If yes, then we return
				// null, so no uninstall will happen.
				$query = 'SELECT COUNT(*) FROM #__templates_menu WHERE template = "' .
					$e->name . '" AND client_id = ' . $e->client;
				$this->_mainDb->setQuery($query);

				if (!$this->_mainDb->query())
				{
					$this->_abort('Database query failed!');

					return null;
				}

				if ($this->_mainDb->loadResult())
				{
					return null;
				}

				$tdirs = $e->client ? JFolder::folders(JPATH_ADMINISTRATOR . '/templates')
					: JFolder::folders(JPATH_SITE . '/templates');

				// Return a value only if the language dir exists.
				foreach ($tdirs as $tmpl)
				{
					if ($tmpl == $e->name)
					{
						// The template uninstaller uses the name as ID
						return $e->name;
					}
				}

				return null;
				break;
			case 'language':
				// For the default language, we return null so it
				// does not get uninstalled.
				$p = JComponentHelper::getParams('com_languages');

				if ($params->get($e->client, 'en-GB') == $e->name)
				{
					return null;
				}

				$ldir = $e->client ? JLanguage::getLanguagePath(JPATH_ADMINISTRATOR)
					: JLanguage::getLanguagePath(JPATH_SITE);

				// Return a value only if the language dir exists.
				foreach (JFolder::folders($ldir) as $lang)
				{
					if ($lang == $e->name)
					{
						// The language uninstaller uses the path as ID
						return $ldir . DS . $lang;
					}
				}

				return null;
				break;
		}

		if ($query != null)
		{
			$this->_mainDb->setQuery($query);

			if (!$this->_mainDb->query())
			{
				$this->_abort('Database query failed!');

				return null;
			}

			return $this->_mainDb->loadResult();
		}

		return null;
	}

	/**
	 * Publish a plugin
	 */
	public function _publish($id, $type = 'plugin')
	{
		$query = null;

		switch ($type)
		{
			case 'plugin':
				$query = 'UPDATE #__extensions SET enabled = 1 WHERE extension_id = ' . $id;
				break;
		}

		if ($query != null)
		{
			$this->_mainDb->setQuery($query);

			if (!$this->_mainDb->query())
			{
				return $this->_abort('Database query failed!');
			}
		}

		return true;
	}

	/**
	 * Set the iscore field of an extension in order to lock/unlock it
	 * (If set, that extension can not be uninstalled).
	 *
	 * @param id          int      The ID of the extension to modify.
	 * @param type        string The extension type (templates and languages are
	 *                    NOT supported).
	 * @param lock        int    The new value if the iscore field.
	 *
	 * @return            boolean true on success, false otherwise.
	 */
	public function _setcore($id, $type, $lock)
	{
		$query = null;

		switch ($type)
		{
			case 'module':
				$query = 'UPDATE #__extensions SET protected = ' . $lock . ' WHERE extension_id = ' . $id;
				break;
			case 'plugin':
				$query = 'UPDATE #__extensions SET protected = ' . $lock . ' WHERE extension_id = ' . $id;
				break;
			case 'component':
				$query = 'UPDATE #__extensions SET protected = ' . $lock . ' WHERE extension_id = ' . $id;
				break;
		}

		if ($query != null)
		{
			$this->_mainDb->setQuery($query);

			if (!$this->_mainDb->query())
			{
				if ($lock == 0)
				{
					// This is an error only when unlocking
					return $this->_abort('Database query failed!');
				}
			}
		}

		return true;
	}

	/**
	 * Performs a rollback (uninstall) of previously installed
	 * extensions in case of an error.
	 *
	 * @param msg string An optional error message to be displayed.
	 *
	 * @return boolean false always
	 */
	public function _abort($msg = null)
	{
		$this->_inabort++;
		$this->_msg($msg, 'error');

		if ($this->_mode && ($this->_inabort < 2))
		{
			// Only install rollback is supported
			while ($ext = array_pop($this->_rollback))
			{
				$id = $this->_getExtID($ext, $ext->core);

				if ($id != null)
				{
					// If the sub extension was locked (core), then it
					// has to be unlocked first.
					if ($ext->core)
					{
						$this->_setcore($id, $ext->type, 0);
					}

					$subInstaller = new JInstaller;
					$subInstaller->uninstall($ext->type, $id, 1);
				}
			}
		}

		$this->_inabort--;

		return false;
	}

	/**
	 * A simple converter for strings that are supposed to represent
	 * a boolean value ("true" and "1" are considered true, everything else
	 * is considered false).
	 *
	 * @param arg The strung to convert.
	 *
	 * @return boolean the converted value.
	 */
	public function _tobool($arg = null)
	{
		if (!empty($arg))
		{
			return ((strcasecmp($arg, 'true') == 0) || ($arg == '1'));
		}

		return false;
	}

	/**
	 * Parses an <extension> element and performs various sanity checks
	 * and default presets on the attributes.
	 *
	 * @param e &object JSimpleXMLElement The element to parse.
	 *
	 * @return &object                    The object representing the extension.
	 */
	public function &_parseAttributes(&$e)
	{
		$ret = new stdClass;
		$ret->skip = false;

		if ($e->name() != 'extension')
		{
			// skip unknown elements
			$ret->skip = true;
			return $ext;
		}

		$ret->type = $e->attributes('type');
		$ret->name = $e->attributes('name');

		// Optional data is considered a display name
		$ret->dname = $e->data();

		if (empty($ret->dname))
		{
			$ret->dname = $ret->name;
		}

		$ret->folder = $e->attributes('folder');
		$ret->core = $this->_tobool($e->attributes('lock')) ? 1 : 0;
		$ret->publish = $this->_tobool($e->attributes('publish')) ? 1 : 0;
		$optional = $this->_tobool($e->attributes('optional'));
		$client = $e->attributes('client');
		$subdir = $e->attributes('subdir');
		$archive = $e->attributes('archive');

		if (!$ret->type)
		{
			$this->_abort('Missing type attribute in sub extension!');
			return null;
		}

		switch ($ret->type)
		{
			case 'plugin':
				if (!$ret->folder)
				{
					$this->_abort('Missing folder attribute in sub extension!');
					return null;
				}
				$client = 'site';
				break;
			case 'component':
				$client = 'site';
				break;
			case 'module':
				break;
			case 'template':
			case 'language':
				// Don't lock templates and languages (they don't have an iscore field)
				$ret->core = 0;
				break;
			default:
				$this->_abort('Unsupported sub install type "' . $ret->type . '"!');
				return null;
		}

		if (!$ret->name)
		{
			$this->_abort('Missing name attribute in sub extension!');

			return null;
		}

		if (!$client)
		{
			return $this->_abort('Missing client attribute in sub extension!');
		}

		if ($this->_mode)
		{
			if (empty($subdir) && empty($archive))
			{
				$this->_abort('Missing subdir and archive attribute in sub extension!');

				return null;
			}

			if (!empty($subdir))
			{
				$ret->source = $this->_mainSource . DS . $subdir;

				if (!is_dir($ret->source))
				{
					if ($optional)
					{
						$ret->skip = true;

						return $ret;
					}

					$this->_abort('Could not find source directory for sub install "' . $ret->dname . '"!');

					return null;
				}
			}

			if (!empty($archive))
			{
				$ret->source = $this->_mainSource . DS . $archive;

				if (!is_file($ret->source))
				{
					if ($optional)
					{
						$ret->skip = true;

						return $ret;
					}

					$this->_abort('Could not find source archive for sub install "' . $ret->dname . '"!');

					return null;
				}
			}
		}

		$ret->client = 0;

		switch ($client)
		{
			case 'site':
				break;
			case 'admin':
				$ret->client = 1;
				break;
			default:
				$this->_abort('Unsupported sub install client "' . $client . '"!');
				return null;
		}

		return $ret;
	}

	/**
	 * Performs the actual installation of all extensions in the
	 * <subinstall> section of the manifest.
	 *
	 * @return boolean true on success, false otherwise.
	 */
	public function install()
	{
		$this->_mode = 1;

		if (is_a($this->_mysection, 'JSimpleXMLElement'))
		{
			$nodes = $this->_mysection->children();

			if (count($nodes) == 0)
			{
				return true;
			}

			foreach ($nodes as $n)
			{
				$ext = $this->_parseAttributes($n);

				if (!is_object($ext))
				{
					return $this->_abort();
				}

				if ($ext->skip)
				{
					continue;
				}

				$res = null;

				if (is_file($ext->source))
				{
					$res = JInstallerHelper::unpack($ext->source);

					if ($res === false)
					{
						return $this->_abort('Unable to unpack archive');
					}

					$ext->source = $res['dir'];
				}

				$subInstaller = new JInstaller;
				$result = $subInstaller->install($ext->source);

				if ($res)
				{
					// Cleanup temporary extract dir.
					if (is_dir($res['extractdir']))
					{
						JFolder::delete($res['extractdir']);
					}
				}

				$smsg = $subInstaller->get('message');
				$msg = $subInstaller->get('extension.message');

				if (!empty($msg))
				{
					echo $msg;
				}

				if ($result)
				{
					// If a plugin is to be published, then
					// do this now.
					if ($ext->publish && ($ext->type == 'plugin'))
					{
						$id = $this->_getExtID($ext, 0);
						if ($id != null)
						{
							if (!$this->_publish($id))
								return false;
						}
					}

					// If the sub extension is to be locked (core), then
					// do this now.
					if ($ext->core)
					{
						$id = $this->_getExtID($ext, 0);
						if ($id != null)
						{
							$this->_setcore($id, $ext->type, 1);
						}
					}

					array_push($this->_rollback, $ext);
					$this->_msg('Successfully installed ' . $ext->type . ' "' . $ext->dname . '".');
				}
				else
				{
					return $this->_abort($smsg);
				}
			}
		}

		return true;
	}

	/**
	 * Performs the actual deinstallation of all extensions in the
	 * <subinstall> section of the manifest.
	 *
	 * @return boolean true on success, false otherwise.
	 */
	public function uninstall()
	{
		$this->_mode = 0;

		if (is_a($this->_mysection, 'JSimpleXMLElement'))
		{
			$nodes = $this->_mysection->children();

			if (count($nodes) == 0)
			{
				return true;
			}

			foreach ($nodes as $n)
			{
				$ext = $this->_parseAttributes($n);

				if (!is_object($ext))
				{
					return $this->_abort();
				}

				$id = $this->_getExtID($ext, $ext->core);

				if ($id != null)
				{
					// If the sub extension was locked (core), then it
					// has to be unlocked first.
					if ($ext->core)
					{
						$this->_setcore($id, $ext->type, 0);
					}

					$subInstaller = new JInstaller;
					$result = $subInstaller->uninstall($ext->type, $id, 1);
					$msg = $subInstaller->get('message');
					$this->_msg($msg, $result ? 'message' : 'warning');
					$msg = $subInstaller->get('extension.message');

					if (!empty($msg))
					{
						echo $msg;
					}

					if ($result)
					{
						$this->_msg('Successfully removed ' . $ext->type . ' "' . $ext->dname . '".');
					}
				}
			}
		}

		return true;
	}
}
