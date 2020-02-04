Run automation with redSHOP
==========

## using composer to get Codeception

Execute
```
# You need to have Composer in your system, if not download it from here: https://getcomposer.org/
```

## using Selenium call ChromeDriver

Download ChromeDriver: https://chromedriver.chromium.org/downloads

Download Selenium: https://selenium.dev/downloads/.

Put them in a single folder, unzip folder ChromeDriver and run:  
```java -Xmx256m -jar selenium-server-standalone-3.141.59.jar```  
(java must be pre-installed in your OS to run above command)

change 3.141.59.jar to version selenium has been download

## Preparation for running the test

### Step 1:

Clone the repository using
```git clone  git@github.com:redCOMPONENT-COM/redSHOP.git```  

### Step 2:

Moving to the main working directory
```cd redSHOP```

### Step 3:

Setup new site Joomla with the latest version of redSHOP created previously.

Copy file "acceptance.suite.yml.dist" to "acceptance.suite.yml" and change config: "url" to " http://localhost/path/to/your/project/".

NOTE: Make sure admin & database user information & local site path are correctly changed to your own ones.

### Step 4:

After changing config, you need to run ```composer install```
Later on run ```vendor/bin/codecept build```

### Step 5. 

Command for running automation:

```vendor/bin/codecept run acceptance --debug tests/acceptance/administrator/g16/Products/ProductManagement/ProductsCest.php```

Change "tests/acceptance/administrator/g16/Products/ProductManagement/ProductsCest.php" to folder or a Cest you want run automation.
