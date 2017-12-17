jQuery(document).ready(function ($) {

    hrefAddToWishlist = $('.add_to_wishlist');
    var addToWishlistLink = hrefAddToWishlist.attr('href');
    hrefAddToWishlist.attr('href', '#allow-finalwishlist');
    hrefAddToWishlist.attr('data-link', addToWishlistLink);
    hrefAddToWishlist.attr('rel', 'modal:open');

    hrefAddToWishlist.removeClass('add_to_wishlist');
    hrefAddToWishlist.addClass('addtowishlist');
    $('.allow-finalwishlist-no').click(function(){call_ajax_add_to_finalwishlist(hrefAddToWishlist,false)});
    $('.allow-finalwishlist-yes').click(function(){call_ajax_add_to_finalwishlist(hrefAddToWishlist,true)});

        return false;

    /**
       * Add a product in the wishlist.
       *
       * @param object el
       * @return void
       * @since 1.0.0
       */
      function call_ajax_add_to_finalwishlist( el,finalwishlist) {
        /**
         * Check if cookies are enabled
         *
         * @return bool
         * @since 2.0.0
         */
        coookie_enable = function() {
            if (navigator.cookieEnabled) return true;

            // set and read cookie
            document.cookie = "cookietest=1";
            var ret = document.cookie.indexOf("cookietest=") != -1;

            // delete cookie
            document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";

            return ret;
        }
          var product_id = el.data( 'product-id' ),
              el_wrap = $( '.add-to-wishlist-' + product_id ),
              data = {
                  add_to_wishlist: product_id,
                  product_type: el.data( 'product-type' ),
                  action: yith_wcwl_l10n.actions.add_to_wishlist_action
              };
          if(finalwishlist){
              data.finalwishlist=1;
          }

          if( yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in ){
              var wishlist_popup_container = el.parents( '.yith-wcwl-popup-footer' ).prev( '.yith-wcwl-popup-content' ),
                  wishlist_popup_select = wishlist_popup_container.find( '.wishlist-select' ),
                  wishlist_popup_name = wishlist_popup_container.find( '.wishlist-name' ),
                  wishlist_popup_visibility = wishlist_popup_container.find( '.wishlist-visibility' );

              data.wishlist_id = wishlist_popup_select.val();
              data.wishlist_name = wishlist_popup_name.val();
              data.wishlist_visibility = wishlist_popup_visibility.val();
          }

          if( ! coookie_enable() ){
              alert( yith_wcwl_l10n.labels.cookie_disabled );
              return;
          }

          $.ajax({
              type: 'POST',
              url: yith_wcwl_l10n.ajax_url,
              data: data,
              dataType: 'json',
              beforeSend: function(){
                  el.siblings( '.ajax-loading' ).css( 'visibility', 'visible' );
              },
              complete: function(){
                  el.siblings( '.ajax-loading' ).css( 'visibility', 'hidden' );
              },
              success: function( response ) {
                  var msg = $( '#yith-wcwl-popup-message' ),
                      response_result = response.result,
                      response_message = response.message;

                  if( yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in ) {
                      var wishlist_select = $( 'select.wishlist-select' );
                      if( typeof $.prettyPhoto != 'undefined' && typeof $.prettyPhoto.close != 'undefined' ) {
                          $.prettyPhoto.close();
                      }

                      wishlist_select.each( function( index ){
                          var t = $(this),
                              wishlist_options = t.find( 'option' );

                          wishlist_options = wishlist_options.slice( 1, wishlist_options.length - 1 );
                          wishlist_options.remove();

                          if( typeof( response.user_wishlists ) != 'undefined' ){
                              var i = 0;
                              for( i in response.user_wishlists ) {
                                  if ( response.user_wishlists[i].is_default != "1" ) {
                                      $('<option>')
                                          .val(response.user_wishlists[i].ID)
                                          .html(response.user_wishlists[i].wishlist_name)
                                          .insertBefore(t.find('option:last-child'))
                                  }
                              }
                          }
                      } );
                  }


                  $( '#yith-wcwl-message' ).html( response_message );

                  msg.css( 'margin-left', '-' + $( msg ).width() + 'px' ).fadeIn();
                  window.setTimeout( function() {
                      msg.fadeOut();
                  }, 2000 );

                  if( response_result == "true" ) {
                      if( ! yith_wcwl_l10n.multi_wishlist || ! yith_wcwl_l10n.is_user_logged_in || ( yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in && yith_wcwl_l10n.hide_add_button ) ) {
                          el_wrap.find('.yith-wcwl-add-button').hide().removeClass('show').addClass('hide');
                      }

                      el_wrap.find( '.yith-wcwl-wishlistexistsbrowse').hide().removeClass('show').addClass('hide').find('a').attr('href', response.wishlist_url);
                      el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).show().removeClass('hide').addClass('show').find('a').attr('href', response.wishlist_url);
                  } else if( response_result == "exists" ) {
                      if( ! yith_wcwl_l10n.multi_wishlist || ! yith_wcwl_l10n.is_user_logged_in || ( yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in && yith_wcwl_l10n.hide_add_button ) ) {
                          el_wrap.find('.yith-wcwl-add-button').hide().removeClass('show').addClass('hide');
                      }

                      el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).show().removeClass('hide').addClass('show').find('a').attr('href', response.wishlist_url);
                      el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).hide().removeClass('show').addClass('hide').find('a').attr('href', response.wishlist_url);
                  } else {
                      el_wrap.find( '.yith-wcwl-add-button' ).show().removeClass('hide').addClass('show');
                      el_wrap.find( '.yith-wcwl-wishlistexistsbrowse' ).hide().removeClass('show').addClass('hide');
                      el_wrap.find( '.yith-wcwl-wishlistaddedbrowse' ).hide().removeClass('show').addClass('hide');
                  }

                  if (response.hasOwnProperty('final_wishlist_url') &&
                      response.final_wishlist_url.length > 0) {
                      finalWishlistUrl = $('<a></a>').attr("href", response.final_wishlist_url)
                          .append('Browse your Final wishlist');
                      $('.yith-wcwl-wishlistaddedbrowse').prepend(finalWishlistUrl);

                  }

                  $('body').trigger('added_to_wishlist');
              }

          });
        jQuery('#allow-finalwishlist').modal.close();
      }

});
