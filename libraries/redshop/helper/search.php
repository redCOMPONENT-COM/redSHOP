<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
/**
 * Search helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.1.4
 */
class RedshopHelperSearch
{
	/**
	 *
	 * @return JDatabaseQuery
	 *
	 * @since version__DEPLOY_VERSION__
	 */
	private function getQuery()
	{
		$db = JFactory::getDbo();
		return $db->getQuery(true)
			->select($db->qn('u.id', 'id'))
			->select(
				'CONCAT(' . $db->qn('uf.firstname') . ','
				. $db->quote(' ') . ','
				. $db->qn('uf.lastname') . ','
				. $db->quote(' ( ') . ','
				. $db->qn('u.username') . ','
				. $db->quote(')') . ') AS ' . $db->qn('value')
			)
			->select($db->qn('u.email', 'volume'))
			->from($db->qn('#__redshop_users_info', 'uf'))
			->leftJoin(
				$db->qn('#__users', 'u')
				. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
			);
	}

	/**
	 * @param $search
	 * @param $containerId
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQueryAlertContainerSearch($search, $containerId)
	{
		$db = JFactory::getDbo();
		return $db->getQuery(true)
			->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
					$db->qn('p.supplier_id'),
					$db->qn('p.product_volume', 'volume')
				)
			)
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_container_product_xref', 'cp') . ' ON cp.product_id = p.product_id')
			->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'))
			->where($db->qn('cp.container_id') . ' != ' . $containerId );
	}

	/**
	 * @param $mediaSection
	 * @param $search
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQuerySwitchCaseMediaSection($search, $mediaSection)
	{
		$db = JFactory::getDbo();
		switch ($mediaSection)
		{
			case 'category':
				return $db->getQuery(true)
					->select(
						array(
							$db->qn('id'),
							$db->qn('name', 'text')
						)
					)
					->from($db->qn('#__redshop_category'))
					->where($db->qn('name') . ' LIKE ' . $db->q('%' . $search . '%'));
			case 'property':
				return $db->getQuery(true)
					->select(
						array(
							$db->qn('property_id', 'id'),
							$db->qn('property_name', 'text')
						)
					)
					->from($db->qn('#__redshop_product_attribute_property'))
					->where($db->qn('property_name') . ' LIKE ' . $db->q('%' . $search . '%'));
			case 'subproperty':
				return $db->getQuery(true)
					->select(
						array(
							$db->qn('subattribute_color_id', 'id'),
							$db->qn('subattribute_color_name', 'text')
						)
					)
					->from($db->qn('#__redshop_product_subattribute_color'))
					->where($db->qn('subattribute_color_name') . ' LIKE ' . $db->q('%' . $search . '%'));
			case 'manufacturer':
				return $db->getQuery(true)
					->select(
						array(
							$db->qn('id', 'id'),
							$db->qn('name', 'text')
						)
					)
					->from($db->qn('#__redshop_manufacturer'))
					->where($db->qn('name') . ' LIKE ' . $db->q('%' . $search . '%'));
			case 'catalog':
				return $db->getQuery(true)
					->select(
						array(
							$db->qn('catalog_id', 'id'),
							$db->qn('catalog_name', 'text')
						)
					)
					->from($db->qn('#__redshop_catalog'))
					->where($db->qn('catalog_name') . ' LIKE ' . $db->q('%' . $search . '%'));
			case 'product':
			default:
				return $db->getQuery(true)
					->select(
						array(
							$db->qn('product_id', 'id'),
							'CONCAT(' . $db->qn('product_name') . ', " (", ' . $db->qn('product_number') . ', ")") as text'
						)
					)
					->from($db->qn('#__redshop_product'))
					->where('('
						. $db->qn('product_name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
						. $db->qn('product_number') . ' LIKE ' . $db->q('%' . $search . '%')
						. ')');

		}
	}

	/**
	 * @param $search
	 * @param $voucherId
	 *
	 * @return JDatabaseQuery
	 *
	 * @since version
	 */
	private function buildQueryAlertVoucherSearch($search, $voucherId)
	{
		$db = JFactory::getDbo();
		$subQuery = $db->getQuery(true)
			->select('COUNT(cp.product_id)')
			->from($db->qn("#__redshop_product_voucher_xref", 'cp'))
			->where('cp.product_id = p.product_id')
			->where('cp.voucher_id = ' . $voucherId );

		return $db->getQuery(true)
			->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text'
				)
			)
			->from($db->qn('#__redshop_product', 'p'))
			->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'))
			->where('(' . $subQuery . ') = 0');
	}

	/**
	 * @param $search
	 * @param $stockroomId
	 *
	 * @return JDatabaseQuery
	 *
	 * @since version
	 */
	private function buildQueryAlertStockroomSearch($search, $stockroomId)
	{
		$db = JFactory::getDbo();
		return  $db->getQuery(true)
			->select(
				array(
					$db->qn('p.container_id', 'id'),
					$db->qn('p.container_name', 'text')
				)
			)
			->from($db->qn('#__redshop_container', 'p'))
			->leftJoin($db->qn('#__redshop_stockroom_container_xref', 'cp') . ' ON cp.container_id = p.container_id')
			->where($db->qn('p.container_name') . ' LIKE ' . $db->q('%' . $search . '%'))
			->where($db->qn('cp.stockroom_id') . ' != ' . $stockroomId );
	}

	/**
	 * @param $search
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQueryAlertTermsArticleSearch($search)
	{
		$db = JFactory::getDbo();
		return  $db->getQuery(true)
			->select(
				array(
					$db->qn('a.id'),
					$db->qn('a.title', 'text')
				)
			)
			->from($db->qn('#__content', 'a'))
			->leftJoin($db->qn('#__categories', 'cc') . ' ON cc.id = a.catid')
			->where($db->qn('a.title') . ' LIKE ' . $db->q('%' . $search . '%'))
			->where($db->qn('a.state') . ' = 1')
			->where($db->qn('cc.extension' . ' = ' . $db->quote('com_content')))
			->where($db->qn('cc.published') . ' = 1');
	}

	/**
	 * @param $search
	 * @param $emailLabel
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQueryAddRedUserSearch($search, $emailLabel)
	{
		$db = JFactory::getDbo();
		return  $db->getQuery(true)
			->select(
				array(
					$db->qn('u.id'),
					'CONCAT (' . $db->qn('uf.firstname') . ', ' . $db->quote(' ') . ', ' . $db->qn('uf.lastname') . ', ' . $db->quote(' (')
					. ', ' . $db->qn('u.username') . ', ' . $db->quote(')') . ',' . $db->quote(' - ') . ',' . $db->qn('uf.phone') . ') AS text',
					$db->qn('u.email', $emailLabel),
					$db->qn('uf.phone', 'phone')
				)
			)
			->from($db->qn('#__users', 'u'))
			->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON uf.user_id = u.id')
			->where('('
				. $db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('uf.firstnamee') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('uf.phone') . ' LIKE ' . $db->q('%' . $search . '%')
				. ')')
			->where($db->qn('uf.address_type') . ' = ' . $db->quote('BT'));
	}

	/**
	 * @param $search
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQueryIsCompanyTrueSearch($search)
	{
		$db = JFactory::getDbo();
		return  $db->getQuery(true)
			->select(
				array(
					$db->qn('u.id'),
					'CONCAT (' . $db->qn('uf.company_name') . ', ' . $db->quote(' (') . ', '
					. $db->qn('u.username') . ', ' . $db->quote(')') . ') AS text',
					$db->qn('u.email', 'volume')
				)
			)
			->from($db->qn('#__redshop_users_info', 'uf'))
			->leftJoin($db->qn('#__users', 'u') . ' ON uf.user_id = u.id')
			->where('(' . $db->qn('u.username') . $search
				. ' OR ' . $db->qn('uf.company_name') . $search . ')'
			)
			->where($db->qn('uf.address_type') . ' = ' . $db->quote('BT'))
			->where($db->qn('uf.is_company') . ' = 1');
	}

	/**
	 * @param $search
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQueryIsCompanyFalseSearch($search)
	{
		$db = JFactory::getDbo();
		return  $db->getQuery(true)
			->select(
				array(
					$db->qn('u.id'),
					'CONCAT (' . $db->qn('uf.firstname') . ', ' . $db->quote(' ') . ', ' . $db->qn('uf.lastname') . ', ' . $db->quote(' (')
					. ', ' . $db->qn('u.username') . ', ' . $db->quote(')') . ') AS text',
					$db->qn('u.email', 'volume')
				)
			)
			->from($db->qn('#__users', 'u'))
			->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON uf.user_id = u.id')
			->where('('
				. $db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%')
				. ')'
			)
			->where($db->qn('uf.address_type') . ' = ' . $db->quote('BT'))
			->where($db->qn('uf.is_company') . ' = 0');
	}

	/**
	 * @param $search
	 *
	 * @return JDatabaseQuery
	 *
	 * @since version
	 */
	private function buildQueryIsProductTrueSearch($search)
	{
		$db = JFactory::getDbo();
		return  $db->getQuery(true)
			->select(
				array(
					$db->qn('product_id', 'id'),
					'CONCAT(' . $db->qn('product_name') . ', " (", ' . $db->qn('product_number') . ', ")") as text',
					$db->qn('product_number', 'value_number')
				)
			)
			->from($db->qn('#__redshop_product'))
			->where('('
				. $db->qn('product_name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('product_number') . ' LIKE ' . $db->q('%' . $search . '%')
				. ')');
	}

	/**
	 * @param $search
	 * @param $productId
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQueryIsRelatedTrueSearch($search, $productId)
	{
		$db = JFactory::getDbo();
		return  $db->getQuery(true)
			->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
					$db->qn('p.product_number', 'value_number')
				)
			)
			->from($db->qn('#__redshop_product', 'p'))
			->where('('
				. $db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%')
				. ')')
			->where($db->qn('p.product_id') . ' != ' . $productId );
	}

	/**
	 * @param $search
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQueryIsNavigatorTrueSearch($search)
	{
		$db = JFactory::getDbo();
		return  $db->getQuery(true)
			->select(
				array(
					$db->qn('p.product_id', 'id'),
					'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
					$db->qn('p.product_number', 'value_number'),
					$db->qn('p.product_price', 'price')
				)
			)
			->from($db->qn('#__redshop_product', 'p'))
			->where($db->qn('p.published') . ' = 1')
			->where('('
				. $db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%')
				. ')');
	}

	/**
	 * @param $search
	 * @param $productId
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	private function buildQueryIsParentTrueSearch($search, $productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(
			array(
				$db->qn('p.product_id', 'id'),
				'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
				$db->qn('p.product_number', 'value_number'),
				$db->qn('p.product_price', 'price')
			)
		)
			->from($db->qn('#__redshop_product', 'p'))
			->where($db->qn('p.published') . ' = 1')
			->where('('
				. $db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%')
				. ')');

		if ($productId)
		{
			$query->where($db->qn('p.product_id') . ' != ' . $productId);
		}

		return $query;
	}

	/**
	 * @param $search
	 * @param $productId
	 * @param $accessoryList
	 *
	 * @return JDatabaseQuery
	 *
	 * @since version__DEPLOY_VERSION__
	 */
	private function buildQueryDefaultSearch($search, $productId, $accessoryList)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			array(
				$db->qn('p.product_id', 'id'),
				'CONCAT(' . $db->qn('p.product_name') . ', " (", ' . $db->qn('p.product_number') . ', ")") as text',
				$db->qn('p.product_number', 'value_number'),
				$db->qn('p.product_price', 'price')
			)
		)
			->from($db->qn('#__redshop_product', 'p'))
			->where('('
				. $db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%')
				. ')');

		if ($accessoryList)
		{
			$accessoryList = explode(',', $accessoryList);
			$accessoryList = Joomla\Utilities\ArrayHelper::toInteger($accessoryList);
			$query->where('p.product_id NOT IN (' . implode(',', $accessoryList) . ')');
		}

		if ($productId)
		{
			$query->leftJoin($db->qn('#__redshop_product_accessory', 'pa') . ' ON pa.child_product_id = p.product_id AND pa.product_id = ' . $productId)
				->where('pa.accessory_id IS NULL')
				->where($db->qn('p.product_id') . ' != ' . $productId);
		}

		return $query;
	}

	/**
	 * @param   array  $params
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static  function getSearchQuery($params = array())
	{
		$result = JFactory::getDbo()->getQuery(true);

		if (empty($params['search']))
		{
			return $result;
		}

		if (!empty($params['productId']) && !empty($params['accessoryId']))
		{
			$result = self::buildQueryDefaultSearch(
				$params['search'],
				$params['productId'],
				$params['accessoryList']);
		}

		if (!empty($params['mediaSection']))
		{
			$result = self::buildQuerySwitchCaseMediaSection(
				$params['mediaSection'],
				$params['search']);
		}

		if (!empty($params['alert']))
		{
			switch ($params['alert'])
			{
				case 'container':

					if (!empty($params['containerId']))
					{
						$result = self::buildQueryAlertContainerSearch(
							$params['search'],
							$params['containerId']
						);
					}

					break;
				case 'voucher':

					if (!empty($params['voucherId']))
					{
						$result = self::buildQueryAlertVoucherSearch(
							$params['search'],
							$params['voucherId']
						);
					}

					break;
				case 'stoockroom':

					if (!empty($params['stockroomId']))
					{
						$result = self::buildQueryAlertStockroomSearch(
							$params['search'],
							$params['stockroomId']
						);
					}

					break;
				case 'termsarticle':
				default:
					$result = self::buildQueryAlertTermsArticleSearch($params['search']);
					break;
			}
		}

		if (!empty($params['user']) && !empty($params['addUser'])
			&& ($params['user'] == 1 || $params['addRedUser'] == 1))
		{
			$emailLabel = '';

			if ($params['addRedUser'] == 1)
			{
				$emailLabel = 'value_number';
			}
			else
			{
				$emailLabel = 'volume';
			}

			$result = self::buildQueryAddRedUserSearch($params['search'], $emailLabel);
		}

		if (!empty($params['plgCustomView']) &&  $params['plgCustomView'] == 1)
		{
			if (empty($params['isCompany']))
			{
				$result = self::buildQueryIsCompanyFalseSearch($params['search']);
			}elseif ($params['isCompany'] == 1)
			{
				$result = self::buildQueryIsCompanyTrueSearch($params['search']);
			}
		}

		if(!empty($params['isProduct']) && $params['isProduct'] == 1)
		{
			$result = self::buildQueryIsProductTrueSearch($params['search']);
		}

		if(!empty($params['related'])
			&& !empty($params['productId'])
			&& $params['related'] == 1)
		{
			$result = self::buildQueryIsRelatedTrueSearch($params['search'], $params['productId']);
		}

		if(!empty($params['parent'])
			&& !empty($params['productId'])
			&& $params['parent'] == 1)
		{
			$result = self::buildQueryIsParentTrueSearch($params['search'], $params['productId']);
		}

		if(!empty($params['navigator'])
			&& $params['navigator'] == 1)
		{
			$result = self::buildQueryIsNavigatorTrueSearch($params['search']);
		}

		return $result;
	}

	/**
	 * Alias of getSearchQuery
	 *
	 * @param   array  $params
	 *
	 * @return JDatabaseQuery
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function getBuildQuery($params = array())
	{
		return self::getSearchQuery($params);
	}
}