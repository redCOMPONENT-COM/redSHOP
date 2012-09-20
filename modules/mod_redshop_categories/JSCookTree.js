/*
	JSCookTree v2.01.  (c) Copyright 2002 by Heng Yuan

	Permission is hereby granted, free of charge, to any person obtaining a
	copy of this software and associated documentation files (the "Software"),
	to deal in the Software without restriction, including without limitation
	the rights to use, copy, modify, merge, publish, distribute, sublicense,
	and/or sell copies of the Software, and to permit persons to whom the
	Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included
	in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
	OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	ITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
	DEALINGS IN THE SOFTWARE.
*/

// data structures

//
// ctTreeInfo stores information about the current tree
//
function ctTreeInfo (nodeProperties, prefix, hideType, expandLevel)
{
	// default node properties
	this.nodeProperties = nodeProperties;
	// current selected item in this tree
	this.currentItem = null;
	// theme prefix
	this.prefix = prefix;
	// open tree type
	//	0:	just open the current tree
	//	1:	close other branches in the same tree
	//	2:	close other branches in other trees as well
	this.hideType =  hideType;
	// the deepest level of the tree is the always expaned
	this.expandLevel = expandLevel;
	// beginIndex is the first index of the tree item
	this.beginIndex = 0;
	// endIndex is same as beginIndex + # of items in the tree
	this.endIndex = 0;
}

function ctMenuInfo (id, idSub)
{
	// id of the menu item that owns the sub menu
	this.id = id;
	// the id of the sub menu
	this.idSub = idSub;
}

// Globals

var _ctIDSubMenuCount = 0;
var _ctIDSubMenu = 'ctSubTreeID';		// for creating submenu id

var _ctCurrentItem = null;		// the current menu item being selected;

var _ctNoAction = new Object ();	// indicate that the item cannot be hovered.

var _ctItemList = new Array ();		// a simple list of items
var _ctTreeList = new Array ();		// a list of ctTreeInfo.
var _ctMenuList = new Array ();		// a list of ctMenuInfo

var _ctMenuInitStr = '';			// initiation command that initiate menu items

// default node properties
var _ctNodeProperties =
{
  	// tree attributes
  	//
	// except themeLevel, all other attributes can be specified
	// for each level of depth of the tree.

  	// HTML code to the left of a folder item
  	// first one is for folder closed, second one is for folder opened
	folderLeft: [['', '']],
  	// HTML code to the right of a folder item
  	// first one is for folder closed, second one is for folder opened
  	folderRight: [['', '']],
	// HTML code to the left of a regular item
	itemLeft: [''],
	// HTML code to the right of a regular item
	itemRight: [''],
	// HTML code for the connector
	// first one is for w/ having next sibling, second one is for no next sibling
	folderConnect: [[['',''],['','']]],
	itemConnect: [['',''],['','']],
	// HTML code for spacers
	// first one connects next, second one doesn"t
	spacer: [['&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;']],
	// deepest level of theme specified
	themeLevel: 1
	// tells JSCookTree to use <A> ancher tag to open links
	// if this field is set to false, then JSCookTree would hand it.
};

//////////////////////////////////////////////////////////////////////
//
// Drawing Functions and Utility Functions
//
//////////////////////////////////////////////////////////////////////

//
// produce a new unique submenu id
//
function ctNewSubMenuID ()
{
	return _ctIDSubMenu + (++_ctIDSubMenuCount);
}

//
// return the property string for the menu item
//
function ctActionItem ()
{
	return ' onmouseover="ctItemMouseOver (this.parentNode)" onmouseout="ctItemMouseOut (this.parentNode)" onmousedown="ctItemMouseDown (this.parentNode)" onmouseup="ctItemMouseUp (this.parentNode)"';
}

//
// return the property string for the menu item
//
function ctNoActionItem (item)
{
	return item[1];
}

//
// used to determine the property string
//
function ctGetPropertyLevel (level, property)
{
	return (level >= property.length) ? (property.length - 1) : level;
}


