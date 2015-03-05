<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Product rating Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerProduct_Rating extends RedshopControllerForm
{
	/**
	 * Method to send Ask Question Mail.
	 *
	 * @return bool
	 */
	public function submit()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$data = $app->input->post->get('jform', array(), 'array');
		$model = $this->getModel('product_rating');

		$productId   = $app->input->getInt('pid', 0);
		$Itemid      = $app->input->getInt('Itemid', 0);
		$rate         = $app->input->getInt('rate', 0);
		$category_id = $app->input->getInt('category_id', 0);
		$userHelper  = new rsUserhelper;

		if ($rate)
		{
			$link = 'index.php?option=com_redshop&view=product&pid=' . $productId . '&cid=' . $category_id . '&Itemid=' . $Itemid;
		}
		else
		{
			$link = 'index.php?option=com_redshop&view=ask_question&pid=' . $productId . '&tmpl=component&Itemid=' . $Itemid;
		}

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			$this->setRedirect($link);

			return false;
		}

		// Save the data in the session.
		$app->setUserState('com_redshop.product_rating.data', $data);

		// Check captcha only for guests
		if (SHOW_CAPTCHA && JFactory::getUser()->guest)
		{
			if (!$userHelper->checkCaptcha($data, false))
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_INVALID_SECURITY'), 'warning');
				$this->setRedirect($link);

				return false;
			}
		}

		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
		}
		else
		{
			$data['product_id'] = $productId;
			$data['Itemid'] = $Itemid;

			if ($model->sendMailForReview($data))
			{
				// Flush the data from the session
				$app->setUserState('com_redshop.ask_question.data', null);
				$app->enqueueMessage(JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY'));

				if (!$rate)
				{
					$link .= '&questionSend=1';
				}
			}
			else
			{
				$app->enqueueMessage($model->getError(), 'warning');
			}
		}

		$this->setRedirect($link);
	}

	/**
	 * save function
	 *
	 * @access public
	 * @return void
	 */
	public function saveOld()
	{
		$post        = JRequest::get('post');
		$Itemid      = JRequest::getVar('Itemid');
		$product_id  = JRequest::getInt('product_id');
		$category_id = JRequest::getInt('category_id');
		$model       = $this->getModel('product_rating');
		$rate        = JRequest::getVar('rate');

		if ($model->sendMailForReview($post))
		{
			$msg = JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY');
		}

		if ($rate == 1)
		{
			$link = 'index.php?option=com_redshop&view=product&pid=' . $product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid;
			$this->setRedirect($link, $msg);
		}
		else
		{
			echo $msg;?>
			<span id="closewindow"><input type="button" value="Close Window" onclick="window.parent.redBOX.close();"/></span>
			<script>
				setTimeout("window.parent.redBOX.close();", 5000);
			</script>
			<?php
			exit;
		}
	}
}
