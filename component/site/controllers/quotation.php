<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
/**
 * Product Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class quotationController extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
	}
	/**
	 * add quotation function
	 *
	 * @access public
	 * @return void
	 */
	function addquotation()
	{
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$return = JRequest::getVar('return');
		$post = JRequest::get('post');

		if (!$post['user_email'])
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_EMAIL_ADDRESS');
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=quotation&return=1&Itemid=' . $Itemid, $msg);
			die();
		}

		$model = $this->getModel('quotation');
		$session =& JFactory::getSession();
		$cart = $session->get('cart');
		$cart['quotation_note'] = $post['quotation_note'];
		$row = $model->store($cart, $post);
		if ($row)
		{
			$sent = $model->sendQuotationMail($row->quotation_id);
			if ($sent)
			{
				$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SENT');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_ERROR_SENDING_QUOTATION_MAIL');
			}

			$session = & JFactory::getSession();
			$session->set('cart', NULL);
			$session->set('ccdata', NULL);
			$session->set('issplit', NULL);
			$session->set('userfiled', NULL);
			unset ($_SESSION ['ccdata']);
			if ($return)
			{
				$link = 'index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid . '&quotemsg=' . $msg;    ?>
				<script>
					window.parent.location.href = "<?php echo $link ?>";
					window.parent.reload();
				</script>
				<?php exit;
			}

			$this->setRedirect('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, $msg);
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=quotation&return=1&Itemid=' . $Itemid, $msg);
		}
	}

	/**
	 * user create function
	 *
	 * @access public
	 * @return void
	 */
	function usercreate()
	{
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

		$return = JRequest::getVar('return');
		$model = $this->getModel('quotation');

		$post = JRequest::get('post');

		$model->usercreate($post);


		$msg = JText::_('COM_REDSHOP_QUOTATION_SENT_AND_USERNAME_PASSWORD_HAS_BEEN_MAILED');
		$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=quotation&return=1&Itemid=' . $Itemid, $msg);

	}

	/**
	 * cancel function
	 *
	 * @access public
	 * @return void
	 */
	function cancel()
	{
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

		$return = JRequest::getVar('return');

		if ($return != "")
		{
			$link = 'index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid;
			?>
			<script language="javascript">
				window.parent.location.href = "<?php echo $link ?>";
			</script>
			<?php
			exit;
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid);
		}
	}
}
