define([
    'jquery'
], function ($) {

    'use strict';
    $.widget('venice.featuredbrandbar', {

        options:{
            triggerEvent: 'click'
        },

        _create: function() {
            this._bind();
            console.log("hello world");
        },

        _bind: function() {
            var self = this;
            console.log("in the binding function");
            self.element.on(self.options.triggerEvent, function() {
                console.log("do something");
            });
        },

    });

    return $.venice.featuredbrandbar;
})