<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.archive');

/**
 * Class to Manage PayPal Payment Subscription
 *
 * @package  RedSHOP
 * @since    2.5
 */
class RedshopControllerMedia_Detail extends RedshopController
{
	/**
	 * Media Detail Constructor
	 *
	 * @param   array  $default  [desctiption]
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	/**
	 * Edit Media
	 *
	 * @return  [type]  [description]
	 */
	public function edit()
	{
		$this->input->set('view', 'media_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	/**
	 * Save Media Detail
	 *
	 * @return  [type]  [description]
	 */
	public function save($apply = 0)
	{
		$post = $this->input->post->getArray();

		$cid   = $this->input->post->get('cid', array(0), 'array');
		$model = $this->getModel('media_detail');

		$product_download_root = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT');

		if (substr(Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT '), -1) != DIRECTORY_SEPARATOR)
		{
			$product_download_root = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT') . '/';
		}

		$bulkfile     = $this->input->files->get('bulkfile', null, 'array');
		$bulkfiletype = strtolower(JFile::getExt($bulkfile['name']));
		$file         = $this->input->files->get('file', array(), 'array');

		if (!empty($bulkfile) && $bulkfile['name'] == null && $file[0]['name'] == null && $post['oldmedia'] != "")
		{
			if ($post['media_bank_image'] == "")
			{
				$post ['media_id']  = $cid[0];
				$post['media_name'] = $post['oldmedia'];

				if ($post['media_type'] != $post['oldtype'])
				{
					$old_path       = JPATH_COMPONENT_SITE . '/assets/' . $post['oldtype'] . '/' . $post['media_section'] . '/' . $post['media_name'];
					$old_thumb_path = JPATH_COMPONENT_SITE . '/assets/' . $post['oldtype']
						. '/' . $post['media_section'] . '/thumb/' . $post['media_name'];

					$new_path = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type']
						. '/' . $post['media_section'] . '/' . RedShopHelperImages::cleanFileName($post['media_name']);

					copy($old_path, $new_path);

					JFile::delete($old_path);
					JFile::delete($old_thumb_path);
				}

				if ($save = $model->store($post))
				{
					$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

					// Set First Image as product Main Imaged
					if ($save->media_section == 'product' && $save->media_type == 'images')
					{
						if (isset($post['set']) && $post['media_section'] != 'manufacturer')
						{
							if ($apply == 1)
							{
								$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section']
								. '&cid[]=' . $save->media_id, $msg, 'warning'
							);
							}
							else
							{
								$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
							);
							}
						}

						elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
						{
							$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
                            <script language="javascript" type="text/javascript">
                                window.parent.document.location = '<?php echo $link; ?>';
                            </script><?php
						}
						else
						{
							$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
						}
					}
					else
					{
						if (isset($post['set']) && $post['media_section'] != 'manufacturer')
						{
							if ($apply == 1)
							{
								$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section']
								. '&cid[]=' . $save->media_id, $msg, 'warning'
							);
							}
							else
							{
								$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
									. $post['section_id'] . '&showbuttons=1&section_name='
									. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
								);
							}
						}
						else
						{
							$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
						}
					}
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

					if (isset($post['set']))
					{
						if ($apply == 1)
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section']
								. '&cid[]=' . $save->media_id, $msg, 'warning'
							);
						}
						else
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section'], $msg, 'warning'
							);
						}
					}
					else
					{
						$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
					}
				}
			}
			else
			{
				if ($cid [0] != 0)
				{
					$model->delete($cid);
					$post['bulk']      = 'no';
					$post ['media_id'] = 0;
				}

				// Media Bank Start

				$image_split = explode('/', $post['media_bank_image']);

				// Make the filename unique
				$filename = RedShopHelperImages::cleanFileName($image_split[count($image_split) - 1]);

				// Download product changes
				if ($post['media_type'] == 'download')
				{
					$post['media_name'] = $product_download_root . str_replace(" ", "_", $filename);
					$dest               = $post['media_name'];
				}
				else
				{
					$post['media_name'] = $filename;
					$dest               = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type'] . '/' . $post['media_section'] . '/' . $filename;
				}

				$row = $model->store($post);

				// Image Upload
				$src = JPATH_ROOT . '/' . $post['media_bank_image'];
				copy($src, $dest);

				// Media Bank End
				if (isset($post['set']) && $post['media_section'] != 'manufacturer')
				{
					if ($apply == 1)
					{
						$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section']
								. '&cid[]=' . $save->media_id, $msg, 'warning'
							);
					}
					else
					{
						$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
							. $post['section_id'] . '&showbuttons=1&section_name='
							. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
						);
					}
				}
				elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
				{
					$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
                    <script language="javascript" type="text/javascript">
                        window.parent.document.location = '<?php echo $link; ?>';
                    </script><?php
				}
				else
				{
					if ($apply == 1)
					{
						$this->setRedirect('index.php?option=com_redshop&view=media_detail&task=edit&cid[]=' . $row->media_id, $msg);
					}
					else
					{
						$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
					}
				}
			}
		}
		elseif ($file[0]['name'] == null && $post['media_bank_image'] == "")
		{
			$save = $model->store($post);
			$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
					. $post['section_id'] . '&showbuttons=1&section_name='
					. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
				);
		}
		else
		{
			if ($cid[0] != 0)
			{
				$model->delete($cid);
				$post['bulk'] = 'no';
			}

			// If file selected from download folder...
			if (isset($post['hdn_download_file']) && $post['hdn_download_file'] != "")
			{
				if ($post['media_type'] == 'download')
				{
					$download_path      = $product_download_root . $post['hdn_download_file_path'];
					$post['media_name'] = $post['hdn_download_file'];
				}
				else
				{
					$download_path      = "product" . '/' . $post['hdn_download_file'];
					$post['media_name'] = $post['hdn_download_file'];
				}

				$filenewtype            = strtolower(JFile::getExt($post['hdn_download_file']));
				$post['media_mimetype'] = $filenewtype;

				if ($post['hdn_download_file_path'] != $download_path)
				{
					// Make the filename unique
					$filename = RedShopHelperImages::cleanFileName($post['hdn_download_file']);

					if ($post['media_type'] == 'download')
					{
						$post['media_name'] = $product_download_root . $filename;

						$down_src = $download_path;

						$down_dest = $post['media_name'];
					}
					else
					{
						$post['media_name'] = $filename;

						$down_src = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type'] . '/' . $post['hdn_download_file_path'];

						$down_dest = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type'] . '/' . $post['media_section'] . '/' . $post['media_name'];
					}

					copy($down_src, $down_dest);
				}

				if ($save = $model->store($post))
				{
					$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

					if (isset($post['set']) && $post['media_section'] != 'manufacturer')
					{
						if ($apply == 1)
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section']
								. '&cid[]=' . $save->media_id, $msg, 'warning'
							);
						}
						else
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
							);
						}
					}
					// Set First Image as product Main Imaged
					elseif ($save->media_section == 'product')
					{
						$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail', $msg);
					}
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

					if (isset($post['set']))
					{
						$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id=' . $post['section_id'] . '&showbuttons=1&section_name='
							. $post['section_name'] . '&media_section=' . $post['media_section'], $msg, 'warning'
						);
					}
					else
					{
						$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
					}
				}
			}

			// Media Bank Start
			if ($post['media_bank_image'] != "")
			{
				$image_split = explode('/', $post['media_bank_image']);

				// Make the filename unique
				$filename = RedShopHelperImages::cleanFileName($image_split[count($image_split) - 1]);

				// Download product changes
				if ($post['media_type'] == 'download')
				{
					$post['media_name'] = $product_download_root . str_replace(" ", "_", $filename);
					$dest               = $post['media_name'];
				}
				else
				{
					$post['media_name'] = $filename;
					$dest               = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type'] . '/' . $post['media_section'] . '/' . $filename;
				}

				$model->store($post);

				// Image Upload
				$src = JPATH_ROOT . '/' . $post['media_bank_image'];
				copy($src, $dest);

				if (isset($post['set']) && $post['media_section'] != 'manufacturer' && $post['oldmedia'] == "")
				{
					if ($apply == 1)
					{
						$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section']
								. '&cid[]=' . $save->media_id, $msg, 'warning'
							);
					}
					else
					{
						$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $post['section_id'] . '&showbuttons=1&section_name='
							. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
						);
					}
				}
				elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
				{
					$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
                    <script language="javascript" type="text/javascript">
                        window.parent.document.location = '<?php echo $link; ?>';
                    </script><?php
				}
				else
				{
					$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
				}
			}


			// Media Bank End
			$post ['media_id'] = 0;
			$directory         = self::writableCell('components/com_redshop/assets');

			if ($directory == 0)
			{
				$msg = JText::_('COM_REDSHOP_PLEASE_CHECK_DIRECTORY_PERMISSION');
				JFactory::getApplication()->enqueueMessage($msg, 'error');
			}
			// Starting of Bull upload creation
			if ($bulkfile['name'] != '')
			{
				if ($bulkfiletype == "zip" || $bulkfiletype == "gz" || $bulkfiletype == "tar" || $bulkfiletype == "tgz" || $bulkfiletype == "gzip")
				{
					// Fix the width of the thumb nail images
					$src         = $bulkfile['tmp_name'];
					$dest        = JPATH_ROOT . '/components/com_redshop/assets/' . $post['media_type'] . '/' . $post['media_section'] . '/'
						. $bulkfile['name'];
					$file_upload = JFile::upload($src, $dest);

					if ($file_upload != 1)
					{
						$msg = JText::_('COM_REDSHOP_PLEASE_CHECK_DIRECTORY_PERMISSION');
						JFactory::getApplication()->enqueueMessage($msg, 'error');
					}

					$target = 'components/com_redshop/assets/media/extracted/' . $bulkfile['name'];
					JArchive::extract($dest, $target);
					$name = explode('.', $bulkfile['name']);
					$scan = scandir($target);

					for ($i = 2, $in = count($scan); $i < $in; $i++)
					{
						if (is_dir($target . '/' . $scan[$i]))
						{
							$newscan = scandir($target . '/' . $scan[$i]);

							for ($j = 2, $jn = count($newscan); $j < $jn; $j++)
							{
								$filenewtype            = strtolower(JFile::getExt($newscan[$j]));
								$btsrc                  = $target . '/' . $scan[$i] . '/' . $newscan[$j];
								$post['media_name']     = RedShopHelperImages::cleanFileName($newscan[$j]);
								$post['media_mimetype'] = $filenewtype;

								if ($post['media_type'] == 'download')
								{
									$post['media_name'] = $product_download_root . RedShopHelperImages::cleanFileName($newscan[$j]);

									if ($row = $model->store($post))
									{
										$originaldir = $post['media_name'];
										copy($btsrc, $originaldir);
										JFile::delete($btsrc);

										$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

										if (isset($post['set']) && $post['media_section'] != 'manufacturer')
										{
											if ($apply == 1)
											{
												$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
													. $post['section_id'] . '&showbuttons=1&section_name='
													. $post['section_name'] . '&media_section=' . $post['media_section']
													. '&cid[]=' . $save->media_id, $msg, 'warning'
												);
											}
											else
											{
												$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
													. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name']
													. '&media_section=' . $post['media_section'], $msg
												);
											}
										}
										elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
										{
											$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
                                            <script language="javascript" type="text/javascript">
                                                window.parent.document.location = '<?php echo $link; ?>';
                                            </script><?php
										}
										else
										{
											$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
										}
									}
									else
									{
										$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

										if (isset($post['set']))
										{
											$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
												. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name']
												. '&media_section=' . $post['media_section'], $msg, 'warning'
											);
										}
										else
										{
											$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
										}
									}
								}
								else
								{
									if ($filenewtype == 'png' || $filenewtype == 'gif' || $filenewtype == 'jpg' || $filenewtype == 'jpeg')
									{
										if ($row = $model->store($post))
										{
											$originaldir = JPATH_ROOT . '/components/com_redshop/assets/' . $row->media_type . '/'
												. $row->media_section . '/' . RedShopHelperImages::cleanFileName($newscan[$j]);

											copy($btsrc, $originaldir);
											JFile::delete($btsrc);
											$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

											if (isset($post['set']) && $post['media_section'] != 'manufacturer')
											{
												if ($apply == 1)
												{
													$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
														. $post['section_id'] . '&showbuttons=1&section_name='
														. $post['section_name'] . '&media_section=' . $post['media_section']
														. '&cid[]=' . $save->media_id, $msg, 'warning'
													);
												}
												else
												{
													$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
														. $post['section_id'] . '&showbuttons=1&section_name='
														. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
													);
												}
											}

											elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
											{
												$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
                                                <script language="javascript" type="text/javascript">
                                                    window.parent.document.location = '<?php echo $link; ?>';
                                                </script><?php
											}
											else
											{
												$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
											}
										}
									}
									else
									{
										$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

										if (isset($post['set']))
										{
											$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
												. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
												. $post['media_section'], $msg, 'warning'
											);
										}
										else
										{
											$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
										}
									}
								}
							}
						}
						else
						{
							$filenewtype            = strtolower(JFile::getExt($scan[$i]));
							$btsrc                  = $target . '/' . $scan[$i];
							$post['media_name']     = RedShopHelperImages::cleanFileName($scan[$i]);
							$post['media_mimetype'] = $filenewtype;

							if ($post['media_type'] == 'download')
							{
								$post['media_name'] = $product_download_root . RedShopHelperImages::cleanFileName($scan[$i]);

								if ($row = $model->store($post))
								{
									$originaldir = $post['media_name'];
									copy($btsrc, $originaldir);
									JFile::delete($btsrc);
									$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

									if (isset($post['set']) && $post['media_section'] != 'manufacturer')
									{
										if ($apply == 1)
										{
											$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
												. $post['section_id'] . '&showbuttons=1&section_name='
												. $post['section_name'] . '&media_section=' . $post['media_section']
												. '&cid[]=' . $save->media_id, $msg, 'warning'
											);
										}
										else
										{
											$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
												. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
												. $post['media_section'], $msg
											);
										}
									}

									elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
									{
										$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
                                        <script language="javascript" type="text/javascript">
                                            window.parent.document.location = '<?php echo $link; ?>';
                                        </script><?php
									}
									else
									{
										$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
									}
								}
								else
								{
									$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

									if (isset($post['set']))
									{
										$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
											. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
											. $post['media_section'], $msg, 'warning'
										);
									}
									else
									{
										$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
									}
								}
							}
							else
							{
								if ($filenewtype == 'png' || $filenewtype == 'gif' || $filenewtype == 'jpg' || $filenewtype == 'jpeg')
								{
									if ($row = $model->store($post))
									{
										// Set First Image as product Main Imaged
										$originaldir = JPATH_ROOT . '/components/com_redshop/assets/' . $row->media_type . '/'
											. $row->media_section . '/' . RedshopHelperMedia::cleanFileName($scan[$i]);

										copy($btsrc, $originaldir);

										if (JFile::exists($btsrc))
										{
											JFile::delete($btsrc);
										}

										if (JFile::exists($target))
										{
											JFolder::delete($target . '/' . $name[0]);
											JFolder::delete($target);
											JFile::delete($dest);

											return true;
										}

										$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

										if (isset($post['set']) && $post['media_section'] != 'manufacturer')
										{
											if ($apply == 1)
											{
												$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
													. $post['section_id'] . '&showbuttons=1&section_name='
													. $post['section_name'] . '&media_section=' . $post['media_section']
													. '&cid[]=' . $save->media_id, $msg, 'warning'
												);
											}
											else
											{
												$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
													. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name']
													. '&media_section=' . $post['media_section'], $msg
												);
											}
										}
										elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
										{
											$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
                                            <script language="javascript" type="text/javascript">
                                                window.parent.document.location = '<?php echo $link; ?>';
                                            </script><?php
										}
										else
										{
											$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
										}
									}
								}
								else
								{
									$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

									if (isset($post['set']))
									{
										$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
											. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
											. $post['media_section'], $msg, 'warning'
										);
									}
									else
									{
										$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
									}
								}
							}
						}
					}
				}
				elseif ($bulkfiletype == 'png' || $bulkfiletype == 'gif' || $bulkfiletype == 'jpg' || $bulkfiletype == 'pdf'
					|| $bulkfiletype != 'mpeg' || $bulkfiletype != 'mp4' || $bulkfiletype != 'avi' || $bulkfiletype != '3gp'
					|| $bulkfiletype != 'swf' || $bulkfiletype != 'jpeg'
				)
				{
					$msg = JText::_('COM_REDSHOP_PLEASE_SELECT_NO');

					if (isset($post['set']))
					{
						$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
							. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
							. $post['media_section'], $msg, 'warning'
						);
					}
					else
					{
						$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
					}
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_MEDIA_FILE_EXTENSION_WRONG');

					if (isset($post['set']))
					{
						$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
							. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
							. $post['media_section'], $msg, 'warning'
						);
					}
					else
					{
						$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
					}
				}
			}

			if ($file[0]['name'] != '')
			{
				$num = count($file);

				for ($i = 0; $i < $num; $i++)
				{
					$filetype = strtolower(JFile::getExt($file[$i]['name']));

					if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg' && $filetype != 'zip'
						&& $filetype != 'mpeg' && $filetype != 'mp4' && $filetype != 'avi' && $filetype != '3gp'
						&& $filetype != 'swf' && $filetype != 'pdf' && $post['media_type'] != 'download'
						&& $post['media_type'] != 'document')
					{
						$msg = JText::_('COM_REDSHOP_MEDIA_FILE_EXTENSION_WRONG');

						if (isset($post['set']))
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
								. $post['media_section'], $msg, 'warning'
							);
						}
						else
						{
							$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
						}
					}
					elseif ($post['media_section'] == '0')
					{
						$msg = JText::_('COM_REDSHOP_SELECT_MEDIA_SECTION_FIRST');

						if (isset($post['set']))
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
								. $post['media_section'], $msg, 'warning'
							);
						}
						else
						{
							$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
						}
					}
					elseif ($post['bulk'] != 'yes' && $post['bulk'] != 'no')
					{
						$msg = JText::_('COM_REDSHOP_PLEASE_SELECT_BULK_OPTION');

						if (isset($post['set']))
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
								. $post['media_section'], $msg, 'warning'
							);
						}
						else
						{
							$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
						}
					}
					elseif ($post['bulk'] == 'no' && $filetype == 'zip' && $post['media_type'] != 'download')
					{
						$msg = JText::_('COM_REDSHOP_YOU_HAVE_SELECTED_NO_OPTION');

						if (isset($post['set']))
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
								. $post['media_section'], $msg, 'warning'
							);
						}
						else
						{
							$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
						}
					}
					else
					{
						$src = $file[$i]['tmp_name'];

						$file[$i]['name'] = str_replace(" ", "_", $file[$i]['name']);

						// Download product changes
						if ($post['media_type'] == 'download')
						{
							$post['media_name'] = $product_download_root . RedShopHelperImages::cleanFileName($file[$i]['name']);
							$dest               = $post['media_name'];
						}
						else
						{
							$post['media_name'] = RedShopHelperImages::cleanFileName($file[$i]['name']);
							$dest               = JPATH_ROOT . '/components/com_redshop/assets/' . $post['media_type'] . '/'
								. $post['media_section'] . '/' . RedShopHelperImages::cleanFileName($file[$i]['name']);
						}

						$post['media_mimetype'] = $file[$i]['type'];
						$file_upload            = JFile::upload($src, $dest);

						if ($file_upload == 1 && $row = $model->store($post))
						{
							$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

							if (isset($post['set']) && $post['media_section'] != 'manufacturer')
							{
								if ($apply == 1)
								{
									$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
										. $post['section_id'] . '&showbuttons=1&section_name='
										. $post['section_name'] . '&media_section=' . $post['media_section']
										. '&cid[]=' . $save->media_id, $msg, 'warning'
									);
								}
								else
								{
									$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
										. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
										. $post['media_section'], $msg
									);
								}
							}
							elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
							{
								$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
                                <script language="javascript" type="text/javascript">
                                    window.parent.document.location = '<?php echo $link; ?>';
                                </script><?php
							}
							else
							{
								if ($apply == 1)
								{
									$this->setRedirect('index.php?option=com_redshop&view=media_detail&task=edit&cid[]=' . $row->media_id, $msg);
								}
								else
								{
									$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
								}
							}
						}
						else
						{
							$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

							if (isset($post['set']))
							{
								$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
									. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
									. $post['media_section'], $msg, 'warning'
								);
							}
							else
							{
								$this->setRedirect('index.php?option=com_redshop&view=media_detail', $msg, 'warning');
							}
						}
					}
				}
			}
		}

		if ($post['media_type'] == 'youtube')
		{
			$post['media_name'] = $post['youtube_id'];

			$link = 'index.php?option=com_redshop&view=media';

			if (isset($post['set']))
			{
				$link = 'index.php?option=com_redshop&view=media&tmpl=component';
			}

			if ($row = $model->store($post))
			{
				$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

				if ($apply)
				{
					$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section']
								. '&cid[]=' . $row->media_id, $msg, 'warning'
							);
				}
				else
				{
					$this->setRedirect($link, $msg, 'success');
				}
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');
				$this->setRedirect($link, $msg, 'warning');
			}
		}
	}

	/**
	 * Remove Media Detail
	 *
	 * @return  [type]  [description]
	 */
	public function remove()
	{
		$post = $this->input->post->getArray();

		$section_id    = $this->input->get('section_id');
		$media_section = $this->input->get('media_section');
		$cid           = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_DELETED_SUCCESSFULLY');

		if ($section_id)
		{
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
				. $section_id . '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
            <script language="javascript" type="text/javascript">
                window.parent.document.location = '<?php echo $link; ?>';
            </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
		}
	}

	/**
	 * Cancel Media Detail
	 *
	 * @return  [type]  [description]
	 */
	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
	}

	/**
	 * Check Media Folder is Writable?
	 *
	 * @param   string   $folder    Folder Name
	 * @param   integer  $relative  Folder is in relative directory then 1 else 0
	 *
	 * @return  boolean             [description]
	 */
	public function writableCell($folder, $relative = 1)
	{
		if ($relative)
		{
			return is_writable("../$folder") ? 1 : 0;
		}
		else
		{
			return is_writable("$folder") ? 1 : 0;
		}
	}

	/**
	 * Save Media Ordering
	 *
	 * @return  [type]  [description]
	 */
	public function saveorder()
	{
		$post = $this->input->post->getArray();

		$section_id    = $this->input->get('section_id');
		$media_section = $this->input->get('media_section');
		$cid           = $this->input->post->get('cid', array(), 'array');
		$order         = $this->input->post->get('order', array(), 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);
		Joomla\Utilities\ArrayHelper::toInteger($order);

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_ORDERING'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->saveorder($cid, $order))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');

		if ($section_id)
		{
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
            <script language="javascript" type="text/javascript">
                window.parent.document.location = '<?php echo $link; ?>';
            </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
		}
	}

	/**
	 * Set Ordering to plus one up
	 *
	 * @return  [type]  [description]
	 */
	public function orderup()
	{
		$post = $this->input->post->getArray();

		$section_id    = $this->input->get('section_id');
		$media_section = $this->input->get('media_section');
		$cid           = $this->input->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_ORDERING'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->orderup())
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');

		if ($section_id)
		{
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
            <script language="javascript" type="text/javascript">
                window.parent.document.location = '<?php echo $link; ?>';
            </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
		}
	}

	/**
	 * Set Ordering Minus one down
	 *
	 * @return  [type]  [description]
	 */
	public function orderdown()
	{
		$post = $this->input->post->getArray();

		$section_id    = $this->input->get('section_id');
		$media_section = $this->input->get('media_section');
		$cid           = $this->input->post->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_ORDERING'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->orderdown())
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');

		if ($section_id)
		{
			$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id=' . $section_id . '&showbuttons=1&media_section=' . $media_section, $msg);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=com_redshop&view=manufacturer'; ?>
            <script language="javascript" type="text/javascript">
                window.parent.document.location = '<?php echo $link; ?>';
            </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=media', $msg);
		}
	}
}
