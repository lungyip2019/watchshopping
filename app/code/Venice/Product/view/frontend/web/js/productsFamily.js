define([
    'ko',
    'uiComponent',
    'mage/url',
    'mage/storage'
], function(ko,Component,urlBuilder,storage) {
 
    return Component.extend({
 
        defaults: {
            template: 'Venice_Product/shopFamily',
            size: 4        
        },        
     
        initialize: function () {            
            this._super();
            this.page = ko.observable(2);
            this.productId = ko.observable(this.productId);
            this.productList = ko.observableArray(this.productList);
            this.loadMore = ko.observable(true);  
            if(this.lastpage == 1){
                this.loadMore(false);
            }          
        },

        getProduct: function () {
            var self = this;
            var serviceUrl = urlBuilder.build('venice/family/index?productId=' + self.productId() + '&size=' + self.size + '&page=' + self.page());
            return storage.post(
                serviceUrl,
                ''
            ).done(
                function (response) {                
                    var items = response.data;
                    items.forEach(product => {
                        self.productList.push(product);
                    });
                    var lastpage = response.lastpage 
                    if(lastpage <= self.page()){
                       self.loadMore(false);
                    }                
                    self.page(self.page()+1);
                }
            ).fail(
                function (response) {
                    console.debug(response);
                }
            );
        }

   
    });
});