<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

require_once JPATH_BASE . '/components/com_redshop/helpers/configuration.php';

class wizardController extends JController
{
	public $_temp_file = null;

	public $_temp_array = null;

	public $_temp_file_dist = null;

	public function __construct($default = array())
	{
		parent::__construct($default);

		$this->_temp_file = JPATH_BASE . '/components/com_redshop/helpers/wizard/redshop.cfg.tmp.php';
		$this->_temp_file_dist = JPATH_BASE . '/components/com_redshop/helpers/wizard/redshop.cfg.tmp.dist.php';
	}

	public function isTmpFile()
	{
		if (file_exists($this->_temp_file))
		{
			if ($this->isWritable())
			{
				require_once $this->_temp_file;
				return true;
			}
		}
		else
		{
			JError::raiseWarning(21, JText::_('COM_REDSHOP_REDSHOP_TMP_FILE_NOT_FOUND'));
		}

		return false;
	}

	public function isWritable()
	{
		if (!is_writable($this->_temp_file))
		{
			JError::raiseWarning(21, JText::_('COM_REDSHOP_REDSHOP_TMP_FILE_NOT_WRITABLE'));
			return false;
		}

		return true;
	}

	public function WriteTmpFile()
	{
		$html = "<?php \n";

		$html .= 'global $temparray;' . "\n" . '$temparray = array();' . "\n";

		foreach ($this->_temp_array as $key => $val)
		{
			$html .= '$temparray["' . $key . '"] = \'' . addslashes($val) . "';\n";
		}

		$html .= "?>";

		if ($fp = fopen($this->_temp_file, "w"))
		{
			fwrite($fp, $html, strlen($html));
			fclose($fp);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 *
	 * Copy temparory distinct file for enable config variable support
	 */
	public function copyTempFile()
	{
		jimport('joomla.filesystem.file');

		JFile::copy($this->_temp_file_dist, $this->_temp_file);
	}

	function save()
	{
		$post = JRequest::get('post');

		$substep = $post['substep'];
		$go = $post['go'];

		global $temparray;

		$this->isTmpFile();

		if ($substep == 2)
		{
			$country_list = JRequest::getVar('country_list');

			$i = 0;
			$country_listCode = '';

			if ($country_list)
			{
				foreach ($country_list as $key => $value)
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

		$post = array_merge($temparray, $post);

		$this->_temp_array = $post;

		if ($this->WriteTmpFile())
		{
			$msg = JText::_('COM_REDSHOP_STEP_SAVED');

			if ($go == 'pre')
			{
				$substep = $substep - 2;
			}
		}
		else
		{
			$substep = $substep - 1;
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_STEP_DETAIL');
		}

		if ($post['vatremove'] == 1)
		{
			$tax_rate_id = $post['vattax_rate_id'];
			$vatlink = 'index.php?option=com_redshop&view=tax_detail&task=removefromwizrd&cid[]=' . $tax_rate_id . '&tax_group_id=1';

			$this->setRedirect($vatlink);
		}

		else
		{
			$link = 'index.php?option=com_redshop&step=' . $substep;
			$this->setRedirect($link);
		}
	}

	public function finish()
	{
		$Redconfiguration = new Redconfiguration;

		$post = JRequest::get('post');

		$msg = "";

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

		$this->isTmpFile();

		if ($Redconfiguration->storeFromTMPFile())
		{
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
		$model = $this->getModel('redshop', 'redshopModel');

		if (!$model->demoContentInsert())
		{
			return false;
		}

		return true;
	}
}
