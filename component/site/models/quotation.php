<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



/**
 * Class quotationModelquotation
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelQuotation extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	public function &getData()
	{
		if ($this->_loadData())
		{
		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		$order_functions = order_functions::getInstance();
		$user            = JFactory::getUser();

		if ($user->id)
		{
			$this->_data               = $order_functions->getBillingAddress($user->id);
			$this->_data->user_info_id = $this->_data->users_info_id;

			return true;
		}

		return false;
	}

	public function _initData()
	{
		$detail                        = new stdClass;
		$detail->user_info_id          = 0;
		$detail->user_id               = 0;
		$detail->firstname             = null;
		$detail->lastname              = null;
		$detail->address               = null;
		$detail->zipcode               = null;
		$detail->city                  = null;
		$detail->country_code          = null;
		$detail->state_code            = null;
		$detail->phone                 = null;
		$detail->user_email            = null;
		$detail->is_company            = null;
		$detail->vat_number            = null;
		$detail->requesting_tax_exempt = 0;
		$detail->tax_exempt            = null;
		$this->_data                   = $detail;
	}

	public function store($data, $post)
	{
		$this->_loadData();
		$quotationHelper = quotationHelper::getInstance();
		$producthelper   = productHelper::getInstance();
		$extra_field     = extra_field::getInstance();
		$user            = JFactory::getUser();
		$user_id         = 0;
		$user_info_id    = 0;
		$user_email      = $post['user_email'];

		if ($user->id)
		{
			$user_id      = $user->id;
			$user_info_id = $this->_data->user_info_id;
			$user_email   = $user->email;
		}

		$res = $this->getUserIdByEmail($user_email);

		if (count($res) > 0)
		{
			$user_id      = $res->user_id;
			$user_info_id = $res->users_info_id;
		}

		$list_field = $extra_field->extra_field_save($post, 16, $user_info_id, $user_email);

		$data['quotation_number']    = $quotationHelper->generateQuotationNumber();
		$data['user_id']             = $user_id;
		$data['user_info_id']        = $user_info_id;
		$data['user_email']          = $user_email;
		$data['quotation_total']     = $data['total'];
		$data['quotation_subtotal']  = $data['subtotal'];
		$data['quotation_tax']       = $data['tax'];
		$data['quotation_status']    = 1;
		$data['quotation_cdate']     = time();
		$data['quotation_mdate']     = time();
		$data['quotation_note']      = $data['quotation_note'];
		$data['quotation_ipaddress'] = $_SERVER ['REMOTE_ADDR'];
		$data['quotation_encrkey']   = $quotationHelper->randomQuotationEncrkey();
		$data['quotation_discount']  = (isset($data['discount2'])) ? $data['discount2'] : 0;

		$totalitem      = $data['idx'];
		$quotation_item = array();

		$row = $this->getTable('quotation_detail');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		for ($i = 0; $i < $totalitem; $i++)
		{
			$rowitem                          = $this->getTable('quotation_item_detail');
			$quotation_item[$i]               = new stdClass;
			$quotation_item[$i]->quotation_id = $row->quotation_id;

			if (isset($data[$i]['giftcard_id']) && $data[$i]['giftcard_id'] != 0)
			{
				$quotation_item[$i]->product_id = $data[$i]['giftcard_id'];

				$giftcardData = $producthelper->getGiftcardData($data[$i]['giftcard_id']);

				$quotation_item[$i]->is_giftcard  = 1;
				$quotation_item[$i]->product_name = $giftcardData->giftcard_name;
				$section                          = 13;
			}
			else
			{
				$product = $producthelper->getProductById($data[$i]['product_id']);

				$retAttArr      = $producthelper->makeAttributeCart($data[$i]['cart_attribute'], $data[$i]['product_id'], 0, 0, $data[$i]['quantity']);
				$cart_attribute = $retAttArr[0];

				$retAccArr      = $producthelper->makeAccessoryCart($data[$i]['cart_accessory'], $data[$i]['product_id']);
				$cart_accessory = $retAccArr[0];

				$quotation_item[$i]->product_id          = $data[$i]['product_id'];
				$quotation_item[$i]->is_giftcard         = 0;
				$quotation_item[$i]->product_name        = $product->product_name;
				$quotation_item[$i]->actualitem_price    = $data[$i]['product_price'];
				$quotation_item[$i]->product_price       = $data[$i]['product_price'];
				$quotation_item[$i]->product_excl_price  = $data[$i]['product_price_excl_vat'];
				$quotation_item[$i]->product_final_price = $data[$i]['product_price'] * $data[$i]['quantity'];
				$quotation_item[$i]->product_attribute   = $cart_attribute;
				$quotation_item[$i]->product_accessory   = $cart_accessory;
				$quotation_item[$i]->product_wrapperid   = $data[$i]['wrapper_id'];
				$quotation_item[$i]->wrapper_price       = $data[$i]['wrapper_price'];

				$section = 12;
			}

			$quotation_item[$i]->product_quantity = $data[$i]['quantity'];

			if (!$rowitem->bind($quotation_item[$i]))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			if (!$rowitem->store())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			/** my accessory save in table start */
			if (count($data[$i]['cart_accessory']) > 0)
			{
				$attArr = $data [$i] ['cart_accessory'];

				for ($a = 0, $an = count($attArr); $a < $an; $a++)
				{
					$accessory_vat_price = 0;
					$accessory_attribute = "";
					$accessory_id        = $attArr[$a]['accessory_id'];
					$accessory_name      = $attArr[$a]['accessory_name'];
					$accessory_price     = $attArr[$a]['accessory_price'];
					$accessory_org_price = $accessory_price;

					if ($accessory_price > 0)
					{
						$accessory_vat_price = $producthelper->getProductTax($rowitem->product_id, $accessory_price);
					}

					$attchildArr = $attArr[$a]['accessory_childs'];

					for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
					{
						$attribute_id = $attchildArr[$j]['attribute_id'];
						$accessory_attribute .= urldecode($attchildArr[$j]['attribute_name']) . ":<br/>";

						$rowattitem                        = $this->getTable('quotation_attribute_item');
						$rowattitem->quotation_att_item_id = 0;
						$rowattitem->quotation_item_id     = $rowitem->quotation_item_id;
						$rowattitem->section_id            = $attribute_id;
						$rowattitem->section               = "attribute";
						$rowattitem->parent_section_id     = $accessory_id;
						$rowattitem->section_name          = $attchildArr[$j]['attribute_name'];
						$rowattitem->is_accessory_att      = 1;

						if ($attribute_id > 0)
						{
							if (!$rowattitem->store())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
						}


						$propArr = $attchildArr[$j]['attribute_childs'];

						for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
						{
							$section_vat = $producthelper->getProducttax($rowitem->product_id, $propArr[$k]['property_price']);
							$property_id = $propArr[$k]['property_id'];
							$accessory_attribute .= urldecode($propArr[$k]['property_name'])
								. " (" . $propArr[$k]['property_oprand']
								. $producthelper->getProductFormattedPrice($propArr[$k]['property_price'] + $section_vat)
								. ")<br/>";
							$subpropArr = $propArr[$k]['property_childs'];

							$rowattitem                        = $this->getTable('quotation_attribute_item');
							$rowattitem->quotation_att_item_id = 0;
							$rowattitem->quotation_item_id     = $rowitem->quotation_item_id;
							$rowattitem->section_id            = $property_id;
							$rowattitem->section               = "property";
							$rowattitem->parent_section_id     = $attribute_id;
							$rowattitem->section_name          = $propArr[$k]['property_name'];
							$rowattitem->section_price         = $propArr[$k]['property_price'];
							$rowattitem->section_vat           = $section_vat;
							$rowattitem->section_oprand        = $propArr[$k]['property_oprand'];
							$rowattitem->is_accessory_att      = 1;

							if ($property_id > 0)
							{
								if (!$rowattitem->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}

							for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
							{
								$section_vat    = $producthelper->getProducttax($rowitem->product_id, $subpropArr[$l]['subproperty_price']);
								$subproperty_id = $subpropArr[$l]['subproperty_id'];
								$accessory_attribute .= urldecode($subpropArr[$l]['subproperty_name'])
									. " (" . $subpropArr[$l]['subproperty_oprand']
									. $producthelper->getProductFormattedPrice($subpropArr[$l]['subproperty_price'] + $section_vat) . ")<br/>";

								$rowattitem                        = $this->getTable('quotation_attribute_item');
								$rowattitem->quotation_att_item_id = 0;
								$rowattitem->quotation_item_id     = $rowitem->quotation_item_id;
								$rowattitem->section_id            = $subproperty_id;
								$rowattitem->section               = "subproperty";
								$rowattitem->parent_section_id     = $property_id;
								$rowattitem->section_name          = $subpropArr[$l]['subproperty_name'];
								$rowattitem->section_price         = $subpropArr[$l]['subproperty_price'];
								$rowattitem->section_vat           = $section_vat;
								$rowattitem->section_oprand        = $subpropArr[$l]['subproperty_oprand'];
								$rowattitem->is_accessory_att      = 1;

								if ($subproperty_id > 0)
								{
									if (!$rowattitem->store())
									{
										$this->setError($this->_db->getErrorMsg());

										return false;
									}
								}
							}
						}
					}

					$accdata = $this->getTable('accessory_detail');

					if ($accessory_id > 0)
					{
						$accdata->load($accessory_id);
					}

					$accProductinfo                    = $producthelper->getProductById($accdata->child_product_id);
					$rowaccitem                        = $this->getTable('quotation_accessory_item');
					$rowaccitem->quotation_item_acc_id = 0;
					$rowaccitem->quotation_item_id     = $rowitem->quotation_item_id;
					$rowaccitem->accessory_id          = $accessory_id;
					$rowaccitem->accessory_item_sku    = $accProductinfo->product_number;
					$rowaccitem->accessory_item_name   = $accessory_name;
					$rowaccitem->accessory_price       = $accessory_org_price;
					$rowaccitem->accessory_vat         = $accessory_vat_price;
					$rowaccitem->accessory_quantity    = $rowitem->product_quantity;
					$rowaccitem->accessory_item_price  = $accessory_price;
					$rowaccitem->accessory_final_price = ($accessory_price * $rowitem->product_quantity);
					$rowaccitem->accessory_attribute   = $accessory_attribute;

					if ($accessory_id > 0)
					{
						if (!$rowaccitem->store())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}
				}
			}

			// My attribute save in table start
			if (count($data[$i]['cart_attribute']) > 0)
			{
				$attArr = $data [$i] ['cart_attribute'];

				for ($j = 0, $jn = count($attArr); $j < $jn; $j++)
				{
					$attribute_id = $attArr[$j]['attribute_id'];

					$rowattitem                        = $this->getTable('quotation_attribute_item');
					$rowattitem->quotation_att_item_id = 0;
					$rowattitem->quotation_item_id     = $rowitem->quotation_item_id;
					$rowattitem->section_id            = $attribute_id;
					$rowattitem->section               = "attribute";
					$rowattitem->parent_section_id     = $rowitem->product_id;
					$rowattitem->section_name          = $attArr[$j]['attribute_name'];
					$rowattitem->is_accessory_att      = 0;

					if ($attribute_id > 0)
					{
						if (!$rowattitem->store())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}

					$propArr = $attArr[$j]['attribute_childs'];

					for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
					{
						$section_vat = $producthelper->getProducttax($rowitem->product_id, $propArr[$k]['property_price']);
						$property_id = $propArr[$k]['property_id'];

						$rowattitem                        = $this->getTable('quotation_attribute_item');
						$rowattitem->quotation_att_item_id = 0;
						$rowattitem->quotation_item_id     = $rowitem->quotation_item_id;
						$rowattitem->section_id            = $property_id;
						$rowattitem->section               = "property";
						$rowattitem->parent_section_id     = $attribute_id;
						$rowattitem->section_name          = $propArr[$k]['property_name'];
						$rowattitem->section_price         = $propArr[$k]['property_price'];
						$rowattitem->section_vat           = $section_vat;
						$rowattitem->section_oprand        = $propArr[$k]['property_oprand'];
						$rowattitem->is_accessory_att      = 0;

						if ($property_id > 0)
						{
							if (!$rowattitem->store())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
						}

						$subpropArr = $propArr[$k]['property_childs'];

						for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
						{
							$section_vat    = $producthelper->getProducttax($rowitem->product_id, $subpropArr[$l]['subproperty_price']);
							$subproperty_id = $subpropArr[$l]['subproperty_id'];

							$rowattitem                        = $this->getTable('quotation_attribute_item');
							$rowattitem->quotation_att_item_id = 0;
							$rowattitem->quotation_item_id     = $rowitem->quotation_item_id;
							$rowattitem->section_id            = $subproperty_id;
							$rowattitem->section               = "subproperty";
							$rowattitem->parent_section_id     = $property_id;
							$rowattitem->section_name          = $subpropArr[$l]['subproperty_name'];
							$rowattitem->section_price         = $subpropArr[$l]['subproperty_price'];
							$rowattitem->section_vat           = $section_vat;
							$rowattitem->section_oprand        = $subpropArr[$l]['subproperty_oprand'];
							$rowattitem->is_accessory_att      = 0;

							if ($subproperty_id > 0)
							{
								if (!$rowattitem->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}
					}
				}
			}

			$quotationHelper->manageQuotationUserfield($data[$i], $rowitem->quotation_item_id, $section);
		}

		return $row;
	}

	public function usercreate($data)
	{
		$app             = JFactory::getApplication();
		$redshopMail     = redshopMail::getInstance();
		$order_functions = order_functions::getInstance();
		$Itemid          = $app->input->get('Itemid');

		// Get required system objects
		$user      = clone(JFactory::getUser());
		$pathway   = $app->getPathway();
		$config    = JFactory::getConfig();
		$authorize = JFactory::getACL();
		$document  = JFactory::getDocument();

		$MailFrom = $app->getCfg('mailfrom');
		$FromName = $app->getCfg('fromname');
		$SiteName = $app->getCfg('sitename');

		$usersConfig = JComponentHelper::getParams('com_users');
		$usersConfig->set('allowUserRegistration', 1);

		if ($usersConfig->get('allowUserRegistration') == '0')
		{
			JError::raiseError(403, JText::_('COM_REDSHOP_ACCESS_FORBIDDEN'));

			return;
		}

		// Initialize new usertype setting
		$newUsertype = $usersConfig->get('new_usertype');

		if (!$newUsertype)
		{
			$newUsertype = 'Registered';
		}

		// Bind the post array to the user object
		if (!$user->bind($app->input->post->getArray(), 'usertype'))
		{
			JError::raiseError(500, $user->getError());
		}

		// Set some initial user values
		$user->set('id', 0);
		$user->set('usertype', 'Registered');
		$user->set('gid', $authorize->get_group_id('', $newUsertype, 'ARO'));

		$date = JFactory::getDate();
		$user->set('registerDate', $date->toSql());

		$useractivation = $usersConfig->get('useractivation');

		if ($useractivation == '1')
		{
			JLoader::import('joomla.user.helper');

			$user->set('block', '0');
		}

		if ($data['is_company'] == 1)
		{
			$tmp  = @explode(" ", $data['contact_person']);
			$name = @ $tmp[0] . ' ' . $tmp[1];
			$name = $app->input->get('username');
		}
		else
		{
			$name = $app->input->get('firstname') . ' ' . $app->input->get('lastname');
		}

		$email = $app->input->get('email');

		$password = $order_functions->random_gen_enc_key(12);

		// Disallow control chars in the email
		$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password);

		$user->password = md5($password);
		$user->set('name', $name);
		$user->name = $name;

		// If there was an error with registration, set the message and display form
		if (!$user->save())
		{
			JError::raiseWarning('', JText::_($user->getError()));

			return false;
		}

		$user_id = $user->id;
		$user->set('id', 0);

		if ($useractivation == 1)
		{
			$message = JText::_('COM_REDSHOP_REG_COMPLETE_ACTIVATE');
		}
		else
		{
			$message = JText::_('COM_REDSHOP_REG_COMPLETE');
		}

		// Creating Joomla user end
		$row          = $this->getTable('user_detail');
		$row->user_id = $user_id;

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if ($data['is_company'] == 1)
		{
			if (Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_COMPANY') != 0)
			{
				$row->shopper_group_id = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_COMPANY');
			}
			else
			{
				$row->shopper_group_id = 2;
			}
		}
		else
		{
			if (Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE') != 0)
			{
				$row->shopper_group_id = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE');
			}
			else
			{
				$row->shopper_group_id = 1;
			}
		}

		if ($data['is_company'] == 1)
		{
			$tmp            = explode(" ", $data['contact_person']);
			$row->firstname = $tmp[0];
			$row->lastname  = $tmp[1];
		}

		$row->user_email   = $user->email;
		$row->address_type = 'BT';

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$email = $user->email;

		$quotation_id       = $quotationDetail->quotation_id;
		$quotationdetailurl = JURI::root() . 'index.php?option=com_redshop&view=quotation_detail&quoid=' . $quotation_id . '&encr=' . $quotationDetail->quotation_encrkey;

		$mailbody = '<table>';
		$mailbody .= '<tr><td>' . JText::_('COM_REDSHOP_USERNAME') . '</td><td> : </td><td>' . $data['username'] . '</td></tr>';
		$mailbody .= '<tr><td>' . JText::_('COM_REDSHOP_PASSWORD') . '</td><td> : </td><td>' . $password . '</td></tr>';
		$mailbody .= '<tr><td>' . JText::_('COM_REDSHOP_QUOTATION_DETAILS') . '</td><td> : </td><td><a href="' . $quotationdetailurl . '">' . JText::_("COM_REDSHOP_QUOTATION_DETAILS") . '</a></td></tr>';
		$mailbody .= '</table>';
		$mailsubject = 'Register';
		$mailbcc     = null;
		$mailinfo    = $redshopMail->getMailtemplate(0, "quotation_user_register");

		if (count($mailinfo) > 0)
		{
			$mailbody    = $mailinfo[0]->mail_body;
			$mailsubject = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}

		$producthelper = productHelper::getInstance();
		$session       = JFactory::getSession();
		$cart          = $session->get('cart');
		$user          = JFactory::getUser();

		$cart['user_id']      = $user_id;
		$user_data            = $producthelper->getUserInformation($user_id);
		$cart['user_info_id'] = $user_data->users_info_id;
		$quotationDetail      = $this->store($cart);

		$this->sendQuotationMail($quotationDetail->quotation_id);

		$link = "<a href='" . $quotationdetailurl . "'>" . JText::_("COM_REDSHOP_QUOTATION_DETAILS") . "</a>";

		$mailbody = str_replace('{link}', $link, $mailbody);
		$mailbody = str_replace('{username}', $name, $mailbody);
		$mailbody = str_replace('{password}', $name, $mailbody);
		$mailbody = $redshopMail->imginmail($mailbody);

		JFactory::getMailer()->sendMail($MailFrom, $FromName, $email, $mailsubject, $mailbody, 1, null, $mailbcc);

		$session = JFactory::getSession();
		RedshopHelperCartSession::setCart(null);
		$session->set('ccdata', null);
		$session->set('issplit', null);
		$session->set('userfield', null);
		unset ($_SESSION ['ccdata']);

		return;
	}

	public function sendQuotationMail($quotaion_id)
	{
		$redshopMail = redshopMail::getInstance();
		$send        = $redshopMail->sendQuotationMail($quotaion_id);

		return $send;
	}

	public function getUserIdByEmail($email)
	{
		$db = JFactory::getDbo();

		$q = "SELECT * FROM " . $this->_table_prefix . "users_info "
			. "WHERE user_email = " . $db->quote($email) . " "
			. "AND address_type='BT' ";
		$this->_db->setQuery($q);
		$list = $this->_db->loadObject();

		return $list;
	}
}
