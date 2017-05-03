<?php
/**
 * (c) shoptimax GmbH, Nürnberg
 *
 * @package   SmxMonolog
 * @author    shoptimax GmbH <info@shoptimax.de>
 * @copyright 2017 shoptimax GmbH
 * @link      http://www.shoptimax.de
 */

$sLangName = 'Deutsch';

$aLang = array(
    'charset' => 'UTF-8',
    'smxmonolog' => 'smx Monolog',
    // Settings interface translations
    'SHOP_MODULE_GROUP_smxMonologSettings' => 'smx Monolog Module Settings',
    'SHOP_MODULE_smxMonologGlActive' => 'Monolog Exception Logging aktiv?',
    'SHOP_MODULE_smxMonologGlServerIp' => 'Graylog Server IP',
    'SHOP_MODULE_smxMonologGlServerPort' => 'Graylog Server Port',
    'SHOP_MODULE_smxMonologType'     => 'Logging Typ',
    'SHOP_MODULE_smxMonologType_gelfLogger'   => 'Graylog',
    'SHOP_MODULE_smxMonologType_fileLogger'   => 'Datei',
    'SHOP_MODULE_smxMonologType_dbLogger'   => 'MySQL',
    'SHOP_MODULE_smxMonologType_gelfDbLogger'   => 'Graylog und Mysql',
    'SHOP_MODULE_smxMonologType_multiLogger'   => 'Graylog, MySQL und Datei',
    'SHOP_MODULE_smxMonologLogErrs'  => 'Auch PHP Errors loggen (error_log.txt)',
    'SHOP_MODULE_smxMonologOnlyErrs' => 'Nur Errors loggen (keine Warnings)',
    'SHOP_MODULE_smxMonologChan'     => 'Custom Graylog Channel / Ident (Standard ist sonst z.B. "OXID-EE meinshopname.de")',
    'SHOP_MODULE_smxMonologAddFields'=> 'Zus&auml;tzliche Felder (Key => Value)',
    'SHOP_MODULE_smxMonologTrBasket' => '"Zum Warenkorb"-Event tracken?',
    'SHOP_MODULE_smxMonologTrOrder'  => '"Bestell"-Event tracken?',
);