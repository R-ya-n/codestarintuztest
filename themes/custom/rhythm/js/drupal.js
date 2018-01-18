(function() {
  
  var $ = jQuery;

  Drupal.behaviors.blog_timeline = {
    attach: function (context, settings) {
       $('.timeline > ul > li:odd > article', context).addClass('fadeInLeft wow');
       $('.timeline > ul > li:even > article', context).addClass('fadeInRight wow');
       if($('.timeline').lenght) {
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 90,
            mobile: false, 
            live: true 
        });
        wow.init(); 
      }
    }
  };

  Drupal.behaviors.button_js = {
    attach: function (context, settings) {
       $('.button-js', context).click(function() {
        $(this).closest('form').submit();
       });
    }
  };

  Drupal.behaviors.href_anchor_smooth = {
    attach: function (context, settings) {
      var $root = $('html, body');
      $('a[href^="#"]', context).click(function() {
          var href = $.attr(this, 'href');
          if (href != '#') {
            $root.animate({
                scrollTop: $(href).offset().top
            }, 500, function () {
                window.location.hash = href;
            });
          }
      });
    }
  };

  Drupal.behaviors.href_click = {
    attach: function (context, settings) {
       $('a[href="#"]', context).click(function() {
        return false;
       });
    }
  };

  Drupal.behaviors.cart_remove_wrap = {
    attach: function (context, settings) {
      $('.cart-remove-wrap a', context).click(function() {
        $(this).parent().find('input').click();
        return false;
      });
    }
  };

  Drupal.behaviors.products_filter = {
    attach: function (context, settings) {
      if($('#block-rhythm-cms-products-filter').length > 0 && $('#edit-commerce-price-amount-wrapper').length > 0) {
        $('#edit-commerce-price-amount-wrapper').hide();
        $('.products-filter-from input').val($('#edit-commerce-price-amount-wrapper #edit-commerce-price-amount-min').val());
        $('.products-filter-to input').val($('#edit-commerce-price-amount-wrapper #edit-commerce-price-amount-max').val());
        $('#block-rhythm-cms-products-filter button').click(function() {
          $('#edit-commerce-price-amount-wrapper #edit-commerce-price-amount-min').val($('.products-filter-from input').val());
          $('#edit-commerce-price-amount-wrapper #edit-commerce-price-amount-max').val($('.products-filter-to input').val());
          $('#edit-commerce-price-amount-wrapper').closest('form').submit();
          return false;
        });
      }
    }
  };

  Drupal.behaviors.product_zoom = {
    attach: function (context, settings) {
        $(".lightbox-gallery-3", context).magnificPopup({
            gallery: {
                enabled: true,
                tCounter: '<span class="mfp-counter">%curr% ' + Drupal.t('of') + ' %total%</span>' // markup of counter
            }
        });
    }
  };

  Drupal.behaviors.tb_megamenu_align = {
    attach: function (context, settings) {
      $('.mega-align-right .mn-sub', context).addClass('to-left');
    }
  };

  $(document).ready(function() {
    Pizza.init();
  });

}());