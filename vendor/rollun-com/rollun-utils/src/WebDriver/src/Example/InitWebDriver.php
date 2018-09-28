<?php

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;

const KEY_BROWSER = "browser";
const KEY_SELENIUM_HOST = "host";

//# Create webDriver

//Web driver config.
$webDriverConfig = [
    KEY_BROWSER => WebDriverBrowserType::CHROME, //select chrome browser
    KEY_SELENIUM_HOST => "http://192.168.123.143:4444/wd/hub/",
];

//Create capability with selected browser.
/** @var DesiredCapabilities $capability */
$capability = call_user_func(['\Facebook\WebDriver\Remote\DesiredCapabilities', $webDriverConfig[KEY_BROWSER]]);
//setup vnc enable -> to view debug/vnc info 192.168.123.143:8080
$capability->setCapability("enableVNC", true);
$webDriver = RemoteWebDriver::create(
    $webDriverConfig[KEY_SELENIUM_HOST],
    $capability,
    600 * 1000,
    600 * 1000
);