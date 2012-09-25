<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'media.php');

class mediaModelmedia extends JModelLegacy
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    public $_context = null;

    public function __construct()
    {
        parent::__construct();

        global $mainframe;

        $this->_table_prefix = '#__redshop_';
        $this->_context      = 'media_id';
        $limit               = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart          = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

        $media_section = $mainframe->getUserStateFromRequest($this->_context . 'media_section', 'media_section', 0);
        $media_type    = $mainframe->getUserStateFromRequest($this->_context . 'media_type', 'media_type', 0);
        $limitstart    = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('media_section', $media_section);
        $this->setState('media_type', $media_type);
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }

    public function getTotal()
    {
        if (empty($this->_total))
        {
            $query        = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    public function getPagination()
    {
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_pagination;
    }

    public function _buildQuery()
    {
        $where         = "";
        $media_section = $this->getState('media_section');
        $media_type    = $this->getState('media_type');
        $section_id    = JRequest::getVar('section_id');

        if ($media_section)
        {
            $where .= "AND media_section = '" . $media_section . "' ";
        }
        if ($section_id)
        {
            $where .= "AND section_id = '" . $section_id . "' ";
        }
        if ($media_type)
        {
            $where .= "AND media_type='" . $media_type . "' ";
        }
        $orderby = $this->_buildContentOrderBy();

        $query = 'SELECT distinct(m.media_id),m.* FROM ' . $this->_table_prefix . 'media AS m ' . 'WHERE 1=1 ' . $where . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        global $mainframe;

        $filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'ordering');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');
        $orderby          = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;
        return $orderby;
    }

    // Media bank changes start
    public function getState($property = null, $default = null)
    {
        static $set;
        if (!$set)
        {
            $folder = JRequest::getVar('folder', '', '', 'path');
            $this->setState('folder', $folder);

            $parent = str_replace("\\", "/", dirname($folder));
            $parent = ($parent == '.') ? null : $parent;
            $this->setState('parent', $parent);
            $set = true;
        }
        return parent::getState($property, $default);
    }

    public function getImages()
    {
        $list = $this->getList();
        return $list['images'];
    }

    public function getFolders()
    {
        $list = $this->getList();
        return $list['folders'];
    }

    public function getDocuments()
    {
        $list = $this->getList();
        return $list['docs'];
    }

    /**
     * Build imagelist
     *
     * @param string $listFolder The image directory to display
     *
     * @since 1.5
     */
    public function getList()
    {
        static $list;

        // Only process the list once per request
        if (is_array($list))
        {
            return $list;
        }

        // Get current path from request
        $current = $this->getState('folder');

        // If undefined, set to empty
        if ($current == 'undefined')
        {
            $current = '';
        }

        $fdownload = JRequest :: getInt('fdownload'); // for retriving files from download folder.
        if ($fdownload != 1)
        {
            // Initialize variables
            if (strlen($current) > 0)
            {
                $basePath = REDSHOP_FRONT_IMAGES_RELPATH . $current;
            }
            else
            {
                $basePath = REDSHOP_FRONT_IMAGES_RELPATH;
            }
            $mediaBase = str_replace(DS, '/', REDSHOP_FRONT_IMAGES_RELPATH);
        }
        else
        {
            if (strlen($current) > 0)
            {
                $basePath = PRODUCT_DOWNLOAD_ROOT . DS . $current;
            }
            else
            {
                $basePath = PRODUCT_DOWNLOAD_ROOT;
            }
            $mediaBase = str_replace(DS, '/', PRODUCT_DOWNLOAD_ROOT . '/');
        }
        $images  = array();
        $folders = array();
        $docs    = array();

        // Get the list of files and folders from the given folder
        $fileList   = JFolder::files($basePath);
        $folderList = JFolder::folders($basePath);

        // Iterate over the files if they exist
        if ($fileList !== false)
        {
            foreach ($fileList as $file)
            {
                if (file_exists($basePath . DS . $file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html')
                {
                    $tmp                = new JObject();
                    $tmp->name          = $file;
                    $tmp->path          = str_replace(DS, '/', JPath::clean($basePath . DS . $file));
                    $tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
                    $tmp->size          = filesize($tmp->path);

                    $mediaHelper = new redMediahelper;

                    $ext = strtolower(JFile::getExt($file));
                    switch ($ext)
                    {
                        // Image
                        case 'jpg':
                        case 'png':
                        case 'gif':
                        case 'xcf':
                        case 'odg':
                        case 'bmp':
                        case 'jpeg':
                            $info        = @getimagesize($tmp->path);
                            $tmp->width  = @$info[0];
                            $tmp->height = @$info[1];
                            $tmp->type   = @$info[2];
                            $tmp->mime   = @$info['mime'];

                            $filesize = $mediaHelper->parseSize($tmp->size);

                            if (($info[0] > 60) || ($info[1] > 60))
                            {
                                $dimensions     = $mediaHelper->imageResize($info[0], $info[1], 60);
                                $tmp->width_60  = $dimensions[0];
                                $tmp->height_60 = $dimensions[1];
                            }
                            else
                            {
                                $tmp->width_60  = $tmp->width;
                                $tmp->height_60 = $tmp->height;
                            }

                            if (($info[0] > 16) || ($info[1] > 16))
                            {
                                $dimensions     = $mediaHelper->imageResize($info[0], $info[1], 16);
                                $tmp->width_16  = $dimensions[0];
                                $tmp->height_16 = $dimensions[1];
                            }
                            else
                            {
                                $tmp->width_16  = $tmp->width;
                                $tmp->height_16 = $tmp->height;
                            }
                            $images[] = $tmp;
                            break;
                        // Non-image document
                        default:
                            $iconfile_32 = JPATH_ADMINISTRATOR . DS . "components" . DS . "com_redshop" . DS . "assets" . DS . "images" . DS . "mime-icon-32" . DS . $ext . ".png";
                            if (file_exists($iconfile_32))
                            {
                                $tmp->icon_32 = "components" . DS . "com_redshop" . DS . "assets" . DS . "images" . DS . "mime-icon-32/" . $ext . ".png";
                            }
                            else
                            {
                                $tmp->icon_32 = "components" . DS . "com_redshop" . DS . "assets" . DS . "images" . DS . "con_info.png";
                            }
                            $iconfile_16 = JPATH_ADMINISTRATOR . DS . "components" . DS . "com_redshop" . DS . "assets" . DS . "images" . DS . "mime-icon-16" . DS . $ext . ".png";
                            if (file_exists($iconfile_16))
                            {
                                $tmp->icon_16 = "components" . DS . "com_redshop" . DS . "assets" . DS . "images" . DS . "mime-icon-16/" . $ext . ".png";
                            }
                            else
                            {
                                $tmp->icon_16 = "components" . DS . "com_redshop" . DS . "assets" . DS . "images" . DS . "con_info.png";
                            }
                            $docs[] = $tmp;
                            break;
                    }
                }
            }
        }

        // Iterate over the folders if they exist
        if ($folderList !== false)
        {
            foreach ($folderList as $folder)
            {
                $tmp                = new JObject();
                $tmp->name          = basename($folder);
                $tmp->path          = str_replace(DS, '/', JPath::clean($basePath . DS . $folder));
                $tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
                $count              = $mediaHelper->countFiles($tmp->path);
                $tmp->files         = $count[0];
                $tmp->folders       = $count[1];

                $folders[] = $tmp;
            }
        }

        $list = array('folders' => $folders, 'docs' => $docs, 'images' => $images);

        return $list;
    }

    public function store($data)
    {
        $row = $this->getTable('media_download');
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

    public function getAdditionalFiles($media_id)
    {
        $query = "SELECT * FROM `" . $this->_table_prefix . "media_download` " . "WHERE `media_id`='" . $media_id . "' ";
        return $this->_getList($query);
    }

    public function deleteAddtionalFiles($fileId)
    {
        $query = "SELECT name FROM `" . $this->_table_prefix . "media_download` " . "WHERE `id`='" . $fileId . "' ";
        $this->_db->setQuery($query);
        $filename = $this->_db->loadResult();
        $path     = JPATH_ROOT . DS . 'components/com_redshop/assets/download/product/' . $filename;
        if (is_file($path))
        {
            unlink($path);
        }
        $query = "DELETE FROM `" . $this->_table_prefix . "media_download` WHERE `id`='" . $fileId . "' ";
        $this->_db->setQuery($query);
        if (!$this->_db->Query())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    public function saveorder($cid = array(), $order)
    {

        $row        = $this->getTable('media_detail');
        $order      = JRequest::getVar('order', array(0), 'post', 'array');
        $conditions = array();
        //$groupings = array();

        // update ordering values
        for ($i = 0; $i < count($cid); $i++)
        {
            $row->load((int)$cid[$i]);
            // track categories
            //$groupings[] = $row->mid;

            if ($row->ordering != $order[$i])
            {
                $row->ordering = $order[$i];
                if (!$row->store())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                // remember to updateOrder this group
                $condition = 'section_id = ' . (int)$row->section_id . ' AND media_section = "' . $row->media_section . '"';
                $found     = false;
                foreach ($conditions as $cond)
                {
                    if ($cond[1] == $condition)
                    {
                        $found = true;
                        break;
                    }
                }
                if (!$found)
                {
                    $conditions[] = array($row->media_id, $condition);
                }
            }
        }
        // execute updateOrder for each group
        foreach ($conditions as $cond)
        {
            $row->load($cond[0]);
            $row->reorder($cond[1]);
        }
    }
}

