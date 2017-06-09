<?php

/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 6/8/2017
 * Time: 1:59 PM
 */
class MassDiscountManagerPage
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=mass_discounts';
    public static $name = "#jform_name";
    public static $valueAmount = "#jform_amount";
    public static $pathNameProduct = "#s2id_autogen3";
    public static $categoryField = "#s2id_jform_category_id";
    public static $dayStart = "#jform_start_date";
    public static $dayEnd = "#jform_end_date";
    public static $checkFirstItems = "//input[@id='cb0']";
    public static $MassDiscountFilter = "#filter_search";
    public static $choiceAll="//input[@onclick='Joomla.checkAll(this)']";

public static $MassDicountResultRow="//div[@class='table-responsive']/table/tbody/tr/td[3]/a";


}