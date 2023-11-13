<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$app        = Factory::getApplication();
$model      = $this->getModel('product');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$ordering   = ($this->lists['order'] == 'x.ordering');
$allowOrder = ($listOrder == 'x.ordering' && strtolower($listDirn) == 'asc');

if ($allowOrder) {
    $saveOrderingUrl = 'index.php?option=com_redshop&task=product.saveOrderAjax';
    HtmlHelper::_('redshopsortable.sortable', 'adminForm', 'adminForm', 'asc', $saveOrderingUrl);
}

$categoryId = $this->state->get('category_id', 0);
$user       = Factory::getUser();
$userId     = (int) $user->id;

HtmlHelper::_('redshopjquery.framework');

?>
<script language="javascript" type="text/javascript">
    Joomla.submitform = submitform = Joomla.submitbutton = submitbutton = function (pressbutton) {
        var form = document.adminForm;

        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if ((pressbutton == 'publish') || (pressbutton == 'unpublish')
            || (pressbutton == 'remove') || (pressbutton == 'copy') || (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
            form.view.value = "product_detail";
        }
        if ((pressbutton == 'assignCategory') || (pressbutton == 'removeCategory')) {
            form.view.value = "product_category";
        }

        if (pressbutton == 'remove') {
            if (confirm("<?php echo Text::_('COM_REDSHOP_PRODUCT_DELETE_CONFIRM') ?> ") != true) {
                form.view.value = 'product';
                form.task.value = '';
                return false;
            }
        }


        try {
            form.onsubmit();
        } catch (e) {
        }

        form.submit();
    }

    function AssignTemplate() {
        var form = document.adminForm;
        if (form.boxchecked.value == 0) {
            jQuery('#product_template').val(0).trigger("liszt:updated");
            alert('<?php echo Text::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT'); ?>');
        } else {
            form.task.value = 'assignTemplate';
            if (confirm("<?php echo Text::_('COM_REDSHOP_SURE_WANT_TO_ASSIGN_TEMPLATE'); ?> ")) {
                form.submit();
            } else {
                jQuery('#product_template').val(0).trigger("liszt:updated");
            }
        }

    }

    function resetFilter() {
        document.getElementById('keyword').value = '';
        document.getElementById('search_field').value = 'p.product_name';
        document.getElementById('category_id').value = 0;
        document.getElementById('manufacturer_id').value = 'all';
        document.getElementById('product_sort').value = 0;
    }

</script>
<form action="index.php?option=com_redshop&view=product" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <div class="filterTool">
            <div class="js-stools" role="search">
                <div class="js-stools-container-bar">
                    <div class="btn-toolbar">
                        <div class="filterItem">
                            <div class="input-group">
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    value="<?php echo $this->keyword; ?>"
                                    placeholder="<?php echo Text::_("COM_REDSHOP_USER_FILTER") ?>">
                                <button class="btn btn-success input-group-text" id="date_range_filter_date_range_btn">
                                    <i class="icon-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="filterItem">
                            <div class="js-stools-field-list">
                                <select id="search_field" name="search_field"
                                    onchange="javascript:document.adminForm.submit();">
                                    <option value="p.product_name" <?php if ($this->search_field == 'p.product_name') {
                                        echo "selected='selected'";
                                    } ?>>
                                        <?php echo Text::_("COM_REDSHOP_PRODUCT_NAME") ?>
                                    </option>
                                    <option value="c.name" <?php if ($this->search_field == 'c.category_name') {
                                        echo "selected='selected'";
                                    } ?>>
                                        <?php echo Text::_("COM_REDSHOP_CATEGORY") ?>
                                    </option>
                                    <option value="p.product_number" <?php if ($this->search_field == 'p.product_number') {
                                        echo "selected='selected'";
                                    } ?>>
                                        <?php echo Text::_("COM_REDSHOP_PRODUCT_NUMBER") ?>
                                    </option>
                                    <option value="p.name_number" <?php if ($this->search_field == 'p.name_number') {
                                        echo "selected='selected'";
                                    } ?>>
                                        <?php echo Text::_("COM_REDSHOP_PRODUCT") . ' ' . Text::_(
                                            "COM_REDSHOP_NAME_AND_NUMBER"
                                        ); ?>
                                    </option>
                                    <option value="pa.property_number" <?php if ($this->search_field == 'pa.property_number') {
                                        echo "selected='selected'";
                                    } ?>>
                                        <?php echo Text::_("COM_REDSHOP_ATTRIBUTE_SKU") ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="filterItem">
                            <?php echo $this->lists['category']; ?>
                        </div>
                        <div class="filterItem">
                            <?php echo $this->lists['manufacturer']; ?>
                        </div>
                        <div class="filterItem">
                            <?php echo $this->lists['product_sort']; ?>
                        </div>
                        <div class="filterItem">
                            <button type="button"
                                class="filter-search-actions__button btn btn-primary js-stools-btn-clear"
                                onclick="resetFilter();this.form.submit();">
                                <?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="unpublished_data" value="">
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <?php if ($categoryId < 0): ?>
                        <th width="5">
                            <?php echo Text::_('COM_REDSHOP_NUM'); ?>
                        </th>
                    <?php endif ?>
                    <?php if ($categoryId > 0): ?>
                        <th width="1" class="nowrap center hidden-phone">
                            <a href="#" onclick="Joomla.tableOrdering('x.ordering','asc','');return false;"
                                data-order="X.ordering" data-direction="asc">
                                <span class="fa fa-sort-alpha-asc"></span>
                            </a>
                        </th>
                    <?php endif; ?>
                    <th width="20">
                        <?php echo HtmlHelper::_('redshopgrid.checkall'); ?>
                    </th>
                    <th class="title">
                        <?php echo HtmlHelper::_(
                            'grid.sort',
                            'COM_REDSHOP_PRODUCT_NAME',
                            'p.product_name',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                    <th class="title">
                        <?php echo HtmlHelper::_(
                            'grid.sort',
                            'COM_REDSHOP_PRODUCT_NUMBER',
                            'p.product_number',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                    <th class="title">
                        <?php echo HtmlHelper::_(
                            'grid.sort',
                            'COM_REDSHOP_PRODUCT_PRICE',
                            'p.product_price',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                    <?php foreach ($this->list_in_products as $listInProduct): ?>
                        <th nowrap="nowrap">
                            <?php echo Text::_($listInProduct->title); ?>
                        </th>
                    <?php endforeach; ?>
                    <th>
                        <?php echo Text::_('COM_REDSHOP_MEDIA'); ?>
                    </th>
                    <th>
                        <?php echo Text::_('COM_REDSHOP_WRAPPER'); ?>
                    </th>
                    <th>
                        <?php echo HtmlHelper::_(
                            'grid.sort',
                            'COM_REDSHOP_NUMBER_OF_VIEWS',
                            'p.visited',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>

                    <th>
                        <?php echo HtmlHelper::_(
                            'grid.sort',
                            'COM_REDSHOP_CATEGORY',
                            'category_id',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                    <th>
                        <?php echo HtmlHelper::_(
                            'grid.sort',
                            'COM_REDSHOP_MANUFACTURER',
                            'm.name',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                    <th>
                        <?php echo Text::_('COM_REDSHOP_CUSTOMER_REVIEWS'); ?>
                    </th>
                    <th width="5%" nowrap="nowrap">
                        <?php echo HtmlHelper::_(
                            'grid.sort',
                            'COM_REDSHOP_PUBLISHED',
                            'p.published',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                    <th width="5%" nowrap="nowrap">
                        <?php echo HtmlHelper::_(
                            'grid.sort',
                            'COM_REDSHOP_ID',
                            'p.product_id',
                            $this->lists['order_Dir'],
                            $this->lists['order']
                        ); ?>
                    </th>
                </tr>
            </thead>
            <?php $k = 0; ?>
            <?php foreach ($this->products as $index => $product): ?>
                <?php
                $product->id = $product->product_id;
                $link        = Redshop\IO\Route::_(
                    'index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $product->product_id
                );
                $published   = HtmlHelper::_('jgrid.published', $product->published, $index, '', 1);
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <?php if ($categoryId < 0): ?>
                        <td>
                            <?php echo $this->pagination->getRowOffset($index); ?>
                        </td>
                    <?php endif ?>
                    <?php if ($categoryId > 0) {
                        ?>
                        <td class="order nowrap center hidden-phone">
                            <span class="sortable-handler <?php echo ($allowOrder) ? '' : 'inactive' ?>">
                                <span class="icon-move"></span>
                            </span>
                            <input type="text" style="display:none" name="order[]" value="<?php echo $product->ordering; ?>" />
                        </td>
                    <?php } ?>
                    <td>
                        <?php echo @HtmlHelper::_('grid.checkedout', $product, $index); ?>
                    </td>
                    <td>
                        <?php

                        $canCheckin = $user->authorise(
                            'core.manage',
                            'com_checkin'
                        ) || $product->checked_out == $userId || $product->checked_out == 0;
                        ?>
                        <?php if ($product->checked_out): ?>
                            <?php $checkedOut = Factory::getUser($product->checked_out); ?>
                            <?php echo HtmlHelper::_(
                                'jgrid.checkedout',
                                $index,
                                $checkedOut->name,
                                $product->checked_out_time,
                                'product.',
                                $canCheckin
                            ); ?>
                        <?php endif; ?>
                        <?php
                        if ($canCheckin) {
                            if (isset($product->children)) {
                                ?>
                                <a href="<?php echo $link; ?>" title="<?php echo Text::_(
                                       'COM_REDSHOP_EDIT_PRODUCT'
                                   ); ?>">
                                    <?php echo $product->treename; ?>
                                </a>
                                <?php
                            } else {
                                if ($product->product_parent_id == 0) {
                                    ?>
                                    <a href="<?php echo $link; ?>" title="<?php echo Text::_(
                                           'COM_REDSHOP_EDIT_PRODUCT'
                                       ); ?>">
                                        <?php echo $product->treename; ?>
                                    </a>
                                    <?php
                                } else {
                                    $pro_array = Redshop::product((int) $product->product_parent_id);

                                    ?>
                                    <a href="<?php echo $link; ?>" title="<?php echo Text::_(
                                           'COM_REDSHOP_EDIT_PRODUCT'
                                       ); ?>">
                                        <?php echo $product->treename; ?>
                                    </a>[child:
                                    <?php echo $pro_array->product_name; ?>]
                                    <?php
                                }
                            }
                        } else {
                            ?>
                            <?php
                            if (isset($product->children)) {
                                ?>
                                <a href="<?php echo $link; ?>" title="<?php echo Text::_(
                                       'COM_REDSHOP_EDIT_PRODUCT'
                                   ); ?>">
                                    <?php echo $product->treename; ?>
                                </a>
                                <?php
                            } else {
                                if ($product->product_parent_id == 0) {
                                    ?>
                                    <a href="<?php echo $link; ?>" title="<?php echo Text::_(
                                           'COM_REDSHOP_EDIT_PRODUCT'
                                       ); ?>">
                                        <?php echo $product->treename; ?>
                                    </a>
                                    <?php
                                } else {
                                    $pro_array = Redshop::product((int) $product->product_parent_id);

                                    ?>
                                    <a href="<?php echo $link; ?>" title="<?php echo Text::_(
                                           'COM_REDSHOP_EDIT_PRODUCT'
                                       ); ?>">
                                        <?php echo $product->treename; ?>
                                    </a>[child:
                                    <?php echo $pro_array->product_name; ?>]
                                    <?php
                                }
                            }
                            ?>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $product->product_number; ?>
                    </td>
                    <td class="nowrap">
                        <?php echo RedshopHelperProductPrice::formattedPrice($product->product_price); ?>
                    </td>

                    <?php foreach ($this->list_in_products as $list_in_product): ?>
                        <?php
                        $fieldArray = RedshopHelperExtrafields::getSectionFieldDataList(
                            $list_in_product->id,
                            1,
                            $product->product_id
                        );
                        $fieldValue = '';
                        if (!empty($fieldArray)) {
                            $fieldValue = $fieldArray->data_txt;
                        }
                        ?>
                        <td>
                            <?php echo $fieldValue; ?>
                        </td>
                    <?php endforeach; ?>

                    <td align="center">
                        <?php echo RedshopLayoutHelper::render(
                            'joomla.html.image',
                            ['src' => REDSHOP_MEDIA_IMAGES_ABSPATH . 'media16.png', 'alt' => 'media', 'align' => 'absmiddle']
                        ); ?>
                        <?php
                        echo RedshopLayoutHelper::render(
                            'modal.button',
                            [
                                'selector' => 'ModalSelectMedia',
                                'params'   => [
                                    'title'       => '',
                                    'footer'      => '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    ' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '
                                                    </button>',
                                    'buttonText'  => '(' . count($model->MediaDetail($product->product_id)) . ')',
                                    'buttonClass' => 'btn btn-link',
                                    'buttonId'    => 'ModalSelectMedia' . $index,
                                    'url'         => 'index.php?option=com_redshop&view=media&section_id='
                                        . $product->product_id . '&showbuttons=1&media_section=product&section_name='
                                        . $product->product_name . '&tmpl=component',
                                    'modalWidth'  => '80',
                                    'bodyHeight'  => '60',
                                ]
                            ]
                        );
                        ?>
                    </td>
                    <td align="center">
                        <?php $wrapper = RedshopHelperProduct::getWrapper($product->product_id, 0, 1); ?>
                        <?php echo RedshopLayoutHelper::render(
                            'joomla.html.image',
                            [
                                'src'   => REDSHOP_MEDIA_IMAGES_ABSPATH . 'wrapper16.png',
                                'alt'   => Text::_('COM_REDSHOP_WRAPPER'),
                                'align' => 'absmiddle'
                            ]
                        ); ?>
                        <?php
                        echo RedshopLayoutHelper::render(
                            'modal.button',
                            [
                                'selector' => 'ModalSelectWrapper',
                                'params'   => [
                                    'title'       => '',
                                    'footer'      => '<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    ' . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '
                                                </button>',
                                    'buttonText'  => '(' . count($wrapper) . ')',
                                    'buttonClass' => 'btn btn-link',
                                    'buttonId'    => 'ModalSelectWrapperButton' . $index,
                                    'url'         => 'index.php?option=com_redshop&showall=1&view=wrapper&layout=edit&tmpl=component&product_id=' . $product->product_id,
                                    'modalWidth'  => '40',
                                    'bodyHeight'  => '70',
                                ]
                            ]
                        );
                        ?>
                    </td>
                    <td align="center">
                        <?php echo $product->visited; ?>
                    </td>

                    <td>
                        <?php $listedincats = $model->listedincats($product->product_id); ?>
                        <?php foreach ($listedincats as $listedincat): ?>
                            <?php echo $cat = $listedincat->name . "<br />"; ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php echo RedshopEntityManufacturer::getInstance($product->manufacturer_id)->get(
                            'name',
                            ''
                        ); ?>
                    </td>
                    <td>
                        <a
                            href="index.php?option=com_redshop&view=rating&task=edit&cid[]=0&pid=<?php echo $product->product_id ?>">
                            <?php echo Text::_(
                                'COM_REDSHOP_ADD_REVIEW'
                            ); ?>
                        </a>
                    </td>
                    <td align="center" width="8%">
                        <?php echo $published; ?>
                    </td>
                    <td align="center" width="5%">
                        <?php echo $product->product_id; ?>
                    </td>
                </tr>
                <?php $k = 1 - $k; ?>
            <?php endforeach; ?>
            <tfoot>
                <td colspan="14">
                    <div class="redShopLimitBox">
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tfoot>
        </table>
    </div>

    <input type="hidden" name="view" value="product" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
    <?php echo HtmlHelper::_('form.token'); ?>
</form>