redSHOP1.2
==========

Documentation

https://github.com/redCOMPONENT-COM/documentation

## Release process for redSHOP
Please follow the next steps in order to release a new version of redSHOP.

- Execute component_packager.xml PHING file to generate the main component package (includes 1 module and 2 plugins).

### Languages & translation
- Move the language files to the translations repository: https://github.com/redCOMPONENT-COM/translations/tree/master/redSHOP/source 
- Check in 24hours that Transifex was able to get the new translation strings adding them to the .ini resource files

### Extensions
#### Plugins
- Check if any plugin has been changed in this last release:

> $ git log --oneline --after={2013-04-18} --no-merges --name-only | grep 'plugins\/.*' | cut -d/ -f2 -f3 | sort | uniq

- if plungins have been modified:
 - Update the release number in their manifest .xml file with the same release number as the component. For example if we are releasing redSHOP 1.3 and plugin plg_default_shipping has been updated during 1.2 - 1.3 period, then you should set the plugin version to 1.3 here: https://github.com/redCOMPONENT-COM/redSHOP-1.2/blob/master/plugins/redshop_shipping/default_shipping/default_shipping.xml#L4 or leave it as it is if it has not been touch. 
 - execute the plugins_packager.xml PHING file and upload the new release to redCOMPONENT.com

#### Modules
- Check if any module has been changed in this last release:

> $ git log --oneline --after={2013-04-18} --no-merges --name-only | grep 'modules\/.*' | cut -d/ -f2 -f3  | sort | uniq

- if modules have been modified:
 - Update the release number in their manifest .xml file in the same way you have done it with plugins https://github.com/redCOMPONENT-COM/redSHOP-1.2#plugins
 - execute the modules_packager.xml PHING file and upload the new release to redCOMPONENT.com

### Test 
- test the packages to ensure that everything works properly.
- share the package in the redSHOP testing chat, so others can do some testing
- add fast fixes if issues has been identified during testing
- Generate the final packages and contact the Product Owner (Ole) and ask him to upload the packages to redCOMPOPNENT.com 

### Update Changelog
- Create the Changelog list of commits:

> $ git log --oneline --after={2013-04-18} --no-merges --format="* %s ( %h )"

- Upload the list to http://wiki.redcomponent.com/index.php?title=redSHOP:Changelog

### Prepare software for next release
- Update component version number with next release number at https://github.com/redCOMPONENT-COM/redSHOP-1.2/blob/master/redshop.xml#L10
- Create an empty update .sql file at: https://github.com/redCOMPONENT-COM/redSHOP-1.2/tree/master/component/admin/sql/updates/mysql
- Merge development branch into Master (see successful git-branching model: http://nvie.com/posts/a-successful-git-branching-model/ )
- Create a release git TAG
 - create the tag in local:
> $ git tag -a 1.3 -m "Version 1.3 Stable"
 - Upload the tag to Github:
> $ git push --tags
 - Check that tag has been created: https://github.com/redCOMPONENT-COM/redSHOP/tags
 - Create the release: https://github.com/redCOMPONENT-COM/redSHOP/releases
