<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');

require_once JPATH_COMPONENT . '/helpers/thumbnail.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

/**
 * Class import data
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @since       11.1
 */

class ImportModelimport extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_countItem = 0;

	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A configuration array including optional elements such as session
	 * session_name, clientId and others. This is not exhaustive.
	 *
	 * @since   11.1
	 */
	public function __construct($config = array())
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	/**
	 * Method get data from model to import
	 *
	 * @return void
	 */
	public function getData()
	{
		ob_clean();
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$post = JRequest::get('post');
		$files = JRequest::get('files');
		$files = $files[$post['task'] . $post['import']];

		if (isset($post['task']) && isset($post['import']))
		{
			if ($files['name'] == "")
			{
				return JText::_('PLEASE_SELECT_FILE');
			}

			$ext = strtolower(JFile::getExt($files['name']));

			if ($ext != 'csv')
			{
				return JText::_('FILE_EXTENSION_WRONG');
			}
		}
		else
		{
			if (!isset($post['import']))
			{
				return JText::_('PLEASE_SELECT_SECTION');
			}
		}

		// Upload csv file
		$src = $files['tmp_name'];
		$dest = JPATH_ROOT . '/components/com_redshop/assets/importcsv/' . $post['import'] . '/' . $files['name'];
		$file_upload = JFile::upload($src, $dest);

		$session->clear('ImportPost');
		$session->clear('Importfile');
		$session->clear('Importfilename');
		$session->set('ImportPost', $post);
		$session->set('Importfile', $files);
		$session->set('Importfilename', $files['name']);

		$app->Redirect('index.php?option=com_redshop&view=import&layout=importlog');

		return;
	}

	/**
	 * Method importdata to begin importdata to database
	 *
	 * @return boolean
	 */
	public function importdata()
	{
		ob_clean();

		// Create db object
		$db = $this->_db;
		$thumb = new thumbnail;
		$obj_img = new thumbnail_images;
		$session = JFactory::getSession();

		/* Get all posted data */
		$new_line = JRequest::getVar('new_line');
		$post = $session->get('ImportPost');

		$files = $session->get('Importfile');
		$file_name = $session->get('Importfilename');

		/* Load the table model */
		switch ($post['import'])
		{
			case 'products':
				$row = $this->getTable('product_detail');
				break;
			case 'categories':
				$row = $this->getTable('category_detail');
				break;
		}

		/**
		 * check is redCRM is installed or not
		 */
		$redhelper = new redhelper;
		$isredcrm = false;

		if ($redhelper->isredCRM())
		{
			$isredcrm = true;
		}

		/* Loop through the CSV file */
		/* First line first as that is the column headers: $this->_countItem = 0 */

		$headers = array();
		$correctlines = 0;
		$handle = fopen(JPATH_ROOT . '/components/com_redshop/assets/importcsv/' . $post['import'] . '/' . $file_name, "r");

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
				if ((int) $this->_countItem == 0)
				{
					foreach ($data as $key => $name)
					{
						/* Set the column headers */
						$headers[$key] = $name;
					}
				}
				else
				{
					if ($this->_countItem > $new_line)
					{
						$rawdata = array();

						foreach ($data as $key => $name)
						{
							// Bind the data
							if ($headers[$key] == 'category_full_image' && $post['import'] == 'categories')
							{
								$image_name = basename($name);
								$rawdata[$headers[$key]] = $image_name;

								if ($image_name != "")
								{
									fopen($name, "r");
									$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $image_name;

									// Copy If file is not already exist
									if (!file_exists($dest))
									{
										copy($name, $dest);
									}
								}
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

						// Import categories
						if ($post['import'] == 'categories')
						{
							$category_id = $rawdata['category_id'];

							// Begin query
							$query = $this->_db->getQuery(true);
							$query->select("COUNT(*)");
							$query->from($db->quoteName($this->_table_prefix . "category"));
							$query->where("category_id = " . $category_id);

							// End query

							$this->_db->setQuery($query);
							$cidCount = $this->_db->loadResult();

							// Updating category
							$row = $this->getTable('category_detail');

							if ($cidCount > 0)
							{
								$row->load($category_id);
							}
							else
							{
								$row->category_id = $category_id;
							}

							$row->category_name = $rawdata['category_name'];
							$row->category_short_description = $rawdata['category_short_description'];
							$row->category_description = $rawdata['category_description'];
							$row->category_template = $rawdata['category_template'];
							$row->category_more_template = $rawdata['category_more_template'];
							$row->products_per_page = $rawdata['products_per_page'];
							$row->category_thumb_image = $rawdata['category_thumb_image'];
							$row->category_full_image = $rawdata['category_full_image'];
							$row->metakey = $rawdata['metakey'];
							$row->metadesc = $rawdata['metadesc'];
							$row->metalanguage_setting = $rawdata['metalanguage_setting'];
							$row->metarobot_info = $rawdata['metarobot_info'];
							$row->pagetitle = $rawdata['pagetitle'];
							$row->pageheading = $rawdata['pageheading'];
							$row->sef_url = $rawdata['sef_url'];
							$row->published = $rawdata['published'];
							$row->category_pdate = $rawdata['category_pdate'];
							$row->ordering = $rawdata['ordering'];

							if ($cidCount > 0)
							{
								// Update
								if (!$row->store())
								{
									return JText::_('COM_REDSHOP_ERROR_DURING_IMPORT');
								}
							}
							else
							{
								// Insert
								$ret = $this->_db->insertObject($this->_table_prefix . 'category', $row, 'category_id');

								if (!$ret)
								{
									return JText::_('COM_REDSHOP_ERROR_DURING_IMPORT');
								}
							}

							// Begin query
							$query = $this->_db->getQuery(true);
							$query->select("COUNT(*)");
							$query->from($db->quoteName($this->_table_prefix . "category_xref"));
							$query->where("category_parent_id = " . $rawdata['category_parent_id']);
							$query->where("category_child_id = " . $row->category_id);

							// End query
							$this->_db->setQuery($query);
							$count = $this->_db->loadResult();

							if ($count == 0)
							{
								// Remove existing
								$query = $this->_db->getQuery(true);

								// Delete all custom keys for category_child_id
								$conditions = array(
										$db->quoteName('category_child_id') . '=' . $row->category_id
								);

								$query->delete($db->quoteName($this->_table_prefix . "category_xref"));
								$query->where($conditions);

								$this->_db->setQuery($query);
								$this->_db->query();

								// Create a new query object to insert to database
								$query = $this->_db->getQuery(true);

								// Insert values.
								$values = array($rawdata['category_parent_id'],$row->category_id);

								// Prepare the insert query.
								$query->insert($db->quoteName($this->_table_prefix . "category_xref"))
								->values(implode(',', $values));

								// Set the query using our newly populated query object and execute it.
								$this->_db->setQuery($query);
								$this->_db->query();
							}

							$correctlines++;
						}

						// Import products
						if ($post['import'] == 'products' && isset($rawdata['product_number']))
						{
							$rawdata['product_price'] = '' . str_replace(',', '.', $rawdata['product_price']) . '';
							$product_id = $this->getProductIdByNumber($rawdata['product_number']);

							if ((int) $product_id > 0)
							{
								$rawdata['product_id'] = (int) $product_id;
							}

							// Product Description is optional - no need to add column in csv everytime.
							if (isset($rawdata['product_desc']) === true)
							{
								$rawdata['product_desc'] = htmlentities($rawdata['product_desc']);
							}

							// Product Short Description is also optional - no need to add column in csv everytime.
							if (isset($rawdata['product_s_desc']) === true)
							{
								$rawdata['product_s_desc'] = htmlentities($rawdata['product_s_desc']);
							}

							if (isset($rawdata['manufacturer_name']))
							{
								$query = $this->_db->getQuery(true);
								$query->select("manufacturer_id");
								$query->from($db->quoteName($this->_table_prefix . "manufacturer"));
								$query->where("manufacturer_name = '" . $rawdata['manufacturer_name'] . "' ");
								$this->_db->setQuery($query);
								$manufacturer_id = $this->_db->loadResult();
								$rawdata['manufacturer_id'] = $manufacturer_id;
							}

							// Updating/inserting product
							$row = $this->getTable('product_detail');
							$row->load($rawdata['product_id']);

							// Do not update with blank imagecategory_id
							if ($rawdata['product_thumb_image'] == "")
							{
								unset($rawdata['product_thumb_image']);
							}

							if ($rawdata['product_full_image'] == "")
							{
								unset($rawdata['product_full_image']);
							}

							if ($rawdata['product_back_full_image'] == "")
							{
								unset($rawdata['product_back_full_image']);
							}

							if ($rawdata['product_preview_back_image'] == "")
							{
								unset($rawdata['product_preview_back_image']);
							}

							$row->bind($rawdata);

							// Set boolean for Error
							$isError = false;

							if ((int) $product_id > 0)
							{
								// Update
								if (!$row->store())
								{
									$isError = true;

									return JText::_('COM_REDSHOP_ERROR_DURING_IMPORT');
								}
							}
							else
							{
								// Insert
								$row->product_id = (int) $rawdata['product_id'];
								$ret = $this->_db->insertObject($this->_table_prefix . 'product', $row, 'product_id');

								if (!$ret)
								{
									$isError = true;

									return JText::_('COM_REDSHOP_ERROR_DURING_IMPORT');
								}
							}

							// Tetst
							if (!$isError)
							{
								// Last inserted product id
								$product_id = $row->product_id;

								// Product Full Image
								$product_full_image = trim($rawdata['product_full_image']);

								if ($product_full_image != "")
								{
									$src = $this->sitepath . "components/com_redshop/assets/images/product/" . $product_full_image;
									fopen($src, "r");
									$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product_full_image;

									// Copy If file is not already exist
									if (!file_exists($dest))
									{
										copy($name, $dest);
									}
								}

								$section_images = $rawdata['images'];
								$image_name = explode("#", $section_images);

								if (is_array($image_name))
								{
									for ($i = 0; $i < count($image_name); $i++)
									{
										if (trim($image_name[$i]) != "")
										{
											$src = $this->sitepath . "components/com_redshop/assets/images/product/" . trim($image_name[$i]);
											fopen($src, "r");
											$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . trim($image_name[$i]);

											// Copy If file is not already exist
											if (!file_exists($dest))
											{
												copy($src, $dest);
											}
										}
									}
								}

								$section_images_order = $rawdata['images_order'];
								$section_images_alternattext = $rawdata['images_alternattext'];

								// Section videos
								$section_video = $rawdata['video'];
								$image_name = explode("#", $section_video);

								if (is_array($image_name))
								{
									for ($i = 0; $i < count($image_name); $i++)
									{
										if (trim($image_name[$i]) != "")
										{
											$src = $this->sitepath . "components/com_redshop/assets/video/product/" . trim($image_name[$i]);
											fopen($src, "r");
											$dest = JPATH_COMPONENT_SITE . '/assets/video/product/' . trim($image_name[$i]);

											// Copy If file is not already exist
											if (!file_exists($dest))
											{
												copy($src, $dest);
											}
										}
									}
								}

								$section_video_order = $rawdata['video_order'];
								$section_video_alternattext = $rawdata['video_alternattext'];

								// Section document
								$section_document = $rawdata['document'];
								$image_name = explode("#", $section_document);

								if (is_array($image_name))
								{
									for ($i = 0; $i < count($image_name); $i++)
									{
										if (trim($image_name[$i]) != "")
										{
											$src = $this->sitepath . "components/com_redshop/assets/document/product/" . trim($image_name[$i]);
											fopen($src, "r");
											$dest = REDSHOP_FRONT_DOCUMENT_RELPATH . 'product/' . trim($image_name[$i]);

											// Copy If file is not already exist
											if (!file_exists($dest))
											{
												copy($src, $dest);
											}
										}
									}
								}

								$section_document_order = $rawdata['document_order'];
								$section_document_alternattext = $rawdata['document_alternattext'];

								// Section Download
								if (isset($rawdata['download']))
								{
									$section_download = $rawdata['download'];
									$image_name = explode("#", $section_download);

									if (is_array($image_name))
									{
										for ($i = 0; $i < count($image_name); $i++)
										{
											if (trim($image_name[$i]) != "")
											{
												$src = $this->sitepath . "components/com_redshop/assets/download/product/" . trim($image_name[$i]);
												fopen($src, "r");
												$dest = JPATH_COMPONENT_SITE . '/assets/download/product/' . trim($image_name[$i]);

												// Copy If file is not already exist
												if (!file_exists($dest))
												{
													copy($src, $dest);
												}
											}
										}
									}
								}

								$section_download_order = $rawdata['download_order'];
								$section_download_alternattext = $rawdata['download_alternattext'];
								$category_id = $rawdata['category_id'];

								// Insert into media
								$query = $this->_db->getQuery(true);
								$query->select("count(*)");
								$query->from($db->quoteName($this->_table_prefix . "media"));
								$query->where("media_name LIKE '" . $product_full_image . "'");
								$query->where("media_section LIKE 'product'");
								$query->where("section_id=" . $product_id);
								$query->where("media_type LIKE 'images' ");
								$query->where("published=1 ");
								$this->_db->setQuery($query);
								$count = $this->_db->loadResult();

								if ($count <= 0)
								{
									$rows = $this->getTable('media_detail');
									$rows->media_id = 0;
									$rows->media_name = $product_full_image;
									$rows->media_section = 'product';
									$rows->section_id = $product_id;
									$rows->media_type = 'images';
									$rows->media_mimetype = '';
									$rows->published = 1;

									if (!$rows->store())
									{
										$this->setError($this->_db->getErrorMsg());
									}
								}

								// Product Extra Field Import
								$newkeys = array();
								array_walk($rawdata, 'checkkeys', $newkeys);

								if (count($newkeys) > 0)
								{
									foreach ($newkeys as $fieldkey)
									{
										$this->importProductExtrafieldData($fieldkey, $rawdata, $product_id);
									}
								}

								$correctlines++;
							}

							// Category product relation insert
							$category_id = '';
							$category_name = '';

							if (isset($rawdata['category_id']))
							{
								$category_id = $rawdata['category_id'];
							}

							if (isset($rawdata['category_name']))
							{
								$category_name = $rawdata['category_name'];
							}

							if ($category_id != "" || $category_name != "")
							{
								$category = false;

								if ($category_id != "")
								{
									$categoryArr = explode("###", $rawdata['category_id']);
								}
								else
								{
									$categoryArr = explode("###", $rawdata['category_name']);
									$category = true;
								}

								// Delete for keys
								$query = $this->_db->getQuery(true);
								$conditions = array(
									"product_id = " . $product_id
								);

								$query->delete($db->quoteName($this->_table_prefix . "product_category_xref"));
								$query->where($conditions);
								$this->_db->setQuery($query);
								$this->_db->query();

								for ($i = 0; $i < count($categoryArr); $i++)
								{
									if ($category)
									{
										$query = $this->_db->getQuery(true);

										$query->select("category_id");
										$query->from($db->quoteName($this->_table_prefix . "category"));
										$query->where("category_name = '" . $categoryArr[$i] . "'");
										$this->_db->setQuery($query);
										$category_id = $this->_db->loadResult();
									}
									else
									{
										$category_id = $categoryArr[$i];
									}

									$query = $this->_db->getQuery(true);

									$query->select("COUNT(*)");
									$query->from($db->quoteName($this->_table_prefix . "product_category_xref"));
									$query->where("category_id = " . $category_id);
									$query->where("product_id = " . $product_id);
									$this->_db->setQuery($query);
									$count = $this->_db->loadResult();

									if ($count <= 0)
									{
										// Create a new query object.
										$query = $this->_db->getQuery(true);

										// Insert columns.
										$columns = array('category_id', 'product_id');

										// Insert values.
										$values = array($category_id, $product_id);

										// Prepare the insert query.
										$query->insert($db->quoteName($this->_table_prefix . "product_category_xref"));
										$query->columns($db->quoteName($columns));
										$query->values(implode(',', $values));
										$this->_db->setQuery($query);
										$this->_db->query();
									}
								}
							}

							// Importing accessory product
							$accessory_products = $rawdata['accessory_products'];

							if ($accessory_products != "")
							{
								$accessory_products = explode("###", $rawdata['accessory_products']);

								for ($i = 0; $i < count($accessory_products); $i++)
								{
									$accids = explode("~", $accessory_products[$i]);
									$accessory_product_sku = $accids[0];
									$accessory_price = $accids[1];
									$query = $this->_db->getQuery(true);

									$query->select("COUNT(*) AS total");
									$query->from($db->quoteName($this->_table_prefix . "product_accessory") . " AS pa ");
									$query->join("LEFT", $this->_table_prefix . "product p ON p.product_id = pa.child_product_id");
									$query->where("pa.product_id= " . $product_id);
									$query->where("p.product_number ='" . $accessory_product_sku . "'");
									$this->_db->setQuery($query);
									$total = $this->_db->loadresult();

									// Query get product_id
									$query = $this->_db->getQuery(true);
									$query->select("product_id");
									$query->from($this->_table_prefix . "product");
									$query->where("product_number=" . $db->quote($accessory_product_sku));
									$this->_db->setQuery($query);
									$child_product_id = $this->_db->loadresult();

									if ($total <= 0)
									{
										// Create a new query object.
										$query = $this->_db->getQuery(true);

										// Insert columns.
										$columns = array(
												"accessory_id",
												"product_id",
												"child_product_id",
												"accessory_price"
										);

										// Insert values.
										$values = array(
												'',
												$product_id,
												$child_product_id,
												$db->quote($accessory_price)
										);

										// Prepare the insert query.
										$query->insert($db->quoteName($this->_table_prefix . "product_category_xref"));
										$query->columns($db->quoteName($columns));
										$query->values(implode(',', $values));
									}
									else
									{
										$db = JFactory::getDbo();

										$query = $db->getQuery(true);

										// Fields to update.
										$fields = array("accessory_price = " . $db->quote($accessory_price));

										// Conditions for which records should be updated.
										$conditions = array("product_id = " . $product_id, "child_product_id =" . $child_product_id);

										$query->update($db->quoteName($db->quoteName($this->_table_prefix . "product_accessory")));
										$query->set($fields);
										$query->where($conditions);
									}

									$this->_db->setQuery($query);
									$this->_db->query();
								}
							}

							$product_stock = $rawdata['product_stock'];
							$query = $this->_db->getQuery(true);
							$query->select("COUNT(*) AS total");
							$query->from($db->quoteName($this->_table_prefix . "product_stockroom_xref"));
							$query->where("product_id =" . $db->quote($product_id));
							$query->where("stockroom_id =" . $db->quote(DEFAULT_STOCKROOM));
							$this->_db->setQuery($query);
							$total = $this->_db->loadresult();

							if ($product_stock && DEFAULT_STOCKROOM != 0)
							{
								if ($total <= 0)
								{
									// Create a new query object.
									$db = $this->_db;
									$query = $this->_db->getQuery(true);

									// Insert columns.
									$columns = array(
											'product_id',
											'stockroom_id',
											'quantity'
									);

									// Insert values.
									$values = array(
											$product_id,
											$db->quote(DEFAULT_STOCKROOM),
											$db->quote($product_stock)
									);

									// Prepare the insert query.
									$query->insert($db->quoteName($this->_table_prefix . "product_category_xref"));
									$query->columns($db->quoteName($columns));
									$query->values(implode(',', $values));
								}
								else
								{
									$query = $this->_db->getQuery(true);

									// Fields to update.
									$fields = array(
											"quantity = " . $db->quote($product_stock)
									);

									// Conditions for which records should be updated.
									$conditions = array(
											"product_id = " . $db->quote($product_id),
											"stockroom_id = " . $db->quote(DEFAULT_STOCKROOM)
									);

									$query->update($db->quoteName($this->_table_prefix . "product_stockroom_xref"));
									$query->set($fields);
									$query->where($conditions);
								}

								$this->_db->setQuery($query);
								$this->_db->query();
							}

							// Import image section
							$section_images = explode("#", $section_images);
							$section_images_order = explode("#", $section_images_order);
							$section_images_alternattext = explode("#", $section_images_alternattext);

							if (is_array($section_images))
							{
								for ($s = 0; $s < count($section_images); $s++)
								{
									if (trim($section_images[$s]) != "")
									{
										$ordering = 0;

										if (isset($section_images_order[$s]))
										{
											$ordering = $section_images_order[$s];
										}

										$media_alternate_text = "";

										if (isset($section_images_alternattext[$s]))
										{
											$media_alternate_text = $section_images_alternattext[$s];
										}

										$query = $this->_db->getQuery(true);
										$query->select("media_id");
										$query->from($db->quoteName($this->_table_prefix . "media"));
										$query->where("media_name  LIKE " . $db->quote($section_images[$s]));
										$query->where("media_section = 'product'");
										$query->where("section_id = " . $db->quote($product_id));
										$query->where("media_type LIKE 'images' ");
										$this->_db->setQuery($query);
										$count = $this->_db->loadResult();

										if ($count <= 0)
										{
											$rows = $this->getTable('media_detail');
											$rows->media_id = 0;
											$rows->media_name = trim($section_images[$s]);
											$rows->media_section = 'product';
											$rows->section_id = $product_id;
											$rows->media_type = 'images';
											$rows->media_mimetype = '';
											$rows->published = 1;
											$rows->media_alternate_text = $media_alternate_text;
											$rows->ordering = $ordering;

											if (!$rows->store())
											{
												$this->setError($this->_db->getErrorMsg());
											}
										}
										else
										{
											$db = $this->_db;
											$query = $this->_db->getQuery(true);

											// Fields to update.
											$fields   = array(
													"media_alternate_text = " . $db->quote($media_alternate_text),
													"ordering = " . $db->quote(ordering)
											);

											// Conditions for which records should be updated.
											$conditions = array(
												"media_id = " . $db->quote($count)
											);

											$query->update($db->quoteName($this->_table_prefix . "product_stockroom_xref"));
											$query->set($fields);
											$query->where($conditions);
											$this->_db->setQuery($query);
											$this->_db->query();
										}
									}
								}
							}

							// Import video section
							$section_video = explode("#", $section_video);
							$section_video_order = explode("#", $section_video_order);
							$section_video_alternattext = explode("#", $section_video_alternattext);

							if (is_array($section_video))
							{
								for ($s = 0; $s < count($section_video); $s++)
								{
									if (trim($section_video[$s]) != "")
									{
										$ordering = 0;

										if (isset($section_video_order[$s]))
										{
											$ordering = $section_video_order[$s];
										}

										$media_alternate_text = "";

										if (isset($section_video_alternattext[$s]))
										{
											$media_alternate_text = $section_video_alternattext[$s];
										}

										$query = $this->_db->getQuery(true);

										$query->select("count(*)");
										$query->from($db->quoteName($this->_table_prefix . "media"));
										$query->where("media_section = 'product'");
										$query->where("section_id = " . $db->quote($product_id));
										$query->where("media_type = 'video'");
										$this->_db->setQuery($query);
										$count = $this->_db->loadResult();

										if ($count <= 0)
										{
											$rows = $this->getTable('media_detail');
											$rows->media_id = 0;
											$rows->media_name = trim($section_video[$s]);
											$rows->media_section = 'product';
											$rows->section_id = $product_id;
											$rows->media_type = 'video';
											$rows->media_mimetype = '';
											$rows->published = 1;
											$rows->media_alternate_text = $media_alternate_text;
											$rows->ordering = $ordering;

											if (!$rows->store())
											{
												$this->setError($this->_db->getErrorMsg());
											}
										}
									}
								}
							}

							// Import document section
							$section_document = explode("#", $section_document);
							$section_document_order = explode("#", $section_document_order);
							$section_document_alternattext = explode("#", $section_document_alternattext);

							if (is_array($section_document))
							{
								for ($s = 0; $s < count($section_document); $s++)
								{
									if (trim($section_document[$s]) != "")
									{
										$ordering = 0;

										if (isset($section_document_order[$s]))
										{
											$ordering = $section_document_order[$s];
										}

										$media_alternate_text = "";

										if (isset($section_document_alternattext[$s]))
										{
											$media_alternate_text = $section_document_alternattext[$s];
										}

										$db    = $this->_db;
										$query = $this->_db->getQuery(true);

										$query->select("count(*)");
										$query->from($db->quoteName($this->_table_prefix . "media"));
										$query->where("media_name LIKE " . $db->quote($section_document[$s]));
										$query->where("media_section = 'product' ");
										$query->where("section_id = " . $db->quote($product_id));
										$query->where("media_type = 'document' ");
										$this->_db->setQuery($query);
										$count = $this->_db->loadResult();

										if ($count <= 0)
										{
											$rows = $this->getTable('media_detail');
											$rows->media_id = 0;
											$rows->media_name = trim($section_download[$s]);
											$rows->media_section = 'product';
											$rows->section_id = $product_id;
											$rows->media_type = 'document';
											$rows->media_mimetype = '';
											$rows->published = 1;
											$rows->media_alternate_text = $media_alternate_text;
											$rows->ordering = $ordering;

											if (!$rows->store())
											{
												$this->setError($this->_db->getErrorMsg());
											}
										}
									}
								}
							}

							// Import download section
							$section_download = explode("#", $section_download);
							$section_download_order = explode("#", $section_download_order);
							$section_download_alternattext = explode("#", $section_download_alternattext);

							if (is_array($section_download))
							{
								for ($s = 0; $s < count($section_download); $s++)
								{
									if (trim($section_download[$s]) != "")
									{
										$ordering = 0;

										if (isset($section_download_order[$s]))
										{
											$ordering = $section_download_order[$s];
										}

										$media_alternate_text = "";

										if (isset($section_download_alternattext[$s]))
										{
											$media_alternate_text = $section_download_alternattext[$s];
										}

										$db    = $this->_db;
										$query = $this->_db->getQuery(true);

										$query->select("count(*)");
										$query->from($db->quoteName($this->_table_prefix . "media"));
										$query->where("media_name LIKE " . $db->quote($section_download[$s]));
										$query->where("media_section = 'product' ");
										$query->where("section_id = " . $db->quote($product_id));
										$query->where("media_type = 'download' ");
										$this->_db->setQuery($query);
										$count = $this->_db->loadResult();

										if ($count <= 0)
										{
											$rows = $this->getTable('media_detail');
											$rows->media_id = 0;
											$rows->media_name = trim($section_download[$s]);
											$rows->media_section = 'product';
											$rows->section_id = $product_id;
											$rows->media_type = 'download';
											$rows->media_mimetype = '';
											$rows->published = 1;
											$rows->media_alternate_text = $media_alternate_text;
											$rows->ordering = $ordering;

											if (!$rows->store())
											{
												$this->setError($this->_db->getErrorMsg());
											}
										}
									}
								}
							}
						}

						// Import Manufacturers
						if ($post['import'] == 'manufacturer')
						{
							$manufacturer_id = $rawdata['manufacturer_id'];
							$product_id = $rawdata['product_id'];
							$prd = explode('|', $product_id);
							$prd_final = implode(',', $prd);

							// Updating manufacturer
							$row = $this->getTable('manufacturer_detail');
							$row->load($manufacturer_id);
							$row->manufacturer_name = $rawdata['manufacturer_name'];
							$row->manufacturer_desc = $rawdata['manufacturer_desc'];
							$row->manufacturer_email = $rawdata['manufacturer_email'];
							$row->product_per_page = $rawdata['product_per_page'];
							$row->template_id = $rawdata['template_id'];
							$row->metakey = $rawdata['metakey'];
							$row->metadesc = $rawdata['metadesc'];
							$row->metalanguage_setting = $rawdata['metalanguage_setting'];
							$row->metarobot_info = $rawdata['metarobot_info'];
							$row->pagetitle = $rawdata['pagetitle'];
							$row->pageheading = $rawdata['pageheading'];
							$row->sef_url = $rawdata['sef_url'];
							$row->published = $rawdata['published'];
							$row->ordering = $rawdata['ordering'];
							$row->manufacturer_url = $rawdata['manufacturer_url'];

							if (!$row->store())
							{
								return JText::_('ERROR_DURING_IMPORT');
							}

							else
							{
								$rows = $this->getTable('manufacturer_detail');
								$rows->manufacturer_id = $manufacturer_id;
								$rows->manufacturer_name = $rawdata['manufacturer_name'];
								$rows->manufacturer_desc = $rawdata['manufacturer_desc'];
								$rows->manufacturer_email = $rawdata['manufacturer_email'];
								$rows->product_per_page = $rawdata['product_per_page'];
								$rows->template_id = $rawdata['template_id'];
								$rows->metakey = $rawdata['metakey'];
								$rows->metadesc = $rawdata['metadesc'];
								$rows->metalanguage_setting = $rawdata['metalanguage_setting'];
								$rows->metarobot_info = $rawdata['metarobot_info'];
								$rows->pagetitle = $rawdata['pagetitle'];
								$rows->pageheading = $rawdata['pageheading'];
								$rows->sef_url = $rawdata['sef_url'];
								$rows->published = $rawdata['published'];
								$rows->ordering = $rawdata['ordering'];
								$rows->manufacturer_url = $rawdata['manufacturer_url'];

								if (!$rows->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}

								$rows->set('manufacturer_id', $manufacturer_id);
								$ret = $this->_db->insertObject($this->_table_prefix . 'manufacturer', $rows, 'manufacturer_id');
							}

							if (count($prd) > 0)
							{
								$db = $this->_db;
								$query = $this->_db->getQuery(true);

								// Fields to update.
								$fields = array(
										"manufacturer_id = " . $manufacturer_id
								);

								// Conditions for which records should be updated.
								$conditions = array(
										"product_id IN(" . $prd_final . ")"
								);

								$query->update($db->quoteName($this->_table_prefix . "product"));
								$query->set($fields);
								$query->where($conditions);
								$this->_db->setQuery($query);
								$this->_db->query();
							}

							$correctlines++;
						}

						// Import attributes
						if ($post['import'] == 'attributes')
						{
							$product_id = $this->getProductIdByNumber($rawdata['product_number']);

							// Insert product attributes
							$attribute_id = "";
							$attribute_name = $rawdata['attribute_name'];
							$attribute_ordering = $rawdata['attribute_ordering'];
							$allow_multiple_selection = $rawdata['allow_multiple_selection'];
							$hide_attribute_price = $rawdata['hide_attribute_price'];
							$attribute_display_type = $rawdata['display_type'];

							$attribute_required = $rawdata['attribute_required'];

							$db = $this->_db;
							$query = $this->_db->getQuery(true);

							$query->select("attribute_id");
							$query->from($db->quoteName($this->_table_prefix . "product_attribute"));
							$query->where("product_id = " . $product_id);
							$query->where("attribute_name = " . $db->quote($attribute_name));
							$this->_db->setQuery($query);
							$attribute_id = $this->_db->loadResult();

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

							$attrow->product_id = $product_id;

							if ($attrow->store())
							{
								$att_insert_id = $attrow->attribute_id;

								// Insert product attributes property
								$property_id = 0;
								$property_name = $rawdata['property_name'];

								if ($property_name != "")
								{
									$property_ordering = $rawdata['property_ordering'];
									$property_price = $rawdata['property_price'];
									$property_number = $rawdata['property_virtual_number'];
									$setdefault_selected = $rawdata['setdefault_selected'];
									$setdisplay_type = $rawdata['setdisplay_type'];
									$setrequire_selected = $rawdata['required_sub_attribute'];
									$oprand = $rawdata['oprand'];
									$property_image = @basename($rawdata['property_image']);
									$property_main_image = @basename($rawdata['property_main_image']);

									$db = $this->_db;
									$query = $this->_db->getQuery(true);

									$query->select("property_id");
									$query->from($db->quoteName($this->_table_prefix . "product_attribute_property"));
									$query->where("attribute_id = " . $db->quote($att_insert_id));
									$query->where("property_name = " . $db->quote($property_name));
									$this->_db->setQuery($query);
									$property_id = $this->_db->loadResult();

									// Get Table Instance
									$proprow = $this->getTable('attribute_property');
									$proprow->load($property_id);
									$proprow->attribute_id = $att_insert_id;
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

										$mainstock = $rawdata['property_stock'];

										if ($mainstock != "")
										{
											$mainstock_split = explode("#", $mainstock);

											for ($r = 0; $r < count($mainstock_split); $r++)
											{
												if ($mainstock_split[$r] != "")
												{
													$mainquaexplode = explode(":", $mainstock_split[$r]);

													if (count($mainquaexplode) == 2)
													{
														$db    = $this->_db;
														$query_mainins_stockroom = $this->_db->getQuery(true);

														$query_mainins_stockroom->select("*");
														$query_mainins_stockroom->from($db->quoteName($this->_table_prefix . "stockroom"));
														$query_mainins_stockroom->where("stockroom_id = " . $db->quote($mainquaexplode[0]));

														$this->_db->setQuery($query_mainins_stockroom);
														$stock_id = $this->_db->loadObjectList();

														if (count($stock_id) > 0)
														{
															$query_mainins = $this->_db->getQuery(true);

															$query_mainins->select("*");
															$tabless = $db->quoteName($this->_table_prefix . "product_attribute_stockroom_xref");
															$query_mainins->from($tabless);
															$query_mainins->where("stockroom_id = " . $db->quote($mainquaexplode[0]));
															$query_mainins->where("section = " . $db->quote("property"));
															$query_mainins->where("section_id = " . $db->quote($prop_insert_id));

															$this->_db->setQuery($query_mainins);
															$product_id = $this->_db->loadObjectList();

															if (count($product_id) > 0)
															{
																$db    = $this->_db;
																$update_row_query = $this->_db->getQuery(true);

																// Fields to update.
																$fields = array(
																		"quantity = " . $db->quote($mainquaexplode[1])
																);

																// Conditions for which records should be updated.
																$conditions = array(
																		"stockroom_id = " . $db->quote($mainquaexplode[0]),
																		"section_id = " . $db->quote($prop_insert_id),
																		"section = 'property' "
																);

																$tabless = $db->quoteName($this->_table_prefix . "product_stockroom_xref");
																$update_row_query->update($tabless);
																$update_row_query->set($fields);
																$update_row_query->where($conditions);

																$this->_db->setQuery($update_row_query);
																$this->_db->query();
															}
															else
															{
																// Create a new query object.
																$insert_row_query = $this->_db->getQuery(true);

																// Insert columns.
																$columns = array(
																		'quantity',
																		'stockroom_id',
																		'section',
																		'section_id'
																);

																// Insert values.
																$values = array(
																		$mainquaexplode[1],
																		$mainquaexplode[0],
																		'property' ,
																		$prop_insert_id
																);

																// Prepare the insert query.
																$tabless = $db->quoteName($this->_table_prefix . "product_attribute_stockroom_xref");
																$insert_row_query->insert($tabless);
																$insert_row_query->columns($db->quoteName($columns));
																$insert_row_query->values(implode(',', $values));
																$this->_db->setQuery($insert_row_query);
																$this->_db->query();
															}
														}
													}
												}
											}
										}

										/**
										 * update property stock placement
										 */
										$rawtemp = $rawdata['property_stock_placement'];
										$rawdatatemp = $rawdata['property_stock_placement'];

										if ($isredcrm && isset($rawtemp) && trim($rawdatatemp) != "")
										{
											$property_save = array();
											$property_save['stockposition'] = $rawdata['property_stock_placement'];
											$property_save['product_id'] = $attrow->product_id;
											$property_save['property_id'] = $prop_insert_id;

											$this->storePropertyStockPosition($property_save);
											unset($property_save);
										}

										if ($property_image != "")
										{
											$property_image_path = $rawdata['property_image'];

											fopen($property_image_path, "r");
											$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $property_image;

											// Copy If file is not already exist
											if (!file_exists($dest))
											{
												copy($property_image_path, $dest);
											}
										}

										if ($property_main_image != "")
										{
											$property_image_path = $rawdata['property_main_image'];

											fopen($property_image_path, "r");
											$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $property_main_image;

											// Copy If file is not already exist
											if (!file_exists($dest))
											{
												copy($property_image_path, $dest);
											}
										}

										// Redshop product attribute subproperty
										$subattribute_color_id = "";
										$subattribute_color_name = $rawdata['subattribute_color_name'];

										if ($subattribute_color_name != "")
										{
											$subattribute_color_ordering = $rawdata['subattribute_color_ordering'];
											$subattribute_setdefault_selected = $rawdata['subattribute_setdefault_selected'];
											$subattribute_color_title = $rawdata['subattribute_color_title'];
											$subattribute_color_number = $rawdata['subattribute_virtual_number'];
											$subattribute_color_price = $rawdata['subattribute_color_price'];
											$oprand = $rawdata['subattribute_color_oprand'];
											$subattribute_color_image = @basename($rawdata['subattribute_color_image']);

											$db = $this->_db;
											$query = $this->_db->getQuery(true);

											$query->select("subattribute_color_id");
											$query->from($db->quoteName($this->_table_prefix . "product_subattribute_color"));
											$query->where("subattribute_id = " . $db->quote($prop_insert_id));
											$query->where("subattribute_color_name = " . $db->quote($subattribute_color_name));
											$this->_db->setQuery($query);
											$subattribute_color_id = $this->_db->loadResult();

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

										// Create a new query object.
										$db    = $this->_db;
										$query = $this->_db->getQuery(true);

										// Insert columns.
										$columns = array(
												"subattribute_color_id" ,
												"subattribute_color_name",
												"subattribute_color_price",
												"oprand" ,
												"subattribute_color_image" ,
												"subattribute_id",
												"ordering",
												"setdefault_selected",
												"subattribute_color_title"
										);

										// Insert values.
										$values = array(
												$db->quote($subattribute_color_id) ,
												$db->quote($subattribute_color_name),
												$db->quote($subattribute_color_price),
												$db->quote($oprand),
												$db->quote($subattribute_color_image),
												$db->quote($prop_insert_id),
												$db->quote($subattribute_color_ordering),
												$db->quote($subattribute_setdefault_selected),
												$db->quote($subattribute_color_title)
										);

										// Prepare the insert query.
										$query->insert($db->quoteName($this->_table_prefix . "product_subattribute_color"));
										$query->columns($db->quoteName($columns));
										$query->values(implode(',', $values));
										$this->_db->setQuery($query);
										$this->_db->query();

											if ($subproprow->store())
											{
												$prop_insert_id_sub = $subproprow->subattribute_color_id;

												$mainstock = $rawdata['subattribute_stock'];

												if ($mainstock != "")
												{
													$mainstock_split = explode("#", $mainstock);

													for ($r = 0; $r < count($mainstock_split); $r++)
													{
														if ($mainstock_split[$r] != "")
														{
															$mainquaexplode = explode(":", $mainstock_split[$r]);

															if (count($mainquaexplode) == 2)
															{
																$db = $this->_db;
																$query_mainins_stockroom = $db->getQuery(true);

																$query_mainins_stockroom->select("*");
																$query_mainins_stockroom->from($db->quoteName($this->_table_prefix . "stockroom"));
																$query_mainins_stockroom->where("stockroom_id = " . $db->quote($mainquaexplode[0]));
																$query_mainins_stockroom->_db->setQuery($query);

																$this->_db->setQuery($query_mainins_stockroom);
																$stock_id = $this->_db->loadObjectList();

																if (count($stock_id) > 0)
																{
																	$db = $this->_db;
																	$query_mainins = $this->_db->getQuery(true);

																	$query_mainins->select("*");
																	$query_mainins->from(
																			$db->quoteName(
																					$this->_table_prefix . "product_attribute_stockroom_xref"
																			)
																	);
																	$query_mainins->where("stockroom_id = " . $db->quote($mainquaexplode[0]));
																	$query_mainins->where("section_id = " . $db->quote($prop_insert_id_sub));
																	$this->_db->setQuery($query_mainins);
																	$product_id = $this->_db->loadObjectList();

																	if (count($product_id) > 0)
																	{
																		$db = $this->_db;
																		$update_row_query = $this->_db->getQuery(true);

																		// Fields to update.
																		$fields = array(
																				"quantity = " . $db->quote($mainquaexplode[1])
																		);

																		// Conditions for which records should be updated.
																		$conditions = array(
																				"stockroom_id = " . $db->quote($mainquaexplode[0]),
																				"section_id = " . $db->quote($prop_insert_id_sub),
																				"section = " . $db->quote("subproperty")
																		);

																		$tabless = $this->_table_prefix . "product_attribute_stockroom_xref";
																		$update_row_query->update($db->quoteName($tabless));
																		$update_row_query->set($fields);
																		$update_row_query->where($conditions);

																		$this->_db->setQuery($update_row_query);
																		$this->_db->query();
																	}
																	else
																	{
																		// Create a new query object.
																		$db = $this->_db;
																		$insert_row_query = $this->_db->getQuery(true);

																		// Insert columns.
																		$columns = array(
																				'quantity',
																				'stockroom_id',
																				'section',
																				'section_id'
																		);

																		// Insert values.
																		$values = array(
																				$db->quote($mainquaexplode[1]),
																				$db->quote($mainquaexplode[0]),
																				$db->quote("subproperty"),
																				$db->quote($prop_insert_id_sub)
																		);

																		// Prepare the insert query.
																		$tabless = $this->_table_prefix . "product_attribute_stockroom_xref";
																		$insert_row_query->insert($db->quoteName($tabless));
																		$insert_row_query->columns($db->quoteName($columns));
																		$insert_row_query->values(implode(',', $values));
																		$this->_db->setQuery($insert_row_query);
																		$this->_db->query();
																	}
																}
															}
														}
													}
												}

												/**
												 * update property stock placement
												 */
												if ($isredcrm && isset($rawdata['subattribute_stock_placement'])
													&& trim($rawdata['subattribute_stock_placement']) != "")
												{
													$subproperty_save = array();
													$subproperty_save['stockposition'] = $rawdata['subattribute_stock_placement'];
													$subproperty_save['product_id'] = $attrow->product_id;
													$subproperty_save['subattribute_color_id'] = $prop_insert_id_sub;

													$this->storePropertyStockPosition($subproperty_save, 'subproperty');
													unset($subproperty_save);
												}

												if ($subattribute_color_image != "")
												{
													$subproperty_image_path = $rawdata['subattribute_color_image'];
													fopen($subproperty_image_path, "r");
													$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $subattribute_color_image;

													// Copy If file is not already exist
													if (!file_exists($dest))
													{
														copy($subproperty_image_path, $dest);
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
							$field_id            = $rawdata['field_id'];
							$field_title         = $rawdata['field_title'];
							$field_name_field    = $rawdata['field_name_field'];
							$field_type          = $rawdata['field_type'];
							$field_desc          = $rawdata['field_desc'];
							$field_class         = $rawdata['field_class'];
							$field_section       = $rawdata['field_section'];
							$field_maxlength     = $rawdata['field_maxlength'];
							$field_cols          = $rawdata['field_cols'];
							$field_rows          = $rawdata['field_rows'];
							$field_size          = $rawdata['field_size'];
							$field_show_in_front = $rawdata['field_show_in_front'];
							$required            = $rawdata['required'];
							$published           = $rawdata['published'];
							$data_id             = $rawdata['data_id'];
							$data_txt            = $rawdata['data_txt'];
							$itemid              = $rawdata['itemid'];
							$section             = $rawdata['section'];
							$data_insert_id      = 0;

							if ($section == 1)
							{
								$itemid = $this->getProductIdByNumber($rawdata['data_number']);
							}

							$value_id         = $rawdata['value_id'];
							$field_value      = $rawdata['field_value'];
							$field_name_value = $rawdata['field_name'];

							// Get field id
							$query = $this->_db->getQuery(true);
							$query->select('field_id');
							$query->from($this->_db->quoteName($this->_table_prefix . 'fields'));
							$query->where($this->_db->quoteName('field_id') . ' = ' . $this->_db->quote($field_id));
							$this->_db->setQuery($query);
							$field_id_dv = $this->_db->loadResult();

							$field_title = $rawdata['field_title'];
							$field_name = $rawdata['field_name_field'];

							// Get Data Id
							$query = $this->_db->getQuery(true);
							$query->select('data_id');
							$query->from($this->_db->quoteName('#__redshop_fields_data'));
							$query->where($this->_db->quoteName('fieldid') . ' = ' . $this->_db->quote($field_id));
							$query->where($this->_db->quoteName('itemid') . ' = ' . $this->_db->quote($itemid));
							$this->_db->setQuery($query);
							$ch_data_id = $this->_db->loadResult();

							// Get Value Id
							$query = $this->_db->getQuery(true);
							$query->select('value_id');
							$query->from($this->_db->quoteName('#__redshop_fields_value'));
							$query->where($this->_db->quoteName('field_id') . ' = ' . $this->_db->quote($field_id));
							$query->where($this->_db->quoteName('value_id') . ' = ' . $this->_db->quote($value_id));
							$this->_db->setQuery($query);
							$ch_value_id = $this->_db->loadResult();

							if ($field_title != "" && $field_id_dv == '')
							{
								// Create a new query object.
								$db = $this->_db;
								$query = $this->_db->getQuery(true);

								// Insert columns.
								$columns = array(
										"field_title" ,
										"field_name" ,
										"field_type",
										"field_desc",
										"field_class",
										"field_section",
										"field_maxlength",
										"field_cols",
										"field_rows",
										"field_size",
										"field_show_in_front",
										"required",
										"published"
								);

								// Insert values.
								$values = array(
										$db->quote($field_title),
										$db->quote($field_name),
										$db->quote($field_type),
										$db->quote($field_desc),
										$db->quote($field_class),
										$db->quote($field_section),
										$db->quote($field_maxlength),
										$db->quote($field_cols),
										$db->quote($field_rows),
										$db->quote($field_size),
										$db->quote($field_show_in_front),
										$db->quote($required),
										$db->quote($published)
								);

								// Prepare the insert query.
								$query->insert($db->quoteName($this->_table_prefix . "fields"));
								$query->columns($db->quoteName($columns));
								$query->values(implode(',', $values));
								$this->_db->setQuery($query);
								$this->_db->query();
								$data_insert_id = $this->_db->insertid();
							}

							if ($data_insert_id == 0)
							{
								$new_field_id = $field_id;
							}
							else
							{
								$new_field_id = $data_insert_id;
							}

							if (!$ch_data_id)
							{
								// Create a new query object.
								$db = $this->_db;
								$query = $this->_db->getQuery(true);

								// Insert columns.
								$columns = array(
										'data_id',
										'fieldid',
										'data_txt',
										'itemid',
										'section'
								);

								// Insert values.
								$values = array(
										'',
										$db->quote($new_field_id),
										$db->quote($data_txt),
										$db->quote($itemid),
										$db->quote($section)
								);

								// Prepare the insert query.
								$query->insert($db->quoteName($this->_table_prefix . "fields_data"));
								$query->columns($db->quoteName($columns));
								$query->values(implode(',', $values));
								$this->_db->setQuery($query);
								$this->_db->query();
							}
							else
							{
								$db = $this->_db;
								$query = $this->_db->getQuery(true);

								// Fields to update.
								$fields = array(
										"fieldid = " . $db->quote($field_id),
										"data_txt = " . $db->quote($data_txt),
										"itemid = " . $db->quote($itemid),
										"section = " . $db->quote($section)
								);

								// Conditions for which records should be updated.
								$conditions = array(
										"data_id = " . $db->quote($ch_data_id)
								);

								$query->update($db->quoteName($this->_table_prefix . "fields_data"));
								$query->set($fields);
								$query->where($conditions);

								$this->_db->setQuery($query);
								$this->_db->query();
							}

							if ($value_id != '')
							{
								if (!$ch_value_id)
								{
									// Create a new query object.
									$db = $this->_db;
									$query = $this->_db->getQuery(true);

									// Insert columns.
									$columns = array(
											"value_id",
											"field_id",
											"field_value",
											"field_name"
									);

									// Insert values.
									$values = array(
											$value_id ,
											$new_field_id,
											$field_value ,
											$field_name_value
									);

									// Prepare the insert query.
									$query->insert($db->quoteName($this->_table_prefix . "product_category_xref"));
									$query->columns($db->quoteName($columns));
									$query->values(implode(',', $values));
									$this->_db->setQuery($query);
									$this->_db->query();
								}
								else
								{
									$db = $this->_db;
									$query = $this->_db->getQuery(true);

									// Fields to update.
									$fields = array(
											"field_value = " . $db->quote($field_value),
											"field_name = " . $db->quote($field_name_value)
									);

									// Conditions for which records should be updated.
									$conditions = array(
											"value_id = " . $db->quote($value_id)
									);

									$query->update($db->quoteName($this->_table_prefix . "fields_value"));
									$query->set($fields);
									$query->where($conditions);

									$this->_db->setQuery($query);
									$this->_db->query();
								}
							}

							$correctlines++;
						}

						// Import fields
						if ($post['import'] == 'fields_data')
						{
							$field_id = $rawdata['field_id'];
							$field_product_number = $rawdata['data_number'];
							$field_data_txt = $rawdata['data_txt'];

							if ($field_product_number && $field_id)
							{
								$product_id = $this->getProductIdByNumber($field_product_number);

								if ($product_id)
								{
									$db = $this->_db;
									$query = $this->_db->getQuery(true);

									$query->select("count(fieldid) as fieldexist");
									$query->from($db->quoteName($this->_table_prefix . "fields_data"));
									$query->where("fieldid = " . $db->quote($field_id));
									$query->where("itemid = " . $db->quote($product_id));
									$query->where("section = " . $db->quote("1"));
									$this->_db->setQuery($query);
									$fieldexist = $this->_db->loadResult();

									if ($fieldexist == 0)
									{
										// Create a new query object.
										$db = $this->_db;
										$query = $this->_db->getQuery(true);

										// Insert columns.
										$columns = array(
												'fieldid',
												'data_txt',
												'itemid',
												'section'
										);

										// Insert values.
										$values = array(
												$db->quote($field_id),
												$db->quote($field_data_txt),
												$db->quote($product_id),
												$db->quote('1')
										);

										// Prepare the insert query.
										$query->insert($db->quoteName($this->_table_prefix . "fields_data"));
										$query->columns($db->quoteName($columns));
										$query->values(implode(',', $values));
										$this->_db->setQuery($query);
										$this->_db->query();
									}
									else
									{
										$db = $this->_db;
										$query = $this->_db->getQuery(true);

										// Fields to update.
										$fields = array(
												"data_txt = " . $db->quote($field_data_txt)
										);

										// Conditions for which records should be updated.
										$conditions = array(
												'fieldid = ' . $db->quote($field_id),
												'itemid = ' . $db->quote($product_id),
												'section = ' . $db->quote("1")
										);

										$query->update($db->quoteName($this->_table_prefix . "fields_data"));
										$query->set($fields);
										$query->where($conditions);

										$this->_db->setQuery($query);
										$this->_db->query();
									}

									$correctlines++;
								}
							}
						}

						// Import Related Products
						if ($post['import'] == 'related_product')
						{
							$relpid = $this->getProductIdByNumber($rawdata['related_sku']);
							$pid = $this->getProductIdByNumber($rawdata['product_sku']);

							// Create a new query object.
							$db = $this->_db;
							$query = $this->_db->getQuery(true);

							// Insert columns.
							$columns = array(
									"related_id",
									"product_id"
							);

							// Insert values.
							$values = array(
									$db->quote($relpid),
									$db->quote($pid)
							);

							// Prepare the insert query.
							$query->insert($db->quoteName($this->_table_prefix . "product_related"));
							$query->columns($db->quoteName($columns));
							$query->values(implode(',', $values));
							$this->_db->setQuery($query);

							if ($this->_db->query())
							{
								$correctlines++;
							}
						}

						// Import users
						if ($post['import'] == 'users')
						{
							$app = JFactory::getApplication();
							$db = $this->_db;
							$query = $this->_db->getQuery(true);

							$query->select("*");
							$query->from($db->quoteName($this->_table_prefix . "shopper_group"));
							$query->where("shopper_group_name = " . $db->quote($rawdata['shopper_group_name']));
							$this->_db->setQuery($query);
							$shopper_group_data = $this->_db->loadObject();

							// Insert shopper group if not available
							if (count($shopper_group_data) <= 0)
							{
								$shopper = $this->getTable('shopper_group_detail');
								$shopper->load();
								$shopper->shopper_group_name = $rawdata['shopper_group_name'];
								$shopper->shopper_group_customer_type = 1;
								$shopper->shopper_group_portal = 0;
								$shopper->store();

								// Get last shopper group id
								$shopper_group_id = $shopper->shopper_group_id;
							}
							else
							{
								// Get shopper group id
								$shopper_group_id = $shopper_group_data->shopper_group_id;
							}

							// Get redshop user info table
							$reduser = $this->getTable('user_detail');

							// Check for user available
							if ($rawdata['id'] > 0)
							{
								$db = $this->_db;
								$query = $this->_db->getQuery(true);

								$query->select("*");
								$query->from($db->quoteName("#__users"));
								$query->where("email = " . $db->quote(trim($rawdata['email'])));
								$this->_db->setQuery($query);
								$joomusers = $this->_db->loadObject();

								if (count($joomusers) == 0)
								{
									$user_id = 0;
								}
								else
								{
									$user_id = $joomusers->id;
								}

								// Initialize some variables
								$db = JFactory::getDbo();
								$me = JFactory::getUser();
								$acl = JFactory::getACL();
								$MailFrom = $app->getCfg('mailfrom');
								$FromName = $app->getCfg('fromname');
								$SiteName = $app->getCfg('sitename');

								// Create a new JUser object
								$user = new JUser($user_id);
								$user->set('username', trim($rawdata['username']));
								$user->set('name', $rawdata['name']);
								$user->set('email', trim($rawdata['email']));
								$user->set('password', $rawdata['password']);
								$user->set('password_clear', $rawdata['password']);
								$user->set('block', $rawdata['block']);
								$user->set('sendEmail', $rawdata['sendEmail']);

								// Set some initial user values
								$user->set('usertype', $rawdata['usertype']);
								$user->set('gid', $rawdata['gid']);
								$date = JFactory::getDate();
								$user->set('registerDate', $date->toMySQL());

								if ($user->save())
								{
									$reduser->set('user_id', $user->id);
									$reduser->set('user_email', trim($rawdata['email']));
									$reduser->set('firstname', $rawdata['firstname']);
									$reduser->set('address_type', 'BT');
									$reduser->set('lastname', $rawdata['lastname']);
									$reduser->set('company_name', $rawdata['company_name']);
									$reduser->set('vat_number', $rawdata['vat_number']);
									$reduser->set('tax_exempt', $rawdata['tax_exempt']);
									$reduser->set('shopper_group_id', $shopper_group_id);
									$reduser->set('is_company', $rawdata['is_company']);
									$reduser->set('address', $rawdata['address']);
									$reduser->set('city', $rawdata['city']);
									$reduser->set('country_code', $rawdata['country_code']);
									$reduser->set('state_code', $rawdata['state_code']);
									$reduser->set('zipcode', $rawdata['zipcode']);
									$reduser->set('phone', $rawdata['phone']);
									$reduser->set('tax_exempt_approved', $rawdata['tax_exempt_approved']);
									$reduser->set('approved', $rawdata['approved']);

									if (count($joomusers) == 0)
									{
										$reduser->set('users_info_id', $rawdata['users_info_id']);
										$ret = $this->_db->insertObject($this->_table_prefix . 'users_info', $reduser, 'users_info_id');
									}
									else
									{
										$user_id = $joomusers->id;
										$db = $this->_db;
										$query = $this->_db->getQuery(true);

										$query->select("*");
										$query->from($db->quoteName($this->_table_prefix . "users_info"));
										$query->where("user_id = " . $db->quote($user_id));
										$this->_db->setQuery($query);
										$redusers = $this->_db->loadObject();

										if (count($redusers) > 0)
										{
											$reduser->set('users_info_id', $redusers->users_info_id);
											$ret = $this->_db->updateObject($this->_table_prefix . 'users_info', $reduser, 'users_info_id');
										}
										else
										{
											$reduser->set('users_info_id', $rawdata['users_info_id']);
											$ret = $this->_db->insertObject($this->_table_prefix . 'users_info', $reduser, 'users_info_id');
										}
									}

									if ($ret)
									{
										$correctlines++;
									}
								}
							}
							else
							{
								$db = $this->_db;
								$query = $this->_db->getQuery(true);

								$query->select("*");
								$query->from($db->quoteName($this->_table_prefix . "users_info"));
								$query->where("user_email = " . $db->quote($rawdata['email']));
								$this->_db->setQuery($query);

								$redusers = $this->_db->loadObject();
								$reduser->set('user_id', $rawdata['id']);
								$reduser->set('user_email', trim($rawdata['email']));
								$reduser->set('firstname', $rawdata['firstname']);
								$reduser->set('address_type', 'BT');
								$reduser->set('lastname', $rawdata['lastname']);
								$reduser->set('company_name', $rawdata['company_name']);
								$reduser->set('vat_number', $rawdata['vat_number']);
								$reduser->set('tax_exempt', $rawdata['tax_exempt']);
								$reduser->set('shopper_group_id', $shopper_group_id);
								$reduser->set('is_company', $rawdata['is_company']);
								$reduser->set('address', $rawdata['address']);
								$reduser->set('city', $rawdata['city']);
								$reduser->set('country_code', $rawdata['country_code']);
								$reduser->set('state_code', $rawdata['state_code']);
								$reduser->set('zipcode', $rawdata['zipcode']);
								$reduser->set('phone', $rawdata['phone']);
								$reduser->set('tax_exempt_approved', $rawdata['tax_exempt_approved']);
								$reduser->set('approved', $rawdata['approved']);

								if (count($redusers) > 0)
								{
									$reduser->set('users_info_id', $redusers->users_info_id);
									$ret = $this->_db->updateObject($this->_table_prefix . 'users_info', $reduser, 'users_info_id');
								}
								else
								{
									$reduser->set('users_info_id', $rawdata['users_info_id']);
									$ret = $this->_db->insertObject($this->_table_prefix . 'users_info', $reduser, 'users_info_id');
								}

								if ($ret)
								{
									$correctlines++;
								}
							}
						}

						// Shipping Address Import
						if ($post['import'] == 'shipping_address')
						{
							if (trim($rawdata['username']) != "")
							{
								$db  = $this->_db;
								$query = $this->_db->getQuery(true);

								$query->select("id");
								$query->from($db->quoteName("#__users"));
								$query->where("username = " . $db->quote(trim($rawdata['username'])));
								$this->_db->setQuery($query);

								$joom_user_id = $this->_db->loadResult();

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
									$ret = $this->_db->insertObject($this->_table_prefix . 'users_info', $reduser, 'users_info_id');

									if ($ret)
									{
										$correctlines++;
									}
								}
							}
						}

						// Shopper group Import
						if ($post['import'] == 'shopper_group_price')
						{
							$ret = $this->importShopperGroupPrice($rawdata);

							if ($ret)
							{
								$correctlines++;
							}
						}

						// Import stockroom data
						if ($post['import'] == 'product_stockroom_data')
						{
							$product_number = $rawdata['Product_SKU'];
							$product_stock = $rawdata['stock'];
							$preorder_stock = 0;
							$ordered_preorder = 0;

							$stockroom_id = $rawdata['stockroom_id'];

							if ($product_number)
							{
								$product_id = $this->getProductIdByNumber($product_number);

								if ($product_id)
								{
									echo $q = "SELECT product_id FROM `" . $this->_table_prefix . "product_stockroom_xref` where product_id ='"
										. $product_id . "' and stockroom_id ='" . $stockroom_id . "'";
									$db = $this->_db;
									$query = $this->_db->getQuery(true);

									$query->select("product_id");
									$query->from($db->quoteName($this->_table_prefix . "product_stockroom_xref"));
									$query->where("product_id = " . $db->quote($product_id));
									$query->where("stockroom_id = " . $db->quote($stockroom_id));

									$this->_db->setQuery($query);
									$stock_exists = $this->_db->loadResult();

									if ($stock_exists == 0)
									{
										// Create a new query object.
										$db = $this->_db;
										$query = $this->_db->getQuery(true);

										// Insert columns.
										$columns = array(
												'product_id',
												'stockroom_id',
												'quantity',
												'preorder_stock',
												'ordered_preorder'
										);

										// Insert values.
										$values = array(
											$db->quote($product_id),
											$db->quote($stockroom_id) ,
											$db->quote($product_stock) ,
											$db->quote($preorder_stock),
											$db->quote($ordered_preorder)
										);

										// Prepare the insert query.
										$query->insert($db->quoteName($this->_table_prefix . "product_stockroom_xref"));
										$query->columns($db->quoteName($columns));
										$query->values(implode(',', $values));
										$this->_db->setQuery($query);

										if (!$this->_db->query())
										{
											$this->setError($this->_db->getErrorMsg());

											return false;
										}
									}
									else
									{
										$db = $this->_db;
										$query = $this->_db->getQuery(true);

										// Fields to update.
										$fields = array(
												"quantity = " . $db->quote($product_stock)
										);

										// Conditions for which records should be updated.
										$conditions = array(
												"product_id = " . $db->quote($product_id),
												"stockroom_id = " . $db->quote($stockroom_id)
										);

										$query->update($db->quoteName($this->_table_prefix . "product_stockroom_xref"));
										$query->set($fields);
										$query->where($conditions);

										$this->_db->setQuery($query);
										$this->_db->query();
									}

									$correctlines++;
								}
							}
						}

						// Import Economic group Products
						if ($post['import'] == 'economic_group_product')
						{
							$product_number = $rawdata['product_number'];
							$product_group = $rawdata['product_group'];

							if ($product_group == "")
							{
								$product_group = 1;
							}

							if ($product_number)
							{
								$product_id = $this->getProductIdByNumber($product_number);

								if ($product_id)
								{
									$query = $this->_db->getQuery(true);

									// Fields to update.
									$fields = array(
											"accountgroup_id = " . $db->quote($product_group)
									);

									// Conditions for which records should be updated.
									$conditions = array(
											"product_id = " . $db->quote($product_id)
									);

									$query->update($db->quoteName($this->_table_prefix . "product"));
									$query->set($fields);
									$query->where($conditions);

									$this->_db->setQuery($query);
									$this->_db->query();

									$correctlines++;
								}
							}
						}
					}
				}

				$this->_countItem = (int) ($this->_countItem + 1);
			}
			else
			{
				$text  = ($this->_countItem - 1);
				$text .= "`_`";
				ob_clean();
				echo  $text;
				exit;
			}
		}

		fclose($handle);
		$text  = "`_`";
		$text .= ($this->_countItem - 1);
		$text .= "`_`";
		ob_clean();
		echo $text;
		exit;
	}

	/**
	 * Method importShopperGroupPrice to help import data into group price
	 *
	 * @param   unknown  $rawdata  The rawdata must an Array data
	 *
	 * @return boolean
	 */

	public function importShopperGroupPrice($rawdata)
	{
		if (trim($rawdata['product_number']) != "")
		{
			$db = $this->_db;
			$query = $this->_db->getQuery(true);

			$query->select(" shopper_group_id");
			$query->from($db->quoteName($this->_table_prefix . "shopper_group"));
			$query->where("shopper_group_id = " . $db->quote($rawdata['shopper_group_id']));
			$this->_db->setQuery($query);
			$shopper_group_id = $this->_db->loadResult();

			if (!$shopper_group_id)
			{
				return false;
			}

			if ($rawdata['section'] == "property" || $rawdata['section'] == "subproperty")
			{
				if ($rawdata['section'] == "property")
				{
					$db = $this->_db;
					$query = $this->_db->getQuery(true);

					$query->select("property_id");
					$query->from($db->quoteName($this->_table_prefix . "product_attribute_property"));
					$query->where("property_number = " . $db->quote($rawdata['product_number']));
					$this->_db->setQuery($query);
					$section_id = $this->_db->loadResult();
				}
				else
				{
					$db = $this->_db;
					$query = $this->_db->getQuery(true);
					$query->select("subattribute_color_id");
					$query->from($db->quoteName($this->_table_prefix . "product_subattribute_color"));
					$query->where("ubattribute_color_number = " . $db->quote($rawdata['product_number']));
					$this->_db->setQuery($query);
					$section_id = $this->_db->loadResult();
				}

				if (!$section_id)
				{
					return false;
				}

				$query = $this->_db->getQuery(true);
				$query->select("price_id");
				$query->from($db->quoteName($this->_table_prefix . "product_attribute_price"));
				$query->where("section_id = " . $db->quote($section_id));
				$query->where("section = " . $db->quote($rawdata['section']));
				$query->where("shopper_group_id = " . $db->quote($rawdata['shopper_group_id']));
				$query->where("price_quantity_start = " . $db->quote($rawdata["price_quantity_start"]));
				$query->where("price_quantity_end = " . $db->quote($rawdata['price_quantity_end']));
				$this->_db->setQuery($query);
				$price_id = $this->_db->loadResult();

				$reduser = $this->getTable('attributeprices_detail');
				$reduser->set('section_id', $section_id);
				$reduser->set('section', trim($rawdata['section']));
			}
			else
			{
				$section_id = $this->getProductIdByNumber($rawdata['product_number']);

				if (!$section_id)
				{
					return false;
				}

				$db = $this->_db;
				$query = $this->_db->getQuery(true);

				$query->select("price_id");
				$query->from($db->quoteName($this->_table_prefix . "product_price"));
				$query->where("product_id = " . $db->quote($section_id));
				$query->where("shopper_group_id = " . $db->quote($rawdata['shopper_group_id']));
				$query->where("price_quantity_start = " . $db->quote($rawdata['price_quantity_start']));
				$query->where("price_quantity_end = " . $db->quote($rawdata['price_quantity_end']));
				$this->_db->setQuery($query);
				$this->_db->setQuery($q);
				$price_id = $this->_db->loadResult();

				$reduser = $this->getTable('prices_detail');
				$reduser->set('product_id', $section_id);
			}

			if (!$price_id)
			{
				$price_id = 0;
			}

			$reduser->set('product_price', trim($rawdata['product_price']));
			$reduser->set('product_currency', CURRENCY_CODE);
			$reduser->set('cdate', time());
			$reduser->set('shopper_group_id', trim($rawdata['shopper_group_id']));
			$reduser->set('price_quantity_start', $rawdata['price_quantity_start']);
			$reduser->set('price_quantity_end', $rawdata['price_quantity_end']);
			$reduser->set('discount_price', $rawdata['discount_price']);
			$reduser->set('discount_start_date', $rawdata['discount_start_date']);
			$reduser->set('discount_end_date', $rawdata['discount_end_date']);
			$reduser->set('price_id', $price_id);

			if ($reduser->store())
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Method check_vm to check if exist virtuemart extension
	 *
	 * @return boolean
	 */
	public function Check_vm()
	{
			// Check Virtual Mart Is Install or Not
			$db = $this->_db;
			$query = $this->_db->getQuery(true);

			$query->select("extension_id");
			$query->from($db->quoteName("#__extensions"));
			$query->where("element = " . $db->quote('com_virtuemart'));
			$this->_db->setQuery($query);
			$check = $this->_db->loadResult();

			if ($check == null)
			{
				JError::raiseWarning(403, "NO_VM");

				return false;
			}
			else
			{
				$product_total = $this->Product_sync();
				$shopper_total = $this->Shopper_Group_Insert();
				$status_total = $this->Order_status_insert();
				$customer_total = $this->customerInformation();
				$orders_total = $this->Orders_insert();
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

	/**
	 * Method Product_sync to sync product of virtuemart
	 *
	 * @return number
	 */
	public function Product_sync()
	{
			// Insert VM Product into Redshop
			$db = $this->_db;
			$query = $this->_db->getQuery(true);

			$query->select("
					vmp.*,
					vmp.cdate as publish_date,
					vmp.mdate as update_date,
					vmp.`product_name`,
					vmp.`product_tax_id`,
					rdp.product_number as red_product_number,
					rdp.product_id as rdp_product_id,
					rdp.product_full_image AS rdp_product_full_image,
					vpp.product_price
			");
			$query->from($db->quoteName("#__vm_product as vmp"));
			$query->join("LEFT", $this->_table_prefix . "product as rdp  on vmp.product_sku = rdp.product_number");
			$query->join("LEFT", "#__vm_product_price as vpp on vpp.product_id = vmp.product_id");
			$query->group("vmp.product_id");

			$this->_db->setQuery($query);
			$data = $this->_db->loadObjectList();

			if ($data != null)
			{
				$product_array = array();

				foreach ($data as $product_data)
				{
					$product_id = '';
					$product_name = addslashes($product_data->product_name);
					$product_s_desc = $product_data->product_s_desc;
					$product_number = $product_data->product_sku;
					$product_in_stock = $product_data->product_in_stock;
					$product_desc = $product_data->product_desc;
					$product_tax_id = $product_data->product_tax_id;
					$product_data->product_publish == 'Y' ? $published = 1 : $published = 0;
					$product_full_image = $product_data->product_full_image;

					$publish_date = date('Y-m-d h:i:s', $product_data->publish_date);
					$update_date = date('Y-m-d h:i:s', $product_data->update_date);
					$product_price = $product_data->product_price;
					$parent_id = $product_data->product_parent_id;
					$weight = $product_data->product_weight;
					$length = $product_data->product_length;
					$height = $product_data->product_height;
					$width = $product_data->product_width;
					$product_unit = $product_data->product_unit;
					$red_product_id = $product_data->rdp_product_id;
					$red_product_full_image = $product_data->rdp_product_full_image;

					if ($product_data->red_product_number == null)
					{
						$rows = $this->getTable('product_detail');
						$rows->product_id = 0;
						$rows->product_parent_id = $parent_id;
						$rows->product_name = $product_name;
						$rows->product_number = $product_number;
						$rows->product_s_desc = mysql_escape_string($product_s_desc);
						$rows->product_desc = mysql_escape_string($product_desc);
						$rows->product_tax_id = $product_tax_id;
						$rows->published = $published;
						$rows->product_full_image = $product_full_image;
						$rows->publish_date = $publish_date;
						$rows->update_date = $update_date;
						$rows->weight = $weight;
						$rows->product_price = $product_price;
						$rows->product_template = PRODUCT_TEMPLATE;
						$rows->product_length = $length;
						$rows->product_height = $height;
						$rows->product_width = $width;

						if (!$rows->store())
						{
							$this->setError($this->_db->getErrorMsg());
						}

						$last_insert = $rows->product_id;

						if ($product_in_stock && DEFAULT_STOCKROOM != 0)
						{
							// Create a new query object.
							$db = $this->_db;
							$query = $this->_db->getQuery(true);

							// Insert columns.
							$columns = array(
									"product_id",
									"stockroom_id",
									"quantity"
							);

							// Insert values.
							$values = array(
									$db->quote($last_insert),
									$db->quote(DEFAULT_STOCKROOM),
									$db->quote($product_in_stock)
							);

							// Prepare the insert query.
							$query->insert($db->quoteName($this->_table_prefix . "product_category_xref"));
							$query->columns($db->quoteName($columns));
							$query->values(implode(',', $values));
							$this->_db->setQuery($query);

							if (!$this->_db->query())
							{
								$this->setError($this->_db->getErrorMsg());
							}
						}

						if ($product_full_image)
						{
							$rows = $this->getTable('media_detail');
							$rows->media_id = 0;
							$rows->media_name = $product_full_image;
							$rows->media_section = 'product';
							$rows->section_id = $last_insert;
							$rows->media_type = 'images';
							$rows->media_mimetype = '';
							$rows->published = 1;

							if (!$rows->store())
							{
								$this->setError($this->_db->getErrorMsg());
							}
						}

						// Copy product images to redshop
						if ($product_full_image != "")
						{
							$src = JPATH_ROOT . "/components/com_virtuemart/shop_image/product/" . $product_full_image;
							$dest = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product_full_image;

							if (is_file($src))
							{
								copy($src, $dest);
							}
						}

						// Copy additional Images
						$moreimage = "SELECT * FROM #__vm_product_files WHERE file_product_id = '" . $product_data->product_id . "'";
						$this->_db->setQuery($moreimage);
						$product_more_img = $this->_db->loadObjectList();

						foreach ($product_more_img as $more_img)
						{
							$filename = basename($more_img->file_name);
							$src = JPATH_ROOT . $more_img->file_name;
							$dest = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $filename;

							if (is_file($src) && file_exists($src))
							{
								copy($src, $dest);
							}

							$rows = $this->getTable('media_detail');
							$rows->media_id = 0;
							$rows->media_name = $filename;
							$rows->media_section = 'product';
							$rows->section_id = $last_insert;
							$rows->media_type = 'images';
							$rows->media_mimetype = $more_img->file_mimetype;
							$rows->published = 1;
							$rows->media_alternate_text = $more_img->file_title;

							if (!$rows->store())
							{
								$this->setError($this->_db->getErrorMsg());
							}
						}

						$product_array[] = array(
								$product_data->product_id => $last_insert
						);
						$inserted[] = array($last_insert);
					}
					else
					{
						$last_insert = $red_product_id;
						$rows = $this->getTable('product_detail');
						$rows->product_id = $red_product_id;
						$rows->product_parent_id = $parent_id;
						$rows->product_name = $product_name;
						$rows->product_s_desc = mysql_escape_string($product_s_desc);
						$rows->product_desc = mysql_escape_string($product_desc);
						$rows->product_tax_id = $product_tax_id;
						$rows->published = $published;
						$rows->product_full_image = $product_full_image;
						$rows->publish_date = $publish_date;
						$rows->update_date = $update_date;
						$rows->weight = $weight;
						$rows->product_price = $product_price;
						$rows->product_template = PRODUCT_TEMPLATE;
						$rows->product_length = $length;
						$rows->product_height = $height;
						$rows->product_width = $width;

						if (!$rows->store())
						{
							$this->setError($this->_db->getErrorMsg());
						}

						if ($product_full_image != $red_product_full_image)
						{
							$db = $this->_db;
							$query = $this->_db->getQuery(true);

							// Fields to update.
							$fields = array(
									"media_name =  " . $db->quote($product_full_image),
									"published	= " . $db->quote($published)
							);

							// Conditions for which records should be updated.
							$conditions = array(
									"media_section = " . $db->quote("product"),
									"section_id = " . $db->quote($red_product_id)
							);

							$query->update($db->quoteName($this->_table_prefix . "media"));
							$query->set($fields);
							$query->where($conditions);
							$this->_db->setQuery($query);

							if (!$this->_db->query())
							{
								$this->setError($this->_db->getErrorMsg());
							}

							// Copy product images to redshop
							$src         = JPATH_ROOT . "/components/com_virtuemart/shop_image/product/" . $product_full_image;
							$redimagesrc = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $red_product_full_image;
							$dest        = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product_full_image;

							if (is_file($redimagesrc))
							{
								@unlink($redimagesrc);
							}

							if (is_file($src))
							{
								copy($src, $dest);
							}
						}

						$product_array[] = array(
								$product_data->product_id => $red_product_id
						);
						$updated[]       = array($red_product_id);
					}

					$vmproarr[]  = $product_data->product_id;
					$redproarr[] = $last_insert;

					// Logic to inserting parent_id
					if ($parent_id != 0)
					{
						$db = $this->_db;
						$query = $this->_db->getQuery(true);

						$query->select("vmp.product_id,rdp.product_id AS rdp_product_id");
						$query->from("#__vm_product AS vmp");
						$query->join("LEFT", $this->_table_prefix . "product AS rdp ON vmp.product_sku = rdp.product_number");
						$query->where("vmp.product_id = " . $db->quote($parent_id));
						$this->_db->setQuery($query);
						$redparent_id = $this->_db->loadObject();

						$query = $this->_db->getQuery(true);

						// Fields to update.
						$fields = array(
								"product_parent_id = " . $db->quote($redparent_id->rdp_product_id)
						);

						// Conditions for which records should be updated.
						$conditions = array(
							"product_id = " . $db->quote($last_insert)
						);

						$query->update($db->quoteName($this->_table_prefix . "product"));
						$query->set($fields);
						$query->where($conditions);

						$this->_db->setQuery($update);

						if (!$this->_db->query())
						{
							$this->setError($this->_db->getErrorMsg());
						}
					}
				}

				$related_product = $this->related_product_sync($vmproarr, $redproarr);
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

	/**
	 * Method Category_sync to sync product to virtuemart
	 *
	 * @param   unknown  $product_array  Array data
	 *
	 * @return number
	 */
	public function Category_sync($product_array)
	{
			$k = 0;

			// Collecting data to insert and check for duplicates
			$query = $this->_db->getQuery(true);

			$query->select("
					DISTINCT vmc.*,
					vmc.cdate as category_pdate ,
					rdc.category_name as rdc_catname,
					rdc.category_id as rdc_catid,
					rdc.category_full_image as rdc_category_full_image
			");
			$query->from("#__vm_category as vmc, #__vm_product_category_xref as vmpcx");
			$query->join("LEFT", $this->_table_prefix . "category AS rdc ON rdc.category_name = vmc.category_name ");

			$this->_db->setQuery($query);
			$data = $this->_db->loadObjectList();
			$vmcatarr = array();
			$redcatarr = array();

			foreach ($data as $cat_data)
			{
				$category_pdate = date('Y-m-d h:i:s', $cat_data->category_pdate);
				$category_name = addslashes($cat_data->category_name);
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
					$rows = $this->getTable('category_detail');
					$rows->category_id = 0;
					$rows->category_name = $category_name;
					$rows->category_description = $category_description;
					$rows->category_thumb_image = $category_thumb_image;
					$rows->category_full_image = $category_full_image;
					$rows->published = $category_publish;
					$rows->category_pdate = $category_pdate;
					$rows->products_per_page = $products_per_row;
					$rows->category_template = CATEGORY_TEMPLATE;

					if (!$rows->store())
					{
						$this->setError($this->_db->getErrorMsg());
					}

					$k++;

					// Get last inserted category id
					$last_insert = $rows->category_id;
				}
				else
				{
					$last_insert = $cat_data->rdc_catid;
					$rowcat = $this->getTable('category_detail');
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
					$src = JPATH_ROOT . "/components/com_virtuemart/shop_image/category/" . $cat_data->category_full_image;
					$dest = REDSHOP_FRONT_IMAGES_RELPATH . "category/" . $cat_data->category_full_image;

					if (is_file($src))
					{
						copy($src, $dest);
					}
				}
				else
				{
					if ($cat_data->category_thumb_image != "")
					{
						$src = JPATH_ROOT . "/components/com_virtuemart/shop_image/category/" . $cat_data->category_thumb_image;
						$dest = REDSHOP_FRONT_IMAGES_RELPATH . "category/" . $cat_data->category_thumb_image;

						if (is_file($src))
						{
							copy($src, $dest);
						}
					}
				}

				// Insert category Xref
				$vmcatarr[] = $cat_data->category_id;
				$redcatarr[] = $last_insert;

				// Inserting/updating category product relation
				if ($product_array != null)
				{
					foreach ($product_array as $products)
					{
						foreach ($products as $key => $value)
						{
							$db = $this->_db;
							$query = $this->_db->getQuery(true);

							$query->select("*");
							$query->from("#__vm_product_category_xref");
							$query->where("category_id = " . $db->quote($cat_data->category_id));
							$query->where("product_id = " . $db->quote($key));
							$this->_db->setQuery($query);
							$data_relation = $this->_db->loadObject();

							$query = $this->_db->getQuery(true);
							$conditions = array(
									"category_id = " . $db->quote($last_insert),
									"product_id  = " . $db->quote($value)
							);
							$query->delete($db->quoteName($this->_table_prefix . "product_category_xref"));
							$query->where($conditions);

							$this->_db->setQuery($query);
							$this->_db->query();

							if (isset($data_relation->product_id) && $data_relation->product_id == $key)
							{
								// Create a new query object.
								$db = $this->_db;
								$query = $this->_db->getQuery(true);

								// Insert columns.
								$columns = array(
										"category_id",
										"product_id"
								);

								// Insert values.
								$values = array($db->quote($last_insert) , $db->quote($value));

								// Prepare the insert query.
								$query->insert($db->quoteName($this->_table_prefix . "product_category_xref"));
								$query->columns($columns);
								$query->values(implode(',', $values));
								$this->_db->setQuery($query);

								if (!$this->_db->query())
								{
									$this->setError($this->_db->getErrorMsg());
								}
							}
						}
					}
				}
			}

			for ($v = 0; $v < count($vmcatarr); $v++)
			{
				$db = $this->_db;
				$query = $this->_db->getQuery(true);

				$query->select("category_parent_id");
				$query->from("#__vm_category_xref");
				$query->where("category_child_id = " . $db->quote($vmcatarr[$v]));

				$this->_db->setQuery($query);
				$vmparent = $this->_db->loadResult();

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

				$query = $this->_db->getQuery(true);

				$query->select("count(*) AS total");
				$query->from($db->quoteName($this->_table_prefix . "category_xref"));
				$query->where("category_parent_id = " . $db->quote($redparentvalue));
				$query->where("category_child_id  = " . $db->quote($redchildvalue));
				$this->_db->setQuery($query_check);
				$rowcheck = $this->_db->loadResult();

				if ($rowcheck == 0)
				{
					// Insert category inter relation
					$db = $this->_db;
					$query = $this->_db->getQuery(true);

					// Insert columns.
					$columns = array(
							"category_parent_id",
							"category_child_id"
					);

					// Insert values.
					$values = array(
							$db->quote($redparentvalue),
							$db->quote($redchildvalue)
					);

					// Prepare the insert query.
					$query->insert($db->quoteName($this->_table_prefix . "category_xref"));
					$query->columns($db->quoteName($columns));
					$query->values(implode(',', $values));
					$this->_db->setQuery($query);
					$this->_db->query();
				}
			}

			return $k;
	}

	/**
	 * Method Shopper_Group_Insert will insert shopper into group
	 *
	 * @return number
	 */

	public function Shopper_Group_insert()
	{
			$db = $this->_db;
			$query = $this->_db->getQuery(true);
			$query->select("
					vmsg.shopper_group_id,
					vmsg.shopper_group_name,
					vmsg.shopper_group_desc,
					rdsg.shopper_group_name as rdsp_shopper_group_name
			");
			$query->from("#__vm_shopper_group as vmsg");
			$query->join("LEFT", $this->_table_prefix . "shopper_group as rdsg on  rdsg.shopper_group_name = vmsg.shopper_group_name");
			$this->_db->setQuery($query);
			$data = $this->_db->loadObjectList();
			$k = 0;

			for ($i = 0; $i <= (count($data) - 1); $i++)
			{
				if ($data[$i]->rdsp_shopper_group_name == null)
				{
					$rows = $this->getTable('shopper_group_detail');
					$rows->shopper_group_id = 0;
					$rows->shopper_group_name = $data[$i]->shopper_group_name;
					$rows->shopper_group_desc = $data[$i]->shopper_group_desc;

					if (!$rows->store())
					{
						$this->setError($this->_db->getErrorMsg());
					}
					else
					{
						$k++;
						$last_insert_shopper = $this->_db->insertid();

						// Update user_info_id for shopper_group_id
						$db = $this->_db;
						$query = $this->_db->getQuery(true);

						$query->select("*");
						$query->from("#__vm_shopper_vendor_xref");
						$query->where("shopper_group_id = " . $db->quote($data[$i]->shopper_group_id));
						$this->_db->setQuery($query);
						$shoppers = $this->_db->loadObjectList();

						for ($s = 0; $s <= count($shoppers); $s++)
						{
							$db = $this->_db;
							$query = $this->_db->getQuery(true);

							// Fields to update.
							$fields = array(
									"shopper_group_id = " . $db->quote($last_insert_shopper)
							);

							// Conditions for which records should be updated.
							$conditions = array(
									"user_id = " . $db->quote($shoppers[$s]->user_id)
							);
							$query->update($db->quoteName($this->_table_prefix . "users_info"));
							$query->set($fields);
							$query->where($conditions);
							$this->_db->setQuery($query);
							$this->_db->query();
						}
					}
				}
			}

			return $k;
	}

	/**
	 * Import customer information From VM
	 *
	 * @return number
	 */
	public function customerInformation()
	{
			$order_functions = new order_functions;
			$query = $this->_db->getQuery(true);

			$query->select("
					vmui.* ,
					vmsvx.shopper_group_id
			");
			$query->from("#__vm_user_info AS vmui");
			$query->join("LEFT", "#__vm_shopper_vendor_xref AS vmsvx ON vmui.user_id = vmsvx.user_id");
			$this->_db->setQuery($query);
			$data = $this->_db->loadObjectList();

			$k = 0;

			for ($i = 0; $i < count($data); $i++)
			{
				if ($data[$i]->address_type == "BT")
				{
					$redshopUser = $order_functions->getBillingAddress($data[$i]->user_id);

					if (count($redshopUser) > 0)
					{
						$redUserId = $redshopUser->users_info_id;
						$row = $this->getTable('user_detail');
						$row->load($redUserId);
						$row->user_email = $data[$i]->user_email;
						$row->shopper_group_id = $data[$i]->shopper_group_id;
						$row->firstname = $data[$i]->first_name;
						$row->lastname = $data[$i]->last_name;
						$row->company_name = $data[$i]->company;
						$row->address = $data[$i]->address_1;
						$row->city = $data[$i]->city;
						$row->country_code = $data[$i]->country;
						$row->state_code = $data[$i]->state;
						$row->zipcode = $data[$i]->zip;
						$row->phone = $data[$i]->phone_1;

						if ($row->store())
						{
							$k++;
						}
					}
					else
					{
						$rows = $this->getTable('user_detail');
						$rows->load();
						$rows->user_id = $data[$i]->user_id;
						$rows->user_email = $data[$i]->user_email;
						$rows->shopper_group_id = $data[$i]->shopper_group_id;
						$rows->firstname = $data[$i]->first_name;
						$rows->address_type = $data[$i]->address_type;
						$rows->lastname = $data[$i]->last_name;
						$rows->company_name = $data[$i]->company;
						$rows->address = $data[$i]->address_1;
						$rows->city = $data[$i]->city;
						$rows->country_code = $data[$i]->country;
						$rows->state_code = $data[$i]->state;
						$rows->zipcode = $data[$i]->zip;
						$rows->phone = $data[$i]->phone_1;

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
					$rows->user_id = $data[$i]->user_id;
					$rows->user_email = $data[$i]->user_email;
					$rows->shopper_group_id = $data[$i]->shopper_group_id;
					$rows->firstname = $data[$i]->first_name;
					$rows->address_type = $data[$i]->address_type;
					$rows->lastname = $data[$i]->last_name;
					$rows->company_name = $data[$i]->company;
					$rows->address = $data[$i]->address_1;
					$rows->city = $data[$i]->city;
					$rows->country_code = $data[$i]->country;
					$rows->state_code = $data[$i]->state;
					$rows->zipcode = $data[$i]->zip;
					$rows->phone = $data[$i]->phone_1;

					if ($rows->store())
					{
						$k++;
					}
				}
			}

			return $k;
	}

	/**
	 * Method Orders_insert will insert order into VM
	 *
	 * @return number
	 */
	public function Orders_insert()
	{
			$producthelper = new producthelper;
			$order_functions = new order_functions;

			$db = $this->_db;
			$query = $this->_db->getQuery(true);

			$query->select("
					rui.users_info_id AS rui_users_info_id,
					vmo . * ,
					rdo.vm_order_number AS rdo_order_number
			");
			$query->from("#__vm_orders AS vmo");
			$query->join("left", $this->_table_prefix . "orders AS rdo ON rdo.vm_order_number = vmo.order_number");
			$query->join("left", $this->_table_prefix . "users_info` AS rui ON rui.user_id = vmo.user_id AND rui.address_type ='BT'");
			$query->order("vmo.order_id ASC");
			$this->_db->setQuery($query);
			$data = $this->_db->loadObjectList();

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
						$this->setError($this->_db->getErrorMsg());
					}
					else
					{
						$k++;
					}

					$last_insert = $reduser->order_id;

					// Copying VM Order_item Data To Redshop
					$db = $this->_db;
					$order_item = $this->_db->getQuery(true);

					$order_item->select("
							vmoi.*,
							rdoi.order_id AS rdoi_order_id,
							rdp.product_id AS rdp_product_id
					");
					$order_item->from("`#__vm_order_item` AS vmoi");
					$order_item->join("LEFT", $this->_table_prefix . "order_item AS rdoi ON rdoi.order_id = " . $db->quote($last_insert));
					$order_item->join("LEFT", $this->_table_prefix . "product AS rdp ON rdp.product_number = vmoi.order_item_sku");
					$order_item->from("`#__vm_order_item` AS vmoi");
					$order_item->where("vmoi.order_id = " . $db->quote($data[$i]->order_id));
					$this->_db->setQuery($order_item);
					$order_item = $this->_db->loadObjectList();

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
							$this->setError($this->_db->getErrorMsg());
						}
					}

					// Starting Copying VM order_payment Data to Redshop
					$query = $this->_db->getQuery(true);

					$query->select("vmop.*,rdop.payment_order_id");
					$query->from("#__vm_order_payment AS vmop");
					$query->join("LEFT", $this->_table_prefix . "order_payment AS rdop ON rdop.order_id = " . $db->quote($last_insert));
					$query->where("vmop.order_id = " . $db->quote($data[$i]->order_id));
					$this->_db->setQuery($query);
					$order_payment = $this->_db->loadObjectList();

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
								$this->setError($this->_db->getErrorMsg());
							}
						}
					}

					// Starting Copying VM order_user_info to Redshop
					$query = $this->_db->getQuery(true);
					$query->select("
							vmoui.*,
							rdoui.order_id as rdoui_order_id,
							rdui.users_info_id
					");
					$query->from("`#__vm_order_user_info` AS vmoui");
					$query->join("LEFT", $this->_table_prefix . "users_info AS rdui ON rdui.user_id = vmoui.user_id");
					$query->join("LEFT", $this->_table_prefix . "order_users_info as rdoui on rdoui.order_id = " . $db->quote($last_insert));
					$query->where("vmoui.order_id = " . $quote($data[$i]->order_id));
					$this->_db->setQuery($query);
					$order_user_info = $this->_db->loadObjectList();

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
								$this->setError($this->_db->getErrorMsg());
							}
						}
					}
				}
			}

			return $k;
	}

	/**
	 * Method Order_status_insert insert status of order shopper
	 *
	 * @return number
	 */
	public function Order_Status_insert()
	{
			$db = $this->_db;
			$query = $this->_db->getQuery(true);

			$query->select("vmos.*,rdos.order_status_code as rdcode");
			$query->from("`#__vm_order_status` AS vmos ");
			$query->join("LEFT", $this->_table_prefix . "order_status AS rdos ON vmos.order_status_code = rdos.order_status_code");
			$this->_db->setQuery($query);
			$data = $this->_db->loadObjectList();

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
						$this->setError($this->_db->getErrorMsg());
					}
					else
					{
						$k++;
					}
				}
			}

			return $k;
	}

	/**
	 * Method Manufacturer_insert : insert manufacture to VM
	 *
	 * @return number
	 */
	public function Manufacturer_insert()
	{
			$db = $this->_db;
			$query = $this->_db->getQuery(true);
			$query->select("
					vmmf.*,
					vmpmf.product_id,
					vmp.product_sku,
					rdp.product_id as rdp_product_id,
					rdmf.manufacturer_id as rdmf_manufacturer_id,
					rdmf.manufacturer_name as rdmf_manufacturer_name
			");
			$query->from("#__vm_manufacturer as vmmf");
			$query->join("LEFT", "#__vm_product_mf_xref as vmpmf ON vmmf.`manufacturer_id` = vmpmf.manufacturer_id");
			$query->join("LEFT", "#__vm_product as vmp ON vmpmf.product_id = vmp.product_id");
			$query->join("LEFT", $this->_table_prefix . "product as rdp ON rdp.product_number = vmp.product_sku");
			$query->join("LEFT", $this->_table_prefix . "manufacturer AS rdmf ON rdmf.manufacturer_name = vmmf.`mf_name`");
			$this->_db->setQuery($query);
			$data = $this->_db->loadObjectList();
			$k = 0;
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
				$rdp_product_id = $data[$i]->rdp_product_id;

				if ($data[$i]->rdmf_manufacturer_id == null || $data[$i]->rdmf_manufacturer_name == null)
				{
					if ($tmp_id == 0)
					{
						$reduser = $this->getTable('manufacturer_detail');
						$reduser->set('published', 1);
						$reduser->set('template_id', MANUFACTURER_TEMPLATE);
						$reduser->set('manufacturer_desc', $manufacturer_desc);
						$reduser->set('manufacturer_name', $manufacturer_name);
						$reduser->set('manufacturer_id', 0);

						if (!$reduser->store())
						{
							$this->setError($this->_db->getErrorMsg());
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

				$db = $this->_db;
				$query = $this->_db->getQuery(true);

				// Fields to update.
				$fields = array(
						"manufacturer_id = " . $db->quote($last_insert_manufacturer)
				);

				// Conditions for which records should be updated.
				$conditions = array(
						"product_id = " . $db->quote($rdp_product_id)
				);
				$query->update($db->quoteName($this->_table_prefix . "product"));
				$query->set($fields);
				$query->where($conditions);
				$this->_db->setQuery($query);
				$this->_db->query();
			}

			return $k;
	}

	/**
	 * Related product sync
	 *
	 * @param   unknown  $vmproarr   value must an Array data
	 * @param   unknown  $redproarr  value must an Array data
	 *
	 * @return boolean
	 */
	public function Related_Product_sync($vmproarr, $redproarr)
	{
			// Vmproduct loop for product inter realtion
			for ($v = 0; $v < count($vmproarr); $v++)
			{
				$redparent = $redproarr[$v];
				$db = $this->_db;
				$query = $this->_db->getQuery(true);

				$query->select("related_products");
				$query->from($db->quoteName("#__vm_product_relations"));
				$query->where("product_id= " . $db->quote($vmproarr[$v]));
				$this->_db->setQuery($query);
				$vmrel = $this->_db->loadResult();

				if ($vmrel != "")
				{
					$vmrel = explode("|", $vmrel);

					for ($i = 0; $i < count($vmrel); $i++)
					{
						$vmrelpro = $vmrel[$i];

						// Search key of related id
						$vmrelprokey = array_search($vmrelpro, $vmproarr);

						if ($vmrelprokey != 0)
						{
							$vmrelvalue = $vmproarr[$vmrelprokey];
							$redrelvalue = $redproarr[$vmrelprokey];

							// Create a new query object.
							$db = $this->_db;
							$query = $this->_db->getQuery(true);

							// Insert columns.
							$columns = array(
									"related_id",
									"product_id"
							);

							// Insert values.
							$values = array(
									$db->quote($redrelvalue),
									$db->quote($redparent)
							);

							// Prepare the insert query.
							$query->insert($db->quoteName($this->_table_prefix . "product_related"));
							$query->columns($db->quoteName($columns));
							$query->values(implode(',', $values));
							$this->_db->setQuery($query);
							$this->_db->query();
						}
					}
				}
			}

			return true;
	}

	/**
	 * Method getProductIdByNumber :get product is by product_number of product
	 *
	 * @param   string  $product_number  Value must a string, number, etc
	 *
	 * @return Number
	 */
	public function getProductIdByNumber($product_number)
	{
			$db = $this->_db;
			$query = $this->_db->getQuery(true);

			$query->select("product_id");
			$query->from($db->quoteName($this->_table_prefix . "product"));
			$query->where("product_number = " . $db->quote($product_number));
			$this->_db->setQuery($query);
			$product_id = $this->_db->loadResult();

			return $product_id;
	}

	/**
	 * Method storePropertyStockPosition : save stock position of property
	 *
	 * @param   array   $data     Value is an Array data
	 * @param   string  $section  Default value is property
	 *
	 * @return void|boolean|unknown
	 */
	public function storePropertyStockPosition($data, $section = 'property')
	{
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redcrm/tables');

			if ($section == 'property')
			{
				$data['section_id'] = $data['property_id'];
			}
			else
			{
				$data['section_id'] = $data['subattribute_color_id'];
			}

			$data['section'] = $section;

			if ($data['section_id'] <= 0)
			{
				return;
			}

			$db = $this->_db;
			$query = $this->_db->getQuery(true);

			$query->select("attribute_stock_placement_id");
			$query->from($db->quoteName($this->_crmtable_prefix . "attribute_stock_placement"));
			$query->where("section = " . $db->quote($data['section']));
			$query->where("section_id = " . $db->quote($data['section_id']));
			$this->_db->setQuery($query);
			$autoid = $this->_db->loadResult();

			$row =& $this->getTable('attributestock_placement');
			$row->load($autoid);

			$data['stock_placement'] = $data['stockposition'];

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

			return $row;
	}

	/**
	 * Update/insert product extra field data
	 *
	 * @param   string   $fieldname   Extra Field Names
	 * @param   array    $rawdata     CSV rawdata
	 * @param   integer  $product_id  Product Id
	 *
	 * @return  void
	 */
	public function importProductExtrafieldData($fieldname, $rawdata, $product_id)
	{
			$value = $rawdata[$fieldname];

			$db = $this->_db;
			$query = $this->_db->getQuery(true);

			$query->select("field_id");
			$query->from($db->quoteName($this->_table_prefix . "fields"));
			$query->where("field_name` LIKE " . $db->quote($fieldname));
			$this->_db->setQuery($query);
			$field_id = $this->_db->loadResult();

			if ($field_id)
			{
				$db = $this->_db;
				$query = $this->_db->getQuery(true);

				$query->select("data_id");
				$query->from($db->quoteName($this->_table_prefix . "fields_data"));
				$query->where("fieldid IN (" . $field_id . ")");
				$query->where("itemid = " . $db->quote($product_id));
				$query->where("section = " . $db->quote("1"));
				$this->_db->setQuery($query);
				$data_id = $this->_db->loadResult();

				if ($data_id)
				{
					$db  = $this->_db;
					$query = $this->_db->getQuery(true);

					// Fields to update.
					$fields = array(
							"data_txt = " . $db->quote($value)
					);

					// Conditions for which records should be updated.
					$conditions = array(
							"fieldid IN (" . $field_id . ")",
							"itemid = " . $db->quote($product_id),
							"section = " . $db->quote("1")
					);

					$query->update($db->quoteName($this->_table_prefix . "fields_data"));
					$query->set($fields);
					$query->where($conditions);
					$this->_db->setQuery($query);
					$this->_db->query();
				}
				else
				{
					if (trim($value) != "")
					{
						// Create a new query object.
						$db = $this->_db;
						$query = $this->_db->getQuery(true);

						// Insert columns.
						$columns = array(
								"data_id" ,
								"fieldid" ,
								"data_txt" ,
								"itemid" ,
								"section" ,
								"alt_text",
								"image_link" ,
								"user_email"
						);

						// Insert values.
						$values = array(
								null,
								$db->quote($field_id),
								$db->quote($value),
								$db->quote($product_id),
								'1',
								'',
								'',
								''
						);

						// Prepare the insert query.
						$query->insert($db->quoteName($this->_table_prefix . "fields_data"));
						$query->columns($db->quoteName($columns));
						$query->values(implode(',', $values));
						$this->_db->setQuery($query);
						$this->_db->query();
					}
				}
			}

			return;
	}

	/**
	 * Method getTimeLeft : get max_execution_time on server
	 *
	 * @return number
	 */
	public function getTimeLeft()
	{
			if (function_exists('ini_get'))
			{
				$php_max_exec = ini_get("max_execution_time");
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
			$minexectime = $php_max_exec;

			list ($usec, $sec) = explode(" ", microtime());
			$micro_time = ((float) $usec + (float) $sec);

			// $start_micro_time = $_SESSION['start_micro_time'];
			$session = JFactory::getSession();
			$start_micro_time = $session->get('start_micro_time');

			$start_micro_time;

			$running_time = $micro_time - $start_micro_time;
			$retun = $php_max_exec - $running_time;

			return $retun;
	}

	/**
	 * External function to collect matched keys
	 *
	 * @param   array  $item        Value is Array
	 * @param   array  $keyproduct  Value is a string
	 * @param   array  &$newkeys    Reference variable
	 *
	 * @return void
	 */
	public function checkkeys($item, $keyproduct, &$newkeys)
	{
			$pattern = '/rs_/';

			if (preg_match($pattern, $keyproduct))
			{
				$newkeys[] = $keyproduct;
			}
	}
}
