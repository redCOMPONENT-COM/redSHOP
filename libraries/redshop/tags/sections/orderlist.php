<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2020 - 2021 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsOrderList extends RedshopTagsAbstract
{
	/**
	 * @var    mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $detail;

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function init()
	{
		$this->detail = $this->data['detail'];
	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replace()
	{
		$url   = JURI::base();
		$print = $this->input->getInt('print');
		$db    = JFactory::getDbo();

		if ($this->data['params']->get('show_page_heading', 1)) {
			$this->template = RedshopLayoutHelper::render(
					'tags.common.pageheading',
					[
						'pageheading' => $db->escape(JText::_('COM_REDSHOP_ORDER_LIST')),
						'params'      => $this->data['params'],
						'class'       => 'order-list'
					],
					'',
					$this->optionLayout
				) . $this->template;
		}

		if ($print) {
			$onClick = "onclick='window.print();'";
		} else {
			$printUrl = $url . 'index.php?option=com_redshop&view=orders&print=1&tmpl=component&Itemid=' . $this->itemId;
			$onClick  = 'onclick="window.open(\'' . $printUrl . '\',\'mywindow\',\'scrollbars=1\',\'location=1\')"';
		}

		$this->replacements['{print}'] = RedshopLayoutHelper::render(
			'tags.common.print',
			[
				'onClick' => $onClick
			],
			'',
			$this->optionLayout
		);

		$this->replaceProductLoop();

		$this->template = Redshop\Cart\Render\Label::replace($this->template);

		if ($this->isTagExists('{pagination}')) {
			$this->replacements['{pagination}'] = $this->data['pagination']->getPagesLinks();
		}

		if ($this->isTagExists('{pagination_limit}')) {
			$this->replacements['{pagination_limit}'] = RedshopLayoutHelper::render(
				'tags.order_list.pagination_limit',
				[
					'paginationLimit' => $this->data['pagination']->getLimitBox(),
					'itemId'          => $this->itemId
				],
				'',
				$this->optionLayout
			);
		}

		$this->template = $this->strReplace($this->replacements, $this->template);
		$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

		return parent::replace();
	}

	/**
	 * Replace product loop
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceProductLoop()
	{
		$subTemplate = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');

		if (!empty($subTemplate)) {
			$templateMid = "";

			for ($i = 0; $i < count($this->detail); $i++) {
				$replace = [];
				$proList = RedshopHelperOrder::getOrderItemDetail($this->detail[$i]->order_id);

				$orderItemName = array();

				for ($j = 0, $jn = count($proList); $j < $jn; $j++) {
					$orderItemName[$j] = $proList[$j]->order_item_name;
				}

				$paymentMethod = RedshopEntityOrder_Payment::getInstance($this->detail[$i]->order_id)->getItem();

				$orderTransFee = '';

				if ($paymentMethod->order_transfee > 0) {
					$orderTransFee = RedshopHelperProductPrice::formattedPrice(
						$paymentMethod->order_transfee
					);
				}

				$replace['{order_transfee}'] = $orderTransFee;

				$replace['{order_total_incl_transfee}'] = RedshopHelperProductPrice::formattedPrice(
					$paymentMethod->order_transfee + $this->detail[$i]->order_total
				);

				$replace['{order_number}'] = $this->replaceTagDiv('order_number', $this->detail[$i]->order_number);
				$replace['{order_id}']     = $this->replaceTagDiv('order_id', $this->detail[$i]->order_id);

				$replace['{order_products}'] = $this->replaceTagDiv(
					'order_products',
					implode(',<br/>', $orderItemName)
				);

				$replace['{order_total}'] = $this->replaceTagDiv(
					'order_total',
					RedshopHelperProductPrice::formattedPrice(
						$this->detail[$i]->order_total
					)
				);

				$replace['{order_date}'] = $this->replaceTagDiv(
					'order_date',
					RedshopHelperDatetime::convertDateFormat(
						$this->detail[$i]->cdate
					)
				);

				$replace['{order_status}'] = $this->replaceTagDiv(
					'order_status',
					RedshopHelperOrder::getOrderStatusTitle(
						$this->detail[$i]->order_status
					)
				);

				$replace['{order_detail_link}'] = RedshopLayoutHelper::render(
					'tags.common.div_link',
					[
						'divClass' => 'order_detail_link',
						'link'     => JRoute::_(
							'index.php?option=com_redshop&view=order_detail&oid=' . $this->detail[$i]->order_id
						),
						'content'  => JText::_('COM_REDSHOP_ORDER_DETAIL')
					],
					'',
					$this->optionLayout
				);

				$reOrderUrl = JURI::root(
					) . 'index.php?option=com_redshop&view=order_detail&order_id=' . $this->detail[$i]->order_id . '&task=reorder&tmpl=component';

				$orderLink = 'javascript:if(confirm(\'' . JText::_(
						'COM_REDSHOP_CONFIRM_CART_EMPTY'
					) . '\')){window.location=\'' . $reOrderUrl . '\';}';

				$replace['{reorder_link}'] = RedshopLayoutHelper::render(
					'tags.common.div_link',
					[
						'divClass' => 'reorder_link',
						'link'     => $orderLink,
						'content'  => JText::_('COM_REDSHOP_REORDER')
					],
					'',
					$this->optionLayout
				);

				$replace['{requisition_number}'] = $this->detail[$i]->requisition_number;
				$templateMid                     .= $this->strReplace($replace, $subTemplate['template']);
			}

			$this->template = $subTemplate['begin'] . $templateMid . $subTemplate['end'];
		}
	}

	private function replaceTagDiv($class, $content)
	{
		return RedshopLayoutHelper::render(
			'tags.common.tag',
			[
				'tag'   => 'div',
				'class' => $class,
				'text'  => $content
			],
			'',
			$this->optionLayout
		);
	}
}