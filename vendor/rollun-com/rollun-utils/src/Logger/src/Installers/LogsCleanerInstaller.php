<?php


namespace rollun\logger\Installers;

use Psr\Log\LoggerInterface;
use rollun\cleaner\CleanableList\TableGatewayCleanableList;
use rollun\datastore\Cleaner\AggregatorValidatorInstaller;
use rollun\datastore\Cleaner\CleanerInstaller;
use rollun\installer\Install\InstallerAbstract;
use rollun\logger\Cleaner\Validators\ExpireTimeValidator;
use rollun\logger\Cleaner\Validators\Factory\ExpireTimeValidatorAbstractFactory;
use rollun\logger\Cleaner\Validators\LevelValidator;
use rollun\logger\Cleaner\Validators\LevelValidatorAbstractFactory;
use rollun\logger\LoggerInstaller;
use rollun\tableGateway\Factory\TableGatewayAbstractFactory;
use rollun\utils\Cleaner\CleanableList\Factory\AbstractCleanableListAbstractFactory;
use rollun\utils\Cleaner\CleanableList\Factory\TableGatewayCleanableListAbstractFactory;
use rollun\utils\Cleaner\Cleaner;
use rollun\utils\Cleaner\CleaningValidator\Aggregator\Factory\AggregatorAbstractFactory;
use rollun\utils\Cleaner\CleaningValidator\Aggregator\LogicalAndValidator;
use rollun\utils\Cleaner\CleaningValidator\Aggregator\LogicalOrValidator;
use rollun\utils\Cleaner\CleaningValidator\Factory\AbstractCleaningValidatorAbstractFactory;
use rollun\utils\Cleaner\Factory\CleanerAbstractFactory;

class LogsCleanerInstaller extends InstallerAbstract
{

