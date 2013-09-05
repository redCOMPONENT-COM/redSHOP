<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/models/product_detail.php';

class SubscriptionModelsubscription extends JModel
{
	public $_catid        = null;

	public $_sid          = null;

	public $_data         = null;

	public $_table_prefix = null;

	public $_db           = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$this->_db = JFactory::getDBO();
	}

	public function loadSubscriptionTemplate($subscription_section)
	{
		$redTemplate              = new Redtemplate;
		$subscription_template_id = $this->getSubscriptionTemplateID($subscription_section);
		$this->_template          = $redTemplate->getTemplate($subscription_section, $subscription_template_id[0]->template_id);

		return $this->_template;
	}

	public function getSubscriptionTemplateID($subscription_section)
	{
		$query = " SELECT t.template_id "
				. " FROM " . $this->_table_prefix . "template AS t "
				. " WHERE t.published=1 AND t.template_section = " . $subscription_section . " ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();

		if (count($result) > 0)
		{
			return $result;
		}
	}

	public function getdata()
	{
		$user    = & JFactory::getUser();
		$user_id = $user->id;

		if ($user_id > 0 )
		{
			$subscription_of_user = $this->checkUserIsSubscriberNotExpired($user_id);

			if (count($subscription_of_user) > 0)
			{
				$subs = array();

				for ($i = 0; $i < count($subscription_of_user); $i++)
				{
					$subs[] = $subscription_of_user[$i]->subscription_id;
				}

				if (count($subs) > 0)
				{
					$res = array();

					for ( $j = 0; $j < count($subs); $j++)
					{
						$result = $this->getListDowngradeSubscription($subs[$j]);

						if (count($result) > 0)
						{
							for ($k = 0; $k < count($result); $k++)
							{
								$res[] = $result[$k]->subscription_id;
							}
						}
					}

					if (count($res) > 0)
					{
						$re    = implode(",", $res);
						$where = "AND s.subscription_id NOT IN (" . $re . ")";
					}
				}
			}
		}
		else
		{
			$where = "AND 1=1 ";
		}

		$query = $this->_db->getQuery(true);
		$query
			->select(array('p.*', 's.subscription_period', 's.subscription_period_unit', 's.subscription_id','s.subscription_applicable_products'))
			->from($this->_table_prefix . 'product AS p')
			->join('INNER', $this->_table_prefix . 'subscription AS s ON (p.product_id = s.product_id)')
			->where("p.product_type = 'newsubscription' AND p.published = 1 " . $where);
		$this->_db->setQuery($query);

		return  $this->_db->loadObjectList();
	}

	public function getNumberOfProduct($number_of_product)
	{
		$result = explode("|", $number_of_product);
		$AdminModelproduct_detail = new product_detailModelproduct_detail;
		$result = $AdminModelproduct_detail->removeNullInArray($result);

		return count($result);
	}

	public function checkNewUserIsSubscriber($user_id)
	{
		$today   = time();
		$query   = " SELECT p.product_id,o.mdate,i.order_item_id"
				. " FROM #__redshop_orders o  INNER JOIN #__redshop_order_item i ON o.order_id = i.order_id INNER JOIN #__redshop_product p ON i.product_id = p.product_id "
				. " WHERE o.user_id = '" . $user_id . "' AND o.order_status = 'C' AND o.order_payment_status = 'Paid' AND o.order_subtotal >=0 AND p.product_type = 'newsubscription' "
				. "ORDER BY i.order_item_id ASC ";
		$this->_db->setQuery($query);

		$result = $this->_db->loadObjectList();

		if (count($result) > 0)
		{
			// Check user had exist subscription, user had not exist subscription, user had expired exist subscription
			$query_check = $this->_db->getQuery(true);
			$query_check->select('u.* ');
			$query_check->from($this->_table_prefix . 'users_subscription as u');
			$query_check->where("u.user_id = " . $user_id);
			$query_check->order("u.end_date_subscription ASC");
			$this->_db->setQuery($query_check);
			$result_check = $this->_db->loadObjectList();

			// User had expired subscription or User had subscription not expired
			if (count($result_check) > 0)
			{
				for ( $k = 0; $k < count($result_check); $k++)
				{
					if ( $result_check[$k]->end_date_subscription < $today)
					{
						// Get joomla_acl_groups and fallback_joomla_acl_groups
						$query_get = $this->_db->getQuery(true);
						$query_get->select('s.* ');
						$query_get->from($this->_table_prefix . 'subscription as s');
						$query_get->where("s.subscription_id = " . $result_check[$k]->subscription_id . " ");
						$this->_db->setQuery($query_get);
						$result_get = $this->_db->loadObject();

						// Remove user_group_map
						$joomla_acl_groups_ex = $result_get->joomla_acl_groups;
						$arr_group_e          = explode("|", $joomla_acl_groups_ex);

						if (count($arr_group_e) > 0)
						{
							for ($r = 0; $r < count($arr_group_e); $r++)
							{
								$group_id_i = $arr_group_e[$r];
								$query_del_groups  			= $this->_db->getQuery(true);
								$conditions_groups 			= array('user_id = "' . $user_id . '" AND group_id = "' . $group_id_i . '"');
								$query_del_groups->delete($this->_db->quoteName('#__user_usergroup_map'));
								$query_del_groups->where($conditions_groups);
								$this->_db->setQuery($query_del_groups);

								try
								{
									$result_del_groups = $this->_db->query();
								}
								catch (Exception $e)
								{
									// Catch any database errors.
								}
							}
						}

						// Set user group again
						$fallback_joomla_acl_groups = $result_get->fallback_joomla_acl_groups;
						$arr_group_ex               = explode("|", $fallback_joomla_acl_groups);

						if (count($arr_group_ex) > 0)
						{
							for ($u = 0; $u < count($arr_group_ex); $u++)
							{
								$group_id_ex = $arr_group_ex[$u];
								$query_w  = $this->_db->getQuery(true);
								$query_w->select('um.* ');
								$query_w->from('#__user_usergroup_map as um');
								$query_w->where("um.group_id = " . $group_id_ex . " AND um.user_id = " . $user_id . "  ");
								$this->_db->setQuery($query_w);
								$result_w = $this->_db->loadObject();

								if (count($result_w) > 0 )
								{
									// No execute
								}
								else
								{
									$query_i     = $this->_db->getQuery(true);
									$columns_i   = array('user_id', 'group_id');
									$values_i    = array($user_id, $group_id_ex);
									$query_i
											->insert($this->_db->quoteName('#__user_usergroup_map'))
											->columns($this->_db->quoteName($columns_i))
											->values(implode(',', $values_i));
									$this->_db->setQuery($query_i);

									try
									{
										$result_i = $this->_db->query();
									}
									catch (Exception $e)
									{
										// Catch any database errors.
									}
								}
							}
						}

						// Insert date if user have subscription expired, then user buy a new subscription

						$check_user_is_subscrbcier = $this->checkUserIsSubscriberNotExpired($user_id);

						if (count($check_user_is_subscrbcier) > 0)
						{
							// No execute
						}
						else
						{
							if (count($result) > 0)
							{
								$resutl_pos = $this->getAllSubscriptionInAllOrder($result);

								if ( count($resutl_pos) > 0)
								{
									for ( $tpi = 0; $tpi < count($resutl_pos); $tpi++)
									{
										$subscription_id            = $resutl_pos[$tpi]['subscription_id'];
										$create_date_subscription   = $resutl_pos[$tpi]['create_date_subscription'];
										$end_date_subscription      = $resutl_pos[$tpi]['end_date_subscription'];
										$order_item_id              = $resutl_pos[$tpi]['order_item_id'];
										$num_product_in_an_order_ex = $this->getNumberProductInOrder($order_item_id);

										if ($num_product_in_an_order_ex > 1)
										{
											$end_date_subscription  = (($end_date_subscription - $create_date_subscription) * ($num_product_in_an_order_ex - 1)) + $end_date_subscription;
										}

										// Insert New Subscription
										$this->insertUserSubscription($user_id, $subscription_id, $create_date_subscription, $end_date_subscription, $order_item_id);

										// Update user group
										$arr_group_update = explode("|", $resutl_pos[$tpi]['joomla_acl_groups']);
										$this->insertUserGroupMap($arr_group_update, $user_id);
									}
								}
							}
						}
					}
					else
					{
						// User had still subscription not expired
						if (count($result) > 0)
						{
							$resutl_pos = $this->getAllSubscriptionInAllOrder($result);

							if (count($resutl_pos) > 0)
							{
								for ( $tpi = 0; $tpi < count($resutl_pos); $tpi++)
								{
									// Check order_item_id again
									$result_check_ex              = $this->checkUserIsSubscriberNotExpired($user_id);
									$check_exist_subscription     = $this->checkExistSubscription($resutl_pos[$tpi]['order_item_id'], $result_check_ex);
									$check_update_subscription    = $this->checkUpdateSubscription($resutl_pos[$tpi]['subscription_id'], $result_check[$k]->subscription_id);

									// Extend Subscription
									if (($resutl_pos[$tpi]['subscription_id'] == $result_check[$k]->subscription_id) && ($check_exist_subscription == 1))
									{
										// No execute
									}

									if (($resutl_pos[$tpi]['subscription_id'] == $result_check[$k]->subscription_id) && ($check_exist_subscription == 0))
									{
										$num_product_in_an_order  = $this->getNumberProductInOrder($resutl_pos[$tpi]['order_item_id']);
										$subscription_during_time = $resutl_pos[$tpi]['subscription_during_time'] * $num_product_in_an_order;
										$time_update = $result_check[$k]->end_date_subscription + $subscription_during_time;

										// Update Subscription end_date
										$q1  = "UPDATE " . $this->_table_prefix . "users_subscription"
											. " SET end_date_subscription ='" . $time_update . "' "
											. " WHERE id ='" . $result_check[$k]->id . "' ";

										$this->_db->setQuery($q1);

										if (!$this->_db->query())
										{
											$this->setError($this->_db->getErrorMsg());
										}

										// Update OrderItemID
										$this->updateOrderItemid($resutl_pos[$tpi]['order_item_id'], $result_check[$k]->order_item_id, $result_check[$k]->id);

										// Check order_item_id again
										$result_check_ex = $this->checkUserIsSubscriberNotExpired($user_id);

										$check_exist_subscription  = $this->checkExistSubscription($resutl_pos[$tpi]['order_item_id'], $result_check_ex);
									}

									// Update Subscription
									if (($resutl_pos[$tpi]['subscription_id'] <> $result_check[$k]->subscription_id) && ($check_update_subscription == 1) && ($check_exist_subscription == 1))
									{
										// No execute
									}


									if (($resutl_pos[$tpi]['subscription_id'] <> $result_check[$k]->subscription_id) && ($check_update_subscription == 1) && ($check_exist_subscription == 0))
									{
										// Update Subscription id
										$q1  = "UPDATE " . $this->_table_prefix . "users_subscription"
											. " SET subscription_id ='" . $resutl_pos[$tpi]['subscription_id'] . "' "
											. " WHERE id ='" . $result_check[$k]->id . "' ";
										$this->_db->setQuery($q1);

										if (!$this->_db->query())
										{
											$this->setError($this->_db->getErrorMsg());
										}

										// Update OrderItemID
										$this->updateOrderItemid($resutl_pos[$tpi]['order_item_id'], $result_check[$k]->order_item_id, $result_check[$k]->id);

										// Update user group

										$arr_group_update = explode("|", $resutl_pos[$tpi]['joomla_acl_groups']);

										$this->insertUserGroupMap($arr_group_update, $user_id);

										// Check order_item_id again
										$result_check_ex2 = $this->checkUserIsSubscriberNotExpired($user_id);

										$check_exist_subscription  = $this->checkExistSubscription($resutl_pos[$tpi]['order_item_id'], $result_check_ex2);
									}

									// Insert Data with 2 subscription not unrelated

									if (($resutl_pos[$tpi]['subscription_id'] <> $result_check[$k]->subscription_id) && ($check_update_subscription == 0) && ($check_exist_subscription == 0))
									{
										$subscription_of_user = $this->checkUserIsSubscriberNotExpired($user_id);

										$arrs = "";

										if (count($subscription_of_user) > 0)
										{
											for ($r = 0; $r < count($subscription_of_user); $r++)
											{
												$arrs[] = $subscription_of_user[$r]->subscription_id;
											}
										}

										if (count($arrs) > 0)
										{
											$check_parent = $this->checkParentSubscription($resutl_pos[$tpi]['subscription_id'], $arrs);

											$check_extend = $this->checkExtendSubscription($resutl_pos[$tpi]['subscription_id'], $arrs);

											if ($check_parent || $check_extend)
											{
												// No execute
											}
											else
											{
												$subscription_id            = $resutl_pos[$tpi]['subscription_id'];
												$create_date_subscription   = $resutl_pos[$tpi]['create_date_subscription'];
												$end_date_subscription      = $resutl_pos[$tpi]['end_date_subscription'];
												$order_item_id              = $resutl_pos[$tpi]['order_item_id'];
												$num_product_in_an_order_ex = $this->getNumberProductInOrder($order_item_id);

												if ($num_product_in_an_order_ex > 1)
												{
													$end_date_subscription  = (($end_date_subscription - $create_date_subscription) * ($num_product_in_an_order_ex - 1)) + $end_date_subscription;
												}

												// Insert New Subscription
												$this->insertUserSubscription($user_id, $subscription_id, $create_date_subscription, $end_date_subscription, $order_item_id);

												// Update user group
												$arr_group_update = explode("|", $resutl_pos[$tpi]['joomla_acl_groups']);
												$this->insertUserGroupMap($arr_group_update, $user_id);
											}
										}
										else
										{
											$subscription_id            = $resutl_pos[$tpi]['subscription_id'];
											$create_date_subscription   = $resutl_pos[$tpi]['create_date_subscription'];
											$end_date_subscription      = $resutl_pos[$tpi]['end_date_subscription'];
											$order_item_id              = $resutl_pos[$tpi]['order_item_id'];
											$num_product_in_an_order_ex = $this->getNumberProductInOrder($order_item_id);

											if ($num_product_in_an_order_ex > 1)
											{
												$end_date_subscription  = (($end_date_subscription - $create_date_subscription) * ($num_product_in_an_order_ex - 1)) + $end_date_subscription;
											}

											// Insert New Subscription
											$this->insertUserSubscription($user_id, $subscription_id, $create_date_subscription, $end_date_subscription, $order_item_id);

											// Update user group
											$arr_group_update = explode("|", $resutl_pos[$tpi]['joomla_acl_groups']);
											$this->insertUserGroupMap($arr_group_update, $user_id);
										}
									}
								}
							}
						}
					}
				}
			}
			else
			{
				// User had never subscription
				for ( $i = 0;$i < count($result); $i++)
				{
					$query_x = $this->_db->getQuery(true);
					$query_x->select('s.* ');
					$query_x->from($this->_table_prefix . 'subscription as s');
					$query_x->where("s.product_id = " . $result[$i]->product_id . " ");
					$this->_db->setQuery($query_x);
					$result_x = $this->_db->loadObject();

					if (count($result_x) > 0)
					{
						$subscription_id          = $result_x->subscription_id;
						$create_date_subscription = $result[$i]->mdate;
						$order_item_id            = $result[$i]->order_item_id;
						$joomla_acl_groups        = $result_x->joomla_acl_groups;
						$end_date_subscription    = $this->getEndDateSubscription($result_x, $create_date_subscription);
						$num_product_in_an_order_x = $this->getNumberProductInOrder($order_item_id);

						if ($num_product_in_an_order_x > 1)
						{
							$end_date_subscription  = (($end_date_subscription - $create_date_subscription) * ($num_product_in_an_order_x - 1)) + $end_date_subscription;
						}

						if ($end_date_subscription > $today)
						{
							// Execute query insert data into table user_subscription

							$this->insertUserSubscription($user_id, $subscription_id, $create_date_subscription, $end_date_subscription, $order_item_id);

							// Execute query insert data into table user_group_map
							$arr_group = explode("|", $joomla_acl_groups);

							$this->insertUserGroupMap($arr_group, $user_id);
						}
					}
				}
			}
		}
		else
		{
			// No execute
		}
	}



	public function checkUserIsSubscriber($user_id)
	{
		$query_check_subscriber = $this->_db->getQuery(true);
		$query_check_subscriber->select('u.* ');
		$query_check_subscriber->from($this->_table_prefix . 'users_subscription as u');
		$query_check_subscriber->where("u.user_id = " . $user_id . "  ");
		$this->_db->setQuery($query_check_subscriber);
		$result_subscriber = $this->_db->loadObjectList();

		return $result_subscriber;

	}

	public function checkUserIsSubscriberNotExpired($user_id)
	{
		$today      = time();
		$query_check_subscriber_ex = $this->_db->getQuery(true);
		$query_check_subscriber_ex->select('u.* ');
		$query_check_subscriber_ex->from($this->_table_prefix . 'users_subscription as u');
		$query_check_subscriber_ex->where("u.user_id = " . $user_id . " AND u.end_date_subscription > " . $today);
		$this->_db->setQuery($query_check_subscriber_ex);
		$result_subscriber_ex = $this->_db->loadObjectList();

		return $result_subscriber_ex;
	}

	public function getAllSubscriptionInAllOrder($result)
	{
		$today = time();
		$resutl_pos = "";

		for ($pos = 0; $pos < count($result); $pos++)
		{
			$subscription_data             = $this->getSubscriptionData($result[$pos]->product_id);
			$subscription_data_end_date    = $this->getEndDateSubscription($subscription_data, $result[$pos]->mdate);
			$subscription_data_create_date = $result[$pos]->mdate;
			$subscription_during_time      = $subscription_data_end_date - $subscription_data_create_date;

			// Get all subscription not expired in $result (All orders)
			if ($subscription_data_end_date > $today)
			{
				$resutl_pos[$pos]['subscription_id']          = $subscription_data->subscription_id;
				$resutl_pos[$pos]['order_item_id']            = $result[$pos]->order_item_id;
				$resutl_pos[$pos]['subscription_during_time'] = $subscription_during_time;
				$resutl_pos[$pos]['joomla_acl_groups']        = $subscription_data->joomla_acl_groups;
				$resutl_pos[$pos]['create_date_subscription'] = $subscription_data_create_date;
				$resutl_pos[$pos]['end_date_subscription']    = $subscription_data_end_date;
			}
		}

		if (count($resutl_pos) > 0)
		{
			sort($resutl_pos);
		}

		return $resutl_pos;
	}

	public function getDataDetail($user_id)
	{
		$today   = time();
		$query = $this->_db->getQuery(true);
		$query
			->select(array('u.create_date_subscription ','u.end_date_subscription','u.subscription_id ', 's.subscription_period', 's.subscription_period_unit','s.subscription_applicable_products','p.*'))
			->from($this->_table_prefix . 'users_subscription AS u')
			->join('INNER', $this->_table_prefix . 'subscription AS s ON (u.subscription_id = s.subscription_id)')
			->join('INNER', $this->_table_prefix . 'product AS p ON (s.product_id = p.product_id)')
			->where("u.end_date_subscription > " . $today . " AND u.user_id =" . $user_id)
			->order('u.end_date_subscription DESC');
		$this->_db->setQuery($query);

		return  $this->_db->loadObjectList();
	}

	public function getDataDetailDownload($subscription_id)
	{
		$list    = "";
		$query1  = $this->_db->getQuery(true);
		$query1->select(array('s.subscription_id','s.subscription_applicable_products'));
		$query1->from($this->_table_prefix . 'subscription as s');
		$query1->where("s.subscription_id = " . $subscription_id);
		$this->_db->setQuery($query1);
		$result1 = $this->_db->loadObject();

		if (count($result1) > 0)
		{
			$result .= $result1->subscription_applicable_products;
			$temp_product = explode("|", $result);
			$temp_product = $this->removeNullInArray($temp_product);

			if (count($temp_product) > 0)
			{
				$ids = implode(",", $temp_product);
				$query2 = $this->_db->getQuery(true);
				$query2->select('p.* ');
				$query2->from($this->_table_prefix . 'product as p');
				$query2->where("p.product_id IN (" . $ids . ") ");
				$this->_db->setQuery($query2);
				$list = $this->_db->loadObjectList();
			}
		}

		return $list;
	}

	public function getDataSubscriptionDownload($user_id)
	{
		$today   = time();
		$query  = $this->_db->getQuery(true);
		$query->select(array('u.subscription_id','p.product_name as subscription_name','p.product_id'));
		$query->from($this->_table_prefix . 'users_subscription as u');
		$query->join('INNER', $this->_table_prefix . 'subscription AS s ON (u.subscription_id = s.subscription_id)');
		$query->join('INNER', $this->_table_prefix . 'product AS p ON (s.product_id = p.product_id)');
		$query->where("u.user_id = " . $user_id . " AND u.end_date_subscription > " . $today);
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();

		return $result;
	}



	public function removeNullInArray($appProducts)
	{
		$sum = count($appProducts);
		$i = 0;

		for (; $i < $sum; )
		{
			if ($appProducts[$i] == "")
			{
				unset($appProducts[$i]);
				sort($appProducts);
				$i = 0;
				$sum = count($appProducts);
			}
			else
			{
				$i++;
			}
		}

		return $appProducts;
	}


	public function getSubscriptionChildOrParent($subscription_id,$forward_direction)
	{
		$parent_id        = $subscription_id;
		$arr_all          = $this->setAllSubscriptionToArray($forward_direction);
		$subscription_all = $this->recursivelySubscription($parent_id, $arr_all);
		$result           = explode("|", $subscription_all);
		$result_x         = $this->removeNullInArray($result);
		$subids           = implode(",", $result_x);

		return $subids;
	}

	public function recursivelySubscription($parent_id, $arr_all, $res = '', $sep = '')
	{
		foreach ($arr_all as $v)
		{
			if ($v[1] == $parent_id)
			{
				$re  = $sep . $v[0] . "|";
				$res .= $this->recursivelySubscription($v[0], $arr_all, $re, $sep . "|");
			}
		}

		return $res;
	}



	public function getListDowngradeSubscription($subscription_id)
	{
		$forward_direction      = 1;
		$subscription_child_ids = $this->getSubscriptionChildOrParent($subscription_id, $forward_direction);
		$query                  = $this->_db->getQuery(true);
		$query
			->select(array('s.subscription_period','s.subscription_id', 's.subscription_period_unit','s.subscription_applicable_products','p.product_name','p.product_id'))
			->from($this->_table_prefix . 'subscription AS s')
			->join('INNER', $this->_table_prefix . 'product AS p ON (s.product_id = p.product_id)')
			->where("s.subscription_id IN (" . $subscription_child_ids . ") ")
			->order('p.product_name DESC');
		$this->_db->setQuery($query);

		return  $this->_db->loadObjectList();
	}

	public function getListExtendSubscription($subscription_id)
	{
		$query = $this->_db->getQuery(true);
		$query
			->select(array('s.subscription_period', 's.subscription_period_unit','s.subscription_applicable_products','p.product_name','p.product_id'))
			->from($this->_table_prefix . 'subscription AS s')
			->join('INNER', $this->_table_prefix . 'product AS p ON (s.product_id = p.product_id)')
			->where("s.subscription_id = " . $subscription_id);
		$this->_db->setQuery($query);

		return  $this->_db->loadObjectList();
	}

	public function getListUpdateSubscription($subscription_id)
	{
		$forward_direction       = 0;
		$subscription_parent_ids = $this->getSubscriptionChildOrParent($subscription_id, $forward_direction);
		$query = $this->_db->getQuery(true);
		$query
			->select(array('s.subscription_period', 's.subscription_period_unit','s.subscription_applicable_products','p.product_name','p.product_id'))
			->from($this->_table_prefix . 'subscription AS s')
			->join('INNER', $this->_table_prefix . 'product AS p ON (s.product_id = p.product_id)')
			->where("s.subscription_id IN (" . $subscription_parent_ids . ") ")
			->order('p.product_name DESC');
		$this->_db->setQuery($query);

		return  $this->_db->loadObjectList();
	}


	public function getEndDateSubscription($data,$start_date)
	{
		$start_date_standar = date("Y-m-d H:i:s", $start_date);
		$day_start_date     = date("d", strtotime($start_date_standar));
		$month_start_date   = date("m", strtotime($start_date_standar));
		$year_start_date    = date("Y", strtotime($start_date_standar));

		if ($data->subscription_period_unit == 'day')
		{
			$add_day = $data->subscription_period;
		}
		elseif ($data->subscription_period_unit == 'month')
		{
			$add_month = $data->subscription_period;
		}
		elseif ($data->subscription_period_unit == 'year')
		{
			$add_year = $data->subscription_period;
		}

		$end_date  = mktime(0, 0, 0, $month_start_date + $add_month, $day_start_date + $add_day, $year_start_date + $add_year);

		return $end_date;
	}


	public function getSubscriptionData($product_id)
	{
		$query = $this->_db->getQuery(true);
		$query
			->select('s.*')
			->from($this->_table_prefix . 'subscription AS s')
			->where("s.product_id = " . $product_id);
		$this->_db->setQuery($query);

		return  $this->_db->loadObject();
	}

	public function setAllSubscriptionToArray($forward_direction)
	{
		$data = $this->getAllSubscription();
		$arr  = "";

		if (count($data) > 0)
		{
			for ($i = 0; $i < count($data); $i++)
			{
				$row = $data[$i];
				$query_x = $this->_db->getQuery(true);
				$query_x
					->select('sx.subscription_parent_id')
					->from($this->_table_prefix . 'subscription_xref AS sx')
					->where("sx.subscription_child_id = " . $row->subscription_id);
				$this->_db->setQuery($query_x);
				$subscription_parent_id = $this->_db->loadResult();

				if ($forward_direction == 1)
				{
					// Arrray (subscription_id , subscription_parent_id)
					$arr[] = array($row->subscription_id,$subscription_parent_id);
				}
				elseif ($forward_direction == 0)
				{
					// Arrray (subscription_parent_id , subscription_id)
					$arr[] = array($subscription_parent_id, $row->subscription_id);
				}
			}
		}

		return $arr;
	}

	public function getAllSubscription()
	{
		$query = $this->_db->getQuery(true);
		$query
			->select('s.*')
			->from($this->_table_prefix . 'subscription AS s');
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}


	public function getNameProductMedia($media_id)
	{
		$query = " SELECT m.media_name FROM " . $this->_table_prefix . "media AS m "
				. " WHERE  m.media_id ='" . $media_id . "' AND media_type='download' ";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}


	public function getNameProduct($prosub)
	{
		$query = " SELECT m.media_name FROM " . $this->_table_prefix . "media AS m "
				. " WHERE section_id ='" . $prosub . "' AND media_type='download' "
				. " ORDER BY m.media_id DESC LIMIT 1";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function checkExistSubscription($order_iem1,$order_item2)
	{
		// Check all order_item in all order
		$arr = "";

		if (count($order_item2) > 0 )
		{
			for ($j = 0; $j < count($order_item2); $j++)
			{
				$order_item2_arr = explode("|", $order_item2[$j]->order_item_id);

				if (count($order_item2_arr) > 0)
				{
					for ($k = 0; $k < count($order_item2_arr); $k++)
					{
						$arr[] = $order_item2_arr[$k];
					}
				}
			}
		}

		if (count($arr) > 0)
		{
			for ( $i = 0; $i < count($arr); $i++)
			{
				if ($order_iem1 == $arr[$i])
				{
					return 1;
				}
			}

			return 0;
		}
	}

	public function getNumberProductInOrder($order_item)
	{
		$query = $this->_db->getQuery(true);
		$query
			->select('oi.product_quantity')
			->from($this->_table_prefix . 'order_item AS oi')
			->where("oi.order_item_id = " . $order_item);
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function checkUpdateSubscription($new_subscription_id,$old_subscription)
	{
		$forward_direction       = 0;
		$subscription_parent_ids = $this->getSubscriptionChildOrParent($old_subscription, $forward_direction);
		$subscription_parent_ids = explode(",", $subscription_parent_ids);

		if (count($subscription_parent_ids) > 0)
		{
			for ( $i = 0; $i < count($subscription_parent_ids); $i++)
			{
				if ($new_subscription_id == $subscription_parent_ids[$i])
				{
					return 1;
				}
			}

			return 0;
		}

		return 0;
	}

	public function checkParentSubscription ($subscription_id,$arr_old_subscription)
	{
		if (count($arr_old_subscription) > 0 )
		{
			for ($i = 0; $i < count($arr_old_subscription); $i++)
			{
				$check = $this->checkUpdateSubscription($subscription_id, $arr_old_subscription[$i]);

				if ($check)
				{
					return 1;
				}
			}
		}

		return 0;
	}

	public function checkParentSubscriptionPrice($subscription_id,$arr_old_subscription)
	{

		if (count($arr_old_subscription) > 0 )
		{
			for ($i = 0; $i < count($arr_old_subscription); $i++)
			{
				$check = $this->checkUpdateSubscription($subscription_id, $arr_old_subscription[$i]);

				if ($check)
				{
					return $arr_old_subscription[$i];
				}
			}
		}

		return 0;
	}

	public function checkExtendSubscription ($subscription_id,$arr_old_subscription)
	{
		if (count($arr_old_subscription) > 0)
		{
			for ($j = 0; $j < count($arr_old_subscription); $j++)
			{
				if ($subscription_id == $arr_old_subscription[$j])
				{
					return 1;
				}
			}
		}

		return 0;
	}

	public function updateOrderItemid($new_order_item, $old_order_item_arr, $id)
	{
		// Update OrderItemID
		$arr_order_item_id = explode("|", $old_order_item_arr);
		array_push($arr_order_item_id, $new_order_item);
		$array_final_order_item_id = implode("|", $arr_order_item_id);

		$q  = "UPDATE " . $this->_table_prefix . "users_subscription"
			. " SET order_item_id ='" . $array_final_order_item_id . "' "
			. " WHERE id ='" . $id . "' ";
			$this->_db->setQuery($q);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());
		}
	}

	public function insertUserGroupMap($joomla_acl_groups, $user_id)
	{
		if (count($joomla_acl_groups) > 0)
		{
			for ($j = 0; $j < count($joomla_acl_groups); $j++)
			{
				$group_id = $joomla_acl_groups[$j];
				$query_k  = $this->_db->getQuery(true);
				$query_k->select('um.* ');
				$query_k->from('#__user_usergroup_map as um');
				$query_k->where("um.group_id = " . $group_id . " AND um.user_id = " . $user_id . "  ");
				$this->_db->setQuery($query_k);
				$result_k = $this->_db->loadObject();

				if (count($result_k) > 0)
				{
					// No execute
				}
				else
				{
					$query_m   = $this->_db->getQuery(true);
					$columns_m = array('user_id', 'group_id');
					$values_m  = array($user_id, $group_id);
					$query_m
							->insert($this->_db->quoteName('#__user_usergroup_map'))
							->columns($this->_db->quoteName($columns_m))
							->values(implode(',', $values_m));
					$this->_db->setQuery($query_m);

					try
					{
						$result_m = $this->_db->query();
					}
					catch (Exception $e)
					{
						// Catch any database errors.
					}
				}
			}
		}
	}

	public function insertUserSubscription($user_id, $subscription_id, $create_date_subscription, $end_date_subscription,$order_item_id)
	{
		$query_z = $this->_db->getQuery(true);
		$columns = array('user_id', 'subscription_id', 'create_date_subscription', 'end_date_subscription', 'order_item_id');
		$values  = array($user_id, $subscription_id, $create_date_subscription, $end_date_subscription,$order_item_id);
		$query_z
				->insert($this->_db->quoteName('#__redshop_users_subscription'))
				->columns($this->_db->quoteName($columns))
				->values(implode(',', $values));
		$this->_db->setQuery($query_z);

		try
		{
			$result_z = $this->_db->query();
		}
		catch (Exception $e)
		{
			// Catch any database errors.
		}
	}

	public function checkDiscountProduct($product)
	{
		if ($product->product_on_sale > 0)
		{
			if (($product->discount_price != 0 && $product->discount_price < $product->product_price) && ($product->discount_enddate != 0 && $product->discount_stratdate != 0) && ($product->discount_stratdate <= time() && $product->discount_enddate > time()) )
			{
				$product_price = $product->discount_price;
			}
			else
			{
				$product_price = $product->product_price;
			}
		}
		else
		{
			$product_price = $product->product_price;
		}

		return $product_price;
	}

	public function getDataDetailProduct($subscription_id)
	{
		$query  = $this->_db->getQuery(true);
		$query->select('p.*');
		$query->from($this->_table_prefix . 'subscription as s');
		$query->join('INNER', $this->_table_prefix . 'product AS p ON (s.product_id = p.product_id)');
		$query->where("s.subscription_id = " . $subscription_id);
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	public function getDataDetailSubscription($user_id,$subscription_id)
	{
		$today   = time();
		$query = $this->_db->getQuery(true);
		$query
			->select(array('u.create_date_subscription ','u.end_date_subscription','u.subscription_id ', 's.subscription_period', 's.subscription_period_unit','s.subscription_applicable_products','p.*'))
			->from($this->_table_prefix . 'users_subscription AS u')
			->join('INNER', $this->_table_prefix . 'subscription AS s ON (u.subscription_id = s.subscription_id)')
			->join('INNER', $this->_table_prefix . 'product AS p ON (s.product_id = p.product_id)')
			->where("u.end_date_subscription > " . $today . " AND u.user_id = " . $user_id . " AND u.subscription_id = " . $subscription_id)
			->order('u.end_date_subscription DESC');
		$this->_db->setQuery($query);

		return  $this->_db->loadObject();
	}

	public function getPriceProuctViaSubscription($user_id,$subscription_id_new)
	{
		$today   = time();
		$product_price = "";
		$all_subscription_of_user = $this->getDataDetail($user_id);

		if (count($all_subscription_of_user) > 0)
		{
			$arr_old = "";

			for ($n = 0; $n < count($all_subscription_of_user); $n++)
			{
				$arr_old[] = $all_subscription_of_user[$n]->subscription_id;
			}

			if (count($arr_old) > 0)
			{
				$child_sub_id = $this->checkParentSubscriptionPrice($subscription_id_new, $arr_old);

				if ($child_sub_id > 0)
				{
					// Calculate price product child - Parent
					$data_product_child      = $this->getDataDetailProduct($child_sub_id);
					$data_product_parent     = $this->getDataDetailProduct($subscription_id_new);
					$product_child_price     = $this->checkDiscountProduct($data_product_child);
					$product_parent_price    = $this->checkDiscountProduct($data_product_parent);

					// Calculate time_life
					$data_subscription_child = $this->getDataDetailSubscription($user_id, $child_sub_id);
					$time_life               = ceil(($data_subscription_child->end_date_subscription - $today) / 86400);

					if ($product_parent_price > 0 && $product_child_price > 0)
					{
						$product_price = $product_parent_price - ceil(($product_child_price / 365) * $time_life);
					}
				}
			}
		}

		if ($product_price > 0)
		{
			return $product_price;
		}
		else
		{
			return 0;
		}
	}

	public function checkProductInSubscription($subscription_id,$product_id)
	{
		$query1  = $this->_db->getQuery(true);
		$query1->select(array('s.subscription_id','s.subscription_applicable_products'));
		$query1->from($this->_table_prefix . 'subscription as s');
		$query1->where("s.subscription_id = " . $subscription_id);
		$this->_db->setQuery($query1);
		$result1 = $this->_db->loadObject();

		if (count($result1) > 0)
		{
			$result .= $result1->subscription_applicable_products;
			$temp_product = explode("|", $result);
			$temp_product = $this->removeNullInArray($temp_product);

			if (count($temp_product) > 0)
			{
				for ($i = 0; $i < count($temp_product); $i++)
				{
					if ($product_id == $temp_product[$i])
					{
						return 1;
					}
				}
			}

			return 0;
		}
	}
}
