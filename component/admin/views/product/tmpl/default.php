<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

$app           = JFactory::getApplication();
$extra_field   = extra_field::getInstance();
$producthelper = productHelper::getInstance();

$model = $this->getModel('product');
$ordering = ($this->lists['order'] == 'x.ordering');

$category_id = $this->state->get('category_id', 0);

$user = JFactory::getUser();
$userId = (int) $user->id;
JHtml::_('redshopjquery.framework');
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

        if (pressbutton == 'remove')
        {
            if (confirm("<?php echo JText::_('COM_REDSHOP_PRODUCT_DELETE_CONFIRM') ?>") != true)
            {
                return false;
            }
        }


        try {
            form.onsubmit();
        }
        catch (e) {
        }

        form.submit();
    }

    function AssignTemplate() {
        var form = document.adminForm;
        if (form.boxchecked.value == 0) {
            jQuery('#product_template').val(0).trigger("liszt:updated");
            alert('<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT');?>');
        } else {
            form.task.value = 'assignTemplate';
            if (confirm("<?php echo JText::_('COM_REDSHOP_SURE_WANT_TO_ASSIGN_TEMPLATE');?>")) {
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
        document.getElementById('product_sort').value = 0;
    }

</script>
<form action="index.php?option=com_redshop&view=product" method="post" name="adminForm" id="adminForm">

<div id="editcell">
<div class="filterTool">
    <div class="filterItem">
        <div class="btn-wrapper input-append">
            <input type="text" name="keyword" id="keyword" value="<?php echo $this->keyword; ?>" placeholder="<?php echo JText::_("COM_REDSHOP_USER_FILTER") ?>">
            <input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
            <input type="button" class="btn reset" onclick="resetFilter();this.form.submit();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
        </div>
    </div>
    <div class="filterItem">
        <select id="search_field" name="search_field" onchange="javascript:document.adminForm.submit();">
            <option
                value="p.product_name" <?php if ($this->search_field == 'p.product_name') echo "selected='selected'";?>>
                <?php echo JText::_("COM_REDSHOP_PRODUCT_NAME")?></option>
            <option
                value="c.category_name" <?php if ($this->search_field == 'c.category_name') echo "selected='selected'";?>>
                <?php echo JText::_("COM_REDSHOP_CATEGORY")?></option>
            <option
                value="p.product_number" <?php if ($this->search_field == 'p.product_number') echo "selected='selected'";?>
                ><?php echo JText::_("COM_REDSHOP_PRODUCT_NUMBER")?></option>
            <option
                value="p.name_number" <?php if ($this->search_field == 'p.name_number') echo "selected='selected'";?>
                ><?php echo JText::_("COM_REDSHOP_PRODUCT") . ' ' . JText::_("COM_REDSHOP_NAME_AND_NUMBER"); ?></option>
            <option
                value="pa.property_number" <?php if ($this->search_field == 'pa.property_number') echo "selected='selected'";?>>
                <?php echo JText::_("COM_REDSHOP_ATTRIBUTE_SKU")?></option>
        </select>
    </div>
    <div class="filterItem">
        <?php echo $this->lists['category']; ?>
    </div>
    <div class="filterItem">
        <?php echo $this->lists['product_sort']; ?>
    </div>
</div>
<input type="hidden" name="unpublished_data" value="">
<table class="adminlist table table-striped">
<thead>
<tr>
    <th width="5">
        <?php echo JText::_('COM_REDSHOP_NUM'); ?>
    </th>
    <th width="20">
        <?php echo JHtml::_('redshopgrid.checkall'); ?>
    </th>
    <th class="title">
        <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'p.product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
    </th>
    <th class="title">
        <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NUMBER', 'p.product_number', $this->lists['order_Dir'], $this->lists['order']); ?>
    </th>
    <th class="title">
        <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_PRICE', 'p.product_price', $this->lists['order_Dir'], $this->lists['order']); ?>
    </th>
    <?php

    for ($i = 0, $n = count($this->list_in_products); $i < $n; $i++)
    {
        ?>
        <th nowrap="nowrap"><?php echo  JText::_($this->list_in_products[$i]->title); ?></th>
    <?php }    ?>
    <th>
        <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MEDIA', 'media', $this->lists['order_Dir'], $this->lists['order']); ?>
    </th>
    <th>
        <?php echo JText::_('COM_REDSHOP_WRAPPER'); ?>
    </th>
    <th>
        <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NUMBER_OF_VIEWS', 'p.visited', $this->lists['order_Dir'], $this->lists['order']); ?>
    </th>

    <th>
        <?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_REDSHOP_MANUFACTURER'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_REDSHOP_CUSTOMER_REVIEWS'); ?>
    </th>
    <th width="5%" nowrap="nowrap">
        <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'p.published', $this->lists['order_Dir'], $this->lists['order']); ?>
    </th>
    <th width="5%" nowrap="nowrap">
        <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'p.product_id', $this->lists['order_Dir'], $this->lists['order']); ?>
    </th>
    <?php if ($category_id > 0)
    {
        ?>
        <th width="15%" nowrap="nowrap">
            <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'x.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
            <?php
            if ($ordering)
            {
                echo JHTML::_('grid.order', $this->products);
            }
            ?>
        </th>
    <?php } ?>
</tr>
</thead>
<?php
$k = 0;



