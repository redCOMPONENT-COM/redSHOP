<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerPrices_detail extends RedshopController
{
	use \Redshop\Model\Traits\HasDateTimeRange;

	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'prices_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * Apply function
	 *
	 * @return void
	 */
	public function apply()
	{
		$this->save(1);
	}

	/**
	 * Save function
	 *
	 * @param   int $apply stay in current page or not
	 * @return  void
	 */
	public function save($apply = 0)
	{
		$post                     = $this->input->post->getArray();
		$type                     = 'error';
		$productId                = $this->input->getInt('product_id');
		$post['product_currency'] = Redshop::getConfig()->get('CURRENCY_CODE');
		$post['cdate']            = time();
		$cid                      = $this->input->post->get('cid', array(0), 'array');
		$post ['price_id']        = $cid [0];

		$this->handleDateTimeRange($post['discount_start_date'], $post['discount_end_date']);

		// Store current post to user state
		$context = "com_redshop.edit.product_price";
		JFactory::getApplication()->setUserState($context . '.data', json_encode($post));

		/** @var RedshopModelPrices_detail $model */
		$model = $this->getModel('prices_detail');

		$row = $model->store($post);

		$msg = JText::_('COM_REDSHOP_ERROR_SAVING_PRICE_QUNTITY_DETAIL');

		if ($row)
		{
			$type = '';
			$msg  = JText::_('COM_REDSHOP_PRICE_DETAIL_SAVED');
			JFactory::getApplication()->setUserState($context . '.data', array());
			$post ['price_id'] = $row->price_id;
		}
		elseif ($post['discount_start_date'] > $post['discount_end_date'])
		{
			$msg = JText::_('COM_REDSHOP_PRODUCT_PRICE_END_DATE_MUST_MORE_THAN_START_DATE');
		}

		if ($apply == 0)
		{
			$this->setRedirect('index.php?option=com_redshop&view=prices&product_id=' . $productId, $msg, $type);

			return;
		}

		$this->setRedirect('index.php?option=com_redshop&view=prices_detail&task=edit&product_id=' . $productId . '&cid[]=' . $post ['price_id'], $msg, $type);
	}

	public function remove()
	{
		$productId = $this->input->get('product_id');
		$cid        = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		/** @var RedshopModelPrices_detail $model */
		$model = $this->getModel('prices_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . /** @scrutinizer ignore-deprecated */  $model->getError(null, true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_PRICE_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=prices&product_id=' . $productId, $msg);
	}

	public function cancel()
	{
		$productId = $this->input->get('product_id');

		$msg     = JText::_('COM_REDSHOP_PRICE_DETAIL_EDITING_CANCELLED');
		$context = "com_redshop.edit.product_price";
		JFactory::getApplication()->setUserState($context . '.data', null);
		$this->setRedirect('index.php?option=com_redshop&view=prices&product_id=' . $productId, $msg);
	}
}
