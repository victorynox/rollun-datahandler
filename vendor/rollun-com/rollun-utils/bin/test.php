<?php

use Psr\Log\LoggerInterface;
use rollun\utils\TelegramClient;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);

/**
 * @var $logger LoggerInterface
 */
$logger = $container->get(LoggerInterface::class);
$logger->info("Test message", ["test" => "asdasd"]);