    /**
     * install
     * @return array
     */
    public function install()
    {
        $adapterName = $this->askParams("Write logger db adapter name: ");
        $loggerTableName = $this->askParams("Write logger table name: ");

        return [
            'dependencies' => [
                "abstract_factories" => [
                    ExpireTimeValidatorAbstractFactory::class,
                    LevelValidatorAbstractFactory::class,
                ]
            ],
            AbstractCleaningValidatorAbstractFactory::KEY => [
                "emergencyLevelValidator" => [
                    LevelValidatorAbstractFactory::KEY_CLASS => LevelValidator::class,
                    LevelValidatorAbstractFactory::KEY_LEVEL => "emergency",
                ],
                "alertLevelValidator" => [
                    LevelValidatorAbstractFactory::KEY_CLASS => LevelValidator::class,
                    LevelValidatorAbstractFactory::KEY_LEVEL => "alert",
                ],
                "criticalLevelValidator" => [
                    LevelValidatorAbstractFactory::KEY_CLASS => LevelValidator::class,
                    LevelValidatorAbstractFactory::KEY_LEVEL => "critical",
                ],
                "errorLevelValidator" => [
                    LevelValidatorAbstractFactory::KEY_CLASS => LevelValidator::class,
                    LevelValidatorAbstractFactory::KEY_LEVEL => "error",
                ],
                "warningLevelValidator" => [
                    LevelValidatorAbstractFactory::KEY_CLASS => LevelValidator::class,
                    LevelValidatorAbstractFactory::KEY_LEVEL => "warning",
                ],
                "noticeLevelValidator" => [
                    LevelValidatorAbstractFactory::KEY_CLASS => LevelValidator::class,
                    LevelValidatorAbstractFactory::KEY_LEVEL => "notice",
                ],
                "infoLevelValidator" => [
                    LevelValidatorAbstractFactory::KEY_CLASS => LevelValidator::class,
                    LevelValidatorAbstractFactory::KEY_LEVEL => "info",
                ],
                "debugLevelValidator" => [
                    LevelValidatorAbstractFactory::KEY_CLASS => LevelValidator::class,
                    LevelValidatorAbstractFactory::KEY_LEVEL => "debug",
                ],
                "emergencyExpireTimeValidator" => [
                    ExpireTimeValidatorAbstractFactory::KEY_CLASS => ExpireTimeValidator::class,
                    ExpireTimeValidatorAbstractFactory::KEY_SECOND_TO_EXPIRE => 60 * 60 * 504,//504 hour - 21d
                ],
                "alertExpireTimeValidator" => [
                    ExpireTimeValidatorAbstractFactory::KEY_CLASS => ExpireTimeValidator::class,
                    ExpireTimeValidatorAbstractFactory::KEY_SECOND_TO_EXPIRE => 60 * 60 * 336,//336 hour - 14 d
                ],
                "criticalExpireTimeValidator" => [
                    ExpireTimeValidatorAbstractFactory::KEY_CLASS => ExpireTimeValidator::class,
                    ExpireTimeValidatorAbstractFactory::KEY_SECOND_TO_EXPIRE => 60 * 60 * 336,//336 hour - 14d
                ],
                "errorExpireTimeValidator" => [
                    ExpireTimeValidatorAbstractFactory::KEY_CLASS => ExpireTimeValidator::class,
                    ExpireTimeValidatorAbstractFactory::KEY_SECOND_TO_EXPIRE => 60 * 60 * 144,//144 hour - 6d
                ],
                "warningExpireTimeValidator" => [
                    ExpireTimeValidatorAbstractFactory::KEY_CLASS => ExpireTimeValidator::class,
                    ExpireTimeValidatorAbstractFactory::KEY_SECOND_TO_EXPIRE => 60 * 60 * 120,//120 hour - 5d
                ],
                "noticeExpireTimeValidator" => [
                    ExpireTimeValidatorAbstractFactory::KEY_CLASS => ExpireTimeValidator::class,
                    ExpireTimeValidatorAbstractFactory::KEY_SECOND_TO_EXPIRE => 60 * 60 * 96,//96 hour - 4d
                ],
                "infoExpireTimeValidator" => [
                    ExpireTimeValidatorAbstractFactory::KEY_CLASS => ExpireTimeValidator::class,
                    ExpireTimeValidatorAbstractFactory::KEY_SECOND_TO_EXPIRE => 60 * 60 * 72,//72 hour - 3d
                ],
                "debugExpireTimeValidator" => [
                    ExpireTimeValidatorAbstractFactory::KEY_CLASS => ExpireTimeValidator::class,
                    ExpireTimeValidatorAbstractFactory::KEY_SECOND_TO_EXPIRE => 60 * 60 * 48, //48 hour
                ],
                "emergencyValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalOrValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "emergencyLevelValidator",
                        "emergencyExpireTimeValidator",
                    ],
                ],
                "alertValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalOrValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "alertLevelValidator",
                        "alertExpireTimeValidator",
                    ],
                ],
                "criticalValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalOrValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "criticalLevelValidator",
                        "criticalExpireTimeValidator",
                    ],
                ],
                "errorValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalOrValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "errorLevelValidator",
                        "errorExpireTimeValidator",
                    ],
                ],
                "warningValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalOrValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "warningLevelValidator",
                        "warningExpireTimeValidator",
                    ],
                ],
                "noticeValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalOrValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "noticeLevelValidator",
                        "noticeExpireTimeValidator",
                    ],
                ],
                "infoValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalOrValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "infoLevelValidator",
                        "infoExpireTimeValidator",
                    ],
                ],
                "debugValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalOrValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "debugLevelValidator",
                        "debugExpireTimeValidator",
                    ],
                ],
                "logsValidator" => [
                    AggregatorAbstractFactory::KEY_CLASS => LogicalAndValidator::class,
                    AggregatorAbstractFactory::KEY_VALIDATORS => [
                        "emergencyValidator",
                        "alertValidator",
                        "criticalValidator",
                        "errorValidator",
                        "warningValidator",
                        "noticeValidator",
                        "infoValidator",
                        "debugValidator",
                    ],
                ]
            ],
            AbstractCleanableListAbstractFactory::KEY => [
                "{$loggerTableName}TableGatewayCleanableList" => [
                    TableGatewayCleanableListAbstractFactory::KEY_TABLE_GATEWAY => $loggerTableName
                ]
            ],
            CleanerAbstractFactory::KEY => [
                "logger{$loggerTableName}TableCleaner" => [
                    CleanerAbstractFactory::KEY_CLASS => Cleaner::class,
                    CleanerAbstractFactory::KEY_CLEANABLE_LIST => "{$loggerTableName}TableGatewayCleanableList",
                    CleanerAbstractFactory::KEY_CLEANABLE_VALIDATOR => "logsValidator"
                ]
            ],
            TableGatewayAbstractFactory::KEY => [
                $loggerTableName => [
                    TableGatewayAbstractFactory::KEY_ADAPTER => $adapterName
                ]
            ],
        ];
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {

    }

    /**
     * Return true if install, or false else
     * @return bool
     */
    public function isInstall()
    {
        return false;
    }

    /**
     * Return string with description of installable functional.
     * @param string $lang ; set select language for description getted.
     * @return string
     */
    public function getDescription($lang = "en")
    {
        switch ($lang) {
            case "ru":
                $description = "Предоставляет сервис для очистки";
                break;
            default:
                $description = "Does not exist.";
        }
        return $description;
    }

    public function getDependencyInstallers()
    {
        return [
            LoggerInstaller::class,
            CleanerInstaller::class,
            AggregatorValidatorInstaller::class,
        ];
    }
}