function ctCollapseTree (id)
{
	var menu = ctGetObject (id).firstChild;
	var i;
	for (i = 0; i < menu.ctItems.length; ++i)
		ctCloseFolder (menu.ctItems[i]);
}

//
// expand a tree such that upto level is exposed
//
function ctExpandTree (id, expandLevel)
{
	if (expandLevel <= 0)
		return;

	var obj = ctGetObject (id);
	if (!obj)
		return;

	var thisMenu = obj.firstChild;
	if (!thisMenu)
		return;

	ctExpandTreeSub (thisMenu, expandLevel)
}

function ctExpandTreeSub (subMenu, expandLevel)
{
	if (subMenu.ctLevel >= expandLevel)
		return;
	var i;
	var item;
	for (i = 0; i < subMenu.ctItems.length; ++i)
	{
		item = subMenu.ctItems[i];
		if (item.ctIdSub)
		{
			ctOpenFolder (item);
			ctExpandTreeSub (ctGetObject (item.ctIdSub), expandLevel);
		}
	}
}

//
// expose a particular menu item use its link as the search value
//
function ctExposeItem (treeIndex, link)
{
	if (treeIndex < 0 || treeIndex >= _ctTreeList.length)
		return;
	var tree = _ctTreeList[treeIndex];
	var endIndex = tree.endIndex;
	var i;
	for (i = tree.beginIndex; i < endIndex; ++i)
	{
		if (_ctItemList[i].length > 2 &&
			_ctItemList[i][2] == link)
		{
			return ctExposeTreeIndex (treeIndex, i);
		}
	}
}

//
// expose a particular menu item using its index
//
function ctExposeTreeIndex (treeIndex, index)
{
	var item = ctGetObject ('ctItemID' + (_ctTreeList[treeIndex].beginIndex + index)).parentNode;
	if (!item)
		return null;

	var parentItem = ctGetThisMenu (item).ctParent;
	if (parentItem)
		ctExposeTreeIndexSub (parentItem);

	ctSetSelectedItem (item);
	return item;
}

function ctExposeTreeIndexSub (item)
{
	var parentItem = ctGetThisMenu (item).ctParent;
	if (parentItem)
		ctExposeTreeIndexSub (parentItem);
	ctOpenFolder (item);
}

//
// mark a particular menu item with id using its link
//
function ctMarkItem (treeIndex, link)
{
	if (treeIndex < 0 || treeIndex >= _ctTreeList.length)
		return;
	var tree = _ctTreeList[treeIndex];
	var endIndex = tree.endIndex;
	var i;
	for (i = tree.beginIndex; i < endIndex; ++i)
	{
		if (_ctItemList[i].length > 2 &&
			_ctItemList[i][2] == link)
		{
			var item = ctGetObject ('ctItemID' + (_ctTreeList[treeIndex].beginIndex + i)).parentNode;
			if (!item)
				return null;
			if (item.id == "JSCookTreeItem")
				item.id = 'JSCookTreeMarked';
			return item;
		}
	}
}

//
// mark a particular menu item with id using index
//
function ctMarkTreeIndex (treeIndex, index)
{
	var item = ctGetObject ('ctItemID' + (_ctTreeList[treeIndex].beginIndex + index)).parentNode;
	if (!item)
		return null;
	if (item.id == "JSCookTreeItem")
		item.id = 'JSCookTreeMarked';
	return item;
}

//
// return the current selected node for the current tree
//
// treeItem treeItem is the table row of where the tree item is located
//
function ctGetSelectedItem (treeIndex)
{
	if (_ctTreeList[treeIndex].hideType <= 1)
		return _ctTreeList[treeIndex].currentItem;
	else
		return _ctCurrentItem;
}

