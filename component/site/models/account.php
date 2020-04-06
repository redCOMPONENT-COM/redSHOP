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
 * Class accountModelaccount
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelAccount extends RedshopModel
{
	/**
	 * @var null
	 */
	public $_id = null;

	/**
	 * @var null
	 */
	public $_data = null;

	/**
	 * @var null
	 */
	public $_table_prefix = null;

	/**
	 * @var  JPagination
	 */
	public $_pagination;

	/**
	 * @var integer
	 */
	public $_total;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	/**
	 * Get user Account information
	 *
	 * @param   integer $uid User ID
	 *
	 * @return  object|boolean
	 */
	public function getUserAccountInfo($uid)
	{
		$user    = JFactory::getUser();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');
		$list    = new stdClass;

		if ($user->id)
		{
			$list = RedshopHelperOrder::getBillingAddress($user->id);
		}
		elseif ($auth['users_info_id'])
		{
			$uid  = -$auth['users_info_id'];
			$list = RedshopHelperOrder::getBillingAddress($uid);
		}

		if (!empty($list))
		{
			$list->email = $list->user_email;
		}

		return $list;
	}

	/**
	 * Method for get current account detail
	 *
	 * @return null|object[]
	 * @throws Exception
	 */
	public function getMyDetail()
	{
		/** @var JApplicationSite $app */
		$app = JFactory::getApplication();

		$start = $app->input->getInt('limitstart', 0);
		$limit = $app->getParams()->get('maxcategory');

		if (empty($this->_data))
		{
			$query = $this->_buildQuery();

			if ($query)
			{
				$this->_data = $this->_getList($query, $start, $limit);
			}
		}

		return $this->_data;
	}

	/**
	 * Method for build query
	 *
	 * @return JDatabaseQuery|boolean
	 *
	 * @throws Exception
	 */
	public function _buildQuery()
	{
		$app    = JFactory::getApplication();
		$layout = $app->input->getCmd('layout', '');

		if ($layout !== 'mytags' && $layout !== 'mywishlist')
		{
			return false;
		}

		$tagId      = $app->input->getInt('tagid', 0);
		$wishListId = $app->input->getInt('wishlist_id', 0);
		$userId     = JFactory::getUser()->id;
		$db         = JFactory::getDbo();
		$query      = $db->getQuery(true);

		// Layout: mytags
		if ($layout == 'mytags')
		{
			$query->select('DISTINCT pt.*')
				->from($db->qn('#__redshop_product_tags', 'pt'))
				->leftJoin(
					$db->qn('#__redshop_product_tags_xref', 'ptx')
					. ' ON ' . $db->qn('pt.tags_id') . ' = ' . $db->qn('ptx.tags_id')
				)
				->where($db->qn('ptx.users_id') . ' = ' . (int) $userId)
				->where($db->qn('pt.published') . ' = 1');

			if ($tagId != 0)
			{
				$query->select(array('ptx.product_id', 'p.*'))
					->leftJoin(
						$db->qn('#__redshop_product', 'p')
						. ' ON ' . $db->qn('ptx.product_id') . ' = ' . $db->qn('p.product_id')
					)
					->where($db->qn('pt.tags_id') . ' = ' . (int) $tagId);
			}

			return $query;
		}

		// Layout: mywishlist
		if ($userId && $wishListId)
		{
			$query->select('DISTINCT(' . $db->qn('w.wishlist_id') . ')')
				->select(array('w.*', 'p.*'))
				->from($db->qn('#__redshop_wishlist', 'w'))
				->leftJoin($db->qn('#__redshop_wishlist_product', 'pw') . ' ON w.wishlist_id = pw.wishlist_id')
				->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = pw.product_id')
				->where('w.user_id = ' . (int) $userId)
				->where('w.wishlist_id = ' . (int) $wishListId)
				->where('pw.wishlist_id = ' . (int) $wishListId);

			return $query;
		}

		// Add this code to send wishlist while user is not logged in ...
		$productIds = array();

		if (isset($_SESSION["no_of_prod"]))
		{
			for ($index = 1; $index <= $_SESSION["no_of_prod"]; $index++)
			{
				if ($_SESSION['wish_' . $index]->product_id != '')
				{
					$productIds[] = (int) $_SESSION['wish_' . $index]->product_id;
				}
			}

			$productIds[] = (int) $_SESSION['wish_' . $index]->product_id;
		}

		if (!empty($productIds))
		{
			$query->select('p.*')
				->from($db->quoteName('#__redshop_product', 'p'))
				->where('p.product_id IN (' . implode(',', $productIds) . ')');
		}

		return $query;
	}

	/**
	 * Method for get pagination
	 *
	 * @return JPagination
	 * @throws Exception
	 */
	public function getPagination()
	{
		/** @var JApplicationSite $app */
		$app   = JFactory::getApplication();
		$start = $app->input->getInt('limitstart', 0);
		$limit = $app->getParams()->get('maxcategory', 5);

		if (empty($this->_pagination))
		{
			JLoader::import('joomla.html.pagination');

			$this->_pagination = new JPagination($this->getTotal(), $start, $limit);
		}

		return $this->_pagination;
	}

	/**
	 * Get total data
	 *
	 * @return  integer
	 * @throws Exception
	 */
	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query        = $this->_buildQuery();
			$this->_total = 0;

			if ($query)
			{
				$this->_total = $this->_getListCount($query);
			}
		}

		return $this->_total;
	}

	/**
	 * Remove product from wishlist
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function removeWishlistProduct()
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDbo();

		$itemId            = $app->input->getInt('Itemid', 0);
		$wishListId        = $app->input->getInt('wishlist_id', 0);
		$pid               = $app->input->getInt('pid', 0);
		$wishlistProductId = $app->input->getInt('wishlist_product_id', 0);

		$user = JFactory::getUser();

		// Check is user have access to wishlist
		$query = $db->getQuery(true)
			->select('wishlist_id')
			->from($db->quoteName('#__redshop_wishlist'))
			->where('user_id = ' . (int) $user->id)
			->where('wishlist_id = ' . (int) $wishListId);

		echo "<pre>";

		$list = $db->setQuery($query)->loadResult();

		if (count($list) > 0)
		{
			$query->clear()
				->delete($db->quoteName('#__redshop_wishlist_product'))
				->where('product_id = ' . (int) $pid)
				->where('wishlist_id = ' . (int) $wishListId);

			if ($wishlistProductId)
			{
				$query->where($db->qn('wishlist_product_id') . ' = ' . $wishlistProductId);
			}

			$db->setQuery($query);

			if ($db->execute())
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_WISHLIST_PRODUCT_DELETED_SUCCESSFULLY'));
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DELETING_WISHLIST_PRODUCT'));
			}
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_YOU_DONT_HAVE_ACCESS_TO_DELETE_THIS_PRODUCT'));
		}

		$app->redirect(
			JRoute::_(
				'index.php?option=com_redshop&wishlist_id=' . $wishListId . '&view=account&layout=mywishlist&Itemid=' . $itemId,
				false
			)
		);
	}

	/**
	 * Method for remove tag
	 *
	 * @return  void
	 * @throws Exception
	 */
	public function removeTag()
	{
		$app = JFactory::getApplication();

		$itemId = $app->input->getInt('Itemid', 0);
		$tagId  = $app->input->getInt('tagid', 0);

		if ($this->removeTags($tagId))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_TAG_DELETED_SUCCESSFULLY'));
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DELETING_TAG'));
		}

		$app->redirect(JRoute::_('index.php?option=com_redshop&view=account&layout=mytags&Itemid=' . $itemId));
	}

	/**
	 * Method for remove tags
	 *
	 * @param   integer $tagId Tag ID
	 *
	 * @return  boolean
	 * @throws  Exception
	 */
	public function removeTags($tagId)
	{
		$user  = JFactory::getUser();
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__redshop_product_tags_xref'))
			->where('tags_id = ' . (int) $tagId)
			->where('users_id = ' . (int) $user->id);

		if (!$db->setQuery($query)->execute())
		{
			return false;
		}

		$query->clear()
			->select('COUNT(tags_id)')
			->from($db->quoteName('#__redshop_product_tags_xref'))
			->where('tags_id =' . (int) $tagId);;

		// If this tag still have reference with other products. Return
		if ($db->setQuery($query)->loadResult() > 0)
		{
			return true;
		}

		// Delete this tags if not have any reference
		$query->clear()
			->delete($db->quoteName('#__redshop_product_tags'))
			->where('tags_id = ' . (int) $tagId);

		return $db->setQuery($query)->execute();
	}

	/**
	 * Method for get my tag
	 *
	 * @param   integer $tagId Tag Id
	 *
	 * @return  integer
	 */
	public function getMyTag($tagId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('tags_name')
			->from($db->quoteName('#__redshop_product_tags'))
			->where('tags_id = ' . (int) $tagId);

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for update tag
	 *
	 * @param   array $post Tag Id
	 *
	 * @return  boolean
	 */
	public function editTag($post)
	{
		if (empty($post) || empty($post['tag_id']))
		{
			return false;
		}

		/** @var Tableproduct_tags $table */
		$table = RedshopTable::getInstance('product_tags', 'Table');

		if (!$table->load($post['tag_id']))
		{
			return false;
		}

		$table->tags_name = $post['tags_name'];

		return $table->store();
	}

	/**
	 * Method for remove compare
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function removeCompare()
	{
		$app       = JFactory::getApplication();
		$itemId    = $app->input->get('Itemid');
		$productId = $app->input->getInt('pid', 0);
		$db        = $this->getDbo();

		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_product_compare'))
			->where($db->qn('product_id') . ' = ' . $productId)
			->where($db->qn('user_id') . ' = ' . JFactory::getUser()->id);

		if ($db->setQuery($query)->execute())
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_PRODUCT_DELETED_FROM_COMPARE_SUCCESSFULLY'));
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DELETING_PRODUCT_FROM_COMPARE'));
		}

		$app->redirect(JRoute::_('index.php?option=com_redshop&view=account&layout=compare&Itemid=' . $itemId, false));
	}

	/**
	 * Function to delete account user
	 *
	 * @param   int $userId User Id
	 *
	 * @return  boolean
	 *
	 * @since   2.1.2
	 */
	public function deleteAccount($userId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__users'))
			->where($db->qn('id') . ' = ' . $userId);

		if (!$db->setQuery($query)->execute())
		{
			return false;
		}

		$query->clear()
			->delete($db->qn('#__redshop_order_users_info'))
			->where($db->qn('user_id') . ' = ' . $userId);

		if (!$db->setQuery($query)->execute())
		{
			return false;
		}

		$query->clear()
			->delete($db->qn('#__redshop_users_info'))
			->where($db->qn('user_id') . ' = ' . $userId);

		return (boolean) $db->setQuery($query)->execute();
	}
}
