jQuery = jQuery.noConflict();  
jQuery(window).load(function(){
	 var $ = jQuery;
   jQuery('.matchHeight').matchHeight();
   jQuery('.owl-product').owlCarousel({
        navText: ['', ''],
        loop: true,
        margin: 10,
        nav: false,
        // navContainer: 'owl-carousel',
        autoplay: false,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        animateIn: 'fadeIn',
        dots:true,
        responsive: {
            0: {
                items: 2
            },
            340 :{
                items:2
            },
            480: {
                items: 3
            },
            769: {
                items: 4
            },
            1025:{
                items:5
            }
        }
    }); 

	 jQuery('#socialTabs .nav-tabs a').click(function (e) { 
	 	jQuery('#socialTabs .nav-tabs a').removeClass("active");
	 	jQuery(this).addClass("active");

	 	jQuery("#socialTabs .tab-content .tab-pane").removeClass('active');
	 	jQuery(jQuery(this).attr('href')).addClass('active'); 
	  	 
	}); 

   jQuery('.fcc-brand-dropdown .vehicle-make').change(function(e){
     jQuery(".fcc-brand-dropdown .loader").addClass('active'); 
     jQuery(".fcc-brand-dropdown").addClass('active');

     jQuery('.fcc-brand-dropdown .vehicle-model, .fcc-brand-dropdown .vehicle-year').hide();

      var option = $(this).find('option:selected').val();
      if(option == -1){
          jQuery(".fcc-brand-dropdown .loader").removeClass('active');
          jQuery(".fcc-brand-dropdown .buttons button.search, .fcc-brand-dropdown .buttons .or").removeClass('active');
          
      }else{ 
          productsearchAjax('.fcc-brand-dropdown .vehicle-model', "Select Vehicle Model", {'category-id':option});
          jQuery(".fcc-brand-dropdown .buttons button.search, .fcc-brand-dropdown .buttons .or").addClass('active');
      } 
   });   

   jQuery('.fcc-brand-dropdown .vehicle-model').change(function(e){
      jQuery(".fcc-brand-dropdown .loader").addClass('active');
      jQuery(".fcc-brand-dropdown").addClass('active');
      jQuery('.fcc-brand-dropdown .vehicle-year').hide();
      var option = $(this).find('option:selected').val();
      if(option == -1){
          jQuery(".fcc-brand-dropdown .loader").removeClass('active');
      }else{ 
          productsearchAjax('.fcc-brand-dropdown .vehicle-year', "Select Vehicle Year", {'category-id':option});
      } 
   });

   jQuery('.fcc-brand-dropdown .ui.cancel.button').click(function(){
      jQuery(".fcc-brand-dropdown .menu").removeClass('active');
   });

    jQuery('.fcc-brand-dropdown .vehicle-year').change(function(e){
       jQuery('.fcc-brand-dropdown .buttons button.search').trigger('click');
    });

   //jQuery('.ui.dropdown').dropdown(); 
   jQuery('.ui.accordion').accordion();
   
   jQuery('a.launch').click(function(){
     $('.ui.sidebar').sidebar('toggle');
   });

   jQuery('.search-keys .vehicle-make').change(function(e){
      var option = $(this).find('option:selected').val();
      //jQuery('.search-keys .vehicle-model, .search-keys .vehicle-year, .search-keys button').addClass("disabled");
      jQuery('.search-keys .vehicle-model, .search-keys .vehicle-year, .search-keys button').attr('disabled', 'disabled');  
      if(option == -1){ 
      }else{    
          productsearchAjax('.search-keys .vehicle-model', "Select Vehicle Model", {'category-id':option}); 
          jQuery('.search-keys button').removeClass("disabled");
          jQuery('.search-keys button').removeAttr('disabled');
      } 
   });

   jQuery('.search-keys .vehicle-model').change(function(e){
      var option = $(this).find('option:selected').val(); 
      //jQuery('.search-keys .vehicle-year').addClass("disabled");  
      jQuery('.search-keys .vehicle-year').attr('disabled', 'disabled');  
      if(option == -1){ 
      }else{ 
          productsearchAjax('.search-keys .vehicle-year', "Select Vehicle Year", {'category-id':option}); 
      } 
   });

    jQuery('.search-keys .vehicle-year').change(function(e){
       jQuery('.search-keys button').trigger('click');
    });

    jQuery('.fcc-brand-dropdown .buttons button.search, .search-keys button').click(function(e){
      e.preventDefault();
      var form = jQuery(this).hasClass('search') ? jQuery(".fcc-brand-dropdown form.menu"):jQuery(".search-keys form");
      var query="#"; 
      form.find('select').each(function(k,v){ 
          var v = $(this).find('option:selected');
          if(v.val() != -1){
            query = jQuery(v).attr('data-href'); 
          } 
      });
      window.location = query;
      return false;
    });

    jQuery("body .main").click(function(){
       jQuery(".fcc-brand-dropdown.active").removeClass('active');
       jQuery("#header-cart.skip-active").removeClass("skip-active");
    });

    jQuery('body:not(.customer-account-login):not(.customer-account-create):not(.customer-account-changeforgotten):not(.cms-account-awaiting-approval):not(.customer-account-forgotpassword) .ui.basic.modal').modal({
      closable:false
    }).modal("show");

    
    jQuery(".search-link.menu-item").hover(function(){
      var menu = jQuery(this).find('.dropdown-menu.dropdown-submenu'); 
      if(menu){
        var per = ((menu.offset().top / jQuery(window).height()) *100); 
        if(per>30 && menu.height()>=200){
          menu.css({'top' : '-'+(menu.height()+per+menu.offset().top)+'%'})
        } 
      }
    }, function(){
      jQuery(this).find('.dropdown-menu.dropdown-submenu');
    });
});   

