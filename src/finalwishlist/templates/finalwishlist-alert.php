<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if($pagenow == "index.php"):
?>
<div id="allow-finalwishlist" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Finalwishlist</h4>
      </div>
      <div class="modal-body">
        <p><?php echo get_bloginfo( 'name' ); ?>  wants to send your wishlist item to FinalWishlist.com <a href="#" data-toggle="tooltip" data-placement="top" title="FinalWishlist is a new service that gather all wishlists you use in your favorite online stores in unique page so you can share it with your friends on your favorite social">What's is it?</a>
        </p>
          <button class="allow-finalwishlist-yes" >Confirm</button>
          <button class="allow-finalwishlist-no" >No, just to <?php echo get_bloginfo( 'name' ); ?> Wishlist</button>
      </div>
    </div>

  </div>
</div>
  <script type="text/javascript">
    var isLoggedIn = true;
  </script>
<?php endif; ?>