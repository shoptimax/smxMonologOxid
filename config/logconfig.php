<?php
/**
 * Monolog Configuration file
 */

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

$oConfig = oxRegistry::getConfig();
$sShopId = $oConfig->getShopId();

$config = array();
$config['graylogHost'] = $oConfig->getShopConfVar('smxMonologGlServerIp', $sShopId, 'module:smxmonologoxid');
$config['graylogPort'] = $oConfig->getShopConfVar('smxMonologGlServerPort', $sShopId, 'module:smxmonologoxid');
$config['mysqlHost'] = $oConfig->getConfigParam('dbHost');
$config['mysqlDb'] = $oConfig->getConfigParam('dbName');
$config['mysqlTable'] = 'smxmonolog_logs';
$config['mysqlUser'] = $oConfig->getConfigParam('dbUser');
$config['mysqlPass'] = $oConfig->getConfigParam('dbPwd');
$config['infoLogfilePath'] = './log/smxmonolog_info.log';
$config['errorLogfilePath'] = './log/smxmonolog_error.log';

return array(
    'version' => 1,

    'formatters' => array(
        'spaced' => array(
            'format' => "%datetime% %channel%.%level_name%  %message%\n",
            'include_stacktraces' => true
        ),
        'dashed' => array(
            'format' => "%datetime%-%channel%.%level_name% - %message%\n"
        ),
    ),
    'handlers' => array(
        'console' => array(
            'class' => 'Monolog\Handler\StreamHandler',
            'level' => 'DEBUG',
            'formatter' => 'spaced',
            'stream' => 'php://stdout'
        ),

        'info_file_handler' => array(
            'class' => 'Monolog\Handler\RotatingFileHandler',
            'level' => 'INFO',
            'formatter' => 'dashed',
            'filename' => $config['infoLogfilePath'],
            'maxFiles' => 20
        ),

        'error_file_handler' => array(
            'class' => 'Monolog\Handler\RotatingFileHandler',
            'level' => 'ERROR',
            'formatter' => 'spaced',
            'filename' => $config['errorLogfilePath'],
            'maxFiles' => 20
        ),

        'gelf' => array(
            'class' => 'Monolog\Handler\GelfHandler',
            'level' => 'DEBUG',
            'publisher' => array(
                'class' => 'Gelf\Publisher',
                'transport' => array(
                    'class' => 'Gelf\Transport\UdpTransport',
                    'host' => $config['graylogHost'],
                    'port' => $config['graylogPort']
                )
            ),
        ),

        'mysql' => array(
            'class' => 'MySQLHandler\MySQLHandler',
            'level' => 'DEBUG',
            'pdo' => array(
                'class' => 'PDO',
                'dsn' => 'mysql:dbname='.$config['mysqlDb'].';host='.$config['mysqlHost'],
                'username' => $config['mysqlUser'],
                'passwd' => $config['mysqlPass'],
                'options' => array()
            ),
            'table' => $config['mysqlTable'],
            'additional_fields' => array('line', 'file', 'origin'),
            'bubble' => 'true'
        )
    ),
    'processors' => array(
        'web_processor' => array(
            'class' => 'Monolog\Processor\WebProcessor'
        ),
        'introspection_processor' => array(
            'class' => 'Monolog\Processor\IntrospectionProcessor'
        ),
        'memory_processor' => array(
            'class' => 'Monolog\Processor\MemoryUsageProcessor'
        ),
        'psr_processor' => array(
            'class' => 'Monolog\Processor\PsrLogMessageProcessor'
        ),
        'tag_processor' => array(
            'class' => 'Monolog\Processor\TagProcessor',
            'tags' => array()
        ),
    ),
    'loggers' => array(
        'multiLogger' => array(
            'handlers' => array('console', 'info_file_handler', 'error_file_handler', 'mysql', 'gelf'),
            'processors' => array('psr_processor', 'web_processor', 'introspection_processor', 'memory_processor')
        ),
        'consoleLogger' => array(
            'handlers' => array('console'),
            'processors' => array('psr_processor')
        ),
        'fileLogger' => array(
            'handlers' => array('info_file_handler', 'error_file_handler'),
            'processors' => array('psr_processor', 'web_processor', 'introspection_processor')
        ),
        'dbLogger' => array(
            'handlers' => array('console', 'mysql'),
            'processors' => array('psr_processor', 'web_processor', 'introspection_processor')
        ),
        'gelfLogger' => array(
            'handlers' => array('console', 'gelf'),
            'processors' => array('psr_processor', 'web_processor', 'introspection_processor')
        ),
        'gelfDbLogger' => array(
            'handlers' => array('console', 'gelf', 'mysql'),
            'processors' => array('psr_processor', 'web_processor', 'introspection_processor')
        ),
        'gelfFileLogger' => array(
            'handlers' => array('console', 'info_file_handler', 'error_file_handler', 'gelf'),
            'processors' => array('psr_processor', 'web_processor', 'introspection_processor', 'memory_processor')
        ),
        'dbFileLogger' => array(
            'handlers' => array('console', 'info_file_handler', 'error_file_handler', 'mysql'),
            'processors' => array('psr_processor', 'web_processor', 'introspection_processor', 'memory_processor')
        ),
    )
);