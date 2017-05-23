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
 * Class smxmonologoxidoxexception
 */
class smxmonologoxidoxexception extends smxmonologoxidoxexception_parent
{
    /**
     * Send a message to the logger
     *
     * @param string $msg     The log message
     * @param string $errfile The error file
     * @param int    $errline The line number
     *
     * @return null
     */
    protected static function logError($msg, $errfile = '', $errline = null)
    {
        $oConfig = oxRegistry::getConfig();
        $sShopId = $oConfig->getShopId();
        $blLogErrors = $oConfig->getShopConfVar('smxMonologLogExc', $sShopId, 'module:smxmonologoxid');
        $smxmonologoxidlogger = new smxmonologoxidlogger();
        if ($blLogErrors && $smxmonologoxidlogger
            && ($logger = $smxmonologoxidlogger->getLogger()) != null
        ) {
            $logger->log($msg, array('file' => $errfile, 'line' => $errline), 'ERROR');
        }
    }

    /**
     * Get complete string dump, should be overwritten by
     * excptions extending this exceptions
     * if they introduce new fields
     *
     * @return string
     */
    public function getString()
    {
        $sWarning = parent::getString();
        return $sWarning;
    }
    /**
     * Prints exception in file EXCEPTION_LOG.txt
     *
     * @return null
     */
    public function debugOut()
    {
        try {
            parent::debugOut();
            $sLogMsg = $this->getString() . "\n---------------------------------------------\n";
            // log
            $this->logError($sLogMsg);
        } catch (Exception $e) {
        }
    }
}