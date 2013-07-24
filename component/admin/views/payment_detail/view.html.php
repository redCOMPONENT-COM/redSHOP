<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class payment_detailViewpayment_detail extends JView
{
	public function display($tpl = null)
	{
		$db = JFactory::getDBO();

		JToolBarHelper::title(JText::_('COM_REDSHOP_TEMPLATES_MANAGEMET'), 'redshop_payment48');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_REDSHOP'), 'index.php?option=com_redshop', true);
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_FIELDS'), 'index.php?option=com_redshop&view=fields');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_PRODUCTS'), 'index.php?option=com_redshop&view=product');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_CATEGORIES'), 'index.php?option=com_redshop&view=category');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_CONTAINER'), 'index.php?option=com_redshop&view=container');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_STOCKROOM'), 'index.php?option=com_redshop&view=stockroom');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_USER'), 'index.php?option=com_redshop&view=user');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_ORDER'), 'index.php?option=com_redshop&view=order');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_PAYMENT'), 'index.php?option=com_redshop&view=payment');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_SHIPPING'), 'index.php?option=com_redshop&view=shipping');
		JSubMenuHelper::addEntry(JText::_('COM_REDSHOP_TEMPLATES'), 'index.php?option=com_redshop&view=template');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->payment_method_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_PAYMENTS') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_payment48');

		if ($isNew)
		{
			JToolBarHelper::cancel();

			$this->setLayout('default_install');
		}
		else
		{
			JToolBarHelper::save();

			JToolBarHelper::cancel();

			$adminpath = JPATH_ADMINISTRATOR . '/components/com_redshop';

			$paymentxml = $adminpath . '/helpers/payments/' . $detail->plugin . '.xml';

			$paymentfile = $adminpath . '/helpers/payments/' . $detail->plugin . DS . $detail->plugin . '.php';

			$paymentcfg = $adminpath . '/helpers/payments/' . $detail->plugin . DS . $detail->plugin . '.cfg.php';

			include_once ($paymentfile);

			$ps = new $detail->payment_class;

			$this->ps = $ps;

			if (file_exists($paymentcfg))
			{
				if (!is_writable($paymentcfg))
				{
					echo "<font color='red'>" . $paymentcfg . ' is not writable</font>';
				}

				include_once ($paymentcfg);
			}

			$myparams = new JRegistry($detail->params, $paymentxml);

			$ret = $myparams->render();
		}
		$cc_list = array();
		$cc_list['VISA'] = 'Visa';
		$cc_list['MC'] = 'MasterCard';
		$cc_list['amex'] = 'American Express';
		$cc_list['maestro'] = 'Maestro';
		$cc_list['jcb'] = 'JCB';
		$cc_list['diners'] = 'Diners Club';

		$query = ' SELECT shopper_group_id as value, shopper_group_name as text '
			. ' FROM  #__' . TABLE_PREFIX . '_shopper_group where  published=1';

		$db->setQuery($query);

		$shopper_groups = $db->loadObjectList();

		$detail->shopper_group = explode(',', $detail->shopper_group);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $detail->shopper_group);

		$lists['shopper_group'] = JHTML::_('select.genericlist', $shopper_groups, 'shopper_group[]',
			'size="10" multiple', 'value', 'text', @$detail->shopper_group
		);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$lists['is_creditcard'] = JHTML::_('select.booleanlist', 'is_creditcard',
			'class="inputbox" onChange="hide_show_cclist(this.value);"', $detail->is_creditcard
		);

		$this->params = $ret;
		$this->lists = $lists;
		$this->cc_list = $cc_list;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
