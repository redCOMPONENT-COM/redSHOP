<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerProduct_attribute_price extends RedshopController
{
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    public function listing()
    {
        $this->input->set('layout', 'listing');
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    /**
     * @throws Exception
     */
    public function saveprice()
    {
        $db = \JFactory::getDbo();

        $sectionId           = $this->input->get('section_id');
        $section             = $this->input->get('section');
        $cid                 = $this->input->get('cid');
        $shopperGroupId      = $this->input->post->get('shopper_group_id', array(), 'array');
        $prices              = $this->input->post->get('price', array(), 'array');
        $pricesQuantityStart = $this->input->post->get('price_quantity_start', array(), 'array');
        $pricesQuantityEnd   = $this->input->post->get('price_quantity_end', array(), 'array');
        $pricesId            = $this->input->post->get('price_id', array(), 'array');
        $query               = $db->getQuery(true);

        for ($i = 0, $in = count($prices); $i < $in; $i++) {
            $query->clear()
                ->select('COUNT(*)')
                ->from($db->qn('#__redshop_product_attribute_price'))
                ->where($db->qn('section_id') . ' = ' . $db->q((int)$sectionId))
                ->where($db->qn('section') . ' = ' . $db->q($section))
                ->where($db->qn('price_id') . ' = ' . $db->q($pricesId[$i]))
                ->where($db->qn('shopper_group_id') . ' = ' . $db->q($shopperGroupId[$i]));

            $db->setQuery($query);

            if ($db->loadResult()) {
                $query->clear()
                    ->select($db->qn('price_id'))
                    ->from($db->qn('#__redshop_product_attribute_price'))
                    ->where($db->qn('shopper_group_id') . ' = ' . $db->q($shopperGroupId[$i]))
                    ->where($db->qn('section_id') . ' = ' . $db->q($sectionId))
                    ->where($db->qn('price_quantity_end') . ' >= ' . $db->q($pricesQuantityStart[$i]))
                    ->where($db->qn('price_quantity_start') . ' <= ' . $db->qn($pricesQuantityStart[$i]));

                $db->setQuery($query);
                $xid = intval($db->loadResult());

                if ($xid && $xid != intval($pricesId[$i])) {
                    echo $xid;
                    $this->_error = JText::sprintf('WARNNAMETRYAGAIN', JText::_('COM_REDSHOP_PRICE_ALREADY_EXISTS'));
                }

                if ($prices[$i] != '') {
                    $query->clear()
                        ->update($db->qn('#__redshop_product_attribute_price'))
                        ->set(
                            [
                                $db->qn('product_price') . ' = ' . $db->q($prices[$i]),
                                $db->qn('price_quantity_start') . ' = ' . $db->q($pricesQuantityStart[$i]),
                                $db->qn('price_quantity_end') . ' = ' . $db->q($pricesQuantityEnd[$i])
                            ]
                        )
                        ->where($db->qn('section_id') . ' = ' . $db->q($sectionId))
                        ->where($db->qn('section') . ' = ' . $db->q($section))
                        ->where($db->qn('price_id') . ' = ' . $db->q($pricesId[$i]))
                        ->where($db->qn('shopper_group_id') . ' = ' . $db->q($shopperGroupId[$i]));
                } else {
                    $query->clear()
                        ->delete($db->qn('$__redshop_product_attribute_price'))
                        ->where($db->qn('section_id') . ' = ' . $db->q($sectionId))
                        ->where($db->qn('section') . ' = ' . $db->q($section))
                        ->where($db->qn('price_id') . ' = ' . $db->q($pricesId[$i]))
                        ->where($db->qn('shopper_group_id') . ' = ' . $db->q($shopperGroupId[$i]));
                }
            } elseif ($prices[$i] != '') {
                $columns = $db->qn(
                    [
                        'product_price',
                        'price_quantity_start',
                        'price_quantity_end',
                        'section_id',
                        'price_quantity_end',
                        'shopper_group_id'
                    ]
                );
                $values  = $db->q(
                    [
                        $prices[$i],
                        $pricesQuantityStart[$i],
                        $pricesQuantityEnd[$i],
                        $sectionId,
                        $section,
                        $shopperGroupId[$i]
                    ]
                );

                $query->clear()
                    ->insert($db->qn('#__redshop_product_attribute_price'))
                    ->columns($columns)
                    ->values(implode(',', $values));
            }

            \Redshop\DB\Tool::safeExecute($db, $query);
        }

        $link = "index.php?tmpl=component&option=com_redshop&view=product_attribute_price&section_id=" . $sectionId
            . "&cid=" . $cid . "&section=" . $section;
        $this->setRedirect($link);
    }
}
