<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arModuleVersion = array(
    "VERSION" => "1.0.0",
    "VERSION_DATE" => "2026-04-15"
);

$arModule = array(
    "id" => "local.myvisits",
    "name" => "My Visits Logger",
    "description" => "A module to log and display visitor activity.",
    "partnertype" => "Local",
    "partneruri" => "",
    "version" => $arModuleVersion,
    "need_install" => true,
    "public_files" => array(),
    "install_reg_file" => "install/index.php",
    "uninstall_reg_file" => "install/index.php",
    "access" => array(
        "install" => array(),
        "uninstall" => array(),
        "update" => array(),
    )
);
