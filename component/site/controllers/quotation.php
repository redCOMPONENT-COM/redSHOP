<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Quotation Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerQuotation extends RedshopController
{
	/**
	 * add quotation function
	 *
	 * @access public
	 * @return void
	 */
	public function addquotation()
	{

		$Itemid = $this->input->get('Itemid');
		$return = $this->input->get('return');
		$post   = $this->input->post->getArray();

		if (!$post['user_email'])
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_EMAIL_ADDRESS');
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=quotation&return=1&Itemid=' . $Itemid, $msg);
			die();
		}

		$model                  = $this->getModel('quotation');
		$session                = JFactory::getSession();
		$cart                   = $session->get('cart');
		$cart['quotation_note'] = $post['quotation_note'];
		$row                    = $model->store($cart, $post);

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

			$session = JFactory::getSession();
			$session->set('cart', null);
			$session->set('ccdata', null);
			$session->set('issplit', null);
			$session->set('userfield', null);
			unset ($_SESSION ['ccdata']);

			if ($return)
			{
				$link = 'index.php?option=com_redshop&view=cart&Itemid=' . $Itemid . '&quotemsg=' . $msg;    ?>
				<script>
					window.parent.location.href = "<?php echo $link ?>";
					window.parent.reload();
				</script>
				<?php exit;
			}

			$this->setRedirect('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid, $msg);
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=quotation&return=1&Itemid=' . $Itemid, $msg);
		}
	}

	/**
	 * user create function
	 *
	 * @access public
	 * @return void
	 */
	public function usercreate()
	{

		$Itemid = $this->input->get('Itemid');
		$return = $this->input->get('return');
		$model  = $this->getModel('quotation');
		$post   = $this->input->post->getArray();

		$model->usercreate($post);

		$msg = JText::_('COM_REDSHOP_QUOTATION_SENT_AND_USERNAME_PASSWORD_HAS_BEEN_MAILED');
		$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=quotation&return=1&Itemid=' . $Itemid, $msg);
	}

	/**
	 * cancel function
	 *
	 * @access public
	 * @return void
	 */
	public function cancel()
	{

		$Itemid = $this->input->get('Itemid');
		$return = $this->input->get('return');

		if ($return != "")
		{
			$link = 'index.php?option=com_redshop&view=cart&Itemid=' . $Itemid;
			?>
			<script language="javascript">
				window.parent.location.href = "<?php echo $link ?>";
			</script>
			<?php
			exit;
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid);
		}
	}
}
