var MultiPaymentManagement = {
    CODE: 'multipaymentforpos',
    LABEL: {},
    selectedPayments: [],
    remaining: 0,
    grandTotal: 0,
    init: function(data){
        this.LABEL = (data.label)?data.label:{
            'remaining' : 'Remain money',
            'change' : 'Expected Change',
            'partial' : 'Mark As Partial',
            'placeOrder' : 'Place Order'
        };
        this.initElement();
        this.initObservable();
        this.resetData();
    },
    initElement: function(){
        this.remainingLabelEl = ($D('#remaining_label').length > 0)?$D('#remaining_label'):false;
        this.remainingAmountEl = ($D('#remaining_amount').length > 0)?$D('#remaining_amount'):false;
        this.placeOrderButtonEl = ($D('#bt_place_order').length > 0)?$D('#bt_place_order'):false;
        this.createInvoiceContainerEl = ($D('#create_invoice_container').length > 0)?$D('#create_invoice_container'):false;
        this.createInvoiceButtonEl = ($D('#create_invoice').length > 0)?$D('#create_invoice'):false;
        this.grandTotalEl = ($D('#webpos_subtotal_button').length > 0)?$D('#webpos_subtotal_button'):false;
        this.needPayAmountLabelEl = ($D('#multipaymentforpos_need_pay_amount').length > 0)?$D('#multipaymentforpos_need_pay_amount'):false;
        this.remainContainerEl = ($D('.payment-remain-info').length > 0)?$D('.payment-remain-info'):false;
    },
    initObservable: function(){
        var self = this;
        EventManager.observer('reload_all_block_after', function(event, data){
            self.initElement();
            self.populateData();
            self.calculateData();
        });
        EventManager.observer('save_shipping_payment_after', function(event, data){
            self.initElement();
            self.populateData();
            self.calculateData();
        });
        EventManager.observer('start_new_order_after', function(event, data){
            self.resetData();
        });
    },
    populateData: function(){
        if(this.selectedPayments.length > 0) {
            $D.each(this.selectedPayments, function () {
                if (this && this.id && this.amount > 0) {
                    $D('#'+this.id).val(this.amount);
                }
            });
        }
    },
    resetData: function(){
        this.selectedPayments = [];
        this.remaining = 0;
        this.calculateData();
    },
    isSelectedPayment: function(id){
        var selected = false;
        if(this.selectedPayments.length > 0) {
            $D.each(this.selectedPayments, function () {
                if (this && this.id == id) {
                    selected = true;
                }
            });
        }
        return selected;
    },
    removeSelectedPayment: function(id){
        var self = this;
        if(self.selectedPayments.length > 0) {
            $D.each(self.selectedPayments, function (index, payment) {
                if (payment&& payment.id == id) {
                    delete self.selectedPayments[index];
                }
            });
        }
    },
    setSelectedPaymentData: function(id, key, value){
        if(this.selectedPayments.length > 0) {
            $D.each(this.selectedPayments, function () {
                if (this.id == id) {
                    this[key] = value;
                }
            });
        }
    },
    addSelectedPayment: function(id, amount){
        if(this.isSelectedPayment(id)){
            this.setSelectedPaymentData(id, 'amount', amount);
        }else{
            this.selectedPayments.push({
                id: id,
                amount: amount
            });
        }
    },
    change: function(focusEl){
        if(focusEl){
            var id = focusEl.id;
            var value = convertLongNumber(parseFloat(focusEl.value));
            if(value > 0){
                this.addSelectedPayment(id, value);
            }else{
                focusEl.value = '';
                this.removeSelectedPayment(id);
            }
        }
        this.calculateData();
    },
    focus: function(focusEl){
        if(focusEl){
            var value = convertLongNumber(focusEl.value);
            if(value == '' && this.remaining > 0){
                focusEl.value = this.remaining;
                if($D('#'+focusEl.id).length > 0){
                    $D('#'+focusEl.id).change();
                }
            }
        }
        this.calculateData();
    },
    calculateData: function(){
        var grandTotal = 0;
        var paidAmount = 0;
        var remaining = 0;
        if(this.selectedPayments.length > 0){
            $D.each(this.selectedPayments, function(){
                if(this.amount){
                    paidAmount += this.amount;
                }
            });
        }
        if(this.grandTotalEl){
            grandTotal = convertLongNumber(this.grandTotalEl.html());
            this.grandTotal = grandTotal;
        }
        remaining = grandTotal - paidAmount;
        this.remaining = convertLongNumber(remaining);
        this.updateViewData();
    },
    updateViewData: function(){
        if(this.validateCondition()) {
            if(this.remainContainerEl){
                this.remainContainerEl.show();
            }
            if (this.needPayAmountLabelEl) {
                this.needPayAmountLabelEl.html(getPriceFormatedNoHtml(this.grandTotal));
            }
            if (this.remainingLabelEl) {
                var label = (this.remaining >= 0) ? this.LABEL.remaining : this.LABEL.change;
                this.remainingLabelEl.html(label);
            }
            if (this.remainingAmountEl) {
                var amount = (this.remaining > 0) ? this.remaining : -this.remaining;
                this.remainingAmountEl.html(getPriceFormatedNoHtml(amount));
            }
            if (this.placeOrderButtonEl) {
                if (this.remaining <= 0) {
                    this.placeOrderButtonEl.html(this.LABEL.placeOrder);
                } else {
                    this.placeOrderButtonEl.html(this.LABEL.partial);
                }
            }
            if (this.createInvoiceButtonEl) {
                if (this.remaining <= 0) {
                    this.createInvoiceContainerEl.removeClass('hide');
                    this.createInvoiceContainerEl.addClass('show');
                } else {
                    this.createInvoiceContainerEl.addClass('hide');
                    this.createInvoiceContainerEl.removeClass('show');
                    this.createInvoiceButtonEl.prop('checked', false).change();
                }
            }
        }else{
            if(this.remainContainerEl){
                this.remainContainerEl.hide();
            }
        }
    },
    validateCondition: function(){
        return ($('p_method_multipaymentforpos') && $('p_method_multipaymentforpos').checked)?true:false;
    }
}

var StorecreditManagement = {
    calculateFixedPrice: function(productId, creditAmount, rate){
        var price = parseFloat(creditAmount) * parseFloat(rate);
        if($('totals_price_' + productId)){
            $('totals_price_' + productId).innerHTML = getPriceFormatedHtml(price);
        }
    },
    calculateDropdownPrice: function(productId, creditAmount, rate){
        var price = parseFloat(creditAmount) * parseFloat(rate);
        if($('totals_price_' + productId)){
            $('totals_price_' + productId).innerHTML = getPriceFormatedHtml(price);
        }
    },
    calculateRangePrice: function(productId, creditAmount, rate, from, to){
        var creditAmount = (creditAmount)?parseFloat(creditAmount):0;
        var from = parseFloat(from);
        var to = parseFloat(to);
        if($('credit_amount_range_'+productId)){
            if(creditAmount > to){
                creditAmount = to;
                $('credit_amount_range_'+productId).value = to;
            }
            if(creditAmount < from){
                creditAmount = from;
                $('credit_amount_range_'+productId).value = from;
            }
        }
        var price = parseFloat(creditAmount) * parseFloat(rate);
        if($('totals_price_' + productId)){
            $('totals_price_' + productId).innerHTML = getPriceFormatedHtml(price);
        }
    },
    prepareFirstTime: function(productId){
        if($D('#'+productId+'_storecredit__values .credit-amount').length > 0){
            $D('#'+productId+'_storecredit__values .credit-amount').change();
        }
    }
}