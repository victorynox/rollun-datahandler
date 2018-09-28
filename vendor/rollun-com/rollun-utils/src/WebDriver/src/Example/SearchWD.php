<?php
//autoload is already required

//create image dir
$imagePath = "public/image";
if(!file_exists($imagePath)) mkdir($imagePath);

//init WebDriver
require_once "InitWebDriver.php";

$data = "";

//get google search page.
$webDriver->get("https://google.com/");
$webDriver->takeScreenshot("{$imagePath}/search_form_empty.png");
$data .= "<img src='/image/search_form_empty.png'>";
//find search form
$searchFormElement = $webDriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("form#tsf"));
//Find search form text box
$searchElement = $webDriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("input#lst-ib"));
//send search key
$searchElement->sendKeys("Test Search");
$webDriver->takeScreenshot("{$imagePath}/search_form_with_text.png");
$data .= "<img src='/image/search_form_with_text.png'>";
//submit form
$searchFormElement->submit();
sleep(1);//wait load result
$webDriver->takeScreenshot("{$imagePath}/search_result.png");
$data .= "<img src='/image/search_result.png'>";
$webDriver->quit();
echo $data;