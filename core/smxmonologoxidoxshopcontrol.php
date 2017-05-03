<?php
/**
 * (c) shoptimax GmbH, Nürnberg
 *
 * @package   SmxMonolog
 * @author    shoptimax GmbH <info@shoptimax.de>
 * @copyright 2017 shoptimax GmbH
 * @link      http://www.shoptimax.de
 */
require_once dirname(__FILE__) . "/smxmonologoxidlogger.php";
register_shutdown_function(array('smxmonologoxidoxshopcontrol', 'logShutdown'));

/**
 * Class smxmonologoxidoxshopcontrol
 */
class smxmonologoxidoxshopcontrol extends smxmonologoxidoxshopcontrol_parent
{
    /**
     * Shutdown, if fatal error occurs
     *
     * @return null
     */
    public static function logShutdown()
    {
        $smxmonologoxidlogger = new smxmonologoxidlogger();
        if (!$smxmonologoxidlogger) {
            return;
        }
        $error = error_get_last();
        if (($logger = $smxmonologoxidlogger->getLogger()) != null
            && $error !== null && $error['type'] === E_ERROR
        ) {
            $errno   = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr  = $error["message"];
            $errstr  = "[".date('d-M-Y H:m:s e')."] PHP Fatal error: " . $errstr;
            $msg = $errstr . " in $errfile on line $errline ";
            $logger->log($msg, array(), 'ERROR');
        }
    }

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
        $smxmonologoxidlogger = new smxmonologoxidlogger();
        if ($smxmonologoxidlogger
            && ($logger = $smxmonologoxidlogger->getLogger()) != null
        ) {
            $logger->log($msg, array('file' => $errfile, 'line' => $errline), 'ERROR');
        }
    }

    /**
     * Render oxView object
     *
     * @param oxView $oViewObject view object to render
     *
     * @return string
     */
    protected function _render($oViewObject)
    {
        $sRet = parent::_render($oViewObject);
        $sTemplateName = $oViewObject->render();
        // check if template dir exists, if not display this error, too!
        $sTemplateFile = $this->getConfig()->getTemplatePath($sTemplateName, $this->isAdmin());
        if (!file_exists($sTemplateFile)) {
            $oEx = oxNew('oxSystemComponentException');
            $oEx->setMessage('EXCEPTION_SYSTEMCOMPONENT_TEMPLATENOTFOUND' . " Template: " . $sTemplateName);
            $oEx->setComponent($sTemplateName);

            // log
            self::logError($oEx->getString());
        }

        return $sRet;
    }

    /**
     * Initiates object (object::init()), executes passed function
     * (oxShopControl::executeFunction(), if method returns some string - will
     * redirect page and will call another function according to returned
     * parameters), renders object (object::render()). Performs output processing
     * oxOutput::ProcessViewArray(). Passes template variables to template
     * engine witch generates output. Output is additionally processed
     * (oxOutput::Process()), fixed links according search engines optimization
     * rules (configurable in Admin area). Finally echoes the output.
     *
     * @param string $sClass      Name of class
     * @param string $sFunction   Name of function
     * @param array  $aParams     Parameters array
     * @param array  $aViewsChain Array of views names that should be initialized also
     */
    protected function _process($sClass, $sFunction, $aParams = null, $aViewsChain = null)
    {
        $sShopId = $this->getConfig()->getShopId();
        $replaceErrorHandler = false;
        $blLogErrors = $this->getConfig()->getShopConfVar('smxMonologLogErrs', $sShopId, 'module:smxmonologoxid');
        $monologLogger = oxRegistry::get('smxmonologoxidlogger');
        if ($monologLogger && $monologLogger->getEnabled() && $blLogErrors) {
            $replaceErrorHandler = true;
            set_error_handler(array($this, 'monologErrorHandler'), E_ERROR | E_WARNING);
        }

        parent::_process($sClass, $sFunction, $aParams, $aViewsChain);

        // restoring previous error handler
        if ($replaceErrorHandler) {
            restore_error_handler();
        }
    }

    /**
     * @param int    $errno      The error number
     * @param string $errstr     The error string
     * @param string $errfile    The error file
     * @param int    $errline    The error line
     * @param array  $errcontext The context
     *
     * @return bool
     */
    public function monologErrorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $sShopId = $this->getConfig()->getShopId();
        $blOnlyErrors = $this->getConfig()->getShopConfVar('smxMonologOnlyErrs', $sShopId, 'module:smxmonologoxid');
        if (!(error_reporting() & $errno) || ($blOnlyErrors && $errno !== E_ERROR)) {
            // this error is not contained in error_reporting
            return false;
        }

        switch ($errno) {
            case E_NOTICE:
                $sErrType = "PHP Notice";
                break;
            case E_ERROR:
                $sErrType = "PHP Error";
                break;
            case E_WARNING:
            default:
                $sErrType = "PHP Warning";
        }
        $errString = "[" . date('d-M-Y H:i:s') . " " . date_default_timezone_get() . "] " . $sErrType . ": " .  $errstr;
        self::logError($errString, $errfile, $errline);

        // let the standard error handler kick in :)
        return false;
    }

}