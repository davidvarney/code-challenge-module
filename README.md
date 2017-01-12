**Code-Challenge-Module**
=======================

Overview
-------------
This Prestashop module was created for a Code Challenge. Once installed the module simply takes a zip code and retrieves the weather for the given zip code via an API.

The weather API used is coming from openweathermap.org

Install Instructions
--------------------------

### Part 1

#### via Git Submodule

1. Make your way to the root of you Git project and run the following:
	<pre>git submodule add git@github.com:davidvarney/code-challenge-module.git <i>path/to/prestashop/modules/folder/here</i>/codechallengemodule</pre>
2. If you were already on the Modules and Services page then go ahead and refresh the page

#### via Zip File

1. Download the file at the following URL:
	https://github.com/davidvarney/code-challenge-module
2. Upload the file within the Admin-->Modules and Services area of Prestashop

### Part 2

1. Find the module within the "Modules and Services" page. The module should go by the name of Code-Challenge-Module
2. Click the Install button for our module
3. Click the "Proceed with the Installation" button displayed in the pop up
4. Enter the desired zip code after the module installs and click the "Save" button
5. Make your way to the "Positions" section
6. Search for our module "Code-Challenge-Module" and place it in any position desired. For the time being the module only has 2 hooks: displayLeftColumn and displayRightColumn
7. Enjoy the weather data!
