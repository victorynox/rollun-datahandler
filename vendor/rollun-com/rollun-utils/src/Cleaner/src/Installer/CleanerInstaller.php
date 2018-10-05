<?php


namespace rollun\datastore\Cleaner;

use rollun\installer\Install\InstallerAbstract;
use rollun\utils\Cleaner\CleaningValidator\Aggregator\Factory\AggregatorAbstractFactory;
use rollun\utils\Cleaner\Factory\CleanerAbstractFactory;

class CleanerInstaller extends InstallerAbstract
{
    /**
     * install
     * @return array
     */
    public function install()
    {
        return [
            'dependencies' => [
                'abstract_factories' => [
                    CleanerAbstractFactory::class,
                ],
            ]
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

    public function isInstall()
    {
        $config = $this->container->get('config');
        return (isset($config['dependencies']['abstract_factories']) &&
            in_array(CleanerAbstractFactory::class, $config['dependencies']['abstract_factories']));
    }
}