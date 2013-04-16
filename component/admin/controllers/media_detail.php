<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.archive');

/**
 * Class to Manage PayPal Payment Subscription
 *
 * @package  RedSHOP
 * @since    2.5
 */
class Media_DetailController extends JController
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
		JRequest::setVar('view', 'media_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	/**
	 * Save Media Detail
	 *
	 * @return  [type]  [description]
	 */
	public function save()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$model = $this->getModel('media_detail');

		$product_download_root = PRODUCT_DOWNLOAD_ROOT;

		if (substr(PRODUCT_DOWNLOAD_ROOT, -1) != DS)
		{
			$product_download_root = PRODUCT_DOWNLOAD_ROOT . DS;
		}

		$bulkfile = JRequest::getVar('bulkfile', null, 'files', 'array');
		$bulkfiletype = strtolower(JFile::getExt($bulkfile['name']));
		$file = JRequest::getVar('file', 'array', 'files', 'array');

		if ($bulkfile['name'] == null && $file['name'][0] == null && $post['oldmedia'] != "")
		{
			if ($post['media_bank_image'] == "")
			{
				$post ['media_id'] = $cid[0];
				$post['media_name'] = $post['oldmedia'];

				if ($post['media_type'] != $post['oldtype'])
				{
					$old_path = JPATH_COMPONENT_SITE . '/assets/' . $post['oldtype'] . DS . $post['media_section'] . DS . $post['media_name'];
					$old_thumb_path = JPATH_COMPONENT_SITE . '/assets/' . $post['oldtype']
						. DS . $post['media_section'] . '/thumb/' . $post['media_name'];

					$new_path = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type']
						. DS . $post['media_section'] . DS . time() . '_' . $post['media_name'];

					copy($old_path, $new_path);

					unlink($old_path);
					unlink($old_thumb_path);
				}

				if ($save = $model->store($post))
				{
					$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

					// Set First Image as product Main Imaged
					if ($save->media_section == 'product' && $save->media_type == 'images')
					{
						if (isset($post['set']) && $post['media_section'] != 'manufacturer')
						{
							$this->setRedirect('index.php?tmpl=component&option=com_redshop&view=media&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name='
								. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
							);
						}

						elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
						{
							$link = 'index.php?option=' . $option . '&view=manufacturer';        ?>
							<script language="javascript" type="text/javascript">
								window.parent.document.location = '<?php echo $link; ?>';
							</script><?php
						}
						else
						{
							$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
						}
					}
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

					if (isset($post['set']))
					{
						$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
							. $post['section_id'] . '&showbuttons=1&section_name='
							. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
						);
					}
					else
					{
						$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
					}
				}
			}
			else
			{
				if ($cid [0] != 0)
				{
					$model->delete($cid);
					$post['bulk'] = 'no';
					$post ['media_id'] = 0;
				}

				// Media Bank Start

				$image_split = explode('/', $post['media_bank_image']);

				// Make the filename unique
				$filename = JPath::clean(time() . '_' . $image_split[count($image_split) - 1]);

				// Download product changes
				if ($post['media_type'] == 'download')
				{
					$post['media_name'] = $product_download_root . str_replace(" ", "_", $filename);
					$dest = $post['media_name'];
				}
				else
				{
					$post['media_name'] = $filename;
					$dest = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type'] . DS . $post['media_section'] . DS . $filename;
				}

				$model->store($post);

				// Image Upload
				$src = JPATH_ROOT . DS . $post['media_bank_image'];
				copy($src, $dest);

				// 	Media Bank End
				if (isset($post['set']) && $post['media_section'] != 'manufacturer')
				{
					$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id='
						. $post['section_id'] . '&showbuttons=1&section_name='
						. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
					);
				}
				elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
				{
					$link = 'index.php?option=' . $option . '&view=manufacturer';        ?>
					<script language="javascript" type="text/javascript">
						window.parent.document.location = '<?php echo $link; ?>';
					</script><?php
				}
				else
				{
					$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
				}
			}
		}
		else
		{
			if ($cid [0] != 0)
			{
				$model->delete($cid);
				$post['bulk'] = 'no';
			}

			// If file selected from download folder...
			if ($post['hdn_download_file'] != "")
			{
				if ($post['media_type'] == 'download')
				{
					$download_path = $product_download_root . $post['hdn_download_file_path'];
					$post['media_name'] = $post['hdn_download_file'];
				}
				else
				{
					$download_path = "product" . DS . $post['hdn_download_file'];
					$post['media_name'] = $post['hdn_download_file'];
				}

				$filenewtype = strtolower(JFile::getExt($post['hdn_download_file']));
				$post['media_mimetype'] = $filenewtype;

				if ($post['hdn_download_file_path'] != $download_path)
				{
					// Make the filename unique
					$filename = time() . '_' . $post['hdn_download_file'];

					if ($post['media_type'] == 'download')
					{
						$post['media_name'] = $product_download_root . str_replace(" ", "_", $filename);

						$down_src = $download_path;

						$down_dest = $post['media_name'];
					}
					else
					{
						$post['media_name'] = $filename;

						$down_src = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type'] . DS . $post['hdn_download_file_path'];

						$down_dest = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type'] . DS . $post['media_section'] . DS . $post['media_name'];
					}

					copy($down_src, $down_dest);
				}

				if ($save = $model->store($post))
				{
					$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

					// Set First Image as product Main Imaged
					if ($save->media_section == 'product')
					{
						$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail', $msg);
					}
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

					if (isset($post['set']))
					{
						$this->setRedirect('index.php?tmpl=component&option=' . $option
							. '&view=media_detail&section_id=' . $post['section_id'] . '&showbuttons=1&section_name='
							. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
						);
					}
					else
					{
						$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
					}
				}
			}

			// Media Bank Start
			if ($post['media_bank_image'] != "")
			{
				$image_split = explode('/', $post['media_bank_image']);

				// Make the filename unique
				$filename = JPath::clean(time() . '_' . $image_split[count($image_split) - 1]);

				// Download product changes
				if ($post['media_type'] == 'download')
				{
					$post['media_name'] = $product_download_root . str_replace(" ", "_", $filename);
					$dest = $post['media_name'];
				}
				else
				{
					$post['media_name'] = $filename;
					$dest = JPATH_COMPONENT_SITE . '/assets/' . $post['media_type'] . DS . $post['media_section'] . DS . $filename;
				}

				$model->store($post);

				// Image Upload
				$src = JPATH_ROOT . DS . $post['media_bank_image'];
				copy($src, $dest);

				if (isset($post['set']) && $post['media_section'] != 'manufacturer' && $post['oldmedia'] == "")
				{
					$this->setRedirect('index.php?tmpl=component&option=' . $option
						. '&view=media&section_id=' . $post['section_id'] . '&showbuttons=1&section_name='
						. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
					);
				}
				elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
				{
					$link = 'index.php?option=' . $option . '&view=manufacturer';        ?>
					<script language="javascript" type="text/javascript">
						window.parent.document.location = '<?php echo $link; ?>';
					</script><?php
				}
				else
				{
					$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
				}
			}

			// Media Bank End
			$post ['media_id'] = 0;
			$directory = self::writableCell('components/com_redshop/assets');

			if ($directory == 0)
			{
				$msg = JText::_('COM_REDSHOP_PLEASE_CHECK_DIRECTORY_PERMISSION');
				JError::raiseWarning(403, $msg);
			}

			// Starting of Bull upload creation
			if ($bulkfile['name'] != '')
			{
				if ($bulkfiletype == "zip" || $bulkfiletype == "gz" || $bulkfiletype == "tar" || $bulkfiletype == "tgz" || $bulkfiletype == "gzip")
				{
					// Fix the width of the thumb nail images
					$src = $bulkfile['tmp_name'];
					$dest = JPATH_ROOT . '/components/' . $option . '/assets/' . $post['media_type'] . '/' . $post['media_section'] . '/'
						. $bulkfile['name'];
					$file_upload = JFile::upload($src, $dest);

					if ($file_upload != 1)
					{
						$msg = JText::_('COM_REDSHOP_PLEASE_CHECK_DIRECTORY_PERMISSION');
						JError::raiseWarning(403, $msg);
					}

					$target = 'components/' . $option . '/assets/media/extracted/' . $bulkfile['name'];
					JArchive::extract($dest, $target);
					$name = explode('.', $bulkfile['name']);
					$scan = scandir($target);

					for ($i = 2; $i < count($scan); $i++)
					{
						if (is_dir($target . '/' . $scan[$i]))
						{
							$newscan = scandir($target . '/' . $scan[$i]);

							for ($j = 2; $j < count($newscan); $j++)
							{
								$filenewtype = strtolower(JFile::getExt($newscan[$j]));
								$btsrc = $target . '/' . $scan[$i] . '/' . $newscan[$j];
								$post['media_name'] = time() . "_" . $newscan[$j];
								$post['media_mimetype'] = $filenewtype;

								if ($post['media_type'] == 'download')
								{
									$post['media_name'] = $product_download_root . time() . "_" . str_replace(" ", "_", $newscan[$j]);

									if ($row = $model->store($post))
									{
										$originaldir = $post['media_name'];
										copy($btsrc, $originaldir);
										unlink($btsrc);

										$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

										if (isset($post['set']) && $post['media_section'] != 'manufacturer')
										{
											$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id='
												. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name']
												. '&media_section=' . $post['media_section'], $msg
											);
										}
										elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
										{
											$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
											<script language="javascript" type="text/javascript">
												window.parent.document.location = '<?php echo $link; ?>';
											</script><?php
										}
										else
										{
											$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
										}
									}
									else
									{
										$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

										if (isset($post['set']))
										{
											$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
												. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name']
												. '&media_section=' . $post['media_section'], $msg
											);
										}
										else
										{
											$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
										}
									}
								}
								else
								{
									if ($filenewtype == 'png' || $filenewtype == 'gif' || $filenewtype == 'jpg' || $filenewtype == 'jpeg')
									{
										if ($row = $model->store($post))
										{
											$originaldir = JPATH_ROOT . '/components/' . $option . '/assets/' . $row->media_type . '/'
												. $row->media_section . '/' . time() . '_' . $newscan[$j];

											copy($btsrc, $originaldir);
											unlink($btsrc);
											$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

											if (isset($post['set']) && $post['media_section'] != 'manufacturer')
											{
												$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id='
													. $post['section_id'] . '&showbuttons=1&section_name='
													. $post['section_name'] . '&media_section=' . $post['media_section'], $msg
												);
											}

											elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
											{
												$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
												<script language="javascript" type="text/javascript">
													window.parent.document.location = '<?php echo $link; ?>';
												</script><?php
											}
											else
											{
												$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
											}
										}
									}
									else
									{
										$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

										if (isset($post['set']))
										{
											$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
												. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
												. $post['media_section'], $msg
											);
										}
										else
										{
											$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
										}
									}
								}
							}
						}
						else
						{
							$filenewtype = strtolower(JFile::getExt($scan[$i]));
							$btsrc = $target . '/' . $scan[$i];
							$post['media_name'] = time() . "_" . $scan[$i];
							$post['media_mimetype'] = $filenewtype;

							if ($post['media_type'] == 'download')
							{
								$post['media_name'] = $product_download_root . time() . "_" . str_replace(" ", "_", $scan[$i]);

								if ($row = $model->store($post))
								{
									$originaldir = $post['media_name'];
									copy($btsrc, $originaldir);
									unlink($btsrc);
									$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

									if (isset($post['set']) && $post['media_section'] != 'manufacturer')
									{
										$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id='
											. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
											. $post['media_section'], $msg
										);
									}

									elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
									{
										$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
										<script language="javascript" type="text/javascript">
											window.parent.document.location = '<?php echo $link; ?>';
										</script><?php
									}
									else
									{
										$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
									}
								}
								else
								{
									$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

									if (isset($post['set']))
									{
										$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
											. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
											. $post['media_section'], $msg
										);
									}
									else
									{
										$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
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
										$originaldir = JPATH_ROOT . '/components/' . $option . '/assets/' . $row->media_type . '/'
											. $row->media_section . '/' . time() . '_' . $scan[$i];

										copy($btsrc, $originaldir);

										if (is_file($btsrc))
										{
											unlink($btsrc);
										}

										if (is_file($target))
										{
											rmdir($target . '/' . $name[0]);
											rmdir($target);
											unlink($dest);

											return true;
										}

										$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

										if (isset($post['set']) && $post['media_section'] != 'manufacturer')
										{
											$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id='
												. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name']
												. '&media_section=' . $post['media_section'], $msg
											);
										}
										elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
										{
											$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
											<script language="javascript" type="text/javascript">
												window.parent.document.location = '<?php echo $link; ?>';
											</script><?php
										}
										else
										{
											$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
										}
									}
								}
								else
								{
									$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

									if (isset($post['set']))
									{
										$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
											. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
											. $post['media_section'], $msg
										);
									}
									else
									{
										$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
									}
								}
							}
						}
					}
				}
				elseif ($bulkfiletype == 'png' || $bulkfiletype == 'gif' || $bulkfiletype == 'jpg' || $bulkfiletype == 'pdf'
					|| $bulkfiletype != 'mpeg' || $bulkfiletype != 'mp4' || $bulkfiletype != 'avi' || $bulkfiletype != '3gp'
					|| $bulkfiletype != 'swf' || $bulkfiletype != 'jpeg')
				{
					$msg = JText::_('COM_REDSHOP_PLEASE_SELECT_NO');

					if (isset($post['set']))
					{
						$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
							. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
							. $post['media_section'], $msg
						);
					}
					else
					{
						$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
					}
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_FILE_EXTENSION_WRONG');

					if (isset($post['set']))
					{
						$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
							. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
							. $post['media_section'], $msg
						);
					}
					else
					{
						$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
					}
				}
			}

			if ($file['name'][0] != '')
			{
				$num = count($file['name']);

				for ($i = 0; $i < $num; $i++)
				{
					$filetype = strtolower(JFile::getExt($file['name'][$i]));

					if ($filetype != 'png' && $filetype != 'gif' && $filetype != 'jpeg' && $filetype != 'jpg' && $filetype != 'zip'
						&& $filetype != 'mpeg' && $filetype != 'mp4' && $filetype != 'avi' && $filetype != '3gp'
						&& $filetype != 'swf' && $filetype != 'pdf' && $post['media_type'] != 'download'
						&& $post['media_type'] != 'document')
					{
						$msg = JText::_('COM_REDSHOP_FILE_EXTENSION_WRONG');

						if (isset($post['set']))
						{
							$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
								. $post['media_section'], $msg
							);
						}
						else
						{
							$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
						}
					}
					elseif ($post['media_section'] == '0')
					{
						$msg = JText::_('COM_REDSHOP_SELECT_MEDIA_SECTION_FIRST');

						if (isset($post['set']))
						{
							$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
								. $post['media_section'], $msg
							);
						}
						else
						{
							$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
						}
					}
					elseif ($post['bulk'] != 'yes' && $post['bulk'] != 'no')
					{
						$msg = JText::_('COM_REDSHOP_PLEASE_SELECT_BULK_OPTION');

						if (isset($post['set']))
						{
							$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
								. $post['media_section'], $msg
							);
						}
						else
						{
							$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
						}
					}

					elseif ($post['bulk'] == 'no' && $filetype == 'zip' && $post['media_type'] != 'download')
					{
						$msg = JText::_('COM_REDSHOP_YOU_HAVE_SELECTED_NO_OPTION');

						if (isset($post['set']))
						{
							$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
								. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
								. $post['media_section'], $msg
							);
						}
						else
						{
							$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
						}
					}
					else
					{
						$src = $file['tmp_name'][$i];

						$file['name'][$i] = str_replace(" ", "_", $file['name'][$i]);

						// Download product changes
						if ($post['media_type'] == 'download')
						{
							$post['media_name'] = $product_download_root . time() . "_" . str_replace(" ", "_", $file['name'][$i]);
							$dest = $post['media_name'];
						}
						else
						{
							$post['media_name'] = time() . "_" . $file['name'][$i];
							$dest = JPATH_ROOT . '/components/' . $option . '/assets/' . $post['media_type'] . '/'
								. $post['media_section'] . '/' . time() . '_' . $file['name'][$i];
						}

						$post['media_mimetype'] = $file['type'][$i];
						$file_upload = JFile::upload($src, $dest);

						if ($file_upload == 1 && $row = $model->store($post))
						{
							$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

							if (isset($post['set']) && $post['media_section'] != 'manufacturer')
							{
								$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id='
									. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
									. $post['media_section'], $msg
								);
							}

							elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
							{
								$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
								<script language="javascript" type="text/javascript">
									window.parent.document.location = '<?php echo $link; ?>';
								</script><?php
							}
							else
							{
								$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
							}
						}
						else
						{
							$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MEDIA_DETAIL');

							if (isset($post['set']))
							{
								$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media_detail&section_id='
									. $post['section_id'] . '&showbuttons=1&section_name=' . $post['section_name'] . '&media_section='
									. $post['media_section'], $msg
								);
							}
							else
							{
								$this->setRedirect('index.php?option=' . $option . '&view=media_detail', $msg);
							}
						}
					}
				}
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
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$section_id = JRequest::getVar('section_id');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_DELETED_SUCCESSFULLY');

		if (isset($section_id))
		{
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id='
				. $section_id . '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
			<script language="javascript" type="text/javascript">
				window.parent.document.location = '<?php echo $link; ?>';
			</script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
		}
	}

	/**
	 * Publish Media
	 *
	 * @return  [type]  [description]
	 */
	public function publish()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$section_id = JRequest::getVar('section_id');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_PUBLISHED_SUCCESSFULLY');

		if (isset($section_id))
		{
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}

		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
			<script language="javascript" type="text/javascript">
				window.parent.document.location = '<?php echo $link; ?>';
			</script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
		}
	}

	/**
	 * Select Media as Default
	 *
	 * @return  [type]  [description]
	 */
	public function defaultmedia()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$section_id = JRequest::getVar('section_id');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_MAKE_PRIMARY_MEDIA'));
		}

		$model = $this->getModel('media_detail');

		if (isset($cid[0]) && $cid[0] != 0)
		{
			if (!$model->defaultmedia($cid[0], $section_id, $media_section))
			{
				echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
			}
		}

		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_SAVED');

		if (isset($section_id))
		{
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
			<script language="javascript" type="text/javascript">
				window.parent.document.location = '<?php echo $link; ?>';
			</script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
		}
	}

	/**
	 * Unpublish Media
	 *
	 * @return  [type]  [description]
	 */
	public function unpublish()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$section_id = JRequest::getVar('section_id');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_UNPUBLISHED_SUCCESSFULLY');

		if (isset($section_id))
		{
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
			<script language="javascript" type="text/javascript">
				window.parent.document.location = '<?php echo $link; ?>';
			</script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
		}
	}

	/**
	 * Cancel Media Detail
	 *
	 * @return  [type]  [description]
	 */
	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_MEDIA_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
	}

	/**
	 * Check Media Folder is Writable?
	 *
	 * @param   string   $folder    Folder Name
	 * @param   integer  $relative  folder is in relative directory then 1 else 0
	 *
	 * @return  [type]              [description]
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
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$section_id = JRequest::getVar('section_id');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_ORDERING'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->saveorder($cid, $order))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');

		if (isset($section_id))
		{
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
			<script language="javascript" type="text/javascript">
				window.parent.document.location = '<?php echo $link; ?>';
			</script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
		}
	}

	/**
	 * Set Ordering to plus one up
	 *
	 * @return  [type]  [description]
	 */
	public function orderup()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$section_id = JRequest::getVar('section_id');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_ORDERING'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->orderup())
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');

		if (isset($section_id))
		{
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id=' . $section_id
				. '&showbuttons=1&media_section=' . $media_section, $msg
			);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
			<script language="javascript" type="text/javascript">
				window.parent.document.location = '<?php echo $link; ?>';
			</script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
		}
	}

	/**
	 * Set Ordering Minus one down
	 *
	 * @return  [type]  [description]
	 */
	public function orderdown()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$section_id = JRequest::getVar('section_id');
		$media_section = JRequest::getVar('media_section');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_ORDERING'));
		}

		$model = $this->getModel('media_detail');

		if (!$model->orderdown())
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');

		if (isset($section_id))
		{
			$this->setRedirect('index.php?tmpl=component&option=' . $option . '&view=media&section_id=' . $section_id . '&showbuttons=1&media_section=' . $media_section, $msg);
		}
		elseif (isset($post['set']) && $post['media_section'] == 'manufacturer')
		{
			$link = 'index.php?option=' . $option . '&view=manufacturer';    ?>
	        <script language="javascript" type="text/javascript">
	            window.parent.document.location = '<?php echo $link; ?>';
	        </script><?php
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=media', $msg);
		}
	}
}
