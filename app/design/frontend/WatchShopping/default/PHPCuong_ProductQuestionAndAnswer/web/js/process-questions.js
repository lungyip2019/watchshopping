
define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('venice.questions',{

        options:{
            fromPages: false
        },

        _processQuestions: function(url, fromPages) {
            var self = this;
            $.ajax({
                url: url,
                cache: true,
                dataType: 'html'
            }).done(function (data) {
                
                if (self.previous) {
                    // no previous page
                    $(self.element).find('.img-loading').remove();
                    $(self.element).find(`[data-pagenum='${self.previous}']`).append(data);
                } else {
                    $(self.element).html(data);
                }
                self.previousLoadMoreElement = $(self.element).find('.question-load-more').first();
                $(self.element).find('.question-load-more a').each(function (index, element) {
                    self.previous = $(element).data('previous-page');
                    $(element).click(function (event) {                        
                        $(self.element).find('.img-loading').show();
                        event.preventDefault();
                        $(self.previousLoadMoreElement).remove();
                        self._processQuestions($(element).attr('href'), true);
                        
                    });
                });
            }).complete(function () {
                if (fromPages == true) {
                    // $('html, body').animate({
                    //     scrollTop: $('#questions').offset().top - 50
                    // }, 300);
                }
            });
        },        
        _create: function(){        
            this.previous = 0;
            this.previousLoadMoreElement = null;
            this._processQuestions(this.options.productQuestionUrl,this.options.fromPages);
            
        }

    });
    
    return $.venice.questions;

})