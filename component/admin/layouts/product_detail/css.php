<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('a.joom-box');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

extract($displayData);

?>

<style>
    .adminlist {
        width: 100%;
        background-color: #e7e7e7;
        color: #666;
    }

    .adminlist .thead {
        vertical-align: center;
        padding: 5px;
        background-color: #f7f7f7;
        font-weight: bold;
    }

    .row0 {
        background-color: #ffffff;
        padding: 5px;
    }

    .row1 {
        background-color: #f0f0f0;
        padding: 5px;
    }

    .row0:hover {
        background-color: lightyellow;
    }

    .row1:hover {
        background-color: lightyellow;
    }

    .row1:hover .btn-edit-inrow {
        display: block;
    }

    .row0:hover .btn-edit-inrow {
        display: block;
    }

    .div_properties {
        padding: 10px;
        border-left: solid 3px darkred;
        background-color: #ffffff;
        display: none;
    }

    .div_subproperties {
        padding: 10px;
        border-left: solid 2px darkgreen;
    }

    .td {
        padding: 5px;
    }

    .btn-edit-inrow {
        display: none;
        padding: 5px;
    }

    .btn-edit-inrow:hover {
        cursor: pointer;
    }

    .btn-edit-inrow span {
        font-size: 20px;
    }

    .btn-functionality {
        padding: 5px;
    }

    #new_attribute {
        padding: 20px;
        display: none;
        font-weight: bold;
        border-left: solid 3px black;
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.7);
        /* Black w/ opacity */
    }

    #loader {
        padding: 20px;
        display: none;
        position: fixed;
        /* Stay in place */
        z-index: 99999999;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.7);
        /* Black w/ opacity */
    }

    #loader img {
        position: absolute;
        left: 40%;
        top: 40%;
    }

    #new_property {
        padding: 20px;
        display: none;
        font-weight: bold;
        border-left: solid 3px black;
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.7);
        /* Black w/ opacity */
    }

    #new_attribute th {
        font-weight: bold;
    }

    #new_property th {
        font-weight: bold;
    }

    #new_subproperty {
        padding: 20px;
        display: none;
        font-weight: bold;
        border-left: solid 3px black;
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.7);
        /* Black w/ opacity */
    }

    #new_subattribute th {
        font-weight: bold;
    }

    #new_subproperty th {
        font-weight: bold;
    }

    .modal-dialog {
        width: 80%;
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
    }

    .attribute-title {
        padding: 10px;
        margin-bottom: 10px;
        border-left: solid 3px black;
    }

    .attribute-title span {
        font-weight: bolder;
        font-size: 120%;
    }

    .attribute-title small {
        color: #999;
        font-size: 120%;
    }

    .property_title {
        padding: 10px;
        margin-bottom: 10px;
        border-left: solid 3px darkred;
    }

    .property-title span {
        font-weight: bolder;
        font-size: 120%;
    }

    .property-title small {
        color: #999;
        font-size: 120%;
    }

    .element_title {
        padding: 10px;
        margin-bottom: 10px;
        border-left: solid 3px darkred;
    }

    .element-title span {
        font-weight: bolder;
        font-size: 120%;
    }

    .element-title small {
        color: #999;
        font-size: 120%;
    }

    .function-bottom {
        margin-top: 10px;
        padding: 10px;
        background-color: cornsilk;
    }

    .element-result-box {
        padding: 10px;
        color: #3c763d;
        background-color: #dff0d8;
        margin: 10px 0 10px;
        display: none;
    }

    .zoom {
        padding-right: 25px;
    }

    .zoom:hover {
        transform: scale(1.8);
        /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }
</style>