<?php
/**
 * (c) shoptimax GmbH, Nürnberg
 *
 * @package   SmxMonolog
 * @author    shoptimax GmbH <info@shoptimax.de>
 * @copyright 2017 shoptimax GmbH
 * @link      http://www.shoptimax.de
 */

use Shoptimax\SmxMonolog\Log\SmxMonologConfig as SmxMonologConfig;
use Shoptimax\SmxMonolog\Log\SmxMonolog as SmxMonolog;

if (file_exists(__DIR__ . '/../../../../vendor/autoload.php')) {
    include_once __DIR__ . '/../../../../vendor/autoload.php';
}

/**
 * Class smxmonologoxidlogger
 */
class smxmonologoxidlogger extends oxSuperCfg
{
    protected $monologConfig = null;
    protected $monologLogger = null;
    protected $defaultLogLevel = 'INFO';
    private $_enabled = false;

    /**
     * Check if error handling enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->_enabled;
    }

    /**
     * Check if GELF PHP lib available
     *
     * @return bool
     */
    protected function libsAvailable()
    {
        // we can't use class_exists() since OXID
        // can't handle namespaces in oxAutoload yet
        // and throws regex compilation error...
        if (!file_exists(dirname(__FILE__) . '/../../../../vendor/shoptimax/smxmonolog/Log/SmxMonolog.php')
            || !file_exists(dirname(__FILE__) . '/../../../../vendor/autoload.php')
        ) {
            return false;
        }
        return true;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $oConfig = oxRegistry::getConfig();
        $sShopId = $oConfig->getShopId();

        if (!$oConfig->getShopConfVar('smxMonologGlActive', $sShopId, 'module:smxmonologoxid')) {
            return;
        }

        if (!$this->libsAvailable()) {
            $this->_enabled = false;
            error_log("smxGrayLog Fatal - smxmonolog lib not installed! Please install it via composer update!");
            return;
        }
        if ($this->monologConfig === null) {
            // we use a YAML config here
            $yamlConfig = oxRegistry::getConfig()->getModulesDir().'shoptimax/smxmonologoxid/config/logconfig.php';
            $this->monologConfig = new SmxMonologConfig();
            $this->monologConfig->setFileConfig($yamlConfig);
            // choose a logger configuration from the defined loggers
            $loggerType = oxRegistry::getConfig()->getShopConfVar('smxMonologType', $sShopId, 'module:smxmonologoxid');
            $this->monologConfig->setLoggerName($loggerType);
            // set generic origin for filtering etc.
            $this->monologConfig->setCtxtOrigin(oxRegistry::getConfig()->getActiveShop()->oxshops__oxname->value);

            try {
                // new logger, set config and initial log level
                $this->monologLogger = new SmxMonolog(
                    $this->monologConfig,
                    SmxMonolog::getLogLevel($this->defaultLogLevel)
                );
            } catch (Exception $ex) {
                oxRegistry::getUtils()->writeToLog('[' . date('d-M-Y H:i:s e') . "] Error using smxmonologoxid module: " . $ex->getMessage() . "\n", "error_log.txt");
            }
        }
        $this->_enabled = true;
    }

    /**
     * Get the logger object
     *
     * @return null|SmxMonolog
     */
    public function getLogger()
    {
        if ($this->_enabled && $this->monologLogger !== null) {
            return $this->monologLogger;
        }
        return null;
    }

}