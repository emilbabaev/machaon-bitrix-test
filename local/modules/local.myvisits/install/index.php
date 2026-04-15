<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

Loader::includeModule('main');
IncludeModuleLangFile(__FILE__);

if (!class_exists('local_myvisits')) {
class local_myvisits extends \CModule
{
    var $MODULE_ID = "local.myvisits";
    var $MODULE_NAME = "My Visits Logger";
    var $MODULE_DESCRIPTION = "A module to log and display visitor activity.";
    var $MODULE_VERSION = "1.0.0";
    var $MODULE_VERSION_DATE = "2026-04-15";
    var $PARTNER_NAME = "Local";
    var $PARTNER_URI = "";

    public function DoInstall()
    {
        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallFiles();
        $this->InstallEvents();

        $APPLICATION->IncludeAdminFile(
            GetMessage("MYVISITS_INSTALL_TITLE"),
            __FILE__
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $this->UnInstallEvents();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            GetMessage("MYVISITS_UNINSTALL_TITLE"),
            __FILE__
        );
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . "/local/modules/{$this->MODULE_ID}/install/files",
            $_SERVER["DOCUMENT_ROOT"],
            true,
            true
        );
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx("/myvisitors.php");

        $publicFile = $_SERVER["DOCUMENT_ROOT"] . "/myvisitors.php";
        if (file_exists($publicFile)) {
            @unlink($publicFile);
        }
    }

    public function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            '\MyVisits\VisitLogger',
            'onEpilog'
        );
    }

    public function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            '\MyVisits\VisitLogger',
            'onEpilog'
        );
    }
}
}
