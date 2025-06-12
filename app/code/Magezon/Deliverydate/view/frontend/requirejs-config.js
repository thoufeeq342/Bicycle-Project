var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Magezon_Deliverydate/js/order/place-order-mixin': true
            },
            'Magezon_Deliverydate/js/view/additional-phone-field': {
                'Magezon_Deliverydate/js/view/additional-phone-field':true
            }
        }
    }
};