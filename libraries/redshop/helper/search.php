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
		private function buildQueryMediaSection( $mediaSection , $search )
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
		
		private function buildQueryAlertVoucher( $voucherId, $search )
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('cp.product_id','value'))
				->select($db->qn('p.product_name','text'))
				->from($db->qn('#__redshop_product', 'p'))
				->leftJoin(
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
				->leftJoin(
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
		
		private function buildQueryAlertTermsArticle( $search)
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
						->leftJoin(
							$db->qn('#__categories', 'cc')
							. ' ON ' . $db->qn('cc.id') . ' = ' . $db->qn('a.catid')
						)
						->leftJoin(
							$db->qn('#__sections', 's')
							. ' ON ' . $db->qn('s.id') . ' = ' . $db->qn('cc.section')
						)
						->leftJoin(
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
		
		private function buildQueryIsUser( $search )
		{
			$db = JFactory::getDbo();
			$query = self::getQuery();
			$query->where('('
					. $db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
					. $db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
					. $db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%')
					. ')')
					->where($db->qn('uf.address_type') . ' LIKE ' . $db->quote('BT'));

			return $query;
		}
		
		private function buildQueryIsCompanyTrue( $search , $isCompany )
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
				->leftJoin(
					$db->qn('#__users', 'u')
					. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
				)
				->where($db->qn('uf.address_type') . ' LIKE ' . $db->q('BT'))
				->where($db->qn('uf.is_company') . ' = ' . $db->q( (int) $isCompany))
				->where('('
				. $db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
				. $db->qn('uf.company_name') . ' LIKE ' . $db->q('%' . $search . '%')
				. ')');
		}
		
		private function buildQueryIsCompanyFalse( $search , $isCompany )
		{
			$db = JFactory::getDbo();
			$query = self::getQuery();
			$query->where($db->qn('uf.address_type') . ' LIKE ' . $db->q('BT'))
				->where($db->qn('uf.is_company') . ' = ' . $db->q( (int) $isCompany))
				->where('('
					. $db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
					. $db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
					. $db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%')
					. ')');

			return $query;
		}
		
		private function buildQueryAddRedUser( $search )
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
				->leftJoin(
					$db->qn('#__users', 'u')
					. ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('u.id')
				)
				->where($db->qn('uf.address_type') . ' LIKE ' . $db->quote('BT'))
				->where('('
					. $db->qn('u.username') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
					. $db->qn('uf.firstname') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
					. $db->qn('uf.lastname') . ' LIKE ' . $db->q('%' . $search . '%')
					. ')');
		}
		
		private function buildQueryProductTrue( $search )
		{
			$db = JFactory::getDbo();
			return $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->from($db->qn('#__redshop_product','p'))
				->where($db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%'));
		}
		
		private function buildQueryRelatedTrue( $search , $productId )
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('p.product_id', 'id'))
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->from($db->qn('#__redshop_product','p'))
				->where('('
					. $db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
					. $db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%')
					. ')');

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
		
		private function buildQueryParentTrue( $search , $productId )
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
		
		private function buildQueryNavigatorTrue( $search )
		{
			$db = JFactory::getDbo();
			return $db->getQuery(true)
				->select('DISTINCT p.product_id AS id')
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->select($db->qn('p.product_price', 'price'))
				->from($db->qn('#__redshop_product','p'))
				->where('p.published = 1')
				->where('('
					. $db->qn('p.product_name') . ' LIKE ' . $db->q('%' . $search . '%') . ' OR '
					. $db->qn('p.product_number') . ' LIKE ' . $db->q('%' . $search . '%')
					. ')');
		}
		
		private function buildQueryDefault( $search , $productId )
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('DISTINCT p.product_id AS id')
				->select($db->qn('p.product_name', 'value'))
				->select($db->qn('p.product_number', 'value_number'))
				->select($db->qn('p.product_price', 'price'))
				->from($db->qn('#__redshop_product','p'))
				->leftJoin(
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
		
		private function buildQuerySwichCaseMediaSection($mediaSection,$search)
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
		
		private function buildQueryAlertVoucherSearch($voucherId,$search)
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
		
		private function buildQueryAlertStoockroomSearch($search,$stockroomId)
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
		
		private function buildQueryAddRedUserSearch($search,$emailLabel)
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
		
		private function buildQueryIsRelatedTrueSearch($search,$productId)
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
		
		private function buildQueryIsParentTrueSearch($search,$productId)
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

		private function buildQueryDefaultSearch($search,$productId,$accessoryList)
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

		public static  function getSearchQuery(
			$mediaSection = null , $alert = null , $user =  0, $plgCustomView = 0, $isCompany = -1,
			$addRedUser = null, $related = 0, $productId = 0, $parent = 0, $navigator =0,
			$search = null , $voucherId = 0,$containerId , $stockroomId, $isProduct , $accessoryList
											)
		{
			$result = self::buildQueryDefaultSearch($search,$productId,$accessoryList);

			if(!empty($mediaSection))
			{
				$result = self::buildQuerySwichCaseMediaSection($mediaSection,$search);
			}

			if($alert == 'container')
			{
				$result = self::buildQueryAlertContainerSearch($search,$containerId);
			}

			if($alert == 'voucher')
			{
				$result = self::buildQueryAlertVoucherSearch($voucherId,$search);
			}

			if($alert == 'stoockroom')
			{
				$result = self::buildQueryAlertStoockroomSearch($search,$stockroomId);
			}

			if($alert == 'termsarticle')
			{
				$result = self::buildQueryAlertTermsArticleSearch($search);
			}

			if($user == 1 || $addRedUser == 1)
			{
				$emailLabel = '';

				if ($addRedUser == 1)
				{
					$emailLabel = 'value_number';
				}
				else
				{
					$emailLabel = 'volume';
				}

				$result = self::buildQueryAddRedUserSearch($search, $emailLabel);
			}

			if($plgCustomView == 1)
			{
				if($isCompany == 0)
				{
					$result = self::buildQueryIsCompanyFalseSearch($search);
				}

				if($isCompany == 1)
				{
					$result = self::buildQueryIsCompanyTrueSearch($search);
				}
			}

			if($isProduct == 1)
			{
				$result = self::buildQueryIsProductTrueSearch($search);
			}

			if($related == 1)
			{
				$result = self::buildQueryIsRelatedTrueSearch($search,$productId);
			}

			if($parent == 1)
			{
				$result = self::buildQueryIsParentTrueSearch($search,$productId);
			}

			if($navigator == 1)
			{
				$result = self::buildQueryIsNavigatorTrueSearch($search);
			}

			return $result;
		}

		public static function getBuildQuery(
			$mediaSection = null , $alert = null , $user =  0, $plgCustomView = 0, $isCompany = -1,
			$addRedUser = null, $products = null, $related = 0, $productId = 0, $parent = 0,
			$navigator =0, $search = null , $voucherId = 0
											)
		{
			$result = self::buildQueryDefault($search,$productId);
			
			if($mediaSection)
			{
				$result =self::buildQueryMediaSection( $mediaSection, $search );
			}

			if($alert && $alert == 'voucher')
			{
				$result = self::buildQueryAlertVoucher( $voucherId, $search );
			}

			if($alert && $alert == 'termsarticle')
			{
				$result = self::buildQueryAlertTermsArticle( $search );
			}

			if($user && $user == 1)
			{
				$result = self::buildQueryIsUser( $search );
			}

			if($plgCustomView && $plgCustomView== 1)
			{
				if($isCompany == 0)
				{
					$result = self::buildQueryIsCompanyFalse($search,$isCompany);
				}

				if($isCompany= 1)
				{
					$result = self::buildQueryIsCompanyTrue($search,$isCompany);
				}
			}

			if($addRedUser && $addRedUser == 1 )
			{
				$result = self::buildQueryAddRedUser($search);
			}

			if($products && $products == 1)
			{
				$result = self::buildQueryProductTrue($search);
			}

			if($related && $related == 1)
			{
				$result = self::buildQueryRelatedTrue($search,$productId);
			}

			if($parent && $parent == 1)
			{
				$result = self::buildQueryParentTrue($search,$productId);
			}

			if($navigator && $navigator == 1)
			{
				$result = self::buildQueryNavigatorTrue($search);
			}

			return $result;
		}
	}