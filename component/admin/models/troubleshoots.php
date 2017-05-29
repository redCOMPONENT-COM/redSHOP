<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop troubleshoots model
 *
 * @package     Redshop.library
 * @subpackage  Model
 * @since       2.1
 */
class RedshopModelTroubleshoots extends RedshopModel
{

	/**
	 * @var    object
	 *
	 * @since  2.0.6
	 */
	private $plugin = null;

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 *
	 * @since  2.0.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config = array());

		$this->plugin = JPluginHelper::getPlugin('system', 'mvcoverride');

		// Convert plugin prams
		if ($this->plugin)
		{
			$this->plugin->params = new JRegistry($this->plugin->params);
		}
	}

	/**
	 * Get data
	 *
	 * @return bool|mixed
	 *
	 * @since  2.1
	 */
	public function getItems()
	{
		$jsonFile = JPATH_ADMINISTRATOR . '/components/com_redshop/assets/checksum.md5.json';

		$list = array();

		// Make sure file exists
		if (JFile::exists($jsonFile))
		{
			$items      = json_decode(file_get_contents($jsonFile));
			$extensions = array();
			if ($items)
			{
				foreach ($items as $index => $item)
				{
					$item = new RedshopTroubleshootItem($item);

					// Store modules & plugins list
					if (($item->getExtension() == 'plugin' || $item->getExtension() == 'module') && $item->getName())
					{
						$extensions[$item->getExtension()][$item->getName()] = $item;
					}
					$list['items'][] = $item;
				}

				$list['extensions'] = $extensions;
				$list['requirements'] = new RedshopTroubleshootRequirements();

				return $list;
			}
		}

		return false;
	}
}
