jQuery( document ).ready( function( $ ) {
    hrefAddToWishlist = jQuery('.yith-wcwl-add-button a');
    var addToWishlistLink = hrefAddToWishlist.attr('href');
    hrefAddToWishlist.attr('href', '#allow-finalwishlist');
    hrefAddToWishlist.attr('data-link', addToWishlistLink);
    hrefAddToWishlist.attr('rel', 'modal:open');
    $('allow-finalwishlist-no').click(sendWishlist);
    $('allow-finalwishlist-yes').click(sendToFinalwishlist);


    function sendFormWishlist() {
        window.wishlistForm.action = lastclickedElement;

        $(document.body).insert(window.wishlistForm);
        window.wishlistForm.submit();
    }

    function sendFormtoFinalWishlist() {
        window.wishlistForm.insert(new Element('input', {
            type: 'hidden', name: 'finalwishlist', value: lastclickedElement
        }));
        $(document.body).insert(window.wishlistForm);
        window.wishlistForm.submit();
    }

    function sendWishlist() {
        productAddToCartForm.submitLight(window.lastclickedElement, window.lastclickedElement.href)
    }

    function sendToFinalwishlist() {
        $('product_addtocart_form').insert(new Element('input', {
            type: 'hidden', name: 'finalwishlist', value: window.lastclickedElement.href
        }));
        productAddToCartForm.submitLight(window.lastclickedElement, BASE_URL + '/finalwishlist/index/add')
    }
});