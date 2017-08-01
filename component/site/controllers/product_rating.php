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

		$productId   = $app->input->getInt('product_id', 0);
		$Itemid      = $app->input->getInt('Itemid', 0);
		$modal       = $app->input->getInt('modal', 0);
		$category_id = $app->input->getInt('category_id', 0);
		$userHelper  = rsUserHelper::getInstance();
		$user = JFactory::getUser();

		if ($modal)
		{
			$link = 'index.php?option=com_redshop&view=product_rating&product_id=' . $productId . '&tmpl=component&Itemid=' . $Itemid;
		}
		else
		{
			$link = 'index.php?option=com_redshop&view=product&pid=' . $productId . '&cid=' . $category_id . '&Itemid=' . $Itemid;
		}

		// Preform security checks
		if (!$user->id && Redshop::getConfig()->get('RATING_REVIEW_LOGIN_REQUIRED'))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ALERTNOTAUTH_REVIEW'), 'warning');
			$this->setRedirect(JRoute::_($link, false));

			return false;
		}

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			$this->setRedirect(JRoute::_($link, false));

			return false;
		}

		// Save the data in the session.
		$app->setUserState('com_redshop.edit.product_rating.' . $productId . '.data', $data);

		// Check captcha only for guests
		if (JFactory::getUser()->guest)
		{
			if (!$userHelper->checkCaptcha($data, false))
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_INVALID_SECURITY'), 'warning');
				$this->setRedirect($link);

				return false;
			}
		}

		if ($user->guest)
		{
			$data['userid'] = 0;
		}
		else
		{
			$userHelper = rsUserHelper::getInstance();
			$data['userid'] = $user->id;

			if ($userInfo = $userHelper->getRedSHOPUserInfo($user->id))
			{
				$data['username'] = $userInfo->firstname . " " . $userInfo->lastname;
				$data['email'] = $userInfo->user_email;

				if ($userInfo->is_company)
				{
					$data['company_name'] = $userInfo->company_name;
				}
			}
			else
			{
				$data['username'] = $user->name;
				$data['email'] = $user->email;
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

			$this->setRedirect($link);

			return false;
		}

		if ((Redshop::getConfig()->get('RATING_REVIEW_LOGIN_REQUIRED') && $model->checkRatedProduct($productId, $user->id))
			|| (!Redshop::getConfig()->get('RATING_REVIEW_LOGIN_REQUIRED') && $model->checkRatedProduct($productId, 0, $data['email'])))
		{
			if ($modal)
			{
				$link .= '&rate=1';
			}

			$app->enqueueMessage(JText::_('COM_REDSHOP_YOU_CAN_NOT_REVIEW_SAME_PRODUCT_AGAIN'), 'warning');
			$this->setRedirect($link);

			return false;
		}

		$data['published'] = 0;
		$data['favoured'] = 0;
		$data['time'] = time();
		$data['product_id'] = $productId;
		$data['Itemid'] = $Itemid;

		if ($model->sendMailForReview($data))
		{
			// Flush the data from the session
			$app->setUserState('com_redshop.edit.product_rating.' . $productId . '.data', null);

			if (Redshop::getConfig()->get('RATING_MSG'))
			{
				$msg = Redshop::getConfig()->get('RATING_MSG');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY');
			}

			$app->enqueueMessage($msg);

			if ($modal)
			{
				$link .= '&rate=1';
			}
		}
		else
		{
			$app->enqueueMessage($model->getError(), 'warning');
		}

		$this->setRedirect($link);
	}
}
