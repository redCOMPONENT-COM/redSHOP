
// theme node properties
var ctThemeNavy =
{
  	// tree attributes
  	//
	// except themeLevel, all other attributes can be specified
	// for each level of depth of the tree.

  	// HTML code to the left of a folder item
  	// first one is for folder closed, second one is for folder opened
	folderLeft: [['','']],
  	// HTML code to the right of a folder item
  	// first one is for folder closed, second one is for folder opened
  	folderRight: [['<img alt="" src="' + ctThemeXPBase + 'open.gif" />', '<img alt="" src="' + ctThemeXPBase + 'close.gif" />']],
	// HTML code for the connector
	// first one is for w/ having next sibling, second one is for no next sibling
	// then inside each, the first field is for closed folder form, and the second field is for open form
	folderConnect: [[['&nbsp;','&nbsp;'],['&nbsp;','&nbsp;']]],

	// HTML code to the left of a regular item
	itemLeft: [''],
	// HTML code to the right of a regular item
	itemRight: [''],
	// HTML code for the connector
	// first one is for w/ having next sibling, second one is for no next sibling
	itemConnect: [['&nbsp;','&nbsp;']],

	// HTML code for spacers
	// first one connects next, second one doesn"t
	spacer: [['&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;&nbsp;']],

	// deepest level of theme style sheet specified
	themeLevel: 1
};
