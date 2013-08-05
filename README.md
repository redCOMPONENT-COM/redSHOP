redSHOP1.2
==========

Documentation

https://github.com/redCOMPONENT-COM/documentation

## Release process for redSHOP

- Execute component_packager.xml PHING file to generate the main component package (includes 1 module and 2 plugins)
- Check if any plugin or module has changed during this release and execute modules_packager.xml and plugins_packager.xml PHING files
- Move the language files to https://github.com/redCOMPONENT-COM/translations/tree/master/redSHOP/source
