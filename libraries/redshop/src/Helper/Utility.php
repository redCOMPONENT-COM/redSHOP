<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

use JCaptcha;
use JFactory;
use JText;

defined('_JEXEC') or die;

/**
 * Utility helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Utility
{
	/**
	 * This function is for check captcha code
	 *
	 * @param   string   $data            The answer
	 * @param   boolean  $displayWarning  Display warning or not.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function checkCaptcha($data, $displayWarning = true)
	{
		$default = JFactory::getConfig()->get('captcha');

		if (JFactory::getApplication()->isSite())
		{
			$default = JFactory::getApplication()->getMenu()->getActive()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
		}

		if (empty($default))
		{
			return true;
		}

		$captcha = JCaptcha::getInstance($default, array('namespace' => 'redshop'));

		if ($captcha != null && !$captcha->checkAnswer($data))
		{
			if ($displayWarning)
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_INVALID_SECURITY'), 'error');
			}

			return false;
		}

		return true;
	}
}