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
 * Class smxmonologoxidoxorder
 */
class smxmonologoxidoxorder extends smxmonologoxidoxorder_parent
{
    /**
     * Order checking, processing and saving method.
     * Before saving performed checking if order is still not executed (checks in
     * database oxorder table for order with know ID), if yes - returns error code 3,
     * if not - loads payment data, assigns all info from basket to new oxorder object
     * and saves full order with error status. Then executes payment. On failure -
     * deletes order and returns error code 2. On success - saves order (oxorder::save()),
     * removes article from wishlist (oxorder::_updateWishlist()), updates voucher data
     * (oxorder::_markVouchers()). Finally sends order confirmation email to customer
     * (oxemail::SendOrderEMailToUser()) and shop owner (oxemail::SendOrderEMailToOwner()).
     * If this is order recalculation, skipping payment execution, marking vouchers as used
     * and sending order by email to shop owner and user
     * Mailing status (1 if OK, 0 on error) is returned.
     *
     * @param oxBasket $oBasket              Shopping basket object
     * @param object   $oUser                Current user object
     * @param bool     $blRecalculatingOrder Order recalculation
     *
     * @return integer
     */
    public function finalizeOrder(oxBasket $oBasket, $oUser, $blRecalculatingOrder = false)
    {
        $ret = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);
        // track order?
        if ($ret === oxOrder::ORDER_STATE_OK || $ret === oxOrder::ORDER_STATE_MAILINGERROR) {
            $sShopId = oxRegistry::getConfig()->getShopId();
            $blTrackOrder = $this->getConfig()->getShopConfVar('smxGraylogTrOrder', $sShopId, 'module:smxgraylog');
            if ($blTrackOrder) {
                $this->trackEvent($ret);
            }
        }
        return $ret;
    }

    /**
     * Track via monolog
     *
     * @param string $ret The order status
     *
     * @return null
     */
    protected function trackEvent($ret)
    {
        $smxmonologoxidlogger = oxRegistry::get('smxmonologoxidlogger');
        if ($smxmonologoxidlogger
            && ($logger = $smxmonologoxidlogger->getLogger()) != null
        ) {
            $msg = sprintf(oxRegistry::getLang()->translateString("SMX_MONOLOG_ORDER_FINISHED"), $this->oxorder__oxordernr->value);
            $logger->log($msg . " Order status: " . $ret, array(), 'INFO');
        }
    }
}