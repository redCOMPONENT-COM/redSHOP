<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Troubleshoot item
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Troubleshoot requirements class
 *
 * @package     Redshop.Library
 * @subpackage  Troubleshoot
 * @since       2.1
 */
class RedshopTroubleshootRequirements
{
	/**
	 * @var     string
	 *
	 * @since   2.0.6
	 */
	private $requirementsFile;
	/**
	 * @var     object
	 *
	 * @since   2.0.6
	 */
	public $requirements;

	/**
	 * RedshopTroubleshootRequirements constructor.
	 *
	 * @since   2.0.6
	 */
	public function __construct()
	{
		$this->requirementsFile = JPATH_ADMINISTRATOR . '/components/com_redshop/assets/system_requirements.json';

		// Load requirements file
		if (JFile::exists($this->requirementsFile))
		{
			$buffer             = file_get_contents($this->requirementsFile);
			$this->requirements = json_decode($buffer);
		}

		$this->phpChecking();
	}

	/**
	 *
	 *
	 * @since   2.0.6
	 */
	public function phpChecking()
	{
		if (is_object($this->requirements))
		{
			// Check php requirements
			foreach ($this->requirements->php as $key => $data)
			{
				// Get current php value
				$currentValue                                = ini_get($key);
				$this->requirements->php->$key->currentValue = $currentValue;

				// If required value provided
				if (isset($data->required))
				{
					if (is_int($data->required))
					{
						if ($currentValue < $data->required)
						{
							$this->requirements->php->$key->messages[] = 'Your system does not match required: ' . $data->required;
						}
					}
					else
					{
						if ($currentValue <> $data->required)
						{
							$this->requirements->php->$key->messages[] = 'Your system does not match required: ' . $data->required;
						}
					}
				}

				// If recommended value provided
				if (isset($data->recommended))
				{
					if (is_int($data->recommended))
					{
						if ($currentValue < $data->recommended)
						{
							$this->requirements->php->$key->messages[] = 'Your system does not match recommend: ' . $data->recommended;
						}
					}
					else
					{
						if ($currentValue <> $data->recommended)
						{
							$this->requirements->php->$key->messages[] = 'Your system does not match recommend: ' . $data->recommended;
						}
					}
				}
			}
		}
	}
}