<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Class RedshopControllerWizard
 */
class RedshopControllerWizard extends RedshopController
{
	/**
	 *
	 * Copy temparory distinct file for enable config variable support
	 */
	public function copyTempFile()
	{
		jimport('joomla.filesystem.file');

		$this->_temp_file_dist = JPATH_COMPONENT_ADMINISTRATOR . '/config/config.dist.php';
		$this->_temp_file = JPATH_COMPONENT_ADMINISTRATOR . '/config/config.php';

		JFile::copy($this->_temp_file_dist, $this->_temp_file);
	}

	/**
	 * Save configuration
	 */
	public function save()
	{
		// Get temporary saved config via wizard
		$session = JFactory::getSession();
		$wizardConfig = $session->get('redshop.wizard');

		// Get submit data
		$post = $this->input->post->getArray();
		$go = $post['go'];

		$substep = $post['substep'];

		if ($substep == 2)
		{
			$country_list = $this->input->get('country_list');

			$i = 0;
			$country_listCode = '';

			if ($country_list)
			{
				foreach ($country_list as $value)
				{
					$country_listCode .= $value;
					$i++;

					if ($i < count($country_list))
					{
						$country_listCode .= ',';
					}
				}
			}

			$post['country_list'] = $country_listCode;
		}

		// Convert post data key to uppercase. Because we'll use uppercase in config file
		foreach ($post as $key => $value)
		{
			$post[strtoupper($key)] = $value;
			unset($post[$key]);
		}

		// Merge with saved config
		$post = array_merge($wizardConfig, $post);

		// Save back to session
		$session->set('redshop.wizard', $post);

		if ($go == 'pre')
		{
			$substep = $substep - 2;
		}

		if ($post['VATREMOVE'] == 1)
		{
			$tax_rate_id = $post['VATTAX_RATE_ID'];
			$vatlink = 'index.php?option=com_redshop&view=tax_detail&task=removefromwizrd&cid[]=' . $tax_rate_id . '&tax_group_id=1';

			$this->setRedirect($vatlink);
		}
		else
		{
			$link = 'index.php?option=com_redshop&step=' . $substep;
			$this->setRedirect($link);
		}
	}

	/**
	 * Final step and finish wizard
	 */
	public function finish()
	{
		$session = JFactory::getSession();

		$msg = "";

		$post = JFactory::getApplication()->input->post->getArray();

		/**
		 *    install sample data
		 */
		if (isset($post['installcontent']))
		{
			if ($this->demoContentInsert())
			{
				$msg .= JText::_('COM_REDSHOP_SAMPLE_DATA_INSTALLED') . "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
		}

		// Convert array to JRegistry before saving
		$configHelper = Redshop::getConfig();
		$config = new Registry;
		$config->loadArray($session->get('redshop.wizard'));

		if ($configHelper->save($config))
		{
			// Clear temporary redshop wizard configuration
			$session->clear('redshop.wizard');

			$msg .= JText::_('COM_REDSHOP_FINISH_WIZARD');

			$link = 'index.php?option=com_redshop';
		}
		else
		{
			$substep = 4;
			$msg .= JText::_('COM_REDSHOP_ERROR_SAVING_DETAIL');

			$link = 'index.php?option=com_redshop&step=' . $substep;
		}

		$this->setRedirect($link, $msg);
	}

	public function demoContentInsert()
	{
		/** @var RedshopModelRedshop $model */
		$model = $this->getModel('redshop', 'redshopModel');

		if (!$model->demoContentInsert())
		{
			return false;
		}

		return true;
	}
}