//
// The function that builds the menu inside the specified element id.
//
function ctDraw (id, tree, nodeProperties, prefix, hideType, expandLevel)
{
	var obj = ctGetObject (id);

	if (!nodeProperties)
		nodeProperties = _ctNodeProperties;
	if (!prefix)
		prefix = '';
	if (!hideType)
		hideType = 0;
	if (!expandLevel)
		expandLevel = 0;

	//var treeIndex = _ctTreeList.push (new ctTreeInfo (nodeProperties, prefix, hideType, expandLevel)) - 1;
	_ctTreeList[_ctTreeList.length] = new ctTreeInfo (nodeProperties, prefix, hideType, expandLevel);
	var treeIndex = _ctTreeList.length - 1;

	var beginIndex = _ctItemList.length;

	_ctMenuInitStr = '';
	var str = ctDrawSub (tree, true, null, treeIndex, 0, nodeProperties, prefix, '');
	obj.innerHTML = str;
	eval (_ctMenuInitStr);
	_ctMenuInitStr = '';

	var endIndex = _ctItemList.length;

	_ctTreeList[treeIndex].beginIndex = beginIndex;
	_ctTreeList[treeIndex].endIndex = endIndex;

	if (expandLevel)
		ctExpandTree (id, expandLevel);

	//document.write ('<textarea wrap="off" rows="15" cols="80">' + str + '</textarea><br>');

	return treeIndex;
}

