define([
    'jquery',
    'mage/template',
    'Magento_Ui/js/modal/alert',
    'jquery/ui',
    'mage/loader'
], function($, mageTemplate, alert) {
    'use strict';

    $.widget('tm.ajaxWishlist', {

        options: {
            addToWishlistSelector: '[data-action="add-to-wishlist"]',
            removeFromWishlistSelector: '.block-wishlist .btn-remove',
            wishlistBlockSelector: '#wishlist-view-form',
            formKeyInputSelector: 'input[name="form_key"]',
            notLoggedInErrorMessage: 'Please <a href="<%- url %>">log in</a> to be able add items to wishlist.',
            errorMessage: 'There is an error occurred while processing the request.',
            isShowSpinner: true,
            isShowSuccessMessage: true,
            customerLoginUrl: null
        },

        _create: function() {
            this._bind();
        },
        
        _bind: function () {
            var selectors = [
                this.options.addToWishlistSelector,
                this.options.removeFromWishlistSelector
            ];

            $('body').on('click', selectors.join(','), $.proxy(this._processViaAjax, this));
        },

        _processViaAjax: function(event) {
            var
                post = $(event.currentTarget).data('post'),
                url = post.action,
                data = $.extend(post.data, {form_key: $(this.options.formKeyInputSelector).val()});

            $.ajax(url, {
                method: 'POST',
                data: data,
                showLoader: this.options.isShowSpinner
            }).done($.proxy(this._successHandler, this)).fail($.proxy(this._errorHandler, this));

            event.stopPropagation();
            event.preventDefault();
        },

        _successHandler: function(data) {
            if (!data.success && data.error == 'not_logged_in') {
                alert({
                    title: 'Ajax Wishlist',
                    content: mageTemplate(this.options.notLoggedInErrorMessage, {
                        url: this.options.customerLoginUrl
                    })
                });

                return;
            }

            $(this.options.wishlistBlockSelector).replaceWith(data.wishlist);
            $('body').trigger('contentUpdated');

            if (this.options.isShowSuccessMessage && data.message) {
                alert({
                    title: 'Ajax Wishlist',
                    content: data.message
                });
            }
        },

        _errorHandler: function () {
            alert({
                title: 'Ajax Wishlist',
                content: mageTemplate(this.options.errorMessage)
            });
        }

    });

    return $.tm.ajaxWishlist;

});