var productAddToCartFormAjax = new VarienForm('product_addtocart_form');

    productAddToCartFormAjax.submit = function(button, url) {
       if (this.validator.validate()) {

           var form = this.form;
           var oldUrl = form.action;

           if (url) {
              form.action = url;
           }
           var e = null;
           try {
               this.form.submit();
           } catch (e) {
           }
           this.form.action = oldUrl;
           if (e) {
               throw e;
           }

           if (button && button != 'undefined') {
               button.disabled = true;
           }
        }
    }.bind(productAddToCartFormAjax);

    productAddToCartFormAjax.submitLight = function(button, url, selector){ 
     		var form_selector = selector+ ' #product_addtocart_form-'+ jQuery(button).attr('form-id'),
          prodid = jQuery(button).attr('form-id'); 
          var form = jQuery(form_selector);
          var oldUrl = form.attr('action');

          if (url) {
             form.attr('action',url);
          }
          var e = null;
		      //Start of our new ajax code
          if(!url){
              url = oldUrl;
          } 
          url = url.replace("checkout/cart","ajax/index");
         
          var formData = jQuery(form_selector).serialize();
          formData += '&isAjax=1'; 
          var formkey = form.find("input[name=form_key]").val(); 
          addToCartLoader(button, selector,prodid, true)
          try { 
              jQuery.ajax({
                    url: url,
                    dataType: 'json',
                    type : 'post',
                    data: formData,
                    success: function(data){    
                        addToCartLoader(button, selector,prodid, false);
                        refreshMinicart(data); 
                    },
                    error:function(data){  
                      addToCartLoader(button, selector,prodid, false);
                      refreshMinicart(data);
                      window.location = url;
                    }	
              });
          } catch (eer) {
            addToCartLoader(selector,prodid, false);
          	e = err;
          } 
	      //End of our new ajax code
          form.attr('action',oldUrl);
          if (e) {
              throw e;
          }  

    }.bind(productAddToCartFormAjax);

    productAddToCartFormAjax.submitWishlist = function(button, url,selector){

    }.bind(productAddToCartFormAjax);


    function addToCartLoader(button, selector,prodid, is_active){
        var loader = '<div class="product-loader ui segment"><div class="ui active dimmer"> <div class="ui indeterminate text loader">Adding To Cart</div> </div></div>';
        var sel = selector+" .product-collection-"+prodid;
        if(is_active){
          jQuery(sel).prepend(loader);
          if (button && button != 'undefined') {
               button.disabled = true;
           }
        }else{
           jQuery(sel+" .product-loader").remove();
           if (button && button != 'undefined') {
               button.disabled = false;
           }
        } 
    }

    function refreshMinicart(data){
      if(data && data.sidebar){ 
          if(data.status == "SUCCESS"){
            jQuery("#header-cart").html(data.sidebar);
            jQuery("#minicart-success-message").html(data.message);
            jQuery("#minicart-success-message").show();
          }else{
            jQuery("#minicart-error-message").html(data.message);
            jQuery("#minicart-error-message").show();
          } 

          if(data.items){
            jQuery(".skip-cart span.count").html(data.items);
          }

          if(!jQuery("#header-cart").hasClass('skip-active')){  
            jQuery(".skip-link.skip-cart").trigger('click');
          }
      }
    }

    function productsearchAjax(selector, label, formData,is_field){
       try { 
        jQuery.ajax({
              url: YELLOWKEY_URL+'ajax/index/productsearch',
              dataType: 'json',
              type : 'get',
              data: formData,
              success: function(data){   
                if(!is_field){ 
                  populateProductSearch(selector, label, data.items);
                  jQuery(".search-loader.loader").removeClass('active');
                }else{
                  populateProductSearchField(selector, label, data.items);
                }
              },
              error:function(data){  
                jQuery(".search-loader.loader").removeClass('active'); 
              } 
        }); 
       } catch (eer) {
          jQuery(".search-loader.loader").removeClass('active'); 
       }
    }

    function populateProductSearch(selector, label, items){
      jQuery(selector).html("<option value='-1'>"+label+"</option>"); 
      items.forEach(function(v,k){ 
          if(v){
            jQuery(selector).append("<option data-href='"+v.url+"' value='"+v.id+"'>"+v.name+"</option>");
          }
      }); 
      jQuery(selector).show();
      if(items.length>0){ 
        jQuery(selector).removeAttr('disabled');
        jQuery(selector).removeClass('disabled');
      }
    }

    function populateProductSearchField(selector, label, items){
      jQuery(selector).find('.menu').html('<div class="item selected" data-value="-1">'+label+'</div>'); 
      jQuery(selector).find('select').html("<option value='-1'>"+label+"</option>");
      jQuery(selector).find('.text').html(label);
      if(items){
        items.forEach(function(v,k){
            if(v){
              jQuery(selector).find('.menu').append('<div class="item" data-href="'+v.url+'" data-value="'+ v.id+'">'+ v.name+'</div>');
              jQuery(selector).find('select').append("<option data-href='"+v.url+"' value='"+v.id+"'>"+v.name+"</option>");
            }
        });  
        if(items.length>0){ 
          jQuery(selector).removeAttr('disabled');
          jQuery(selector).removeClass('disabled');
        }
      }    
  }