//
// draw the sub menu recursively
//
function ctDrawSub (subMenu, isMain, id, treeIndex, level, nodeProperties, prefix, indent)
{
	var lvl = level;
	if (lvl > nodeProperties.themeLevel)
		lvl = nodeProperties.themeLevel;

	var str = '<div class="' + prefix + 'TreeLevel' + lvl + '"';
	if (!isMain)
		str += ' id="' + id + '"';
	str += '>';

	var strSub = '';

	var item;
	var idSub;
	var hasChild;

	var classStr;
	var connectSelect;
	var childIndent;
	var index;
	var actionStr;
	var itemID;
	var markerStr;
	var themeLevel = nodeProperties.themeLevel;

	var i;
	if (isMain)
		i = 0;
	else
		i = 5;

	var className = ' class="' + prefix + 'Row"';

	for (; i < subMenu.length; ++i)
	{
		item = subMenu[i];
		if (!item)
			continue;

		//index = _ctItemList.push (item) - 1;
		_ctItemList[_ctItemList.length] = item;
		index = _ctItemList.length - 1;

		hasChild = (item.length > 5);
		idSub = hasChild ? ctNewSubMenuID () : null;

		str += '<table cellspacing="0" class="' + prefix + 'Table">';

		//
		// #JSCookTreeFolderClose & #JSCookTreeFolderOpen
		// are used in style sheet to control the animation of folder open/close
		// Also, it tells status of the submenu
		//
		str += '<tr' + className;
		if (hasChild)
			str += ' id="JSCookTreeFolderClosed">';
		else
			str += ' id="JSCookTreeItem">';

		classStr = prefix + (hasChild ? 'Folder' : 'Item');

		//
		// markerStr is used to mark Spacer cell such that the item (<tr> tag)
		// could be tracked in an alternative way
		// _ctMenuInitStr is used to initate the menu item
		//
		itemID = 'ctItemID' + index;
		markerStr = ' id="' + itemID + '"';
		_ctMenuInitStr += 'ctSetupItem (ctGetObject ("' + itemID + '").parentNode,' + index + ',' + treeIndex + ',' + level + ',' + (idSub ? ('"' + idSub + '"') : 'null') + ');';

		str += '<td class="' + classStr + 'Spacer"' + markerStr + '>' + indent;

 		str += '</td>';

		if (item[0] == _ctNoAction)
		{
			str += ctNoActionItem (item, prefix);
			str += '</tr></table>';
			continue;
		}

		actionStr = ctActionItem ();

		str += '<td class="' + classStr + 'Left"' + actionStr + '>';
		// add connect part
		if (hasChild)
		{
			connectSelect = ctHasNextItem (i, subMenu) ? 0 : 1;
			lvl = ctGetPropertyLevel (level, nodeProperties.folderConnect);
			str += '<span class="JSCookTreeFolderClosed">' + nodeProperties.folderConnect[lvl][connectSelect][0] + '</span>' +
				   '<span class="JSCookTreeFolderOpen">' + nodeProperties.folderConnect[lvl][connectSelect][1] + '</span>';
		}
		else
		{
			connectSelect = ctHasNextItem (i, subMenu) ? 0 : 1;
			lvl = ctGetPropertyLevel (level, nodeProperties.itemConnect);
			str += nodeProperties.itemConnect[lvl][connectSelect];
		}

		if (item[0] != null && item[0] != _ctNoAction)
		{
			str += item[0];
		}
		else if (hasChild)
		{
			lvl = ctGetPropertyLevel (level, nodeProperties.folderLeft);
			str += '<span class="JSCookTreeFolderClosed">' + nodeProperties.folderLeft[lvl][0] + '</span>' +
				   '<span class="JSCookTreeFolderOpen">' + nodeProperties.folderLeft[lvl][1] + '</span>';
		}
		else
		{
			lvl = ctGetPropertyLevel (level, nodeProperties.itemLeft);
			str += nodeProperties.itemLeft[lvl];
		}
		str += '</td>';

		str += '<td class="' + classStr + 'Text"' + actionStr + '>';

		str += '<a';

		if (item[2] != null)
		{
			str += ' href="' + item[2] + '"';
			if (item[3])
				str += ' target="' + item[3] + '"';
		}

		if (item[4] != null)
			str += ' title="' + item[4] + '"';
		else
			str += ' title="' + item[1] + '"';

		str += '>' + item[1] + '</a></td>';

		str += '<td class="' + classStr + 'Right"' + actionStr + '>';

		if (hasChild)
		{
			lvl = ctGetPropertyLevel (level, nodeProperties.folderRight);
			str += '<span class="JSCookTreeFolderClosed">' + nodeProperties.folderRight[lvl][0] + '</span>' +
				   '<span class="JSCookTreeFolderOpen">' + nodeProperties.folderRight[lvl][1] + '</span>';
		}
		else
		{
			lvl = ctGetPropertyLevel (level, nodeProperties.itemRight);
			str += nodeProperties.itemRight[lvl];
		}
		str += '</td>'
		str += '</tr></table>';

		if (hasChild)
		{
			childIndent = indent;
			lvl = ctGetPropertyLevel (level, nodeProperties.spacer);
			childIndent += nodeProperties.spacer[lvl][connectSelect];

			str += ctDrawSub (item, false, idSub, treeIndex, level + 1, nodeProperties, prefix, childIndent);
		}
	}

	str += '</div>';

	return str;
}

//////////////////////////////////////////////////////////////////////
//
// Mouse Event Handling Functions
//
//////////////////////////////////////////////////////////////////////

//
// action should be taken for mouse moving in to the menu item
//
function ctItemMouseOver (item)
{
	var treeItem = _ctItemList[item.ctIndex];
	var isDefaultItem = ctIsDefaultItem (treeItem);

	if (isDefaultItem)
	{
		var className = ctGetDefaultClassName (item);

		if (item.className == className)
			item.className = className + 'Hover';
	}
}

//
// action should be taken for mouse moving out of the menu item
//
function ctItemMouseOut (item)
{
	if (ctIsDefaultItem (_ctItemList[item.ctIndex]))
	{
		var className = ctGetDefaultClassName (item);

		if (item.className == (className + 'Hover') ||
			item.className == (className + 'Active'))
		{
			var tree = _ctTreeList[item.ctTreeIndex];
			var currentItem = (tree.hideType <= 1) ? tree.currentItem : _ctCurrentItem;

			if (item == currentItem)
				item.className = className + 'Selected';
			else
				item.className = className;
		}
	}
}

