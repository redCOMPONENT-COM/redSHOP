<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.modellist');

JLoader::load('RedshopHelperAdminCategory');
JLoader::load('RedshopHelperProduct');

/**
 * Class searchModelsearch
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelSearch extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'product_id', 'p.product_id',
				'product_name', 'p.product_name',
				'product_price', 'p.product_price',
				'product_number', 'p.product_number',
				'ordering ASC', 'pc.ordering'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('p.product_name', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return	string  A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':' . $this->getState('filter.product_name');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 */
	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'*'
			)
		);

		$query->from('#__redshop_product AS p');

		return $query;
	}

	public function getCategoryTemplet()
	{
		$app = JFactory::getApplication();
		$context = 'search';

		$layout     = $app->getUserStateFromRequest($context . 'layout', 'layout', '');
		$templateid = $app->getUserStateFromRequest($context . 'templateid', 'templateid', '');

		$params = JComponentHelper::getParams('com_redshop');
		$menu   = $app->getMenu();
		$item   = $menu->getActive();
		$cid 	= 0;

		$cid = 0;

		if ($layout == 'newproduct')
		{
			$cid = $item->query['categorytemplate'];
		}
		elseif ($layout == 'productonsale')
		{
			$cid = $item->params->get('categorytemplate');
		}

		if ($layout == 'productonsale' || $layout == 'featuredproduct')
		{
			$templateid = $item->params->get('template_id');

			if ($templateid != 0)
			{
				$cid = 0;
			}

			if ($templateid == 0 && $cid == 0)
			{
				$templateid = $app->getUserStateFromRequest($context . 'templateid', 'templateid', '');
			}
		}

		if ($templateid == "" && JModuleHelper::isEnabled('redPRODUCTFILTER'))
		{
			$module        = JModuleHelper::getModule('redPRODUCTFILTER');
			$module_params = new JRegistry($module->params);

			if ($module_params->get('filtertemplate') != "")
			{
				$templateid = $module_params->get('filtertemplate');
			}
		}

		$and = "";

		if ($cid != 0)
		{
			$and .= " AND c.category_id = " . (int) $cid . " ";
		}

		if ($templateid != 0)
		{
			$and .= " AND t.template_id = " . (int) $templateid . " ";
		}

		$query = "SELECT c.category_template, t.* FROM #__redshop_template AS t "
			. "LEFT JOIN #__redshop_category AS c ON t.template_id = c.category_template "
			. "WHERE t.template_section='category' AND t.published=1 "
			. $and;

		return $this->_getList($query);
	}

	/**
	 * Red Product Filter
	 */
	public function getRedFilterProduct($remove = 0)
	{
		// Get seeion filter data

		$session = JSession::getInstance('none', array());

		// Get filter types and tags
		$getredfilter = $session->get('redfilter');

		$type_id_main = explode('.', JRequest::getVar('tagid'));

		// Initialise variables
		$lstproduct_id = array();
		$lasttypeid    = 0;
		$lasttagid     = 0;
		$productid     = 0;
		$products      = "";

		if (count($getredfilter) != 0)
		{
			$main_sal_sp   = array();
			$main_sal_type = array();
			$main_sal_tag  = array();

			if (JRequest::getVar('main_sel') != "")
			{
				$main_sal_sp = explode(",", JRequest::getVar('main_sel'));

				for ($f = 0; $f < count($main_sal_sp); $f++)
				{
					if ($main_sal_sp[$f] != "")
					{
						$main_typeid     = explode(".", $main_sal_sp[$f]);
						$main_sal_type[] = $main_typeid[1];
						$main_sal_tag[]  = $main_typeid[0];
					}
				}
			}

			$q = "SELECT a.product_id
						  FROM #__redproductfinder_association_tag AS ta
						  LEFT JOIN #__redproductfinder_associations AS a ON a.id = ta.association_id
						  LEFT JOIN #__redshop_product AS p ON p.product_id = a.product_id
						  LEFT JOIN #__redshop_product_category_xref x ON x.product_id = a.product_id ";

			for ($i = 0; $i < count($main_sal_type); $i++)
			{
				if ($i != 0)
				{
					$q .= " LEFT JOIN #__redproductfinder_association_tag AS t" . $i . " ON t" . $i . ".association_id=ta.association_id ";
				}
			}

			$q .= "where ( ";
			$dep_cond = array();

			for ($i = 0; $i < count($main_sal_type); $i++)
			{
				$chk_q = "";

				// Search for checkboxes
				if ($i != 0)
				{
					$chk_q .= "t" . $i . ".tag_id='" . (int) $main_sal_tag[$i] . "' ";
				}
				else
				{
					$chk_q .= "ta.tag_id='" . (int) $main_sal_tag[$i] . "' ";
				}

				if ($chk_q != "")
				{
					$dep_cond[] = " ( " . $chk_q . " ) ";
				}
			}

			if (count($dep_cond) <= 0)
			{
				$dep_cond[] = "1=1";
			}

			$q .= implode(" AND ", $dep_cond);

			$q .= ") AND p.published = '1' AND x.category_id = " . (int) JRequest::getInt('cid', 0) . " order by p.product_name ";
			$product = $this->_getList($q);

			for ($i = 0; $i < count($product); $i++)
			{
				$lstproduct_id[] = $product[$i]->product_id;
			}

			$products = implode(",", $lstproduct_id);
		}
		else
		{
			$session->set('redfilterproduct', array());
		}

		return $products;
	}

	public function mod_redProductfilter($Itemid)
	{
		$db = JFactory::getDbo();
		$query = "SELECT t.*, f.formname AS form_name FROM #__redproductfinder_types t
		LEFT JOIN #__redproductfinder_forms f
		ON t.form_id = f.id
		ORDER BY ordering";

		$types   = $this->_getList($query);
		$session = JSession::getInstance('none', array());

		$getredfilter = $session->get('redfilter');

		$redfilterproduct = $session->get('redfilterproduct');

		$redproducttotal = count($redfilterproduct);

		foreach ($types as $key => $type)
		{
			if (@!array_key_exists($type->id, $getredfilter))
			{
				$str                        = htmlentities($type->type_name, ENT_COMPAT, "UTF-8");
				$str                        = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|elig|slash|ring);/', '$1', $str);
				$str                        = str_replace(' ', '', $str);
				$types[$key]->type_name_css = html_entity_decode($str);

				$id         = $type->id;
				$all        = 1;
				$productids = "";

				if (count($getredfilter) > 0 && $all == 1)
				{
					$type_id = array();
					$tag_id  = array();

					$k = 0;

					foreach ($getredfilter as $typeid => $tags)
					{
						$type_id[] = $typeid;
						$tags      = explode(".", $tags);
						$tag_id[]  = $tags[0];

						if (count($getredfilter) - 1 == $k)
						{
							$lasttypeid = $typeid;
							$lasttagid  = $tags[0];
						}

						$k++;
					}

					$typeids = implode(",", $type_id);
					$tagids  = implode(",", $tag_id);

					$query = "SELECT ra.product_id FROM `#__redproductfinder_association_tag` as rat
					LEFT JOIN #__redproductfinder_associations as ra ON rat.`association_id` = ra.id
					WHERE  rat.`type_id` = " . $db->quote($lasttypeid) . " ";

					$query .= "AND  rat.`tag_id` = " . $db->quote($lasttagid) . " ";

					$product = $this->_getList($query);

					$products = array();

					for ($i = 0; $i < count($product); $i++)
					{
						$products[] = $product[$i]->product_id;
					}

					JArrayHelper::toInteger($products);
					$productids = implode(",", $products);
				}

				$q = "SELECT DISTINCT j.tag_id as tagid ,ra.product_id,count(ra.product_id) as ptotal ,CONCAT(j.tag_id,'.',j.type_id) AS tag_id, t.tag_name
			FROM ((#__redproductfinder_tag_type j, #__redproductfinder_tags t )
			LEFT JOIN #__redproductfinder_association_tag as rat ON  t.`id` = rat.`tag_id`)
			LEFT JOIN #__redproductfinder_associations as ra ON ra.id = rat.association_id
			WHERE j.tag_id = t.id
			AND j.type_id = " . (int) $id . "  ";

				if ($productids != "")
				{
					// Sanitize ids
					$productIds = explode(',', $productids);
					JArrayHelper::toInteger($productIds);

					$q .= " AND ra.product_id  IN ( " . implode(',', $productIds) . " ) ";
				}
				$q .= " GROUP BY t.id ORDER BY t.ordering  ";

				$tags = $this->_getList($q);

				$tagname = "";

				// Only show if the type has tags
				if (count($tags) > 0)
				{
					// Create the selection boxes
					for ($t = 0; $t < count($tags); $t++)
					{
						$type_id = explode('.', $tags[$t]->tag_id);

						$query = "SELECT count(*) as count FROM #__redproductfinder_association_tag as ra
							left join #__redproductfinder_associations as a on ra.association_id = a.id
							left join #__redshop_product as rp on rp.product_id = a.product_id
							WHERE type_id = " . $db->quote($type_id[1]) . " AND tag_id = " . $db->quote($type_id[0]) . " AND rp.published = 1";

						$published = $this->_getList($query);

						if ($published[0]->count > $redproducttotal && $redproducttotal > 0)
						{
							$finalcount = $redproducttotal;
						}
						else
						{
							$finalcount = $published[0]->count;
						}

						if ($finalcount > 0)
						{
							$tagname .= "&nbsp;&nbsp;<a  href='" . JRoute::_('index.php?option=com_redshop&view=search&layout=redfilter&typeid=' . $type->id . '&tagid=' . $tags[$t]->tag_id . '&Itemid=' . $Itemid) . "' title='" . $tags[$t]->tag_name . "' >" . $tags[$t]->tag_name . "</a> ( " . $finalcount . " )<br/>";
						}
					}

					if ($tagname != "")
					{
						$lists['type' . $key] = $tagname;
					}
				}
				else
				{
					unset($types[$key]);
				}
			}
		}

		if (count($getredfilter) != 0)
		{
			foreach ($getredfilter as $typeid => $tag_id)
			{
				foreach ($types as $key => $type)
				{
					if ($typeid == $type->id)
					{
						$str                        = htmlentities($type->type_name, ENT_COMPAT, "UTF-8");
						$str                        = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|elig|slash|ring);/', '$1', $str);
						$str                        = str_replace(' ', '', $str);
						$types[$key]->type_name_css = html_entity_decode($str);

						$tags = $this->getTagsDetail($type->id, 0);

						$tagname = "";

						// Only show if the type has tags
						if (count($tags) > 0)
						{
							// Create the selection boxes
							for ($t = 0; $t < count($tags); $t++)
							{
								if ($tags[$t]->tagid == $tag_id)
								{
									$tagname .= "<span style='float:left;'>&nbsp;&nbsp;" . $tags[$t]->tag_name . "</span><span style='float:right;'><a href='javascript:deleteTag(\"$type->id\",\"$Itemid\");' title='" . JText::_('COM_REDSHOP_DELETE') . "' >" . JText::_('COM_REDSHOP_DELETE') . "</a></span><br/>";
								}
							}

							if ($tagname != "")
							{
								$filteredlists['type' . $key] = $tagname;
							}
						}
						else
						{
							unset($types[$key]);
						}
					}
				}
			}
		}

		if (count($getredfilter) != 0)
		{
			?>
			<div id="pfsearchheader"><?php echo JText::_('COM_REDSHOP_SEARCH_RESULT');?></div>

			<div class="hrdivider"></div>
			<?php
			foreach ($getredfilter as $typeid => $tag_id)
			{
				foreach ($types as $key => $type)
				{
					if ($typeid == $type->id)
					{
						?>
						<div id="typename_<?php echo $type->id; ?>"
						     class="typename <?php echo $type->type_name_css; ?>">
							<?php echo $type->type_name; ?>
							<?php
							if (strlen($type->tooltip) > 0)
							{
								echo ' ' . JHTML::tooltip($type->tooltip, $type->type_name, 'tooltip.png', '', '', false);
							} ?>
						</div>
						<div id="typevalue_<?php echo $type->id; ?>"
						     class="typevalue <?php echo $type->type_name_css; ?>">
							<?php echo $filteredlists['type' . $key];?></div>
						<div class="hrdivider <?php echo $type->type_name_css; ?>"></div>

					<?php
					}
				}
			}
			?>
			<div>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=search&layout=redfilter&remove=1&Itemid=' . $Itemid); ?>"
				   title="<?php echo JText::_('COM_REDSHOP_CLEAR_ALL'); ?>">
					<?php echo JText::_('COM_REDSHOP_CLEAR_ALL'); ?></a>
			</div>
			<div id="spacer">&nbsp;_________________________</div>
		<?php
		}

		if (count($types) > 0)
		{
			?>
			<div id="pfsearchheader"><?php echo JText::_('COM_REDSHOP_SEARCH_CRITERIA');?></div>

			<div class="hrdivider"></div>
			<?php

			foreach ($types as $key => $type)
			{
				if (@!array_key_exists($type->id, $getredfilter) && @array_key_exists('type' . $key, $lists))
				{
					?>
					<div id="<?php echo $type->id; ?>"
					     class="typename <?php echo $type->type_name_css; ?>">
						<?php echo $type->type_name; ?>
						<?php
						if (strlen($type->tooltip) > 0)
						{
							echo ' ' . JHTML::tooltip($type->tooltip, $type->type_name, 'tooltip.png', '', '', false);
						}    ?>
					</div>
					<div class="typevalue <?php echo $type->type_name_css; ?>">
						<?php echo $lists['type' . $key];?></div>
					<div class="hrdivider <?php echo $type->type_name_css; ?>"></div>
				<?php
				}
			}
		}
	}

	public function getTagsDetail($id, $all = 1)
	{
		// For session
		$session      = JSession::getInstance('none', array());
		$getredfilter = $session->get('redfilter');
		$db           = JFactory::getDbo();
		$productids   = "";

		if (count($getredfilter) > 0 && $all == 1)
		{
			$type_id = array();
			$tag_id  = array();
			$k       = 0;

			foreach ($getredfilter as $typeid => $tags)
			{
				$type_id[] = $typeid;
				$tags      = explode(".", $tags);
				$tag_id[]  = $tags[0];

				if (count($getredfilter) - 1 == $k)
				{
					$lasttypeid = $typeid;
					$lasttagid  = $tags[0];
				}

				$k++;
			}

			$typeids = implode(",", $type_id);
			$tagids  = implode(",", $tag_id);

			$query = "SELECT ra.product_id FROM #__redproductfinder_association_tag AS rat "
				. "LEFT JOIN #__redproductfinder_associations AS ra ON rat.association_id = ra.id "
				. "WHERE rat.type_id = " . $db->quote($lasttypeid) . " "
				. "AND rat.tag_id = " . $db->quote($lasttagid) . " ";
			$db->setQuery($query);
			$product  = $db->loadObjectList();
			$products = array();

			for ($i = 0; $i < count($product); $i++)
			{
				$products[] = $product[$i]->product_id;
			}
		}

		$q = "SELECT DISTINCT j.tag_id AS tagid,ra.product_id,count(ra.product_id) AS ptotal, "
			. "CONCAT(j.tag_id,'.',j.type_id) AS tag_id, t.tag_name "
			. "FROM ((#__redproductfinder_tag_type j, #__redproductfinder_tags t ) "
			. "LEFT JOIN #__redproductfinder_association_tag as rat ON  t.`id` = rat.`tag_id`) "
			. "LEFT JOIN #__redproductfinder_associations as ra ON ra.id = rat.association_id "
			. "WHERE j.tag_id = t.id "
			. "AND j.type_id = " . (int) $id . " ";

		if ($productids != "")
		{
			// Sanitize ids
			JArrayHelper::toInteger($products);

			$q .= " AND ra.product_id IN (" . implode(",", $products) . ") ";
		}

		$q .= " GROUP BY t.id ORDER BY t.ordering ";
		$db->setQuery($q);

		return $db->loadObjectList();
	}

	/**
	 * Get Category products selected in search Module
	 */
	public function loadCatProductsManufacturer($cid)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT  p.product_id, p.manufacturer_id FROM #__redshop_product_category_xref AS cx "
			. ", #__redshop_product AS p "
			. "WHERE cx.category_id = " . (int) $cid . " "
			. "AND p.product_id=cx.product_id ";
		$db->setQuery($query);
		$manufacturer = $db->loadObjectList();

		$mids = array();

		for ($i = 0; $i < count($manufacturer); $i++)
		{
			if ($manufacturer[$i]->manufacturer_id > 0)
			{
				$mids[] = $manufacturer[$i]->manufacturer_id;
			}
		}

		// Sanitize ids
		JArrayHelper::toInteger($mids);

		$query = "SELECT manufacturer_id AS value,manufacturer_name AS text FROM #__redshop_manufacturer "
			. "WHERE manufacturer_id IN ('" . implode(",", $mids). "')";
		$db->setQuery($query);

		return $db->loadObjectList();
	}
}

