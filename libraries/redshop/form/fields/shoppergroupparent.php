<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

JFormHelper::loadFieldClass('list');

/**
 * @package        RedSHOP.Backend
 * @subpackage     Element
 * @since          __DEPLOY_VERSION__
 */
class JFormFieldShopperGroupParent extends JFormFieldList
{
    /**
     * Element name
     *
     * @var  string
     */
    protected $type = 'shoppergroupparent';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function getOptions()
    {
        $options = ['-Top-'];
        $options = $this->getListTree(0, 1, $options);

        return array_merge(parent::getOptions(), $options);
    }

    /**
     * Method to get the field input markup for a generic list.
     * Use the multiple attribute to enable multiselect.
     *
     * @return  string  The field input markup.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getInput()
    {
        $options = $this->getOptions();

        $attr = '';
        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="' . (string)$this->element['class'] . '"' : '';
        $attr .= $this->element['size'] ? ' size="' . (int)$this->element['size'] . '"' : '';

        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="' . (string)$this->element['onchange'] . '"' : '';

        return JHTML::_(
            'select.genericlist',
            $options,
            $this->name,
            trim($attr),
            'value',
            'text',
            $this->value,
            $this->id
        );
    }

    /**
     * @param int   $id
     * @param int   $level
     * @param mixed $options
     *
     * @return mixed
     * @throws \Exception
     */
    public function getListTree($id, $level, $options)
    {
        $db             = JFactory::getDbo();
        $input          = JFactory::getApplication()->input->get;
        $view           = $input->get('view');
        $shopperGroupId = $input->get('id', 0);
        $query          = $db->getQuery(true);

        $query->select('id as value, name as text, parent_id')
            ->from($db->qn('#__redshop_shopper_group'))
            ->where($db->qn('parent_id') . ' = ' . $id);

        if ($shopperGroupId && $view == 'shoppergroup') {
            $query->where($db->qn('id') . ' != ' . $shopperGroupId);
        }

        $groups = $db->setQuery($query)->loadObjectList();

        if (empty($groups)) {
            return $options;
        }

        foreach ($groups as $group) {
            $options[] = (object)[
                'value' => $group->value,
                'text'  => str_repeat('- ', $level) . $group->text
            ];

            $options = self::getListTree($group->value, $level + 1, $options);
        }

        return $options;
    }
}
