<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Import model
 *
 * @since  2.0.3
 */
class RedshopModelImport extends RedshopModel
{
	/**
	 * Shopper Groups information array
	 *
	 * @var  array
	 *
	 * @since  2.0.3
	 */
	private $shopperGroups = null;

	/**
	 * Users information array - email as a key
	 *
	 * @var  array
	 *
	 * @since  2.0.3
	 */
	private $usersInfo = null;

	/**
	 * Method for get all available imports plugin.
	 *
	 * @return  array  List of available imports.
	 *
	 * @since  2.0.3
	 */
	public function getImports()
	{
		$plugins = JPluginHelper::getPlugin('redshop_import');

		if (empty($plugins))
		{
			return array();
		}

		asort($plugins);

		$language = JFactory::getLanguage();

		foreach ($plugins as $plugin)
		{
			$language->load('plg_redshop_import_' . $plugin->name, JPATH_SITE . '/plugins/redshop_import/' . $plugin->name);
		}

		return $plugins;
	}

	/**
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public function getData()
	{
		ob_clean();
		$app     = JFactory::getApplication();
		$session = JFactory::getSession();
		$jinput  = $app->input;
		$import  = $jinput->get('import', '');
		$task    = explode('.', $jinput->get('task', ''));
		$task    = $task[count($task) - 1];
		$post    = $jinput->post->getArray();
		$files   = $jinput->files->getArray();
		$msg     = '';

		if (isset($files[$task . $import]))
		{
			$files = $files[$task . $import];
		}

		if ($task && $import)
		{
			if ($files['name'] == "")
			{
				$msg = JText::_('COM_REDSHOP_PLEASE_SELECT_FILE');
			}

			$ext = strtolower(JFile::getExt($files['name']));

			if ($ext != 'csv')
			{
				$msg = JText::_('COM_REDSHOP_FILE_EXTENSION_WRONG');
			}

			// Upload csv file
			$src  = $files['tmp_name'];
			$dest = JPATH_ROOT . '/components/com_redshop/assets/importcsv/' . $post['import'] . '/' . $files['name'];
			JFile::upload($src, $dest);

			$session->clear('ImportPost');
			$session->clear('Importfile');
			$session->clear('Importfilename');
			$session->set('ImportPost', $post);
			$session->set('Importfile', $files);
			$session->set('Importfilename', $files['name']);

			$app->redirect('index.php?option=com_redshop&view=import&layout=importlog');
		}
		else
		{
			if (!$import)
			{
				$msg = JText::_('COM_REDSHOP_PLEASE_SELECT_SECTION');
			}
		}

		$app->redirect('index.php?option=com_redshop&view=import', $msg, 'error');
	}

	/**
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public function importdata()
	{
		ob_clean();
		$session = JFactory::getSession();
		$db      = JFactory::getDbo();
		$jinput  = JFactory::getApplication()->input;

		// Get all posted data
		$newLine  = $jinput->get('new_line');
		$post     = $session->get('ImportPost');
		$fileName = $session->get('Importfilename');

		$import = trim($jinput->getString('import'));

		// Load the table model
		switch ($import)
		{
			case 'products':
				$row = $this->getTable('product_detail');
				break;
			case 'categories':
				$row = $this->getTable('category_detail');
				break;
		}

		// Loop through the CSV file
		// First line first as that is the column headers
		$line         = 1;
		$headers      = array();
		$correctlines = 0;
		$handle       = fopen(JPATH_ROOT . '/components/com_redshop/assets/importcsv/' . $import . '/' . $fileName, "r");

		$separator = ",";

		if ($post['separator'] != "")
		{
			$separator = $post['separator'];
		}

		list($susec, $ssec) = explode(" ", microtime());
		$start_micro_time = ((float) $susec + (float) $ssec);
		$session->set('start_micro_time', $start_micro_time);

		while (($data = fgetcsv($handle, 0, $separator, '"')) !== false)
		{
			if ($this->getTimeLeft() > 0)
			{
				// Skip headers
				if ($line == 1)
				{
					foreach ($data as $key => $name)
					{
						// Set the column headers and remove any non-ASCII characters from a string
						$headers[$key] = preg_replace('/[^(\x20-\x7F)]*/', '', $name);
					}
				}
				else
				{
					if ($line > $newLine)
					{
						$rawdata = array();

						foreach ($data as $key => $name)
						{
							if (!isset($headers[$key]))
							{
								continue;
							}

							// Bind the data
							if ($headers[$key] == 'category_full_image' && $post['import'] == 'categories')
							{
								/*
								 * $image_name = basename($name);
								$rawdata[$headers[$key]] = $image_name;

								if ($image_name != "")
								{
									@fopen($name, "r");
									$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $image_name;

									// Copy If file is not already exist
									if (!file_exists($dest))
									{
										copy($name, $dest);
									}
								}
								*/
							}

							elseif ($headers[$key] == 'sitepath' && $post['import'] == 'products')
							{
								$this->sitepath = $rawdata[$headers[$key]] = $name;
							}
							else
							{
								$rawdata[$headers[$key]] = $name;
							}
						}

						// Import attributes
						if ($import == 'attributes')
						{
							$productId = $this->getProductIdByNumber($rawdata['product_number']);

							// Insert product attributes
							$attribute_id             = "";
							$attribute_name           = mb_convert_encoding($rawdata['attribute_name'], 'UTF-8', $post['encoding']);
							$attribute_ordering       = isset($rawdata['attribute_ordering']) ? $rawdata['attribute_ordering'] : '';
							$allow_multiple_selection = isset($rawdata['allow_multiple_selection']) ? $rawdata['allow_multiple_selection'] : '';
							$hide_attribute_price     = isset($rawdata['hide_attribute_price']) ? $rawdata['hide_attribute_price'] : '';
							$attribute_display_type   = isset($rawdata['display_type']) ? $rawdata['display_type'] : '';
							$attribute_required       = isset($rawdata['attribute_required']) ? $rawdata['attribute_required'] : '';

							$query = $db->getQuery(true)
								->select($db->quoteName('attribute_id'))
								->from($db->quoteName('#__redshop_product_attribute'))
								->where($db->quoteName('product_id') . ' = ' . $db->quote($productId))
								->where($db->quoteName('attribute_name') . ' = ' . $db->quote($attribute_name));

							$db->setQuery($query);

							$attribute_id = $db->loadResult();

							// Get table Instance
							$attrow = $this->getTable('product_attribute');
							$attrow->load($attribute_id);
							$attrow->attribute_name = $attribute_name;

							if ($attribute_ordering != '')
							{
								$attrow->ordering = $attribute_ordering;
							}

							if ($allow_multiple_selection != '')
							{
								$attrow->allow_multiple_selection = $allow_multiple_selection;
							}

							if ($hide_attribute_price != '')
							{
								$attrow->hide_attribute_price = $hide_attribute_price;
							}

							if ($attribute_required != '')
							{
								$attrow->attribute_required = $attribute_required;
							}

							if ($attribute_display_type != '')
							{
								$attrow->display_type = $attribute_display_type;
							}

							$attrow->product_id = $productId;

							if ($attrow->store())
							{
								$att_insert_id = $attrow->attribute_id;

								// Insert product attributes property
								$property_id   = 0;
								$property_name = isset($rawdata['property_name']) ? mb_convert_encoding($rawdata['property_name'], 'UTF-8', $post['encoding']) : '';

								if ($property_name != "")
								{
									$property_ordering   = isset($rawdata['property_ordering']) ? $rawdata['property_ordering'] : '';
									$property_price      = isset($rawdata['property_price']) ? $rawdata['property_price'] : '';
									$property_number     = isset($rawdata['property_virtual_number']) ? $rawdata['property_virtual_number'] : '';
									$setdefault_selected = isset($rawdata['setdefault_selected']) ? $rawdata['setdefault_selected'] : '';
									$setdisplay_type     = isset($rawdata['setdisplay_type']) ? $rawdata['setdisplay_type'] : '';
									$setrequire_selected = isset($rawdata['required_sub_attribute']) ? $rawdata['required_sub_attribute'] : '';
									$oprand              = isset($rawdata['oprand']) ? $rawdata['oprand'] : '';
									$property_image      = basename(isset($rawdata['property_image']) ? $rawdata['property_image'] : '');
									$property_main_image = basename(isset($rawdata['property_main_image']) ? $rawdata['property_main_image'] : '');

									$query = $db->getQuery(true)
										->select($db->quoteName('property_id'))
										->from($db->quoteName('#__redshop_product_attribute_property'))
										->where($db->quoteName('attribute_id') . ' = ' . $db->quote($att_insert_id))
										->where($db->quoteName('property_name') . ' = ' . $db->quote($property_name));

									$db->setQuery($query);
									$property_id = $db->loadResult();

									// Get Table Instance
									$proprow = $this->getTable('attribute_property');
									$proprow->load($property_id);
									$proprow->attribute_id  = $att_insert_id;
									$proprow->property_name = $property_name;

									if ($property_price != "")
									{
										$proprow->property_price = $property_price;
									}

									if ($property_ordering != "")
									{
										$proprow->ordering = $property_ordering;
									}

									if ($property_number != "")
									{
										$proprow->property_number = $property_number;
									}

									if ($setdefault_selected != "")
									{
										$proprow->setdefault_selected = $setdefault_selected;
									}

									if ($setrequire_selected != "")
									{
										$proprow->setrequire_selected = $setrequire_selected;
									}

									if ($setdisplay_type != "")
									{
										$proprow->setdisplay_type = $setdisplay_type;
									}

									if ($oprand == '+' || $oprand == '-' || $oprand == '*' || $oprand == '/' || $oprand == '=')
									{
										$proprow->oprand = $oprand;
									}

									if ($property_image)
									{
										$proprow->property_image = $property_image;
									}

									if ($property_main_image)
									{
										$proprow->property_main_image = $property_main_image;
									}

									if ($proprow->store())
									{
										$prop_insert_id = $proprow->property_id;

										$mainstock = isset($rawdata['property_stock']) ? $rawdata['property_stock'] : '';

										if ($mainstock != "")
										{
											$mainstock_split = explode("#", $mainstock);

											for ($r = 0, $rn = count($mainstock_split); $r < $rn; $r++)
											{
												if ($mainstock_split[$r] != "")
												{
													$mainquaexplode = explode(":", $mainstock_split[$r]);

													if (count($mainquaexplode) == 2)
													{
														$query_mainins_stockroom = $db->getQuery(true)
															->select("*")
															->from($db->quoteName('#__redshop_stockroom'))
															->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($mainquaexplode[0]));

														$db->setQuery($query_mainins_stockroom);
														$stock_id = $db->loadObjectList();

														if (count($stock_id) > 0)
														{
															$query_mainins = $db->getQuery(true)
																->select("*")
																->from($db->quoteName('#__redshop_product_attribute_stockroom_xref'))
																->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($mainquaexplode[0]))
																->where($db->quoteName('section') . ' = ' . $db->quote('property'))
																->where($db->quoteName('section_id') . ' = ' . $db->quote($prop_insert_id));

															$db->setQuery($query_mainins);
															$product_id = $db->loadObjectList();

															if (count($product_id) > 0)
															{
																$update_row_query = $db->getQuery(true)
																	->update($db->quoteName('#__redshop_product_attribute_stockroom_xref'))
																	->set($db->quoteName('quantity') . ' = ' . $db->quote($mainquaexplode[1]))
																	->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($mainquaexplode[0]))
																	->where($db->quoteName('section') . ' = ' . $db->quote('property'))
																	->where($db->quoteName('section_id') . ' = ' . $db->quote($prop_insert_id));

																$db->setQuery($update_row_query);
																$db->execute();
															}
															else
															{
																$insert_row_query               = new stdClass();
																$insert_row_query->quantity     = $mainquaexplode[1];
																$insert_row_query->stockroom_id = $mainquaexplode[0];
																$insert_row_query->section      = 'property';
																$insert_row_query->section_id   = $prop_insert_id;
																$db->insertObject('#__redshop_product_attribute_stockroom_xref', $insert_row_query);

															}
														}
													}
												}
											}
										}

										if ($property_image != "")
										{
											$property_image_path = $rawdata['property_image'];

											try
											{
												fopen($property_image_path, "r");
											}
											catch (Exception $ex)
											{
												JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'error');
											}

											$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property_image;

											// Copy If file is not already exist
											if (!JFile::exists($dest))
											{
												JFile::copy($property_image_path, $dest);
											}
										}

										if ($property_main_image != "")
										{
											$property_image_path = $rawdata['property_main_image'];

											try
											{
												fopen($property_image_path, "r");
											}
											catch (Exception $ex)
											{
												JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'error');
											}

											$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $property_main_image;

											// Copy If file is not already exist
											if (!JFile::exists($dest))
											{
												JFile::copy($property_image_path, $dest);
											}
										}

										// Redshop product attribute subproperty
										$subattribute_color_id   = "";
										$subattribute_color_name = mb_convert_encoding($rawdata['subattribute_color_name'], 'UTF-8', $post['encoding']);

										if ($subattribute_color_name != "")
										{
											$subattribute_color_ordering      = isset($rawdata['subattribute_color_ordering']) ? $rawdata['subattribute_color_ordering'] : '';
											$subattribute_setdefault_selected = isset($rawdata['subattribute_setdefault_selected']) ? $rawdata['subattribute_setdefault_selected'] : '';
											$subattribute_color_title         = isset($rawdata['subattribute_color_title']) ? mb_convert_encoding($rawdata['subattribute_color_title'], 'UTF-8', $post['encoding']) : '';
											$subattribute_color_number        = isset($rawdata['subattribute_virtual_number']) ? $rawdata['subattribute_virtual_number'] : '';
											$subattribute_color_price         = isset($rawdata['subattribute_color_price']) ? $rawdata['subattribute_color_price'] : '';
											$oprand                           = isset($rawdata['subattribute_color_oprand']) ? $rawdata['subattribute_color_oprand'] : '';
											$subattribute_color_image         = basename(isset($rawdata['subattribute_color_image']) ? $rawdata['subattribute_color_image'] : '');

											$query = $db->getQuery(true)
												->select(array('subattribute_color_id'))
												->from($db->quoteName('#__redshop_product_subattribute_color'))
												->where($db->quoteName('subattribute_id') . ' = ' . $db->quote($prop_insert_id))
												->where($db->quoteName('subattribute_color_name') . ' = ' . $db->quote($subattribute_color_name));

											$db->setQuery($query);
											$subattribute_color_id = $db->loadResult();

											// Get Table Instance
											$subproprow = $this->getTable('subattribute_property');
											$subproprow->load($subattribute_color_id);
											$subproprow->subattribute_color_name = $subattribute_color_name;

											if ($subattribute_color_price != "")
											{
												$subproprow->subattribute_color_price = $subattribute_color_price;
											}

											if ($subattribute_color_ordering != "")
											{
												$subproprow->ordering = $subattribute_color_ordering;
											}

											if ($subattribute_setdefault_selected != "")
											{
												$subproprow->setdefault_selected = $subattribute_setdefault_selected;
											}

											if ($subattribute_color_title != "")
											{
												$subproprow->subattribute_color_title = $subattribute_color_title;
											}

											if ($subattribute_color_number != "")
											{
												$subproprow->subattribute_color_number = $subattribute_color_number;
											}

											if ($oprand == '+' || $oprand == '-' || $oprand == '*' || $oprand == '/' || $oprand == '=')
											{
												$subproprow->oprand = $oprand;
											}

											if ($subattribute_color_image)
											{
												$subproprow->subattribute_color_image = $subattribute_color_image;
											}

											$subproprow->subattribute_id = $prop_insert_id;

											if ($subproprow->store())
											{
												$prop_insert_id_sub = $subproprow->subattribute_color_id;

												$mainstock = $rawdata['subattribute_stock'];

												if ($mainstock != "")
												{
													$mainstock_split = explode("#", $mainstock);

													for ($r = 0, $rn = count($mainstock_split); $r < $rn; $r++)
													{
														if ($mainstock_split[$r] != "")
														{
															$mainquaexplode = explode(":", $mainstock_split[$r]);

															if (count($mainquaexplode) == 2)
															{
																$query_mainins_stockroom = $db->getQuery(true)
																	->select("*")
																	->from($db->quoteName('#__redshop_stockroom'))
																	->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($mainquaexplode[0]));
																$db->setQuery($query_mainins_stockroom);
																$stock_id = $db->loadObjectList();

																if (count($stock_id) > 0)
																{
																	$query_mainins = $db->getQuery(true)
																		->select("*")
																		->from('#__redshop_product_attribute_stockroom_xref')
																		->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($mainquaexplode[0]))
																		->where($db->quoteName('section') . ' = ' . $db->quote('subproperty'))
																		->where($db->quoteName('section_id') . ' = ' . $db->quote($prop_insert_id_sub));

																	$db->setQuery($query_mainins);
																	$product_id = $db->loadObjectList();

																	if (count($product_id) > 0)
																	{
																		$update_row_query = $db->getQuery(true)
																			->update($db->quoteName('#__redshop_product_attribute_stockroom_xref'))
																			->set($db->quoteName('quantity') . ' = ' . $db->quote($mainquaexplode[1]))
																			->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($mainquaexplode[0]))
																			->where($db->quoteName('section') . ' = ' . $db->quote('subproperty'))
																			->where($db->quoteName('section_id') . ' = ' . $db->quote($prop_insert_id_sub));
																		$db->setQuery($update_row_query);
																		$db->execute();
																	}
																	else
																	{
																		$insert_row_query               = new stdClass;
																		$insert_row_query->quantity     = $mainquaexplode[1];
																		$insert_row_query->stockroom_id = $mainquaexplode[0];
																		$insert_row_query->section_id   = $prop_insert_id_sub;
																		$insert_row_query->section      = 'subproperty';
																		$db->insertObject('#__redshop_product_attribute_stockroom_xref', $insert_row_query);
																	}
																}
															}
														}
													}
												}

												if ($subattribute_color_image != "")
												{
													$subproperty_image_path = $rawdata['subattribute_color_image'];

													try
													{
														fopen($subproperty_image_path, "r");
													}
													catch (Exception $ex)
													{
														JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'error');
													}

													$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $subattribute_color_image;

													// Copy If file is not already exist
													if (!JFile::exists($dest))
													{
														JFile::copy($subproperty_image_path, $dest);
													}
												}
											}
										}
									}
								}

								$correctlines++;
							}
						}

						// Import fields
						if ($post['import'] == 'fields')
						{
							$field_section       = $rawdata['section'];
							$field_maxlength     = $rawdata['maxlength'];
							$field_cols          = $rawdata['cols'];
							$field_rows          = $rawdata['rows'];
							$field_size          = $rawdata['size'];
							$field_show_in_front = $rawdata['show_in_front'];
							$required            = $rawdata['required'];
							$published           = $rawdata['published'];
							$itemid              = $rawdata['itemid'];
							$section             = $rawdata['section'];
							$ch_data_id          = 0;
							$ch_value_id         = 0;
							$field_value         = $rawdata['field_value'];
							$field_name_value    = $rawdata['field_name'];

							if ($section == 1)
							{
								$itemid = $this->getProductIdByNumber($rawdata['data_number']);
							}

							// Get field id
							$query = $db->getQuery(true)
								->select('id')
								->from($db->qn('#__redshop_fields'))
								->where($db->qn('section') . ' = ' . $db->quote($field_section))
								->where('name = ' . $db->q($rawdata['name_field']));
							$db->setQuery($query);

							if ($field_id_dv = $db->loadResult())
							{
								// Get Data Id
								if (isset($rawdata['data_txt']) && $rawdata['data_txt'] != '')
								{
									$query = $db->getQuery(true)
										->select('data_id')
										->from($db->qn('#__redshop_fields_data'))
										->where($db->qn('fieldid') . ' = ' . $db->quote($field_id_dv))
										->where($db->qn('itemid') . ' = ' . $db->quote($itemid));
									$db->setQuery($query);
									$ch_data_id = $db->loadResult();
								}

								// Get Value Id
								if (isset($rawdata['field_name']) && $rawdata['field_name'] != '')
								{
									$query = $db->getQuery(true)
										->select('value_id')
										->from($db->qn('#__redshop_fields_value'))
										->where($db->qn('field_id') . ' = ' . $db->quote($field_id_dv))
										->where('field_name = ' . $db->q($rawdata['field_name']));
									$db->setQuery($query);
									$ch_value_id = $db->loadResult();
								}
							}

							if (isset($rawdata['title']) && $rawdata['title'] != '')
							{
								$fieldObject                = new stdClass;
								$fieldObject->title         = $rawdata['title'];
								$fieldObject->name_field    = $rawdata['name_field'];
								$fieldObject->type          = $rawdata['type'];
								$fieldObject->desc          = $rawdata['desc'];
								$fieldObject->class         = $rawdata['class'];
								$fieldObject->section       = $field_section;
								$fieldObject->maxlength     = $field_maxlength;
								$fieldObject->cols          = $field_cols;
								$fieldObject->rows          = $field_rows;
								$fieldObject->size          = $field_size;
								$fieldObject->show_in_front = $field_show_in_front;
								$fieldObject->required      = $required;
								$fieldObject->published     = $published;

								if ($field_id_dv)
								{
									$fieldObject->id = $field_id_dv;
									$db->updateObject('#__redshop_fields', $fieldObject, 'id');
								}
								else
								{
									if ($db->insertObject('#__redshop_fields', $fieldObject, 'id'))
									{
										$field_id_dv = $fieldObject->id;
									}
								}
							}

							if (isset($rawdata['data_txt']) && $rawdata['data_txt'] != '')
							{
								$fieldObject           = new stdClass;
								$fieldObject->fieldid  = $field_id_dv;
								$fieldObject->data_txt = $rawdata['data_txt'];
								$fieldObject->itemid   = $itemid;
								$fieldObject->section  = $section;

								if (!$ch_data_id)
								{
									$db->insertObject('#__redshop_fields_data', $fieldObject);
								}
								else
								{
									$fieldObject->data_id = $ch_data_id;
									$db->updateObject('#__redshop_fields_data', $fieldObject, 'data_id');
								}
							}

							if (isset($rawdata['field_name']) && $rawdata['field_name'] != '')
							{
								$fieldObject              = new stdClass;
								$fieldObject->field_id    = $field_id_dv;
								$fieldObject->field_value = $field_value;
								$fieldObject->field_name  = $field_name_value;

								if (!$ch_value_id)
								{
									$db->insertObject('#__redshop_fields_value', $fieldObject);
								}
								else
								{
									$fieldObject->value_id = $ch_value_id;
									$db->updateObject('#__redshop_fields_value', $fieldObject, 'value_id');
								}
							}

							$correctlines++;
						}

						// Import fields
						if ($post['import'] == 'fields_data')
						{
							$field_id             = $rawdata['id'];
							$field_product_number = $rawdata['data_number'];
							$field_data_txt       = $rawdata['data_txt'];

							if ($field_product_number && $field_id)
							{
								$product_id = $this->getProductIdByNumber($field_product_number);

								if ($product_id)
								{
									$q = "SELECT count(fieldid) as fieldexist FROM `#__redshop_fields_data` "
										. "WHERE `fieldid` = '" . $field_id . "' "
										. "AND itemid ='" . $product_id . "' "
										. "AND section ='1' ";
									$db->setQuery($q);
									$fieldexist = $db->loadResult();

									if ($fieldexist == 0)
									{
										$query = "INSERT IGNORE INTO `#__redshop_fields_data` "
											. "(`fieldid`, `data_txt`, `itemid`, `section` ) "
											. "VALUES ('" . $field_id . "', '" . $field_data_txt . "', '" . $product_id . "', '1') ";
										$db->setQuery($query);
										$db->execute();
									}
									else
									{
										$query = "UPDATE `#__redshop_fields_data` SET
											`data_txt` = '" . $field_data_txt . "'
											 WHERE `fieldid` = '" . $field_id . "'
		                                     AND itemid ='" . $product_id . "'
		                                     AND 	section ='1' ";
										$db->setQuery($query);
										$db->execute();
									}

									$correctlines++;
								}
							}
						}

						// Import Related Products
						if ($post['import'] == 'related_product')
						{
							$relpid = $this->getProductIdByNumber($rawdata['related_sku']);
							$pid    = $this->getProductIdByNumber($rawdata['product_sku']);
							$query  = "INSERT IGNORE INTO `#__redshop_product_related` (`related_id`, `product_id`) VALUES ('"
								. $relpid . "', '" . $pid . "')";
							$db->setQuery($query);

							if ($db->execute())
							{
								$correctlines++;
							}
						}

						// Import users
						if ($post['import'] == 'users')
						{
							// Get all shopper group information
							$this->getShopperGroupInfo();

							// Shopper Group Exist
							if (array_key_exists(trim($rawdata['shopper_group_name']), $this->shopperGroups->name))
							{
								$shopperGroupId = $this->shopperGroups->name[trim($rawdata['shopper_group_name'])]
									->shopper_group_id;
							}
							// Create new shopper group
							else
							{
								$shopper = $this->getTable('shopper_group_detail');
								$shopper->load();
								$shopper->shopper_group_name          = trim($rawdata['shopper_group_name']);
								$shopper->shopper_group_customer_type = 1;
								$shopper->shopper_group_portal        = 0;
								$shopper->store();

								// Get inserted shopper group id
								$shopperGroupId = $shopper->shopper_group_id;
							}

							// Get redshop user info table
							$reduser = $this->getTable('user_detail');

							// Get all users information
							$this->getUsersInfoByEmail();

							$csvUserId = 0;

							if (isset($rawdata['id']))
							{
								$csvUserId = (int) trim($rawdata['id']);
							}

							$csvRSUserId = 0;

							if (isset($rawdata['users_info_id']))
							{
								$csvRSUserId = (int) trim($rawdata['users_info_id']);
							}

							// Setting default for new users
							$jUserId    = 0;
							$newRedUser = true;
							$redUserId  = $csvRSUserId;

							// Using email to map users as unique
							if (isset($this->usersInfo[trim($rawdata['email'])]))
							{
								$usersInfo = $this->usersInfo[trim($rawdata['email'])];

								// Joomla User
								$jUserId = $usersInfo->id;

								$redUserId = (int) $usersInfo->users_info_id;

								// Redshop User
								// @todo: review this condition 0 != $csvRSUserId && $csvRSUserId == $usersInfo->users_info_id
								if ($redUserId)
								{
									$newRedUser = false;
								}
							}

							// Update/Create Joomla User
							$user = JUser::getInstance($jUserId);

							$jUserInfo = array(
								'username'     => trim($rawdata['username']),
								'name'         => trim($rawdata['name']),
								'email'        => trim($rawdata['email']),
								'groups'       => explode(',', trim($rawdata['usertype'])),
								'registerDate' => JFactory::getDate()->toSql()
							);

							if (isset($rawdata['block']))
							{
								$jUserInfo['block'] = (int) $rawdata['block'];
							}

							if (isset($rawdata['sendEmail']))
							{
								$jUserInfo['sendEmail'] = (int) $rawdata['sendEmail'];
							}

							if (isset($rawdata['password']) && '' != trim($rawdata['password']))
							{
								$jUserInfo['password']  = trim($rawdata['password']);
								$jUserInfo['password2'] = trim($rawdata['password']);
							}

							// Bind the data.
							if (!$user->bind($jUserInfo))
							{
								$this->setError($user->getError());

								return false;
							}

							// Save user information
							if ($user->save())
							{
								// Assign user id from table
								$jUserId = $user->id;
							}

							// Seeting users info id
							$reduser->set('users_info_id', $redUserId);

							// Setting Joomla user id
							$reduser->set('user_id', $jUserId);
							$reduser->set('user_email', trim($rawdata['email']));
							$reduser->set('firstname', $rawdata['firstname']);
							$reduser->set('address_type', 'BT');
							$reduser->set('lastname', $rawdata['lastname']);
							$reduser->set('company_name', $rawdata['company_name']);
							$reduser->set('vat_number', $rawdata['vat_number']);
							$reduser->set('tax_exempt', $rawdata['tax_exempt']);
							$reduser->set('shopper_group_id', $shopperGroupId);
							$reduser->set('is_company', $rawdata['is_company']);
							$reduser->set('address', $rawdata['address']);
							$reduser->set('city', $rawdata['city']);
							$reduser->set('country_code', $rawdata['country_code']);
							$reduser->set('state_code', $rawdata['state_code']);
							$reduser->set('zipcode', $rawdata['zipcode']);
							$reduser->set('phone', $rawdata['phone']);
							$reduser->set('tax_exempt_approved', $rawdata['tax_exempt_approved']);
							$reduser->set('approved', $rawdata['approved']);

							if ($newRedUser)
							{
								$ret = $db->insertObject('#__redshop_users_info', $reduser, 'users_info_id');
							}
							else
							{
								$ret = $db->updateObject('#__redshop_users_info', $reduser, 'users_info_id');
							}

							if ($ret)
							{
								$correctlines++;
							}
						}

						// Shipping Address Import
						if ($post['import'] == 'shipping_address')
						{
							if (trim($rawdata['username']) != "")
							{
								$q = "SELECT id FROM `#__users` "
									. "WHERE `username` = '" . trim($rawdata['username']) . "' ";
								$db->setQuery($q);
								$joom_user_id = $db->loadResult();

								if ($joom_user_id > 0)
								{
									$reduser = $this->getTable('user_detail');
									$reduser->set('user_id', $joom_user_id);
									$reduser->set('user_email', trim($rawdata['email']));
									$reduser->set('firstname', $rawdata['firstname']);
									$reduser->set('address_type', 'ST');
									$reduser->set('lastname', $rawdata['lastname']);
									$reduser->set('company_name', $rawdata['company_name']);
									$reduser->set('address', $rawdata['address']);
									$reduser->set('city', $rawdata['city']);
									$reduser->set('country_code', $rawdata['country_code']);
									$reduser->set('state_code', $rawdata['state_code']);
									$reduser->set('zipcode', $rawdata['zipcode']);
									$reduser->set('phone', $rawdata['phone']);
									$reduser->set('users_info_id', 0);
									$ret = $db->insertObject('#__redshop_users_info', $reduser, 'users_info_id');

									if ($ret)
									{
										$correctlines++;
									}
								}
							}
						}

						// Shopper group Import
						if ($post['import'] == 'shopperGroupProductPrice')
						{
							$ret = $this->importShopperGroupProductPrice($rawdata);

							if ($ret)
							{
								$correctlines++;
							}
						}

						// Shopper group Import
						if ($post['import'] == 'shopperGroupAttributePrice')
						{
							$ret = $this->importShopperGroupAttributePrice($rawdata);

							if ($ret)
							{
								$correctlines++;
							}
						}

						// Import stockroom data
						if ($post['import'] == 'product_stockroom_data')
						{
							$product_id = $this->getProductIdByNumber(trim($rawdata['product_number']));

							if ($product_id > 0)
							{
								array_shift($rawdata);

								foreach ($rawdata as $stockroom_name => $quantity)
								{
									$query = $db->getQuery(true)
										->select('stockroom_id')
										->from($db->qn('#__redshop_stockroom'))
										->where($db->qn('stockroom_name') . ' = ' . $db->quote($stockroom_name));

									$db->setQuery($query);
									$stockroom_id = $db->loadResult();

									if ($stockroom_id > 0)
									{
										$productStockroom               = new stdClass;
										$productStockroom->product_id   = $product_id;
										$productStockroom->stockroom_id = $stockroom_id;
										$productStockroom->quantity     = $quantity;

										$query = $db->getQuery(true)
											->select('product_id')
											->from($db->qn('#__redshop_product_stockroom_xref'))
											->where($db->qn('product_id') . ' = ' . $db->quote($product_id))
											->where($db->qn('stockroom_id') . ' = ' . $db->quote($stockroom_id));

										$db->setQuery($query);
										$stockExists = $db->loadResult();

										if ($stockExists)
										{
											$db->updateObject('#__redshop_product_stockroom_xref', $productStockroom, array('product_id', 'stockroom_id'));
										}
										else
										{
											$productStockroom->preorder_stock   = 0;
											$productStockroom->ordered_preorder = 0;

											$db->insertObject('#__redshop_product_stockroom_xref', $productStockroom);
										}

										$correctlines++;
									}
								}
							}
						}

						// Import Economic group Products
						if ($post['import'] == 'economic_group_product')
						{
							$product_number = $rawdata['product_number'];
							$product_group  = $rawdata['product_group'];

							if ($product_group == "")
							{
								$product_group = 1;
							}

							if ($product_number)
							{
								$product_id = $this->getProductIdByNumber($product_number);

								if ($product_id)
								{
									$query = "UPDATE `#__redshop_product` SET
											`accountgroup_id` = '" . $product_group . "'
											 WHERE `product_id` = '" . $product_id . "'";

									$db->setQuery($query);
									$db->execute();

									$correctlines++;
								}
							}
						}
					}
				}

				$line++;
			}
			else
			{
				$blank = "";
				$text  = "" . $line . "`_`" . $blank . "";
				ob_clean();
				echo $text;
				JFactory::getApplication()->close();
			}
		}

		fclose($handle);
		$text = "`_`" . $correctlines . "`_`" . $correctlines . "";
		ob_clean();
		echo $text;

		JFactory::getApplication()->close();
	}

	/**
	 * Get all users information
	 *
	 * @return  array  User email id as a key of an array
	 */
	private function getUsersInfoByEmail()
	{
		// Return loaded info if available
		if (null != $this->usersInfo)
		{
			return $this->usersInfo;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
			->from($db->qn('#__users', 'u'))
			->leftjoin(
				$db->qn('#__redshop_users_info', 'ui')
				. ' ON ' . $db->qn('u.email') . ' = ' . $db->qn('ui.user_email')
				. ' AND ' . $db->qn('ui.address_type') . '=' . $db->q('BT')
			);

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$this->usersInfo = $db->loadObjectList('email');
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $this->usersInfo;
	}

	/**
	 * Get Shopper Group Id from input
	 *
	 * @param   integer $shopperGroupInputId Shopper Group Id from CSV File
	 *
	 * @return  integer  Shopper Group Id
	 */
	public function getShopperGroupId($shopperGroupInputId)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('shopper_group_id')
			->from($db->qn('#__redshop_shopper_group'))
			->where($db->qn('shopper_group_id') . ' = ' . (int) trim($shopperGroupInputId));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$shopperGroupId = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $shopperGroupId;
	}

	/**
	 * Get Shopper Group Id from input
	 *
	 * @param   integer $shopperGroupInputId Shopper Group Id from CSV File
	 *
	 * @return  integer  Shopper Group Id
	 */
	public function getShopperGroupInfo()
	{
		if (null == $this->shopperGroups)
		{
			// Initialiase variables.
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_shopper_group'));

			// Set the query and load the result.
			$db->setQuery($query);

			try
			{
				$this->shopperGroups        = new stdClass;
				$this->shopperGroups->index = $db->loadObjectList();
				$this->shopperGroups->name  = $db->loadObjectList('shopper_group_name');
			}
			catch (RuntimeException $e)
			{
				throw new RuntimeException($e->getMessage(), $e->getCode());
			}
		}

		return $this->shopperGroups;
	}

	/**
	 * Import Shopper Group Product price data
	 *
	 * @param   array $rawdata CSV raw data
	 *
	 * @return  boolean            True on success
	 */
	public function importShopperGroupProductPrice($rawdata)
	{
		if (trim($rawdata['product_number']) == "")
		{
			return false;
		}

		// Initialiase variables.
		$db = JFactory::getDbo();

		$shopperGroupId = $this->getShopperGroupId(trim($rawdata['shopper_group_id']));

		if (!$shopperGroupId)
		{
			return false;
		}

		// Get Product by number
		$productId = $this->getProductIdByNumber(trim($rawdata['product_number']));

		if (!$productId)
		{
			return false;
		}

		$query = $db->getQuery(true)
			->select('price_id')
			->from($db->qn('#__redshop_product_price'))
			->where($db->qn('product_id') . ' = ' . (int) $productId)
			->where($db->qn('shopper_group_id') . ' = ' . (int) $shopperGroupId)
			->where($db->qn('price_quantity_start') . ' = ' . (int) trim($rawdata['price_quantity_start']))
			->where($db->qn('price_quantity_end') . ' = ' . (int) trim($rawdata['price_quantity_end']));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$priceId = (int) $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$reduser = $this->getTable('prices_detail');
		$reduser->set('product_id', $productId);
		$reduser->set('product_price', trim($rawdata['product_price']));
		$reduser->set('product_currency', Redshop::getConfig()->get('CURRENCY_CODE'));
		$reduser->set('cdate', time());
		$reduser->set('shopper_group_id', $shopperGroupId);
		$reduser->set('price_quantity_start', trim($rawdata['price_quantity_start']));
		$reduser->set('price_quantity_end', trim($rawdata['price_quantity_end']));
		$reduser->set('discount_price', trim($rawdata['discount_price']));
		$reduser->set('discount_start_date', trim($rawdata['discount_start_date']));
		$reduser->set('discount_end_date', trim($rawdata['discount_end_date']));
		$reduser->set('price_id', $priceId);

		if ($reduser->store())
		{
			return true;
		}
	}

	/**
	 * Import Shopper Group Product price data
	 *
	 * @param   array $rawdata CSV raw data
	 *
	 * @return  boolean   True on success
	 */
	public function importShopperGroupAttributePrice($rawdata)
	{
		if (trim($rawdata['attribute_number']) == "")
		{
			return false;
		}

		$shopperGroupId = $this->getShopperGroupId(trim($rawdata['shopper_group_id']));

		if (!$shopperGroupId)
		{
			return false;
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if ($rawdata['section'] == "property")
		{
			$query->select('property_id')
				->from($db->qn('#__redshop_product_attribute_property'))
				->where($db->qn('property_number') . ' = ' . $db->q($rawdata['attribute_number']));
		}
		else
		{
			$query->select('subattribute_color_id')
				->from($db->qn('#__redshop_product_subattribute_color'))
				->where($db->qn('subattribute_color_number') . ' = ' . $db->q($rawdata['attribute_number']));
		}

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$sectionId = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		if (!$sectionId)
		{
			return false;
		}

		$query = $db->getQuery(true)
			->select('price_id')
			->from($db->qn('#__redshop_product_attribute_price'))
			->where($db->qn('section_id') . ' = ' . (int) $sectionId)
			->where($db->qn('section') . ' = ' . $db->q(trim($rawdata['section'])))
			->where($db->qn('shopper_group_id') . ' = ' . (int) $shopperGroupId)
			->where($db->qn('price_quantity_start') . ' = ' . (int) trim($rawdata['price_quantity_start']))
			->where($db->qn('price_quantity_end') . ' = ' . (int) trim($rawdata['price_quantity_end']));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$priceId = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$reduser = $this->getTable('attributeprices_detail');
		$reduser->set('section_id', $sectionId);
		$reduser->set('section', trim($rawdata['section']));

		if (!$price_id)
		{
			$price_id = 0;
		}

		$reduser->set('product_price', trim($rawdata['attribute_price']));
		$reduser->set('product_currency', Redshop::getConfig()->get('CURRENCY_CODE'));
		$reduser->set('cdate', time());
		$reduser->set('shopper_group_id', trim($rawdata['shopper_group_id']));
		$reduser->set('price_quantity_start', (int) trim($rawdata['price_quantity_start']));
		$reduser->set('price_quantity_end', (int) trim($rawdata['price_quantity_end']));
		$reduser->set('discount_price', trim($rawdata['discount_price']));
		$reduser->set('discount_start_date', trim($rawdata['discount_start_date']));
		$reduser->set('discount_end_date', trim($rawdata['discount_end_date']));
		$reduser->set('price_id', $priceId);

		if ($reduser->store())
		{
			return true;
		}
	}

	public function check_vm()
	{
		$db = JFactory::getDbo();

		// Check Virtual Mart Is Install or Not
		$query_check = "SELECT extension_id FROM #__extensions WHERE `element` = 'com_virtuemart' ";
		$db->setQuery($query_check);
		$check = $db->loadResult();

		if ($check == null)
		{
			JFactory::getApplication()->enqueueMessage(JText::_("COM_REDSHOP_NO_VM"), 'error');

			return false;
		}
		else
		{
			$product_total      = $this->Product_sync();
			$shopper_total      = $this->Shopper_Group_Insert();
			$status_total       = $this->Order_status_insert();
			$customer_total     = $this->customerInformation();
			$orders_total       = $this->Orders_insert();
			$manufacturer_total = $this->Manufacturer_insert();

			JRequest::setVar('product_total', $product_total);
			JRequest::setVar('shopper_total', $shopper_total);
			JRequest::setVar('customer_total', $customer_total);
			JRequest::setVar('orders_total', $orders_total);
			JRequest::setVar('status_total', $status_total);
			JRequest::setVar('manufacturer_total', $manufacturer_total);

			return true;
		}
	}

	public function Product_sync()
	{
		$db = JFactory::getDbo();

		// Insert VM Product into Redshop
		$query = "SELECT vmp.*,vmp.cdate as publish_date,vmp.mdate as update_date,vmp.`product_name`,vmp.`product_tax_id`,rdp.product_number as red_product_number,rdp.product_id as rdp_product_id,rdp.product_full_image AS rdp_product_full_image,vpp.product_price
						FROM (#__vm_product as vmp left join #__redshop_product as rdp  on vmp.product_sku = rdp.product_number)
						left join #__vm_product_price as vpp on vpp.product_id = vmp.product_id GROUP BY vmp.product_id";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		if ($data != null)
		{
			$product_array = array();

			foreach ($data as $product_data)
			{
				$product_name     = addslashes($product_data->product_name);
				$product_s_desc   = $product_data->product_s_desc;
				$product_number   = $product_data->product_sku;
				$product_in_stock = $product_data->product_in_stock;
				$product_desc     = $product_data->product_desc;
				$product_tax_id   = $product_data->product_tax_id;
				$product_data->product_publish == 'Y' ? $published = 1 : $published = 0;
				$product_full_image = $product_data->product_full_image;

				$publish_date           = date('Y-m-d h:i:s', $product_data->publish_date);
				$update_date            = date('Y-m-d h:i:s', $product_data->update_date);
				$product_price          = $product_data->product_price;
				$parent_id              = $product_data->product_parent_id;
				$weight                 = $product_data->product_weight;
				$length                 = $product_data->product_length;
				$height                 = $product_data->product_height;
				$width                  = $product_data->product_width;
				$red_product_id         = $product_data->rdp_product_id;
				$red_product_full_image = $product_data->rdp_product_full_image;

				if ($product_data->red_product_number == null)
				{
					$rows                     = $this->getTable('product_detail');
					$rows->product_id         = 0;
					$rows->product_parent_id  = $parent_id;
					$rows->product_name       = $product_name;
					$rows->product_number     = $product_number;
					$rows->product_s_desc     = mysql_escape_string($product_s_desc);
					$rows->product_desc       = mysql_escape_string($product_desc);
					$rows->product_tax_id     = $product_tax_id;
					$rows->published          = $published;
					$rows->product_full_image = $product_full_image;
					$rows->publish_date       = $publish_date;
					$rows->update_date        = $update_date;
					$rows->weight             = $weight;
					$rows->product_price      = $product_price;
					$rows->product_template   = Redshop::getConfig()->get('PRODUCT_TEMPLATE');
					$rows->product_length     = $length;
					$rows->product_height     = $height;
					$rows->product_width      = $width;

					if (!$rows->store())
					{
						$this->setError($db->getErrorMsg());
					}

					$last_insert = $rows->product_id;

					if ($product_in_stock && Redshop::getConfig()->get('DEFAULT_STOCKROOM') != 0)
					{
						$query = "INSERT IGNORE INTO `#__redshop_product_stockroom_xref` "
							. "(`product_id`, `stockroom_id`, `quantity`) "
							. "VALUES ('" . $last_insert . "', '" . Redshop::getConfig()->get('DEFAULT_STOCKROOM') . "', '" . $product_in_stock . "') ";
						$db->setQuery($query);

						if (!$db->execute())
						{
							$this->setError($db->getErrorMsg());
						}
					}

					if ($product_full_image)
					{
						$rows                 = $this->getTable('media_detail');
						$rows->media_id       = 0;
						$rows->media_name     = $product_full_image;
						$rows->media_section  = 'product';
						$rows->section_id     = $last_insert;
						$rows->media_type     = 'images';
						$rows->media_mimetype = '';
						$rows->published      = 1;

						if (!$rows->store())
						{
							$this->setError($db->getErrorMsg());
						}
					}

					// Copy product images to redshop
					if ($product_full_image != "")
					{
						$src  = JPATH_ROOT . "/components/com_virtuemart/shop_image/product/" . $product_full_image;
						$dest = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product_full_image;

						if (JFile::exists($src))
						{
							JFile::copy($src, $dest);
						}
					}

					// Copy additional Images
					$moreimage = "SELECT * FROM #__vm_product_files WHERE file_product_id = '" . $product_data->product_id . "'";
					$db->setQuery($moreimage);
					$product_more_img = $db->loadObjectList();

					foreach ($product_more_img as $more_img)
					{
						$filename = basename($more_img->file_name);
						$src      = JPATH_ROOT . $more_img->file_name;
						$dest     = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $filename;

						if (JFile::exists($src))
						{
							@copy($src, $dest);
						}

						$rows                       = $this->getTable('media_detail');
						$rows->media_id             = 0;
						$rows->media_name           = $filename;
						$rows->media_section        = 'product';
						$rows->section_id           = $last_insert;
						$rows->media_type           = 'images';
						$rows->media_mimetype       = $more_img->file_mimetype;
						$rows->published            = 1;
						$rows->media_alternate_text = $more_img->file_title;

						if (!$rows->store())
						{
							$this->setError($db->getErrorMsg());
						}
					}

					$product_array[] = array($product_data->product_id => $last_insert);
					$inserted[]      = array($last_insert);
				}
				else
				{
					$last_insert              = $red_product_id;
					$rows                     = $this->getTable('product_detail');
					$rows->product_id         = $red_product_id;
					$rows->product_parent_id  = $parent_id;
					$rows->product_name       = $product_name;
					$rows->product_s_desc     = mysql_escape_string($product_s_desc);
					$rows->product_desc       = mysql_escape_string($product_desc);
					$rows->product_tax_id     = $product_tax_id;
					$rows->published          = $published;
					$rows->product_full_image = $product_full_image;
					$rows->publish_date       = $publish_date;
					$rows->update_date        = $update_date;
					$rows->weight             = $weight;
					$rows->product_price      = $product_price;
					$rows->product_template   = Redshop::getConfig()->get('PRODUCT_TEMPLATE');
					$rows->product_length     = $length;
					$rows->product_height     = $height;
					$rows->product_width      = $width;

					if (!$rows->store())
					{
						$this->setError($db->getErrorMsg());
					}

					if ($product_full_image != $red_product_full_image)
					{
						$query = "UPDATE #__redshop_media "
							. "SET `media_name` =  '" . $product_full_image . "' ,`published`	 = '" . $published . "' "
							. "WHERE `media_section`='product' "
							. "AND `section_id`='" . $red_product_id . "' ";
						$db->setQuery($query);

						if (!$db->execute())
						{
							$this->setError($db->getErrorMsg());
						}

						// Copy product images to redshop
						$src         = JPATH_ROOT . "/components/com_virtuemart/shop_image/product/" . $product_full_image;
						$redimagesrc = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $red_product_full_image;
						$dest        = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product_full_image;

						if (JFile::exists($redimagesrc))
						{
							JFile::delete($redimagesrc);
						}

						if (JFile::exists($src))
						{
							JFile::copy($src, $dest);
						}
					}

					$product_array[] = array($product_data->product_id => $red_product_id);
					$updated[]       = array($red_product_id);
				}

				$vmproarr[]  = $product_data->product_id;
				$redproarr[] = $last_insert;

				// Logic to inserting parent_id
				if ($parent_id != 0)
				{
					$query = "SELECT vmp.product_id,rdp.product_id AS rdp_product_id FROM (#__vm_product AS vmp LEFT JOIN #__redshop_product AS rdp ON vmp.product_sku = rdp.product_number) "
						. "LEFT JOIN #__vm_product_price AS vpp ON vpp.product_id = vmp.product_id "
						. "WHERE vmp.product_id = '" . $parent_id . "' ";

					$db->setQuery($query);
					$redparent_id = $db->loadObject();

					$update = "UPDATE #__redshop_product SET product_parent_id = '" . $redparent_id->rdp_product_id
						. "' WHERE product_id = '" . $last_insert . "' ";

					$db->setQuery($update);

					if (!$db->execute())
					{
						$this->setError($db->getErrorMsg());
					}
				}
			}

			$this->related_product_sync($vmproarr, $redproarr);
			$category_total = $this->Category_sync($product_array);

			JRequest::setVar('category_total', $category_total);

			if (isset($inserted))
			{
				JRequest::setVar('product_inserted', count($inserted));
			}

			if (isset($updated))
			{
				JRequest::setVar('product_updated', count($updated));
			}

			return count($product_array);
		}
	}

	public function Category_sync($product_array)
	{
		$db = JFactory::getDbo();

		$k = 0;

		// Collecting data to insert and check for duplicates
		$query = "SELECT DISTINCT vmc.*,vmc.cdate as category_pdate ,rdc.category_name as rdc_catname,rdc.category_id as rdc_catid,
		rdc.category_full_image as rdc_category_full_image  FROM ( #__vm_category as vmc,#__vm_product_category_xref as vmpcx) "
			. "LEFT JOIN #__redshop_category AS rdc ON rdc.category_name = vmc.category_name ";
		$db->setQuery($query);
		$data      = $db->loadObjectList();
		$vmcatarr  = array();
		$redcatarr = array();

		foreach ($data as $cat_data)
		{
			$category_pdate       = date('Y-m-d h:i:s', $cat_data->category_pdate);
			$category_name        = addslashes($cat_data->category_name);
			$category_description = mysql_escape_string($cat_data->category_description);
			$category_thumb_image = $cat_data->category_thumb_image;

			if ($cat_data->category_full_image != "")
			{
				$category_full_image = $cat_data->category_full_image;
			}
			else
			{
				$category_full_image = $cat_data->category_thumb_image;
			}

			$cat_data->category_publish == 'Y' ? $category_publish = 1 : $category_publish = 0;
			$products_per_row = $cat_data->products_per_row;

			if ($cat_data->rdc_catname == null)
			{
				// Inserting category to redshop
				$rows                       = $this->getTable('category_detail');
				$rows->category_id          = 0;
				$rows->category_name        = $category_name;
				$rows->category_description = $category_description;
				$rows->category_thumb_image = $category_thumb_image;
				$rows->category_full_image  = $category_full_image;
				$rows->published            = $category_publish;
				$rows->category_pdate       = $category_pdate;
				$rows->products_per_page    = $products_per_row;
				$rows->category_template    = Redshop::getConfig()->get('CATEGORY_TEMPLATE');

				if (!$rows->store())
				{
					$this->setError($db->getErrorMsg());
				}

				$k++;

				// Get last inserted category id
				$last_insert = $rows->category_id;
			}
			else
			{
				$last_insert = $cat_data->rdc_catid;
				$rowcat      = $this->getTable('category_detail');
				$rowcat->load($last_insert);
				$rowcat->category_thumb_image = $cat_data->category_thumb_image;

				if ($cat_data->category_full_image != "")
				{
					$rowcat->category_full_image = $cat_data->category_full_image;
				}
				else
				{
					$rowcat->category_full_image = $cat_data->category_thumb_image;
				}

				$rowcat->store();
			}

			// Copy images to redshop
			if ($cat_data->category_full_image != "")
			{
				$src  = JPATH_ROOT . "/components/com_virtuemart/shop_image/category/" . $cat_data->category_full_image;
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . "category/" . $cat_data->category_full_image;

				if (JFile::exists($src))
				{
					JFile::copy($src, $dest);
				}
			}
			else
			{
				if ($cat_data->category_thumb_image != "")
				{
					$src  = JPATH_ROOT . "/components/com_virtuemart/shop_image/category/" . $cat_data->category_thumb_image;
					$dest = REDSHOP_FRONT_IMAGES_RELPATH . "category/" . $cat_data->category_thumb_image;

					if (JFile::exists($src))
					{
						JFile::copy($src, $dest);
					}
				}
			}

			// Insert category Xref
			$vmcatarr[]  = $cat_data->category_id;
			$redcatarr[] = $last_insert;

			// Inserting/updating category product relation
			if ($product_array != null)
			{
				foreach ($product_array as $products)
				{
					foreach ($products as $key => $value)
					{
						$query = "SELECT * from #__vm_product_category_xref "
							. "WHERE category_id = '" . $cat_data->category_id . "' "
							. "AND product_id = '" . $key . "'";
						$db->setQuery($query);
						$data_relation = $db->loadObject();

						$query_delete_rel = "DELETE FROM `#__redshop_product_category_xref` "
							. "WHERE `category_id` = '" . $last_insert . "' "
							. "AND `product_id` = '" . $value . "' ";
						$db->setQuery($query_delete_rel);
						$db->execute();

						if (isset($data_relation->product_id) && $data_relation->product_id == $key)
						{
							$query_data_relation = "INSERT INTO  #__redshop_product_category_xref "
								. "(`category_id`, `product_id`) "
								. "VALUES ('" . $last_insert . "','" . $value . "') ";
							$db->setQuery($query_data_relation);

							if (!$db->execute())
							{
								$this->setError($db->getErrorMsg());
							}
						}
					}
				}
			}
		}

		for ($v = 0, $vn = count($vmcatarr); $v < $vn; $v++)
		{
			$query = "SELECT category_parent_id from #__vm_category_xref "
				. "WHERE category_child_id = '" . $vmcatarr[$v] . "' ";
			$db->setQuery($query);
			$vmparent = $db->loadResult();

			$vmparentkey = array_search($vmparent, $vmcatarr);

			if ($vmparent == 0)
			{
				$redparentvalue = 0;
			}
			else
			{
				$redparentvalue = $redcatarr[$vmparentkey];
			}

			$redchildvalue = $redcatarr[$v];

			$query_check = "SELECT count(*) AS total  FROM `#__redshop_category_xref` "
				. "WHERE `category_parent_id` = '" . $redparentvalue . "' "
				. "AND `category_child_id` = '" . $redchildvalue . "' ";
			$db->setQuery($query_check);
			$rowcheck = $db->loadResult();

			if ($rowcheck == 0)
			{
				// Insert category inter relation
				$query_cat_relation = "INSERT INTO  #__redshop_category_xref   (
									`category_parent_id` ,
									`category_child_id`
								)
								VALUES ('" . $redparentvalue . "','" . $redchildvalue . "') ";
				$db->setQuery($query_cat_relation);
				$db->execute();
			}
		}

		return $k;
	}

	public function Shopper_Group_Insert()
	{
		$db = JFactory::getDbo();

		$query = "SELECT vmsg.shopper_group_id,vmsg.shopper_group_name,vmsg.shopper_group_desc,rdsg.shopper_group_name as rdsp_shopper_group_name FROM `#__vm_shopper_group` as vmsg left join #__redshop_shopper_group as rdsg on  rdsg.shopper_group_name = vmsg.shopper_group_name";
		$db->setQuery($query);
		$data = $db->loadObjectList();
		$k    = 0;

		for ($i = 0; $i <= (count($data) - 1); $i++)
		{
			if ($data[$i]->rdsp_shopper_group_name == null)
			{
				$rows                     = $this->getTable('shopper_group_detail');
				$rows->shopper_group_id   = 0;
				$rows->shopper_group_name = $data[$i]->shopper_group_name;
				$rows->shopper_group_desc = $data[$i]->shopper_group_desc;

				if (!$rows->store())
				{
					$this->setError($db->getErrorMsg());
				}
				else
				{
					$k++;
					$last_insert_shopper = $db->insertid();

					// Update user_info_id for shopper_group_id
					$query = "SELECT * FROM `#__vm_shopper_vendor_xref` WHERE `shopper_group_id` = " . $data[$i]->shopper_group_id;
					$db->setQuery($query);
					$shoppers = $db->loadObjectList();

					for ($s = 0, $countShopper = count($shoppers); $s <= $countShopper; $s++)
					{
						$queryshop = "UPDATE `#__redshop_users_info` "
							. "SET `shopper_group_id` = '" . $last_insert_shopper . "' "
							. "WHERE `user_id`='" . $shoppers[$s]->user_id . "' ";
						$db->setQuery($queryshop);
						$db->execute();
					}
				}
			}
		}

		return $k;
	}

	/*
	 * import customer information From VM
	 */
	public function customerInformation()
	{
		$db = JFactory::getDbo();

		$order_functions = order_functions::getInstance();
		$query           = "SELECT vmui.* , vmsvx.shopper_group_id FROM `#__vm_user_info` AS vmui "
			. "LEFT JOIN #__vm_shopper_vendor_xref AS vmsvx ON vmui.user_id = vmsvx.user_id ";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		$k = 0;

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			if ($data[$i]->address_type == "BT")
			{
				$redshopUser = $order_functions->getBillingAddress($data[$i]->user_id);

				if (count($redshopUser) > 0)
				{
					$redUserId = $redshopUser->users_info_id;
					$row       = $this->getTable('user_detail');
					$row->load($redUserId);
					$row->user_email       = $data[$i]->user_email;
					$row->shopper_group_id = $data[$i]->shopper_group_id;
					$row->firstname        = $data[$i]->first_name;
					$row->lastname         = $data[$i]->last_name;
					$row->company_name     = $data[$i]->company;
					$row->address          = $data[$i]->address_1;
					$row->city             = $data[$i]->city;
					$row->country_code     = $data[$i]->country;
					$row->state_code       = $data[$i]->state;
					$row->zipcode          = $data[$i]->zip;
					$row->phone            = $data[$i]->phone_1;

					if ($row->store())
					{
						$k++;
					}
				}
				else
				{
					$rows = $this->getTable('user_detail');
					$rows->load();
					$rows->user_id          = $data[$i]->user_id;
					$rows->user_email       = $data[$i]->user_email;
					$rows->shopper_group_id = $data[$i]->shopper_group_id;
					$rows->firstname        = $data[$i]->first_name;
					$rows->address_type     = $data[$i]->address_type;
					$rows->lastname         = $data[$i]->last_name;
					$rows->company_name     = $data[$i]->company;
					$rows->address          = $data[$i]->address_1;
					$rows->city             = $data[$i]->city;
					$rows->country_code     = $data[$i]->country;
					$rows->state_code       = $data[$i]->state;
					$rows->zipcode          = $data[$i]->zip;
					$rows->phone            = $data[$i]->phone_1;

					if ($rows->store())
					{
						$k++;
					}
				}
			}
			else
			{
				$rows = $this->getTable('user_detail');
				$rows->load();
				$rows->user_id          = $data[$i]->user_id;
				$rows->user_email       = $data[$i]->user_email;
				$rows->shopper_group_id = $data[$i]->shopper_group_id;
				$rows->firstname        = $data[$i]->first_name;
				$rows->address_type     = $data[$i]->address_type;
				$rows->lastname         = $data[$i]->last_name;
				$rows->company_name     = $data[$i]->company;
				$rows->address          = $data[$i]->address_1;
				$rows->city             = $data[$i]->city;
				$rows->country_code     = $data[$i]->country;
				$rows->state_code       = $data[$i]->state;
				$rows->zipcode          = $data[$i]->zip;
				$rows->phone            = $data[$i]->phone_1;

				if ($rows->store())
				{
					$k++;
				}
			}
		}

		return $k;
	}

	public function Orders_insert()
	{
		$db            = JFactory::getDbo();
		$producthelper = productHelper::getInstance();

		$query = "SELECT rui.users_info_id AS rui_users_info_id, vmo . * , rdo.vm_order_number AS rdo_order_number
				FROM (
				(
				#__vm_orders AS vmo
				LEFT JOIN #__redshop_orders AS rdo ON rdo.vm_order_number = vmo.order_number
				)
				LEFT JOIN `#__redshop_users_info` AS rui ON rui.user_id = vmo.user_id AND rui.address_type ='BT'
				)
				ORDER BY vmo.order_id ASC";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		$k = 0;

		for ($i = 0; $i <= (count($data) - 1); $i++)
		{
			if ($data[$i]->rdo_order_number == null)
			{
				$order_number = $order_functions->generateOrderNumber();

				$reduser = $this->getTable('order_detail');
				$reduser->set('order_id', 0);
				$reduser->set('user_id', $data[$i]->user_id);
				$reduser->set('order_number', $order_number);
				$reduser->set('user_info_id', $data[$i]->rui_users_info_id);
				$reduser->set('order_total', $data[$i]->order_total);
				$reduser->set('order_subtotal', $data[$i]->order_subtotal);
				$reduser->set('order_tax', $data[$i]->order_tax);
				$reduser->set('order_tax_details', $data[$i]->order_tax_details);
				$reduser->set('order_shipping', $data[$i]->order_shipping);
				$reduser->set('order_shipping_tax', $data[$i]->order_shipping_tax);
				$reduser->set('coupon_discount', $data[$i]->coupon_discount);
				$reduser->set('order_discount', $data[$i]->order_discount);
				$reduser->set('order_status', $data[$i]->order_status);
				$reduser->set('order_payment_status', '');
				$reduser->set('cdate', $data[$i]->cdate);
				$reduser->set('mdate', $data[$i]->mdate);
				$reduser->set('ship_method_id', $data[$i]->ship_method_id);
				$reduser->set('customer_note', $data[$i]->customer_note);
				$reduser->set('ip_address', $data[$i]->ip_address);
				$reduser->set('vm_order_number', $data[$i]->order_number);

				if (!$reduser->store())
				{
					$this->setError($db->getErrorMsg());
				}
				else
				{
					$k++;
				}

				$last_insert = $reduser->order_id;

				// Copying VM Order_item Data To Redshop
				$order_item = "SELECT vmoi.*,rdoi.order_id AS rdoi_order_id "
					. ",rdp.product_id AS rdp_product_id "
					. "FROM `#__vm_order_item` AS vmoi "
					. "LEFT JOIN #__redshop_order_item AS rdoi ON rdoi.order_id = '" . $last_insert . "' "
					. "LEFT JOIN #__redshop_product AS rdp ON rdp.product_number = vmoi.order_item_sku "
					. "WHERE vmoi.order_id='" . $data[$i]->order_id . "' ";
				$db->setQuery($order_item);
				$order_item = $db->loadObjectList();

				for ($j = 0; $j <= (count($order_item) - 1); $j++)
				{
					$reduser = $this->getTable('order_item_detail');
					$reduser->set('order_item_id', 0);
					$reduser->set('order_id', $last_insert);
					$reduser->set('user_info_id', $data[$i]->rui_users_info_id);
					$reduser->set('product_id', $order_item[$j]->rdp_product_id);
					$reduser->set('order_item_sku', $order_item[$j]->order_item_sku);
					$reduser->set('order_item_name', $order_item[$j]->order_item_name);
					$reduser->set('product_quantity', $order_item[$j]->product_quantity);
					$reduser->set('product_item_price', $order_item[$j]->product_item_price);
					$reduser->set('product_final_price', $order_item[$j]->product_final_price);
					$reduser->set('order_item_currency', $order_item[$j]->order_item_currency);
					$reduser->set('order_status', $order_item[$j]->order_status);
					$reduser->set('cdate', $order_item[$j]->cdate);
					$reduser->set('mdate', $order_item[$j]->mdate);
					$reduser->set('product_attribute', $order_item[$j]->product_attribute);

					if (!$reduser->store())
					{
						$this->setError($db->getErrorMsg());
					}
				}

				// Starting Copying VM order_payment Data to Redshop
				$order_item = "SELECT vmop.*,rdop.payment_order_id FROM `#__vm_order_payment` AS vmop "
					. "LEFT JOIN #__redshop_order_payment AS rdop ON rdop.order_id = '" . $last_insert . "' "
					. "WHERE vmop.order_id = '" . $data[$i]->order_id . "' ";
				$db->setQuery($order_item);
				$order_payment = $db->loadObjectList();

				for ($l = 0; $l <= (count($order_payment) - 1); $l++)
				{
					if ($order_payment[$l]->payment_order_id == null)
					{
						$reduser = $this->getTable('order_payment');
						$reduser->set('payment_order_id', 0);
						$reduser->set('order_id', $last_insert);
						$reduser->set('payment_method_id', $order_payment[$l]->payment_method_id);
						$reduser->set('order_payment_code', $order_user_info[$m]->order_payment_code);
						$reduser->set('order_payment_number', $order_user_info[$m]->order_payment_number);
						$reduser->set('order_payment_amount', $order_user_info[$m]->order_total);
						$reduser->set('order_payment_expire', $order_user_info[$m]->order_payment_expire);
						$reduser->set('order_payment_name', $order_payment[$l]->order_payment_name);
						$reduser->set('order_payment_trans_id', $order_payment[$l]->order_payment_trans_id);

						if (!$reduser->store())
						{
							$this->setError($db->getErrorMsg());
						}
					}
				}

				// Starting Copying VM order_user_info to Redshop
				$order_item = "SELECT vmoui.*,rdoui.order_id as rdoui_order_id,rdui.users_info_id FROM (`#__vm_order_user_info` AS vmoui LEFT JOIN #__redshop_users_info AS rdui ON rdui.user_id = vmoui.user_id) "
					. "LEFT JOIN #__redshop_order_users_info as rdoui on rdoui.order_id = '" . $last_insert . "' "
					. "WHERE vmoui.order_id='" . $data[$i]->order_id . "' ";
				$db->setQuery($order_item);
				$order_user_info = $db->loadObjectList();

				for ($m = 0; $m <= (count($order_user_info) - 1); $m++)
				{
					if ($order_user_info[$m]->rdoui_order_id == null)
					{
						($order_user_info[$m]->company == null) ? $company = 0 : $company = 1;

						$reduser = $this->getTable('order_user_detail');
						$reduser->set('order_info_id', 0);
						$reduser->set('users_info_id', $order_user_info[$m]->users_info_id);
						$reduser->set('order_id', $last_insert);
						$reduser->set('user_id', $order_user_info[$m]->user_id);
						$reduser->set('firstname', $order_user_info[$m]->first_name);
						$reduser->set('lastname', $order_user_info[$m]->middle_name);
						$reduser->set('address_type', $order_user_info[$m]->address_type);
						$reduser->set('vat_number', '');
						$reduser->set('tax_exempt', '');
						$reduser->set('shopper_group_id', '');
						$reduser->set('country_code', $order_user_info[$m]->country);
						$reduser->set('state_code', $order_user_info[$m]->state);
						$reduser->set('zipcode', $order_user_info[$m]->zip);
						$reduser->set('tax_exempt_approved', '');
						$reduser->set('approved', '');
						$reduser->set('is_company', $company);
						$reduser->set('phone', $order_user_info[$m]->phone_1);
						$reduser->set('address', $order_user_info[$m]->address_1);
						$reduser->set('city', $order_user_info[$m]->city);
						$reduser->set('user_email', $order_user_info[$m]->user_email);
						$reduser->set('company_name', $order_user_info[$m]->company);

						if (!$reduser->store())
						{
							$this->setError($db->getErrorMsg());
						}
					}
				}
			}
		}

		return $k;
	}

	public function Order_status_insert()
	{
		$db = JFactory::getDbo();

		$query = "SELECT vmos.*,rdos.order_status_code as rdcode FROM `#__vm_order_status` AS vmos "
			. "LEFT JOIN #__redshop_order_status AS rdos ON vmos.order_status_code = rdos.order_status_code ";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		$k = 0;

		for ($i = 0; $i <= (count($data) - 1); $i++)
		{
			if ($data[$i]->rdcode == null)
			{
				$reduser = $this->getTable('orderstatus_detail');
				$reduser->set('published', 1);
				$reduser->set('order_status_name', $data[$i]->order_status_name);
				$reduser->set('order_status_code', $data[$i]->order_status_code);
				$reduser->set('order_status_id', 0);

				if (!$reduser->store())
				{
					$this->setError($db->getErrorMsg());
				}
				else
				{
					$k++;
				}
			}
		}

		return $k;
	}

	public function Manufacturer_insert()
	{
		$db = JFactory::getDbo();

		$query = "SELECT vmmf.*,vmpmf.product_id,vmp.product_sku,rdp.product_id as rdp_product_id,rdmf.manufacturer_id as rdmf_manufacturer_id,rdmf.manufacturer_name as rdmf_manufacturer_name  FROM (((`#__vm_manufacturer` as vmmf LEFT JOIN #__vm_product_mf_xref as vmpmf ON vmmf.`manufacturer_id` = vmpmf.manufacturer_id) LEFT JOIN #__vm_product as vmp ON vmpmf.product_id = vmp.product_id) LEFT JOIN #__redshop_product as rdp ON rdp.product_number = vmp.product_sku) "
			. "LEFT JOIN #__redshop_manufacturer AS rdmf ON rdmf.manufacturer_name = vmmf.`mf_name` ";
		$db->setQuery($query);
		$data   = $db->loadObjectList();
		$k      = 0;
		$tmp_id = 0;

		for ($i = 0; $i <= (count($data) - 1); $i++)
		{
			if ($i > 0)
			{
				if ($data[$i - 1]->manufacturer_id == $data[$i]->manufacturer_id)
				{
					$tmp_id = 1;
				}
				else
				{
					$tmp_id = 0;
				}
			}

			$manufacturer_name = $data[$i]->mf_name;
			$manufacturer_desc = $data[$i]->mf_desc;
			$rdp_product_id    = $data[$i]->rdp_product_id;

			if ($data[$i]->rdmf_manufacturer_id == null || $data[$i]->rdmf_manufacturer_name == null)
			{
				if ($tmp_id == 0)
				{
					$reduser = $this->getTable('manufacturer_detail');
					$reduser->set('published', 1);
					$reduser->set('template_id', Redshop::getConfig()->get('MANUFACTURER_TEMPLATE'));
					$reduser->set('manufacturer_desc', $manufacturer_desc);
					$reduser->set('manufacturer_name', $manufacturer_name);
					$reduser->set('manufacturer_id', 0);

					if (!$reduser->store())
					{
						$this->setError($db->getErrorMsg());
					}
					else
					{
						$k++;
						$last_insert_manufacturer = $reduser->manufacturer_id;
					}
				}
			}
			else
			{
				$last_insert_manufacturer = $data[$i]->rdmf_manufacturer_id;
			}

			$query = "UPDATE #__redshop_product "
				. "SET `manufacturer_id` = '" . $last_insert_manufacturer . "' "
				. "WHERE `product_id` = '" . $rdp_product_id . "'";
			$db->setQuery($query);
			$db->execute();
		}

		return $k;
	}

	// 	related product sync
	public function related_product_sync($vmproarr, $redproarr)
	{
		$db = JFactory::getDbo();

		// Vmproduct loop for product inter realtion
		for ($v = 0, $vn = count($vmproarr); $v < $vn; $v++)
		{
			$redparent = $redproarr[$v];
			$query     = "SELECT `related_products` FROM `#__vm_product_relations` WHERE `product_id`= '" . $vmproarr[$v] . "'";
			$db->setQuery($query);
			$vmrel = $db->loadResult();

			if ($vmrel != "")
			{
				$vmrel = explode("|", $vmrel);

				for ($i = 0, $in = count($vmrel); $i < $in; $i++)
				{
					$vmrelpro = $vmrel[$i];

					// Search key of related id
					$vmrelprokey = array_search($vmrelpro, $vmproarr);

					if ($vmrelprokey != 0)
					{
						$redrelvalue = $redproarr[$vmrelprokey];

						$query = "INSERT IGNORE INTO `#__redshop_product_related` (`related_id`, `product_id`) VALUES ('" . $redrelvalue . "', '" . $redparent . "')";
						$db->setQuery($query);
						$db->execute();
					}
				}
			}
		}

		return true;
	}

	public function getProductIdByNumber($product_number)
	{

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName(array('product_id')))
			->from($db->quoteName('#__redshop_product'))
			->where($db->quoteName('product_number') . ' = ' . $db->quote($product_number));

		$db->setQuery($query);

		$product_id = $db->loadResult();

		return $product_id;
	}

	/**
	 * Get Extra Field Names
	 *
	 * @param   array $keyProducts Array key products
	 *
	 * @return  array
	 */
	public function getExtraFieldNames($keyProducts)
	{
		$extraFieldNames = array();

		if (is_array($keyProducts))
		{
			$pattern = '/rs_/';

			foreach ($keyProducts as $key => $value)
			{
				if (preg_match($pattern, $key))
				{
					$extraFieldNames[] = $key;
				}
			}
		}

		return $extraFieldNames;
	}

	/**
	 * Update/insert product extra field data
	 *
	 * @param   string  $fieldname Extra Field Names
	 * @param   array   $rawdata   CSV rawdata
	 * @param   integer $productId Product Id
	 *
	 * @return  void
	 */
	/*public function importProductExtrafieldData($fieldname, $rawdata, $productId)
	{
		$db = JFactory::getDbo();
		$value = $rawdata[$fieldname];
		$query = $db->getQuery(true)
			->select('field_id')
			->from($db->qn('#__redshop_fields'))
			->where('field_name = ' . $db->q($fieldname));

		if ($fieldId = $db->setQuery($query)->loadResult())
		{
			$query->clear()
				->select('data_id')
				->from($db->qn('#__redshop_fields_data'))
				->where('fieldid = ' . $db->q($fieldId))
				->where('itemid = ' . (int) $productId)
				->where('section = 1');

			if ($dataId = $db->setQuery($query)->loadResult())
			{
				$query->clear()
					->update($db->qn('#__redshop_fields_data'))
					->set('data_txt = ' . $db->q($value))
					->where('data_id = ' . $db->q($dataId));
				$db->setQuery($query)->execute();
			}
			else
			{
				if (trim($value) != '')
				{
					$queryObject = new stdClass;
					$queryObject->fieldid = $fieldId;
					$queryObject->data_txt = $value;
					$queryObject->itemid = $productId;
					$queryObject->section = 1;
					$db->insertObject('#__redshop_fields_data', $queryObject);
				}
			}
		}
	}*/

	public function getTimeLeft()
	{
		if (@function_exists('ini_get'))
		{
			$php_max_exec = @ini_get("max_execution_time");
		}
		else
		{
			$php_max_exec = 10;
		}

		if (($php_max_exec == "") || ($php_max_exec == 0))
		{
			$php_max_exec = 10;
		}

		/* Decrease $php_max_exec time by 500 msec we need (approx.) to tear down
		the application, as well as another 500msec added for rounding
		error purposes. Also make sure this is never gonna be less than 0.*/
		$php_max_exec = 20;

		list($usec, $sec) = explode(" ", microtime());
		$micro_time = ((float) $usec + (float) $sec);

		// $start_micro_time = $_SESSION['start_micro_time'];
		$session          = JFactory::getSession();
		$start_micro_time = $session->get('start_micro_time');

		$start_micro_time;

		$running_time = $micro_time - $start_micro_time;
		$retun        = $php_max_exec - $running_time;

		return $retun;
	}
}
