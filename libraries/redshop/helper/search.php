<?php
	/**
	 * @package     Redshop.Libraries
	 * @subpackage  Helpers
	 *
	 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
	 * @license     GNU General Public License version 2 or later; see LICENSE
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
		public static function buildQueryMediaSection( $mediaSection , $search )
		{
			$db = JFactory::getDbo();

			if ($mediaSection == 'product')
			{
				return $db->getQuery(true)
					->select($db->qn('product_id','id'))
					->select($db->qn('product_name','value'))
					->from($db->qn('#__redshop_product', 'p'))
					->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'));
			}
			elseif ($mediaSection == 'category')
			{
				return $db->getQuery(true)
					->select($db->qn('id','id'))
					->select($db->qn('name','value'))
					->from($db->qn('#__redshop_category', 'cat'))
					->where($db->qn('cat.name') . ' LIKE ' . $db->q('%' . $search . '%'));
			}
			else
			{
				return $db->getQuery(true)
					->select($db->qn('catalog_id','id'))
					->select($db->qn('catalog_name','value'))
					->from($db->qn('#__redshop_catalog', 'log'))
					->where('published = 1')
					->where($db->qn('log.catalog_name') . ' LIKE ' . $db->q('%' . $search . '%'));
			}
		}

		public static function buildQueryAlertVoucher( $voucherId, $search )
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('cp.product_id','value'))
				->select($db->qn('p.product_name','text'))
				->from($db->qn('#__redshop_product', 'p'))
				->leftjoin(
					$db->qn('#__redshop_product_voucher_xref', 'cp')
					. ' ON ' . $db->qn('cp.product_id') . ' = ' . $db->qn('p.product_id')
				)
				->where($db->qn('cp.voucher_id') . ' = ' . $db->q( (int) $voucherId) );

			$db->setQuery($query);
			$productData = $db->loadObjectList();

			$query = $db->getQuery(true)
				->select('DISTINCT p.product_id AS id')
				->select($db->qn('p.product_name', 'value'))
				->from($db->qn('#__redshop_product','p'))
				->leftjoin(
					$db->qn('#__redshop_product_voucher_xref', 'cp')
					. ' ON ' . $db->qn('cp.product_id') . ' = ' . $db->qn('p.product_id')
				);

			$pid =array();

			if (count($productData) > 0)
			{
				foreach ($productData as $rc)
				{
					$pid[] = $rc->value;
				}

				if(is_array($pid) && $pid)
				{
					$query->where($db->qn('p.product_id') . ' NOT IN (' . implode("," , $pid ) . ')')
						->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'));
				}
				else
				{
					$query->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'));
				}
			}
			else
			{
				$query->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'));
			}
			
			return $query;
		}

		public static function buildQueryAlertTermsArticle( $search)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('a.sectionid'))
				->select($db->qn('a.catid'))
				->select($db->qn('a.id','value'))
				->select($db->qn('a.title','text'))
				->from($db->qn('#__content', 'a'))
				->where('a.state = 1')
				->where($db->qn('a.title') . ' LIKE ' . $db->q('%' . $search . '%'));

			$db->setQuery($query);
			$rows    = $db->loadObjectList();
			$article = array();

			for ($j = 0, $jn = count($rows); $j < $jn; $j++)
			{
				if ($rows[$j]->sectionid != 0 && $rows[$j]->catid != 0)
				{
					$query = $db->getQuery(true)
						->select($db->qn('a.id','value'))
						->select($db->qn('a.title','text'))
						->from($db->qn('#__content', 'a'))
						->leftjoin(
							$db->qn('#__categories', 'cc')
							. ' ON ' . $db->qn('cc.id') . ' = ' . $db->qn('a.catid')
						)
						->leftjoin(
							$db->qn('#__sections', 's')
							. ' ON ' . $db->qn('s.id') . ' = ' . $db->qn('cc.section')
						)
						->leftjoin(
							$db->qn('#__groups', 'g')
							. ' ON ' . $db->qn('a.access') . ' = ' . $db->qn('g.id')
						)
						->where('a.state = 1')
						->where('cc.published = 1')
						->where('s.published = 1')
						->where($db->qn('s.scope' . ' = ' . $db->quote('content')))
						->where($db->qn('a.title') . ' LIKE ' . $db->q('%' . $search . '%'));

					$db->setQuery($query);
					$r = $db->loadObjectList();
					$i = 0;

					foreach ($r as $value)
					{
						$article[$i]->value = $value->text;
						$article[$i]->id    = $value->value;
						$i++;
					}
				}
				else
				{
					$article[$j]->value = $rows[$j]->text;
					$article[$j]->id    = $rows[$j]->value;
				}
			}

			return $article;
		}

		public static function buildQueryIsUser( $search )
		{
			$db = JFactory::getDbo();
			$query = self::getQuery();
			$query->orwhere($db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%'))
				->where($db->qn('uf.address_type') . ' LIKE ' . $db->quote('BT'));

			return $query;
		}

		public static function buildQueryIsCompanyTrue( $search , $isCompany )
		{
			$db = JFactory::getDbo();
			return $db->getQuery(true)
				->select($db->qn('u.id', 'id'))
				->select(
					'CONCAT(' . $db->qn('uf.company_name') . ','
					. $db->quote(' ( ') . ','
					. $db->qn('u.username') . ','
					. $db->quote(')') . ') AS ' . $db->qn('value')
				)
				->select($db->qn('u.email', 'volume'))
				->from($db->qn('#__redshop_users_info', 'uf'))
				->leftjoin(
					$db->qn('#__users', 'u')
					. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
				)
				->where($db->qn('uf.address_type') . ' LIKE ' . $db->q('BT'))
				->where($db->qn('uf.is_company') . ' = ' . $db->q( (int) $isCompany))
				->orwhere($db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('uf.company_name') . ' LIKE ' . $db->q('%' . $search . '%'));
		}

		public static function buildQueryIsCompanyFalse( $search , $isCompany )
		{
			$db = JFactory::getDbo();
			$query = self::getQuery();
			$query->where($db->qn('uf.address_type') . ' LIKE ' . $db->q('BT'))
				->where($db->qn('uf.is_company') . ' = ' . $db->q( (int) $isCompany))
				->orwhere($db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%'));

			return $query;
		}

		public static function buildQueryAddRedUser( $search )
		{
			$db = JFactory::getDbo();
			return $db->getQuery(true)
				->select($db->qn('uf.user_id', 'id'))
				->select(
					'CONCAT(' . $db->qn('uf.firstname') . ','
					. $db->quote(' ') . ','
					. $db->qn('uf.lastname') . ','
					. $db->quote(' IF( ') . ','
					. $db->qn('u.username')  . ' != ' . $db->q('') . ','
					. 'CONCAT(' . $db->qn('u.username') . ' ) ,'
					. $db->quote(')') . ') AS ' . $db->qn('value')
				)
				->select($db->qn('u.user_email', 'value_number'))
				->from($db->qn('#__redshop_users_info', 'uf'))
				->leftjoin(
					$db->qn('#__users', 'u')
					. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
				)
				->where($db->qn('uf.address_type') . ' LIKE ' . $db->quote('BT'))
				->orwhere($db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%'));
		}

		public static function buildQueryProductTrue( $search )
		{
			$db = JFactory::getDbo();
			return $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->from($db->qn('#__redshop_product','p'))
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'));
		}

		public static function buildQueryRelatedTrue( $search , $productId )
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->from($db->qn('#__redshop_product','p'))
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%'));

			if ($productId != 0)
			{
				$subQuery = $db->getQuery(true)
					->select($db->qn('related_id'))
					->from($db->qn('#__redshop_product_related'))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));

				$db->setQuery($subQuery);

				$related                  = $db->loadColumn();
				$related[count($related)] = $productId;

				if ( is_array($related) && $related )
				{
					$query->where($db->qn('p.product_id') . ' NOT IN (' . implode("," , $related ) . ')');
				}
			}

			$query->setLimit(50,0);

			return $query;
		}

		public static function buildQueryParentTrue( $search , $productId )
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->from($db->qn('#__redshop_product','p'))
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'));

			if ($productId != 0)
			{
				$query->where($db->qn('p.product_id') . ' NOT IN (' . $productId . ')');
			}

			$query->setLimit(50,0);

			return $query;
		}

		public static function buildQueryNavigatorTrue( $search )
		{
			$db = JFactory::getDbo();
			return $db->getQuery(true)
				->select('DISTINCT p.product_id AS id')
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->select($db->qn('p.product_price', 'price'))
				->from($db->qn('#__redshop_product','p'))
				->where('p.published = 1')
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'))
				->orwhere($db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%'));
		}

		public static function buildQueryNavigatorFalse( $search , $productId )
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('DISTINCT p.product_id AS id')
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->select($db->qn('p.product_price', 'price'))
				->from($db->qn('#__redshop_product','p'))
				->leftjoin(
					$db->qn('#__redshop_product_accessory', 'cp')
					. ' ON ' . $db->qn('cp.product_id') . ' = ' . $db->qn('p.product_id')
				);

			if ($productId != 0)
			{
				$subQuery = $db->getQuery(true)
					->select($db->qn('child_product_id'))
					->from($db->qn('#__redshop_product_accessory'))
					->where($db->qn('product_id') . ' = ' . $db->q( $productId ));

				$query->where($db->qn('p.product_id') . ' NOT IN (' . $subQuery . ')')
					->where($db->qn('p.product_id') . ' != ' . $db->q( (int) $productId ))
					->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'))
					->where($db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%'));
			}
			else
			{
				$query->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'))
					->where($db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%'));
			}

			return $query;
		}

		public static function getQuery()
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
				->leftjoin(
					$db->qn('#__users', 'u')
					. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
				);
		}
	}