//
// action should be taken for mouse button down at a menu item
//
function ctItemMouseDown (item)
{
	if (ctIsDefaultItem (_ctItemList[item.ctIndex]))
	{
		var className = ctGetDefaultClassName (item);

		if (item.className == (className + 'Hover'))
			item.className = className + 'Active';
	}
}

//
// action should be taken for mouse button up at a menu item
//
function ctItemMouseUp (item)
{
	if (item.ctIdSub)
	{
		// toggle the submenu
		var subMenu = ctGetObject (item.ctIdSub);
		if (subMenu.style.display == 'block')
		{
			ctCloseFolder (item);
		}
		else
		{
			ctOpenFolder (item);
		}
	}
	ctSetSelectedItem (item);
}

//
// set the item as the selected item
//
function ctSetSelectedItem (item)
{
	var tree = _ctTreeList[item.ctTreeIndex];
	var hideType = tree.hideType;

	var otherItem;

	if (hideType <= 1)
		otherItem = tree.currentItem;
	else
		otherItem = _ctCurrentItem;

	if (otherItem != item)
	{
		ctLabelMenu (item);

		// set otherItem to normal
		if (otherItem)
		{
			if (ctIsDefaultItem (_ctItemList[otherItem.ctIndex]))
			{
				var className = ctGetDefaultClassName (otherItem);
				if (otherItem.className == (className + 'Selected'))
					otherItem.className = className;
			}

			// hide otherItem if required
			if (hideType > 0 && otherItem)
				ctHideMenu (otherItem, item);
		}

		// finally, set this item as selected
		if (hideType <= 1)
			tree.currentItem = item;
		else
			_ctCurrentItem = item;

		if (ctIsDefaultItem (_ctItemList[item.ctIndex]))
		{
			var className = ctGetDefaultClassName (item);
			item.className = className + 'Selected';
		}
	}
}

//////////////////////////////////////////////////////////////////////
//
// Mouse Event Support Utility Functions
//
//////////////////////////////////////////////////////////////////////

//
// check if an item is in open form
//
function ctIsFolderOpen (item)
{
	if (item.id == 'JSCookTreeFolderOpen')
		return true;
	return false;
}

//
// change an item into the open form
//
function ctOpenFolder (item)
{
	if (ctIsFolderOpen (item))
		return;
	if (item.ctIdSub)
	{
		var subMenu = ctGetObject (item.ctIdSub);
		subMenu.style.display = 'block';

		item.id = 'JSCookTreeFolderOpen';
	}
}

//
// change an item into the closed form
//
function ctCloseFolder (item)
{
	if (!ctIsFolderOpen (item))
		return;

	// hide the downstream menus
	if (item.ctIdSub)
	{
		var subMenu = ctGetObject (item.ctIdSub);
		var i;
		for (i = 0; i < subMenu.ctSubMenu.length; ++i)
			ctCloseFolder (subMenu.ctSubMenu[i].ctParent);

		var expandLevel = _ctTreeList[item.ctTreeIndex].expandLevel;
		if (item.ctLevel < expandLevel)
			return;
		subMenu.style.display = 'none';

		item.id = 'JSCookTreeFolderClosed';
	}
}

//
// setup an menu item
//
function ctSetupItem (item, index, treeIndex, level, idSub)
{
	if (!item.ctIndex)
	{
		item.ctIndex = index;
		item.ctTreeIndex = treeIndex;
		item.ctLevel = level;
		item.ctIdSub = idSub;
	}

	var thisMenu = ctGetThisMenu (item);
	ctSetupMenu (thisMenu, item, null, null);

	if (idSub)
	{
		var subMenu = ctGetObject (idSub);
		ctSetupMenu (subMenu, null, thisMenu, item);
	}
}

