<?php
/**
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Plugin rs_menuitem_sh404urls
 * @extends JPlugin
 */
class PlgContentRs_Menuitem_Sh404urls extends JPlugin
{
	public function onContentAfterSave($context, $item, $isNew)
	{
		if ($context !== 'com_menus.item')
		{
			return true;
		}

		if (!$isNew)
		{
			return true;
		}

		$db      = JFactory::getDbo();
		$query   = $db->getQuery(true);
		$results = $db->setQuery('SHOW TABLES')->loadColumn();
		$table   = $db->getPrefix() . 'sh404sef_urls';

		if (empty($results) || !in_array($table, $results))
		{
			return true;
		}

		$link = $item->link;

		if (empty($link))
		{
			return true;
		}

		$cid = '';
		$categoryIds  = [];
		$conds = [];
		$explodeLink = explode("&cid=", $link);

		if (isset($explodeLink[1])) {
			$explodeLink = ($explodeLink[1]);
			$cid = explode("&",$explodeLink)[0];
		}

		if (empty($cid))
		{
			return true;
		}

		$categoryIds[] = $cid;

		$query->clear()
			->select($db->qn('parent_id'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('id') . '=' . (int) $cid);
		$parentId = $db->setQuery($query)->loadResult();

		$categoryIds[] = $parentId;
		$categoryChildren = RedshopHelperCategory::getCategoryTree($cid);

		foreach ($categoryChildren as $categoryChild)
		{
			$categoryIds[] = $categoryChild->id;
		}

		foreach ($categoryIds as $categoryId)
		{
			$conds[] = $db->qn('newurl') . ' LIKE ' . $db->q('%cid=' . (int) $categoryId . '&%');
		}

		$query->clear()
			->delete($db->qn($table))
			->where($db->qn('newurl') . ' LIKE ' . $db->q('%option=com_redshop%'))
			->where('(' . $db->qn('newurl') . ' LIKE ' . $db->q('%view=category%'). 'OR' . $db->qn('newurl') . ' LIKE ' . $db->q('%view=product%') . ')')
			->where('(' . implode(' OR ', $conds) . ')');

		return $db->setQuery($query)->execute();
	}
}
