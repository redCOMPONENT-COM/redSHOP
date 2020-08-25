<?php

/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');

class plgRedshop_productAttribute_dependencies extends JPlugin
{
    /**
     * plgRedshop_productAttribute_dependencies constructor.
     *
     * @param          $subject
     * @param   array  $config
     */
    public function __construct(&$subject, $config = array())
    {
        $lang = JFactory::getLanguage();
        $lang->load('plg_redshop_product_attribute_dependencies', JPATH_ADMINISTRATOR);
        $lang->load('com_redshop', JPATH_ADMINISTRATOR);

        parent::__construct($subject, $config);
    }

    /**
     * @throws Exception
     */
    public function onAjaxUpdateDependencies()
    {
        $post = \JFactory::getApplication()->input->post->getArray();
        $level = 0;

        if ($post['targetType'] == 'subproperty') {
            $level = $this->calculateSubPropertyLevel((int) $post['targetId']);
        }

        $post['level'] = $level;

        $this->handleSelectedSession($post);

        $callBackResponse = $this->loadShowConditions((int) $post['prdId']);

        return json_encode($callBackResponse);
    }

    /**
     * @param $post
     */
    public function handleSelectedSession($post)
    {
        $sessionKey = 'session_dependencies';
        $session = \JFactory::getSession();
        $data = $session->get($sessionKey, []);
        $data['selected'] = empty($data['selected']) ? [] : $data['selected'];

        /* Condition to store to session */

        $flag = true;

        for ($i = 0; $i < count($data['selected']); $i++) {
            if (($post['attId'] == $data['selected'][$i]['attId'])
                && ($post['level'] == $data['selected'][$i]['level'])
                && ($post['targetType'] == $data['selected'][$i]['targetType'])
            ) {
                $data['selected'][$i] = $post;

                $flag = false;
                break;
            }
        }

        if ($flag) {
            $data['selected'][] = $post;
        }

        $session->set($sessionKey, $data);
    }

    /**
     * @param $sid
     * @return int
     */
    public function calculateSubPropertyLevel($sid)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        $parent = 999;
        $level = 0;
        $conditionId = $sid;

        while ($parent != 0) {
            $query->clear()
                ->select('*')
                ->from($db->qn('#__redshop_subproperty_xref'))
                ->where($db->qn('subproperty_id') . ' = ' . $db->q($conditionId));

            $res = $db->setQuery($query)->loadObject();

            $parent = $conditionId = $res->parent_subproperty_id;
            $level = $parent == 0 ? $level : $level + 1;
        }