//
// setup the relationship between a node and its sub menu
//
function ctSetupMenu (thisMenu, thisItem, parentMenu, parentItem)
{
	if (!thisMenu.ctSubMenu)
			thisMenu.ctSubMenu = new Array ();

	if (parentItem)
	{
		if (!thisMenu.ctParent)
		{
			// establish the tree w/ back edge
			thisMenu.ctParent = parentItem;
			thisMenu.ctLevel = parentItem.ctLevel + 1;

			//parentMenu.ctSubMenu.push (thisMenu);
			parentMenu.ctSubMenu[parentMenu.ctSubMenu.length] = thisMenu;
		}
	}

	if (thisItem)
	{
		if (!thisItem.ctMenu)
		{
			thisItem.ctMenu = thisMenu;

			thisMenu.ctLevel = thisItem.ctLevel;

			if (!thisMenu.ctItems)
				thisMenu.ctItems = new Array ();

			//thisMenu.ctItems.push (thisItem);
			thisMenu.ctItems[thisMenu.ctItems.length] = thisItem;
		}
	}
}

//
// label the path from the menu root to the item
//
function ctLabelMenu (item)
{
	var thisMenu = ctGetThisMenu (item);
	while (thisMenu && thisMenu.ctLevel != 0)
	{
		thisMenu.ctCurrentItem = item;
		thisMenu = ctGetThisMenu (thisMenu.ctParent);
	}
}

//
// hide an item up to the parent menu of activeItem
//
function ctHideMenu (item, activeItem)
{
	var subMenu;
	while (item)
	{
		if (item.ctIdSub &&
			(subMenu = ctGetObject (item.ctIdSub)).ctLevel &&
			(subMenu.ctCurrentItem != activeItem))
		{
			ctCloseFolder (item);
		}
		item = ctGetThisMenu (item).ctParent;
	}
}

//
// returns the menu div where this obj (menu item) is in
//
function ctGetThisMenu (item)
{
	var str = _ctTreeList[item.ctTreeIndex].prefix;
	if (item.ctLevel == 0)
		str += 'TreeLevel0';
	else
	{
		var themeLevel = _ctTreeList[item.ctTreeIndex].nodeProperties.themeLevel;
		var lvl = (item.ctLevel < themeLevel) ? item.ctLevel : themeLevel;
		str += 'TreeLevel' + lvl;
	}
	while (item)
	{
		if (item.className == str)
			return item;
		item = item.parentNode;
	}
	return null;
}

//
// return true if there is next item
//
// used to determine connectors
//
function ctHasNextItem (index, tree)
{
	if (index < (tree.length - 2) ||
		(index == (tree.length - 2) && tree[index + 1]))
		return true;
	else
		return false;
}

function ctGetDefaultClassName (item)
{
	var tree = _ctTreeList[item.ctTreeIndex];
	return tree.prefix + 'Row';
}

//
// return true if this item is handled using default handlers
//
function ctIsDefaultItem (item)
{
	if (item[0] == _ctNoAction)
		return false;
	return true;
}

//
// returns the object baring the id
//
function ctGetObject (id)
{
	if (document.all)
		return document.all[id];
	return document.getElementById (id);
}

//
// debug function, ignore :)
//
function ctGetProperties (obj)
{
	var msg = obj + ':\n';
	var i;
	for (i in obj)
		msg += i + ' = ' + obj[i] + '; ';
	return msg;
}

/* JSCookTree v2.01		1. change Array.push (obj) call to Array[length] = obj.
						   Suggestion from Dick van der Kaaden <dick@netrex.nl> to
						   make the script compatible with IE 5.0
						2. added ctGetSelectedItem (treeIndex) function due to demand
*/
/* JSCookTree v2.0		1. added controls over tree branches opening/closing
						2. added the ability to mark a specific tree item
						3. added an extra description field to make the tree
						   format the same as JSCookMenu
						4. more control over themes.  allow multiple trees
						   w/ different themes co-exist in the same page
						5. tooltips.
*/
/* JSCookTree v1.01.	made more tolerant to extra commas */
/* JSCookTree v1.0.	(c) Copyright 2002 by Heng Yuan */
