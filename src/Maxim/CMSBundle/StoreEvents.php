<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 04/09/13
 * Time: 15:26
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle;


final class StoreEvents {
    const STORE_ORDER            = "store.order";
    const STORE_VALIDATE         = "store.validate";
    const STORE_VALIDATE_FAILURE = "store.validatefailure";
    const STORE_PAYMENT_SUCCESS  = "store.payment.pay";
}