<?php
/**
 * (c) shoptimax GmbH, Nürnberg
 *
 * @package   SmxMonolog
 * @author    shoptimax GmbH <info@shoptimax.de>
 * @copyright 2017 shoptimax GmbH
 * @link      http://www.shoptimax.de
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'smxmonologoxid',
    'title'       => array(
        'de' => 'smx Monolog Logging',
        'en' => 'smx Monolog Logging',
    ),
    'description' => array(
        'de' => 'smx Monolog Logging',
        'en' => 'smx Monolog Logging',
    ),
    'thumbnail'   => 'out/pictures/shoptimax_logo.jpg',
    'version'     => '1.0.1',
    'author'      => 'shoptimax GmbH',
    'url'         => 'http://www.shoptimax.de',
    'email'       => 'module@shoptimax.de',
    'extend'      => array(
        'oxshopcontrol' => 'shoptimax/smxmonologoxid/core/smxmonologoxidoxshopcontrol',
        'oxexception' => 'shoptimax/smxmonologoxid/core/smxmonologoxidoxexception',
        'oxbasket' => 'shoptimax/smxmonologoxid/application/models/smxmonologoxidoxbasket',
        'oxorder'  => 'shoptimax/smxmonologoxid/application/models/smxmonologoxidoxorder',
    ),
    'files'       => array(
        'smxmonologoxidlogger' => 'shoptimax/smxmonologoxid/core/smxmonologoxidlogger.php',
    ),
    'templates'   => array(
    ),
    'blocks'      => array(
    ),
    'settings'    => array(
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologGlActive',
            'type'  => 'bool',
            'value' => true,
            'position' => 1
        ),
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologGlServerIp',
            'type'  => 'str',
            'value' => '127.0.0.1',
            'position' => 2
        ),
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologGlServerPort',
            'type'  => 'num',
            'value' => 12201,
            'position' => 3
        ),
        array('group' => 'smxMonologSettings',
              'name' => 'smxMonologType',
              'type' => 'select',
              'value' => 'gelfLogger',
              'constraints' => 'gelfLogger|fileLogger|dbLogger|dbFileLogger|gelfDbLogger|gelfFileLogger|multiLogger',
              'position' => 4
        ),
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologLogExc',
            'type'  => 'bool',
            'value' => true,
            'position' => 5
        ),
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologLogErrs',
            'type'  => 'bool',
            'value' => true,
            'position' => 6
        ),
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologOnlyErrs',
            'type'  => 'bool',
            'value' => false,
            'position' => 7
        ),
        /*
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologChan',
            'type'  => 'str',
            'value' => '',
            'position' => 7
        ),
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologAddFields',
            'type'  => 'aarr',
            'value' => array(),
            'position' => 8
        ),
        */
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologTrBasket',
            'type'  => 'bool',
            'value' => false,
            'position' => 9
        ),
        array(
            'group' => 'smxMonologSettings',
            'name'  => 'smxMonologTrOrder',
            'type'  => 'bool',
            'value' => false,
            'position' => 10
        ),
    ),
    'events'      => array(
        /*
        'onActivate'   => 'smxMonologModule::onActivate',
        'onDeactivate' => 'smxMonologModule::onDeactivate',
        */
    ),
);