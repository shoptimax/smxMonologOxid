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
 * Class smxmonologoxidoxbasket
 */
class smxmonologoxidoxbasket extends smxmonologoxidoxbasket_parent
{
    /**
     * Adds user item to basket. Returns oxBasketItem object if adding succeeded
     *
     * @param string $sProductID       id of product
     * @param double $dAmount          product amount
     * @param mixed  $aSel             product select lists (default null)
     * @param mixed  $aPersParam       product persistent parameters (default null)
     * @param bool   $blOverride       marker to accumulate passed amount or renew (default false)
     * @param bool   $blBundle         marker if product is bundle or not (default false)
     * @param mixed  $sOldBasketItemId id if old basket item if to change it
     *
     * @throws oxOutOfStockException oxArticleInputException, oxNoArticleException
     *
     * @return object
     */
    public function addToBasket($sProductID, $dAmount, $aSel = null, $aPersParam = null, $blOverride = false, $blBundle = false, $sOldBasketItemId = null)
    {
        $ret = parent::addToBasket($sProductID, $dAmount, $aSel, $aPersParam, $blOverride, $blBundle, $sOldBasketItemId);

        $sShopId = oxRegistry::getConfig()->getShopId();
        $blTrackBasket = true;//$this->getConfig()->getShopConfVar('smxMonologTrBasket', $sShopId, 'module:smxmonologoxid');
        if ($blTrackBasket) {
            $this->trackEvent($sProductID);
        }
        return $ret;
    }

    /**
     * Track via monolog
     *
     * @param string $sProductId The article id
     *
     * @return null
     */
    protected function trackEvent($sProductId)
    {
        $smxmonologoxidlogger = oxRegistry::get('smxmonologoxidlogger');
        if ($smxmonologoxidlogger
            && ($logger = $smxmonologoxidlogger->getLogger()) != null
        ) {
            $oArt = oxNew('oxArticle');
            if ($oArt->load($sProductId)) {
                $msg = sprintf(oxRegistry::getLang()->translateString("SMX_MONOLOG_PRODUCT_ADDED_TO_BASKET"), $oArt->oxarticles__oxartnum->value);
                $logger->log($msg, array(), 'INFO');
            }
        }
    }
}