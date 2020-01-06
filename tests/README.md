Run automation with redSHOP
==========

## using composer to get Codeception

Execute
```
# You need to have Composer in your system, if not download it from here: https://getcomposer.org/
composer install
```

## Preparation for running the test
###Step 1:

Clone the repository using 
```git clone```  

###Step 2:

Download ChromeDriver https://chromedriver.chromium.org/downloads

Download https://selenium.dev/downloads/.

Put them in a single folder, unzip folder ChromeDriver and run
```java -Xmx256m -jar selenium-server-standalone-3.141.59.jar```

change 3.141.59.jar to version selenium has been download

###Step 3:

Setup new site Joomla with the latest version of redSHOP  created previously.

Copy file "acceptance.suite.yml.dist" to "acceptance.suite.yml" and change config: "url" to "http://localhost/address-your-local-file-exist/".

Command lines 28 to 32 of "acceptance.suite.yml". 

NOTE: Make sure username/password are correct. and site http://localhost/address-your-local-file-exist/ already exist.

###Step 4: 
After change config, you need to run ```composer install``` or ```composer update``` 
and run ```vendor/bin/codecept build```.

###Step 5. 

Command for run automation:

```vendor\bin\codecept run acceptance --debug tests\acceptance\administrator\g16\Products\ProductManagement\ProductsCest.php```

Change ‘tests\acceptance\administrator\g16\Products\ProductManagement\ProductsCest.php’ to folder or Cest you want run automation.