for ($i = 0, $n = count($this->products); $i < $n; $i++)
{
    $row = $this->products[$i];

    $row->id = $row->product_id;
    $link = JRoute::_('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id);

    //  $published  = JHtml::_('jgrid.published', $row->published, $i,'',1);

    $published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

    ?>
    <tr class="<?php echo "row$k"; ?>">
        <td>
            <?php echo $this->pagination->getRowOffset($i); ?>
        </td>
        <td>
            <?php echo @JHTML::_('grid.checkedout', $row, $i); ?>
        </td>
        <td>
            <?php

            $canCheckin = $user->authorise('core.manage', 'com_checkin') || $row->checked_out == $userId || $row->checked_out == 0;
            ?>
            <?php if ($row->checked_out) : ?>
                <?php $checkedOut = JFactory::getUser($row->checked_out); ?>
                <?php echo JHtml::_('jgrid.checkedout', $i, $checkedOut->name, $row->checked_out_time, 'product.', $canCheckin); ?>
            <?php endif; ?>
            <?php
            if ($canCheckin)
            {
                if (isset($row->children))
                {
                    ?>
                    <a href="<?php echo $link; ?>"
                       title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?></a>
                <?php
                }
                else
                {
                    if ($row->product_parent_id == 0)
                    {
                        ?>
                        <a href="<?php echo $link; ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?></a>
                    <?php
                    }
                    else
                    {
                        $pro_array = Redshop::product((int) $row->product_parent_id);

                        ?>
                        <a href="<?php echo $link; ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?> </a>[child: <?php echo $pro_array->product_name; ?>]
                    <?php
                    }
                }
            }
            else
            {
                ?>
                <?php
                if (isset($row->children))
                {
                    ?>
                    <a href="<?php echo $link; ?>"
                       title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?></a>
                <?php
                }
                else
                {
                    if ($row->product_parent_id == 0)
                    {
                        ?>
                        <a href="<?php echo $link; ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?></a>
                    <?php
                    }
                    else
                    {
                        $pro_array = Redshop::product((int) $row->product_parent_id);

                        ?>
                        <a href="<?php echo $link; ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->treename; ?> </a>[child: <?php echo $pro_array->product_name; ?>]
                    <?php
                    }
                }
                ?>
            <?php
            }
            ?>
        </td>
        <td>
            <?php echo $row->product_number;?>
        </td>
        <td class="nowrap">
            <?php echo $producthelper->getProductFormattedPrice($row->product_price);?>
        </td>

        <?php    for ($j = 0, $k = count($this->list_in_products); $j < $k; $j++)
        {
            $field_arr = $extra_field->getSectionFieldDataList($this->list_in_products[$j]->id, 1, $row->product_id);
            $field_value = '';
            if (count($field_arr) > 0)
            {
                $field_value = $field_arr->data_txt;
            }    ?>
            <td><?php echo $field_value;  ?></td>
        <?php }    ?>

        <td align="center">
            <?php $mediadetail = $model->MediaDetail($row->product_id); ?>
            <a class="joom-box"
               href="index.php?option=com_redshop&view=media&section_id=<?php echo $row->product_id; ?>&showbuttons=1&media_section=product&section_name=<?php echo $row->product_name; ?>&tmpl=component"
               rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""> <img
                    src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>media16.png" align="absmiddle"
                    alt="media"> (<?php  echo count($mediadetail);?>)</a>
        </td>
        <td align="center">
            <?php $wrapper = $producthelper->getWrapper($row->product_id, 0, 1);?>
            <a class="joom-box"
               href="index.php?option=com_redshop&showall=1&view=wrapper&product_id=<?php echo $row->product_id; ?>&tmpl=component"
               rel="{handler: 'iframe', size: {x: 700, y: 450}}">
                <img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>wrapper16.png" align="absmiddle"
                     alt="<?php echo JText::_('COM_REDSHOP_WRAPPER'); ?>"> <?php echo "(" . count($wrapper) . ")";?></a>
        </td>
        <td align="center">
            <?php echo $row->visited;?>
        </td>

		<td>
			<?php $listedincats = $model->listedincats($row->product_id);
			for ($j = 0, $jn = count($listedincats); $j < $jn; $j++)
			{
				echo $cat = $listedincats[$j]->name . "<br />";
			}
			?>
		</td>
		<td>
			<?php echo RedshopEntityManufacturer::getInstance($row->manufacturer_id)->get('name', ''); ?>
		</td>
		<td>
			<a href="index.php?option=com_redshop&view=rating_detail&task=edit&cid[]=0&pid=<?php echo $row->product_id ?>"><?php echo JText::_('COM_REDSHOP_ADD_REVIEW'); ?></a>
		</td>
		<td align="center" width="8%">
			<?php echo $published;?>
		</td>
		<td align="center" width="5%">
			<?php echo $row->product_id; ?>
		</td>
		<?php if ($category_id > 0)
		{
			?>
			<td class="order">
				<?php if ($ordering) :
					$orderDir = strtoupper($this->lists['order_Dir']);
					?>
					<div class="input-prepend">
						<?php if ($orderDir == 'ASC' || $orderDir == '') : ?>
							<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'orderup'); ?></span>
							<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderdown'); ?></span>
						<?php elseif ($orderDir == 'DESC') : ?>
							<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'orderdown'); ?></span>
							<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $n, true, 'orderup'); ?></span>
						<?php endif; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="width-20 text-area-order" />
					</div>
				<?php else : ?>
					<?php echo $row->ordering; ?>
				<?php endif; ?>
				</td>
		<?php } ?>
	</tr>
	<?php
	$k = 1 - $k;
}
?>

<tfoot>
<td colspan="14">
    <?php if (version_compare(JVERSION, '3.0', '>=')): ?>
        <div class="redShopLimitBox">
            <?php echo $this->pagination->getLimitBox(); ?>
        </div>
    <?php endif; ?>
    <?php echo $this->pagination->getListFooter(); ?>
</td>
</tfoot>
</table>
</div>

<input type="hidden" name="view" value="product"/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
<?php echo JHtml::_('form.token'); ?>
</form>
