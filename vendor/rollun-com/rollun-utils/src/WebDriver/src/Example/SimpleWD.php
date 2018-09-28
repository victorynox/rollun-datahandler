<?php
//autoload is already required

//init WebDriver
require_once "InitWebDriver.php";

//get google search page.
$webDriver->get("https://google.com/");
$imagePath = "public/image";
if(!file_exists($imagePath)) mkdir($imagePath);
$webDriver->takeScreenshot("{$imagePath}/simple.png");
$webDriver->quit();
echo "<img src='/image/simple.png'>";
