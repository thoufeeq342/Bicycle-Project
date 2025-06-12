var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'BSS_Deliverydate/js/order/place-order-mixin': true
            },
            'BSS_Deliverydate/js/view/additional-phone-field': {
                'BSS_Deliverydate/js/view/additional-phone-field':true
            }
        }
    }
};