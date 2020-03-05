<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



/**
 * Class registrationModelregistration
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelRegistration extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	public function store(&$data)
	{
        $plugin = JPluginHelper::getPlugin('captcha','recaptcha');

        $dataCaptcha = $data;

        if ($plugin)
        {
            $params  = new JRegistry($plugin->params);

            if ($params->get('version', '') === '2.0')
            {
                $dataCaptcha = null;
            }
        }

		$captcha = Redshop\Helper\Utility::checkCaptcha($dataCaptcha);

		if (!$captcha)
		{
			return false;
		}

		$joomlauser = RedshopHelperJoomla::createJoomlaUser($data, 1);

		if (!$joomlauser)
		{
			return false;
		}

		$data['billisship'] = 1;
		$reduser            = RedshopHelperUser::storeRedshopUser($data, $joomlauser->id);

		return $reduser;
	}
}