        return $level;
    }

    /**
     * @param $pid
     * @return array
     */
    public function loadShowConditions($pid)
    {
        $session = \JFactory::getSession();
        $data = $session->get('session_dependencies', []);

        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        /* Load attributes */
        $query->clear()
            ->select('*')
            ->from($db->qn('#__redshop_product_attribute'))
            ->where($db->qn('product_id') . ' = ' . $db->q($pid));

        $attributes = $db->setQuery($query)->loadObjectList();
        $aids = [];
        $allData = [];

        foreach ($attributes as $a) {
            $aids[] = $a->attribute_id;

            $temp = new stdClass();
            $temp->type = 'attribute';
            $temp->id = $a->attribute_id;
            $temp->hide = $a->hide;
            $allData[] = $temp;
        }

        $query->clear()
            ->select('*')
            ->from($db->qn('#__redshop_product_attribute_property'))
            ->where($db->qn('attribute_id') . ' IN (' . implode(',', $db->q($aids)) . ')');

        $properties = $db->setQuery($query)->loadObjectList();
        $pids = [];

        foreach ($properties as $p) {
            $pids[] = $p->property_id;

            $temp = new stdClass();
            $temp->type = 'property';
            $temp->id = $p->property_id;
            $temp->hide = $p->hide;
            $allData[] = $temp;
        }

        $query->clear()
            ->select('*')
            ->from($db->qn('#__redshop_product_subattribute_color'))
            ->where($db->qn('subattribute_id') . ' IN (' . implode(',', $db->q($pids)) . ')');

        $subProperties = $db->setQuery($query)->loadObjectList();
        $spids = [];

        foreach ($subProperties as $sp) {
            $spids[] = $sp->subattribute_color_id;

            $temp = new stdClass();
            $temp->type = 'subproperty';
            $temp->id = $sp->subattribute_color_id;
            $temp->hide = $sp->hide;
            $allData[] = $temp;
        }

        $query->clear()
            ->select('*')
            ->from($db->qn('#__redshop_attribute_dependencies'))
            ->where($db->qn('product_id') . ' = ' . $db->q($pid));

        $dependencies = $db->setQuery($query)->loadObjectList();

        //$finalResponse = $session->get('finalDependenciesResponse', []);
        $finalResponse = [];
        usort($data['selected'], 'orderSelected');
        //$data['selected'] = array_reverse($data['selected']);
        $or = [];


        foreach ($allData as $ad) {

            foreach ($dependencies as $c) {
                foreach ($data['selected'] as $d) {
                    if (($d['targetId'] == $ad->id) && ($d['targetType'] == $ad->type)) {
                        $ad->hide = 0;
                        continue;
                    }

                    if (($ad->id == $c->show_id) && ($ad->type == $c->type)) {
                        $ad->hide = 1;

                        if (($c->dependency_id == $d['targetId']) && ($c->dependency_type == $d["targetType"])) {
                            //var_dump($ad->id);
                            //var_dump('c->dependency_id: ' .  $c->dependency_id);
                            //var_dump('selected id:' . $d['targetId']);
                            //var_dump($c->dependency_type);
                            //var_dump($d["targetType"]);
                            $ad->hide = 0;
                            $t = clone $ad;
                            $t->targetId = $d['targetId'];
                            $t->targetType = $d['targetType'];
                            $or[] = $t;
                            continue;
                        }
                    }
                }
            }

            $finalResponse[] = $ad;
        }

        for ($i = 0; $i < count($finalResponse); $i++) {
            foreach ($or as $o) {
                foreach ($data['selected'] as $d) {
                    if (($finalResponse[$i]->id == $o->id)
                        && ($finalResponse[$i]->type == $o->type)
                        && ($o->targetId == $d['targetId'])
                        && ($o->targetType == $d['targetType'])
                    ) {
                        $finalResponse[$i] = clone $o;
                    }
                }
            }
        }

        //$session->set('finalDependenciesResponse', $finalResponse);

        return $finalResponse;
    }

    public function orderSelected($a, $b)
    {
        return strcmp($a['targetType'], $b['targetType']);
    }

    /**
     * @since 1.0
     */
    public function onAjaxCreateDependency()
    {
        $post = \JFactory::getApplication()->input->post->getArray();
        $data = $this->refineData((int) $post['productId']);

        echo RedshopLayoutHelper::render(
            'attribute.dependencies',
            array(
                'data' => $data,
                'pref' => $post['pref'],
                'selected' => null
            )
        );
    }

    /**
     * @param $productId
     *
     * @return array|bool
     */
    public function getAttributes($productId)
    {
        if ($productId != 0) {
            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('*')
                ->from($db->qn('#__redshop_product_attribute'))
                ->where($db->qn('product_id') . ' = ' . $db->q($productId))
                ->order($db->qn('ordering') . ' ASC');

            //$query = 'SELECT * FROM ' . $this->table_prefix . 'product_attribute WHERE product_id="' . $this->id . '" ORDER BY ordering ASC';

            $db->setQuery($query);
            $attr           = $db->loadObjectlist();
            $attribute_data = array();

            for ($i = 0, $in = count($attr); $i < $in; $i++) {
                $query->clear()
                    ->select('*')
                    ->from($db->qn('#__redshop_product_attribute_property'))
                    ->where($db->qn('attribute_id') . ' = ' . $db->q($attr[$i]->attribute_id))
                    ->order($db->qn('ordering') . ' ASC');
                //$query = 'SELECT * FROM ' . $this->table_prefix . 'product_attribute_property WHERE attribute_id ="'
                //    . $attr[$i]->attribute_id . '" ORDER BY ordering ASC';

                $db->setQuery($query);
                $prop                     = $db->loadObjectlist();
                $attribute_id             = $attr[$i]->attribute_id;
                $attribute_name           = $attr[$i]->attribute_name;
                $attribute_description    = $attr[$i]->attribute_description;
                $attribute_required       = $attr[$i]->attribute_required;
                $allow_multiple_selection = $attr[$i]->allow_multiple_selection;
                $hide_attribute_price     = $attr[$i]->hide_attribute_price;
                $ordering                 = $attr[$i]->ordering;
                $attribute_published      = $attr[$i]->attribute_published;
                $display_type             = $attr[$i]->display_type;

                /* KON-1087 TODO: Move to plugin */

                for ($j = 0, $jn = count($prop); $j < $jn; $j++) {
                    $query->clear()->select('ac.*, sbx.*')
                        ->from($db->qn('#__redshop_product_subattribute_color', 'ac'))
                        ->where($db->qn('ac.subattribute_id') . ' = ' . $db->q($prop[$j]->property_id))
                        ->order($db->qn('ac.ordering') . ' ASC');

                    /* Add up xref will be moved to plugin */

                    $query->leftJoin($db->qn('#__redshop_subproperty_xref', 'sbx')
                        . ' ON ' . $db->qn('ac.subattribute_color_id')
                        . ' = '
                        . $db->qn('sbx.subproperty_id'));


                    $db->setQuery($query);
                    $subProp            = $db->loadObjectlist();
                    $prop[$j]->subvalue = $subProp;
                }

                /* END KON-1087 */

                $attribute_data[] = array(
                    'attribute_id' => $attribute_id, 'attribute_name' => $attribute_name,
                    'attribute_description' => $attribute_description,
                    'attribute_required' => $attribute_required, 'ordering' => $ordering, 'property' => $prop,
                    'allow_multiple_selection' => $allow_multiple_selection, 'hide_attribute_price' => $hide_attribute_price,
                    'attribute_published' => $attribute_published, 'display_type' => $display_type,
                    'attribute_set_id' => $attr[$i]->attribute_set_id
                );
            }

            return $attribute_data;
        }

        return false;
    }

    /**
     * @param $productId
     *
     * @return stdClass
     */
    public function refineData($productId)
    {
        $attributes = $this->getAttributes($productId);

        $attributeSL = [];
        $propertySL = [];
        $subpropertySL = [];

        $firstElement = new stdClass();
        $firstElement->text = '--- Select ---';
        $firstElement->value = 0;

        $attributeSL[] = clone $firstElement;
        $propertySL[] = clone $firstElement;
        $subpropertySL[] = clone $firstElement;

        foreach ($attributes as $key => $attribute) {
            $item = new stdClass();
            $item->text = $attribute['attribute_name'];
            $item->value = $attribute['attribute_id'];
            $attributeSL[] = clone $item;

            foreach ($attribute['property'] as $property) {
                $item = new stdClass();
                $item->text = $property->property_name;
                $item->value = $property->property_id;
                $propertySL[] = clone $item;

                foreach ($property->subvalue as $subproperty) {
                    $item = new stdClass();
                    $item->text = $subproperty->subattribute_color_name;
                    $item->value = $subproperty->subattribute_color_id;
                    $subpropertySL[] = clone $item;
                }
            }
        }

        $data = new stdClass();
        $data->attributeSL = $attributeSL;
        $data->propertySL = $propertySL;
        $data->subpropertySL = $subpropertySL;

        return $data;
    }

    /**
     * @throws Exception
     * @since 1.0
     */
    public function onAjaxGetLayoutAttribute()
    {
        $post = \JFactory::getApplication()->input->post->getArray();

        echo RedshopLayoutHelper::render(
            'attribute.attribute',
            array(
                'data' => $post,
            )
        );
    }

    /**
     * @throws Exception
     * @since 1.0
     */
    public function onAjaxSaveElement()
    {
        JSession::checkToken() or die('Invalid Token');

        $post = \JFactory::getApplication()->input->post->getArray();
        $decode = (array) json_decode($post['encodeValues']);
        $dependencies = [];

        switch ($post['type']) {
            case 'attribute':
                $this->saveAttribute($post, $decode);
                break;
            case 'property':
                $this->saveProperty($post, $decode);
                break;
            case 'subproperty':
                $this->saveSubProperty($post, $decode);
                break;
            default:
                break;
        }
    }

    /**
     * @param $post
     * @param $decode
     * @throws Exception
     * @since 1.0
     */
    public function saveSubProperty($post, $decode)
    {
        $aid = 0;
        $subProperty = new stdClass();

        $image = '';
        $dependencies = [];

        for ($i = 0; $i < $decode['length']; $i++) {
            $name = $decode[$i]->name ?? '';
            $value = $decode[$i]->value ?? '';
            if (($name == 'subattribute_color_id') && ($value > 0)) {
                $aid = $value;
                $subProperty->$name = $value;
            } elseif ($name == 'dependency') {
                $dependencies[] = json_decode($value);
            } elseif ($name == 'subattribute_color_hide') {
                $subProperty->hide = $value;
            } elseif (($name != 'subattribute_color_id')
                && ($name != 'dropzone[subattribute_color_image][]')
                && ($name != 'subattribute_color_hide')
                && ($name != '')
            ) {
                $subProperty->$name = $value;
            } elseif ($name == 'dropzone[subattribute_color_image][]') {
                $image = $value;
            }
        }

        $subProperty->dependency = base64_encode(json_encode($dependencies));

        if ($image) {
            $src = JPATH_SITE . '/' . $image;
            $imageFileName = basename($image);

            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $imageFileName;

            if (\JFile::exists($src)) {
                if (copy($src, $dest)) {
                    $subProperty->subattribute_color_image = $imageFileName;
                }
            }
        }

        $result = false;

        if ($aid == 0) {
            $result = \JFactory::getDbo()->insertObject('#__redshop_product_subattribute_color', $subProperty);
            $insertId = \JFactory::getDbo()->insertid();
            $subProperty->subattribute_color_id = (string) $insertId;
            $subProperty->queryType = 'insert';
        } elseif ($aid > 0) {
            $result = \JFactory::getDbo()->updateObject('#__redshop_product_subattribute_color', $subProperty, 'subattribute_color_id');
            $subProperty->queryType = 'update';
        }

        $subProperty->result = $result;

        // Smart optimize serve for frontend;
        $this->saveShowTarget('subproperty', $subProperty->subattribute_color_id, $dependencies);

        $subProperty->attribute_id = $decode[0]->value;

        echo json_encode($subProperty);

        \JFactory::getApplication()->close();
    }

    /**
     * @param $post
     * @param $decode
     * @throws Exception
     */
    public function saveProperty($post, $decode)
    {
        $aid = 0;
        $property = new stdClass();
        $dependencies = [];

        $image = '';

        for ($i = 0; $i < $decode['length']; $i++) {
            $name = $decode[$i]->name ?? '';
            $value = $decode[$i]->value ?? '';
            if (($name == 'property_id') && ($value > 0)) {
                $aid = $value;
                $property->$name = $value;
            } elseif ($name == 'property_setdefault_selected') {
                $property->setdefault_selected = $value;
            } elseif ($name == 'property_setrequire_selected') {
                $property->setrequire_selected = $value;
            } elseif ($name == 'property_hide') {
                $property->hide = $value;
            } elseif ($name == 'dependency') {
                $dependencies[] = json_decode($value);
            } elseif (($name != 'property_id')
                && ($name != 'dropzone[property_image][]')
                && ($name != 'property_hide')
                && ($name != '')
            ) {
                $property->$name = $value;
            } elseif ($name == 'dropzone[property_image][]') {
                $image = $value;
            }
        }

        $property->dependency = base64_encode(json_encode($dependencies));

        if ($image) {
            $src = JPATH_SITE . '/' . $image;
            $imageFileName = basename($image);

            $dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $imageFileName;

            if (\JFile::exists($src)) {
                if (copy($src, $dest)) {
                    $property->property_image = $imageFileName;
                }
            }
        }

        $result = false;

        if ($aid == 0) {
            $result = \JFactory::getDbo()->insertObject('#__redshop_product_attribute_property', $property);
            $insertId = \JFactory::getDbo()->insertid();
            $property->property_id = (string) $insertId;
            $property->queryType = 'insert';
        } elseif ($aid > 0) {
            $result = \JFactory::getDbo()->updateObject('#__redshop_product_attribute_property', $property, 'property_id');
            $property->queryType = 'update';
        }

        $property->result = $result;

        // Smart optimize serve for frontend;
        $this->saveShowTarget('property', $property->property_id, $dependencies);

        echo json_encode($property);

        \JFactory::getApplication()->input->post->getArray();
    }

    /**
     * @throws Exception
     * @since  1.0
     */
    public function saveAttribute($post, $decode)
    {
        $aid = 0;
        $attribute = new stdClass();
        $attribute->product_id = (int) $post['productId'];
        $attribute->attribute_set_id = (int) $post['attributeSetId'];

        $dependencies = [];

        for ($i = 0; $i < $decode['length']; $i++) {
            $name = $decode[$i]->name ?? '';
            $value = $decode[$i]->value ?? '';

            if (($name == 'attribute_id') && ($value > 0)) {
                $aid = $value;
                $attribute->$name = $value;
            } elseif ($name == 'attribute_hide') {
                $attribute->hide = $value;
            } elseif ($name == 'dependency') {
                $dependencies[] = json_decode($value);
            } elseif ($name != 'attribute_id' && $name != "") {
                $attribute->$name = $value;
            }
        }

        $attribute->dependency = base64_encode(json_encode($dependencies));

        $result = false;

        if ($aid == 0) {
            $result = \JFactory::getDbo()->insertObject('#__redshop_product_attribute', $attribute);
            $insertId = \JFactory::getDbo()->insertid();
            $attribute->attribute_id = (string) $insertId;
            $attribute->queryType = 'insert';
        } elseif ($aid > 0) {
            $result = \JFactory::getDbo()->updateObject('#__redshop_product_attribute', $attribute, 'attribute_id');
            $attribute->queryType = 'update';
        }

        $attribute->result = $result;

        // Smart optimize serve for frontend;
        $this->saveShowTarget('attribute', $attribute->attribute_id, $dependencies);

        echo json_encode($attribute);

        \JFactory::getApplication()->close();
    }

    /**
     * @param $showType
     * @param $showId
     * @param $dependency
     */
    public function saveShowTarget($showType, $showId, $dependency)
    {
        $data = new stdClass();
        $data->type = $showType;
        $data->value = $showId;

        for ($i = 0; $i < count($dependency); $i++) {
            $type = '';
            $id = '';

            foreach ((array) $dependency[$i] as $k => $v) {
                if (empty($v->value)) {
                    break;
                } else {
                    $type = $k;
                    $id = $v->value;
                }
            }

            if (!empty($type) && (!empty($id))) {
                switch ($type) {
                    case 'subproperty':
                        $this->processingShowTarget(
                            '#__redshop_product_subattribute_color',
                            'subattribute_color_id',
                            $id,
                            $data
                        );
                        break;
                    case 'property':
                        $this->processingShowTarget('#__redshop_product_attribute_property', 'property_id', $id, $data);
                        break;
                    case 'attribute':
                    default:
                        $this->processingShowTarget('#__redshop_product_attribute', 'attribute_id', $id, $data);
                        break;
                }
            }
        }
    }

    /**
     * @param $table
     * @param $key
     * @param $id
     * @param $data
     */
    public function processingShowTarget($table, $key, $id, $data)
    {
        $object = new stdClass();
        $object->$key = $id;
        $object->show_target = '';

        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->qn('show_target'))
            ->from($db->qn($table))
            ->where($db->qn($key)
                . ' = ' . $db->q($id));

        $result = $db->setQuery($query)->loadObject();

        $showTarget = $result->show_target;

        if (!empty($showTarget)) {
            $showTarget = json_decode(base64_decode($showTarget));
        } else {
            $showTarget = [];
        }

        $flag = true;

        if (count($showTarget)) {
            foreach ($showTarget as $s) {
                if (($s->type == $data->type) && ($s->value == $data->value)) {
                    $flag = false;
                }
            }
        }

        if ($flag) {
            $showTarget[] = $data;
        }

        $showTarget = base64_encode(json_encode($showTarget));
        $object->show_target = $showTarget;

        \JFactory::getDbo()->updateObject($table, $object, $key);
    }

    /**
     * @throws Exception
     */
    public function onAjaxDeleteElement()
    {
        JSession::checkToken() or die('Invalid Token');

        $post = \JFactory::getApplication()->input->post->getArray();

        switch ($post['type']) {
            case 'subproperty':
                $this->deleteSubProperty($post);
                break;
            case 'property':
                $this->deleteProperty($post);
                break;
            case 'attribute':
            default:
                $this->deleteAttribute($post);
                break;
        };
    }

    /**
     * @param $post
     */
    public function deleteSubProperty($post)
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true);
        $conditions = [
            $db->qn('subattribute_color_id') . ' = ' . $db->q($post['id'])
        ];

        $query->delete($db->qn('#__redshop_product_subattribute_color'))
            ->where($conditions);

        $db->setQuery($query);
        echo $db->execute();

        \JFactory::getApplication()->close();
    }

    /**
     * @param $post
     * @throws Exception
     */
    public function deleteProperty($post)
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true);
        $conditions = [
            $db->qn('property_id') . ' = ' . $db->q($post['id'])
        ];

        $query->delete($db->qn('#__redshop_product_attribute_property'))
            ->where($conditions);

        $db->setQuery($query);
        echo $db->execute();

        \JFactory::getApplication()->close();
    }

    /**
     * @param $post
     * @throws Exception
     */
    public function deleteAttribute($post)
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true);
        $conditions = [
            $db->qn('attribute_id') . ' = ' . $db->q($post['id'])
        ];

        $query->delete($db->qn('#__redshop_product_attribute'))
            ->where($conditions);

        $db->setQuery($query);
        echo $db->execute();

        \JFactory::getApplication()->close();
    }
}
