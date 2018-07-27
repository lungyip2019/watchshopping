/**
 * Copyright © 2015. All rights reserved.
 */

define([
    'jquery'
], function($){
    "use strict";

    $.widget('TemplateMonster.categoriesListCollapse', {

        options: {
            extender : '#blog-show-all',
            extendable : '.categories-list',
            limit: 3,
            total: 100
        },

        _create: function() {
            var data = this.options,
                extender = data.extender,
                list = data.extendable,
                limit = data.limit,
                total = data.total;

            $(extender).click(function(){
                if($(extender).hasClass('closed')) {
                    var currentIndex = $(list).children('li:visible:last').index(),
                        nextIndex = currentIndex + (total - limit) + 1;
                    $('li:lt(' + nextIndex + '):gt(' + currentIndex + ')', list).show(300);
                    $(extender).toggleClass('closed opened');
                } else {
                    var prevIndex = limit - 1;
                    $('li:gt(' + prevIndex + ')', list).hide(300);
                    $(extender).toggleClass('closed opened');
                }
            });
        }
        
    });

    return $.TemplateMonster.categoriesListCollapse;

});
