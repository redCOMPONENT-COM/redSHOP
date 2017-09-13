<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * search Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerSearch extends RedshopController
{
	/**
	 * cancel function
	 *
	 * @access public
	 * @return void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * loadProducts function
	 *
	 * @access public
	 * @return manufacturer select box
	 */
	public function loadProducts()
	{
		$app = JFactory::getApplication();
		$get = $app->input->get->getArray();
		$taskid = $get['taskid'];

		$model = $this->getModel('search');

		$brands = $model->loadCatProductsManufacturer($taskid);

		// Manufacture Select Id
		$manufac_data = ($app->input->getInt('manufacture_id', 0));

		JLoader::import('joomla.application.module.helper');
		$module           = JModuleHelper::getModule('redshop_search');
		$params           = new JRegistry($module->params);
		$enableAjaxsearch = $params->get('enableAjaxsearch');
		$javaFun          = "";

		if ($enableAjaxsearch)
		{
			$javaFun = "makeUrl();";
		}

		if (count($brands) > 0)
		{
			$manufac     = array();
			$manufac[]   = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_MANUFACTURE'));
			$manufacdata = @array_merge($manufac, $brands);

			echo JText::_('COM_REDSHOP_SELECT_MANUFACTURE') . '<br/>' . JHTML::_('select.genericlist', $manufacdata, 'manufacture_id', 'class="inputbox span12" size="1" onChange="' . $javaFun . '" ', 'value', 'text', $manufac_data);
		}

		$app->close();
	}

	/**
	 * ajaxsearch function
	 *
	 * @access public
	 * @return search product results
	 */
	public function ajaxsearch()
	{
		$model  = $this->getModel('Search');
		$detail = $model->getajaxData();

		$encoded = json_encode($detail);
		ob_clean();
		echo "{\"results\": " . $encoded . "}";
		JFactory::getApplication()->close();
	}

	/**
	 * AJAX Task to get states list
	 *
	 * @return  string  JSON encoded string of states list.
	 */
	public function getStatesAjax()
	{
		// Only verify token for frontend
		RedshopHelperAjax::validateAjaxRequest('get');

		$app = JFactory::getApplication();

		ob_clean();

		echo RedshopHelperWorld::getStatesAjax($app->input->getCmd('country'));

		$app->close();
	}

	/**
	 * AJAX Task to filter products
	 *
	 * @return  void
	 */
	public function findProducts()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$model = $this->getModel('Search');
		$post  = $input->post->getArray();
		$data  = $post['redform'];

		$model->setState("filter.data", $post);
		$list       = $model->getItem();
		$pagination = $model->getFilterPagination();
		$total      = $model->getFilterTotal();
		$url = JRoute::_(
			'index.php?option=' . $post['option']
			. '&view=' . $post['view']
			. '&layout=' . $post['layout']
			. '&cid=' . $data['cid']
			. '&manufacturer_id=' . $data['mid']
			. '&Itemid=' . $post['Itemid']
			. '&categories=' . implode(',', $data['category'])
			. '&manufacturers=' . implode(',', $data['manufacturer'])
			. '&filterprice[min]=' . $data['filterprice']['min']
			. '&filterprice[max]=' . $data['filterprice']['max']
			. '&template_id=' . $data['template_id']
			. '&keyword=' . $data['keyword']
			. '&order_by=' . $post['order_by']
			. '&limit=' . $post['limit']
			. '&limitstart=' . $post['limitstart']
		);

		foreach ($data['custom_field'] as $fieldId => $fieldValues)
		{
			$url .= '&custom_field[' . $fieldId . ']=' . implode(',', $fieldValues);
		}

		// Get layout HTML
		if (empty($list))
		{
			echo JText::_('COM_REDSHOP_MSG_SORRY_NO_RESULT_FOUND');
			$app->close();
		}

		echo RedshopLayoutHelper::render(
			'filter.result',
			array(
				"products"   => $list,
				"model"      => $model,
				"post"       => $data,
				"pagination" => $pagination,
				"orderBy"    => $post['order_by'],
				'total'      => $total,
				'templateId' => $data['template_id'],
				'url'        => $url,
				'keyword'    => $data['keyword']
			),
			'',
			array(
				'component' => 'com_redshop'
			)
		);

		$app->close();
	}

	/**
	 * AJAX Task to restricted data
	 *
	 * @return  void
	 */
	public function restrictedData()
	{
		JLoader::register('ModRedshopFilter', JPATH_SITE . '/modules/mod_redshop_filter/helper.php');

		$app    = JFactory::getApplication();
		$input  = $app->input;
		$params = json_decode($input->post->getString('params', ''));
		$pids   = explode(',', $input->post->getString('pids', ''));
		$form   = urldecode(stripslashes($input->post->get('form', '', 'RAW')));
		parse_str($form, $formData);

		$cid           = $formData['redform']['cid'];
		$mid           = $formData['redform']['mid'];
		$rootCategory  = $params->root_category;
		$productFields = $params->product_fields;

		if (!empty($cid))
		{
			$productList = array();

			foreach ($pids as $key => $product)
			{
				$detail = RedshopHelperProduct::getProductById($product);
				$productList[] = $detail;
			}

			$manuList    = array();
			$catList     = array();

			foreach ($productList as $k => $value)
			{
				$tmpCategories = is_array($value->categories) ? $value->categories : explode(',', $value->categories);
				$catList = array_merge($catList, $tmpCategories);

				if ($value->manufacturer_id && $value->manufacturer_id != $mid)
				{
					$manuList[] = $value->manufacturer_id;
				}
			}

			$catList       = array_unique($catList);
			$manufacturers = ModRedshopFilter::getManufacturers(array_unique($manuList));
			$categories    = ModRedshopFilter::getCategories($catList, $rootCategory, $cid);
			$rangePrice    = ModRedshopFilter::getRange($pids);
		}
		elseif (!empty($mid))
		{
			$productList = array();

			foreach ($pids as $key => $product)
			{
				$detail = RedshopHelperProduct::getProductById($product);
				$productList[] = $detail;
			}

			$manuList    = array();
			$catList     = array();

			foreach ($productList as $k => $value)
			{
				$tmpCategories = is_array($value->categories) ? $value->categories : explode(',', $value->categories);
				$catList = array_merge($catList, $tmpCategories);

				if ($value->manufacturer_id && $value->manufacturer_id != $mid)
				{
					$manuList[] = $value->manufacturer_id;
				}
			}

			$manufacturers = array();
			$pids          = ModRedshopFilter::getProductByManufacturer($mid);
			$categories    = ModRedshopFilter::getCategorybyPids($pids, $rootCategory);
			$rangePrice    = ModRedshopFilter::getRange($pids);
		}
		elseif ($formData['view'] == 'search')
		{
			$productList = array();

			foreach ($pids as $key => $product)
			{
				$detail = RedshopHelperProduct::getProductById($product);
				$productList[] = $detail;
			}

			$manuList    = array();
			$catList     = array();

			foreach ($productList as $k => $value)
			{
				$tmpCategories = is_array($value->categories) ? $value->categories : explode(',', $value->categories);
				$catList = array_merge($catList, $tmpCategories);

				if ($value->manufacturer_id && $value->manufacturer_id != $mid)
				{
					$manuList[] = $value->manufacturer_id;
				}
			}

			$manufacturers = ModRedshopFilter::getManufacturers(array_unique($manuList));
			$categories    = ModRedshopFilter::getSearchCategories(array_unique($catList));
			$rangePrice    = ModRedshopFilter::getRange($pids);
		}

		$customFields = ModRedshopFilter::getCustomFields($pids, $productFields);
		$rangeMin     = $formData['redform']['filterprice']['min'] ? $formData['redform']['filterprice']['min'] : $rangePrice['min'];
		$rangeMax     = $formData['redform']['filterprice']['max'] ? $formData['redform']['filterprice']['max'] : $rangePrice['max'];

		echo RedshopLayoutHelper::render(
			'filter.restricted',
			array(
				"params"        => $params,
				"manufacturers" => $manufacturers,
				"categories"    => $categories,
				"rangeMin"      => $rangeMin,
				"rangeMax"      => $rangeMax,
				"customFields"  => $customFields,
				'formData'      => $formData,
				"productList"   => $productList
			),
			'',
			array(
				'component' => 'com_redshop'
			)
		);

		$app->close();
	}
}
