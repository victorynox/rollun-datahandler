<?php

namespace rollun\test\utils\DbInstaller;

use rollun\installer\TestCase\InstallerTestCase;
use rollun\utils\DbInstaller\DbDefaultInstaller;

class DbDefaultInstallerTest extends InstallerTestCase
{

    protected $outputStream;
    protected $container;

    protected function setUp()
    {
        $this->container = $this->getContainer();
    }

    protected function getDbDefaultInstaller($answers)
    {
        $this->outputStream = $this->getOutputStream();
        $io = $this->getIo($answers, $this->outputStream);
        $dbDefaultInstaller = new DbDefaultInstaller($this->container, $io);
        return $dbDefaultInstaller;
    }

    //==========================================================================

    public function test_Install()
    {
        $installer = $this->getDbDefaultInstaller("y\n");
        $resalt = $installer->install();
        $this->assertEquals(["y"], $resalt);
    }

}
