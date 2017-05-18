There are basically only 3 files a) in src/controller/PlaidController.php b)In Component, Webservice controllerUtility and c)In Lib, PlaidAPI.php

This is a webservice implemented.
It can be executed using soap UI or advanced REST CLient plugin on chrome browser.
Run composer install in root directory, if it shows vendor/autoload error.
The input should be 
{
	"access_token": "access-sandbox-e6a609bd-4be8-4f1a-a754-fad52b800bee",
	"start_date": "2017-04-11",
	"end_date": "2017-04-11"
}
