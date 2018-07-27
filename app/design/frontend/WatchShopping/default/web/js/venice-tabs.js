define([
    'jquery',
    'tabs'
], function ($) {

    $.widget('venice.tabs', $.mage.tabs, {

        options:{
            tabs: []
        },

        _create: function() {
            this._super();
            var self = this;
            self.options.tabs.forEach(tab=> {
                var source = tab.source;
                var destination = tab.destination;
                var content = $(source).find('a').html();
                $(source).after('<div class="item title"><a class="anchor-tab" href="#">'+ content +'</a></div>');                   
                $(source).next().on('click',function(event){
                    event.preventDefault();
		    var temp = $(destination).last().offset();
                    $("html, body").animate(
                        { scrollTop: $(destination).last().offset().top },
                    1000);
                });                                
            });            
        }

    });

    return $.venice.tabs;
})
