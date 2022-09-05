## Zip/code
Define and manage all zipcode in redSHOP.

<hr>

### In this article you will fine

<ul>
<li><a href="#overview-1">Overview of Zipcodes/Cities screen</a>
    <ul>
    <li><a href="#field-1">Field</a>
    <li><a href="#action-1">Action</a>
    </ul>

<li><a href="#overview-2">Overview of Zipcodes/Cities details</a>
    <ul>
    <li><a href="#field-2">Field</a>
    <li><a href="#action-2">Action</a>
    </ul>

<li><a href="#working">Working with Zipcodes/Cities within redSHOP</a>
    <ul>
    <li><a href="#create">Create Zipcode item</a>
    <li><a href="#use">Use Zipcode to do?</a>
    </ul>
</ul>

<hr>

You have one web-site use Joomla and installed redSHOP component. Access your web-site by administrator page by (username/password) has been provided.

<img src="./manual/en-US/chapters/customization/img/administrator.png" class="example"/><br><br>

You click on Component on main menu and select on "redSHOP".

<img src="./manual/en-US/chapters/customization/img/img1.png" class="example"/><br><br>

Webpage will display overview page administrator of redSHOP.

<img src="./manual/en-US/chapters/customization/img/img175.png" class="example"/><br><br>

<hr>

<!-- Overview of Manufacturer Listings screen -->
<h2 id="overview-1">Overview of Manufacturer Listings screen</h2>

<h4 id="field-1">Field</h4>

<img src="./manual/en-US/chapters/customization/img/img176.png" class="example"/><br><br>

<ul>
<li><b>(1) City - </b>City where given zip code

<li><b>(2) Country name - </b>Country name where given zip code

<li><b>(3) State name - </b>State name where given zip code

<li><b>(4) Zipcode - </b>Zip Postal Code 

<li><b>(5) ID - </b>ID of the zipcode
</ul>

<hr>

<h4 id="action-1">Action</h4>

<img src="./manual/en-US/chapters/customization/img/img177.png" class="example"/><br><br>

<ul>
<li><b>New -  </b>appears when adding a new zip code

<li><b>Delete - </b>delete 1 or more items has selected. Items has deleted will remove list zip code items

<li><b>Check-in - </b>will unlock any zip code items when someone viewing it 
</ul>

<hr>

<!-- Overview of Manufacturer details -->
<h2 id="overview-2">Overview of Manufacturer details</h2>

<h4 id="field-2">Field</h4>

<img src="./manual/en-US/chapters/customization/img/img178.png" class="example"/><br><br>

<ul>
<li><b>City - </b>City where given zip code

<li><b>Country name - </b>Country name where given zip code

<li><b>State name - </b>State name where given zip code

<li><b>From - </b>start zipcode

<li><b>To - </b>end zipcode
</ul>

<hr>

<h4 id="action-2">Action</h4>

<img src="./manual/en-US/chapters/customization/img/img179.png" class="example"/><br><br>

<ul>
<li><b>Save - </b>apply the changes made to the  zip code details or create new item and show information change latest on the page 

<li><b>Save & Close - </b>apply the changes made to the zip code details or create new and return to the Zipcode Management 

<li><b>Save & New - </b>apply the changes made to the zip code details or create new and return to the zip code create page new items other 

<li><b>Cancel - </b>return to the Zipcode Management  without saving any changes
</ul>

<hr>

<!-- Working with  Zipcodes/Cities within redSHOP -->
<h2 id="working">Working with  Zipcodes/Cities within redSHOP</h2>

<h4 id="create">Create Zipcode item</h4>

Firstly, user need have states and countries which want to create zipcode

<ul>
<li>Go to backend page of redSHOP and click Customization then select "Zipcodes/Cities" tab. Then click on New button.
<img src="./manual/en-US/chapters/customization/img/img180.png" class="example"/><br><br>

<li>User fill in some data as City, Country Name, State Name, From fields... 
<img src="./manual/en-US/chapters/customization/img/img181.png" class="example"/><br><br>

<li>After that, User click on Save & Close button.
<img src="./manual/en-US/chapters/customization/img/img182.png" class="example"/><br><br>

<li>Webpage will return Zipcode Management with zip code item just created.
<img src="./manual/en-US/chapters/customization/img/img183.png" class="example"/><br><br>
</ul>

Video for Create Zipcode: <a href="https://redshop.fleeq.io/l/tjia63p7fs-4v4itdkzv6">Click here</a>

<hr>

<h4 id="use">Use Zipcode to do?</h4>

Zipcodes/Cities show all zipcode belong to city. To shipper or shopper can know where user live.

Use the Zipcode for the shipping rate that depend on the city where the user lives, the shipping rate will change according to the user's area

User can only see Shipping Rate which have Zipcode same as zipcode of them account, or zipcode of account is in range of zipcode of shipping rate.

<ul>
<li>Example, with user live in HCM and zippcode is 700000.
<img src="./manual/en-US/chapters/customization/img/img184.png" class="example"/><br><br>

<li>User create shipping rate with name is HCM and choose Zip code is 700000.
<img src="./manual/en-US/chapters/customization/img/img185.png" class="example"/><br><br>

<li>When user checkout in frontend, shipping Method only display shipping rate which user have role to see (Mean is zipcode of user's account which is in range of the zipcode of shipping rate)

<li>After user go to frontend page, user login on site.
<img src="./manual/en-US/chapters/customization/img/img186.png" class="example"/><br><br>

<li>Then add to cart some product and click on Checkout. 
<img src="./manual/en-US/chapters/customization/img/img187.png" class="example"/><br><br>

<li>Webpage only display shipping Method which user can choose 
<img src="./manual/en-US/chapters/customization/img/img188.png" class="example"/><br><br>

<li>Add a example with shipping rate. User lives in Denmark and zipcodes is 2000.
<img src="./manual/en-US/chapters/customization/img/img189.png" class="example"/><br><br>

<li>User create an shipping rate with zipcode is 600000.
<img src="./manual/en-US/chapters/customization/img/img190.png" class="example"/><br><br>

<li>With zipcode of the shipping rate, user not in range this zipcode, they can't see this shipping rate. 
<img src="./manual/en-US/chapters/customization/img/img191.png" class="example"/><br><br>
</ul>

Video for Use zipcode: <a href="https://redshop.fleeq.io/l/8poa7y00y0-320npwo4nj">Click here</a>

<hr>

<h6>Last updated on October 16, 2019</h6>