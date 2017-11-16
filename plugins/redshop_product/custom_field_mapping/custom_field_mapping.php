<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
JLoader::import('redshop.library');

/**
 * PlgRedshop_ProductCustom_Field_Mapping Class
 *
 * @since  1.5
 */
class PlgRedshop_ProductCustom_Field_Mapping extends JPlugin
{
	/**
	 * constructor
	 *
	 * @param   object  $subject  subject
	 * @param   array   $params   params
	 */
	public function __construct($subject, $params)
	{
		parent::__construct($subject, $params);
		$this->loadLanguage();
	}

	/**
	 * Method is called before add product to cart
	 *
	 * @param   array  $data  Cart data
	 *
	 * @return  void
	 */
	public function onBeforeAddProductToCart($data)
	{
		$userHelper = rsUserHelper::getInstance();
		$mapping    = $this->mappedName($data);
		$result     = array();

		foreach ($data as $key => $value)
		{
			foreach ($mapping as $k => $vl)
			{
				if ($k == $key)
				{
					$result[$vl] = $value;
				}
			}
		}

		$result['country_code'] = Redshop::getConfig()->get('SHOP_COUNTRY');
		$result['address_type'] = 'BT';
		$result['user_id'] = 0;
		$result['usertype'] = 'Registered';
		$result['groups'] = array(2);
		$result['shopper_group_id'] = 1;

		return $userHelper->storeRedshopUser($result, 0);
	}

	/**
	 * Returns the field name mapped to a redSHOP customfield, if defined
	 * each mapping line has to be formatted this way: <redSHOP custom field>;<billing info>
	 *
	 * @param   array  $data  redSHOP cart data
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	private function mappedName($data)
	{
		$result = array();
		$mapping = $this->params->get('mapping');

		if (!strstr($mapping, ';'))
		{
			throw new Exception('invalid mapping');
		}

		$lines = explode("\n", $mapping);

		foreach ($lines as $l)
		{
			if (strstr($l, ';'))
			{

				list($field, $fname) = explode(";", $l);
				$field = trim($field);
				$fname = trim($fname);

				if ($field)
				{
					$result[$field] = $fname;
				}
			}
		}

		return $result;
	}
}
