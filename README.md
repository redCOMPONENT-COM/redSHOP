redSHOP1.2
==========

Documentation

https://github.com/redCOMPONENT-COM/documentation

## Release process for redSHOP

- Execute component_packager.xml PHING file to generate the main component package (includes 1 module and 2 plugins)
- Move the language files to https://github.com/redCOMPONENT-COM/translations/tree/master/redSHOP/source

- Check if any plugin or module has been changed in this last release:
>$ git log --oneline --after={2013-04-18} --no-merges --name-only | grep 'plugins/*' | cut -d/ -f2 -f3  | sort | uniq
- if plungins have been modified execute the plugins_packager.xml PHING file and upload the new release to redCOMPONENT.com

- Check if any module has been changed in this last release:
>$ git log --oneline --after={2013-04-18} --no-merges --name-only | grep 'modules/*' | cut -d/ -f2 -f3  | sort | uniq
- if modules have been modified execute the modules_packager.xml PHING file and upload the new release to redCOMPONENT.com
