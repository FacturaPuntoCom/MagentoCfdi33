!function(a,b){"function"==typeof define&&define.amd?define(["jquery"],function(c){return b(a,c)}):"object"==typeof exports?b(a,require("jquery")):b(a,a.jQuery||a.Zepto)}(this,function(a,b){"use strict";function c(a){if(w&&"none"===a.css("animation-name")&&"none"===a.css("-webkit-animation-name")&&"none"===a.css("-moz-animation-name")&&"none"===a.css("-o-animation-name")&&"none"===a.css("-ms-animation-name"))return 0;var b,c,d,e,f=a.css("animation-duration")||a.css("-webkit-animation-duration")||a.css("-moz-animation-duration")||a.css("-o-animation-duration")||a.css("-ms-animation-duration")||"0s",g=a.css("animation-delay")||a.css("-webkit-animation-delay")||a.css("-moz-animation-delay")||a.css("-o-animation-delay")||a.css("-ms-animation-delay")||"0s",h=a.css("animation-iteration-count")||a.css("-webkit-animation-iteration-count")||a.css("-moz-animation-iteration-count")||a.css("-o-animation-iteration-count")||a.css("-ms-animation-iteration-count")||"1";for(f=f.split(", "),g=g.split(", "),h=h.split(", "),e=0,c=f.length,b=Number.NEGATIVE_INFINITY;c>e;e++)d=parseFloat(f[e])*parseInt(h[e],10)+parseFloat(g[e]),d>b&&(b=d);return d}function d(){if(b(document.body).height()<=b(window).height())return 0;var a,c,d=document.createElement("div"),e=document.createElement("div");return d.style.visibility="hidden",d.style.width="100px",document.body.appendChild(d),a=d.offsetWidth,d.style.overflow="scroll",e.style.width="100%",d.appendChild(e),c=e.offsetWidth,d.parentNode.removeChild(d),a-c}function e(){var a,c,e=b("html"),f=k("is-locked");e.hasClass(f)||(c=b(document.body),a=parseInt(c.css("padding-right"),10)+d(),c.css("padding-right",a+"px"),e.addClass(f))}function f(){var a,c,e=b("html"),f=k("is-locked");e.hasClass(f)&&(c=b(document.body),a=parseInt(c.css("padding-right"),10)-d(),c.css("padding-right",a+"px"),e.removeClass(f))}function g(a,b,c,d){var e=k("is",b),f=[k("is",u.CLOSING),k("is",u.OPENING),k("is",u.CLOSED),k("is",u.OPENED)].join(" ");a.$bg.removeClass(f).addClass(e),a.$overlay.removeClass(f).addClass(e),a.$wrapper.removeClass(f).addClass(e),a.$modal.removeClass(f).addClass(e),a.state=b,!c&&a.$modal.trigger({type:b,reason:d},[{reason:d}])}function h(a,d,e){var f=0,g=function(a){a.target===this&&f++},h=function(a){a.target===this&&0===--f&&(b.each(["$bg","$overlay","$wrapper","$modal"],function(a,b){e[b].off(r+" "+s)}),d())};b.each(["$bg","$overlay","$wrapper","$modal"],function(a,b){e[b].on(r,g).on(s,h)}),a(),0===c(e.$bg)&&0===c(e.$overlay)&&0===c(e.$wrapper)&&0===c(e.$modal)&&(b.each(["$bg","$overlay","$wrapper","$modal"],function(a,b){e[b].off(r+" "+s)}),d())}function i(a){a.state!==u.CLOSED&&(b.each(["$bg","$overlay","$wrapper","$modal"],function(b,c){a[c].off(r+" "+s)}),a.$bg.removeClass(a.settings.modifier),a.$overlay.removeClass(a.settings.modifier).hide(),a.$wrapper.hide(),f(),g(a,u.CLOSED,!0))}function j(a){var b,c,d,e,f={};for(a=a.replace(/\s*:\s*/g,":").replace(/\s*,\s*/g,","),b=a.split(","),e=0,c=b.length;c>e;e++)b[e]=b[e].split(":"),d=b[e][1],("string"==typeof d||d instanceof String)&&(d="true"===d||("false"===d?!1:d)),("string"==typeof d||d instanceof String)&&(d=isNaN(d)?d:+d),f[b[e][0]]=d;return f}function k(){for(var a=q,b=0;b<arguments.length;++b)a+="-"+arguments[b];return a}function l(){var a,c,d=location.hash.replace("#","");if(d){try{c=b("[data-"+p+"-id="+d.replace(new RegExp("/","g"),"\\/")+"]")}catch(e){}c&&c.length&&(a=b[p].lookup[c.data(p)],a&&a.settings.hashTracking&&a.open())}else n&&n.state===u.OPENED&&n.settings.hashTracking&&n.close()}function m(a,c){var d=b(document.body),e=this;e.settings=b.extend({},t,c),e.index=b[p].lookup.push(e)-1,e.state=u.CLOSED,e.$overlay=b("."+k("overlay")),e.$overlay.length||(e.$overlay=b("<div>").addClass(k("overlay")+" "+k("is",u.CLOSED)).hide(),d.append(e.$overlay)),e.$bg=b("."+k("bg")).addClass(k("is",u.CLOSED)),e.$modal=a,e.$modal.addClass(q+" "+k("is-initialized")+" "+e.settings.modifier+" "+k("is",u.CLOSED)),e.$wrapper=b("<div>").addClass(k("wrapper")+" "+e.settings.modifier+" "+k("is",u.CLOSED)).hide().append(e.$modal),d.append(e.$wrapper),e.$wrapper.on("click."+q,"[data-"+p+'-action="close"]',function(a){a.preventDefault(),e.close()}),e.$wrapper.on("click."+q,"[data-"+p+'-action="cancel"]',function(a){a.preventDefault(),e.$modal.trigger(v.CANCELLATION),e.settings.closeOnCancel&&e.close(v.CANCELLATION)}),e.$wrapper.on("click."+q,"[data-"+p+'-action="confirm"]',function(a){a.preventDefault(),e.$modal.trigger(v.CONFIRMATION),e.settings.closeOnConfirm&&e.close(v.CONFIRMATION)}),e.$wrapper.on("click."+q,function(a){var c=b(a.target);c.hasClass(k("wrapper"))&&e.settings.closeOnOutsideClick&&e.close()})}var n,o,p="remodal",q=a.REMODAL_GLOBALS&&a.REMODAL_GLOBALS.NAMESPACE||p,r=b.map(["animationstart","webkitAnimationStart","MSAnimationStart","oAnimationStart"],function(a){return a+"."+q}).join(" "),s=b.map(["animationend","webkitAnimationEnd","MSAnimationEnd","oAnimationEnd"],function(a){return a+"."+q}).join(" "),t=b.extend({hashTracking:!0,closeOnConfirm:!0,closeOnCancel:!0,closeOnEscape:!0,closeOnOutsideClick:!0,modifier:""},a.REMODAL_GLOBALS&&a.REMODAL_GLOBALS.DEFAULTS),u={CLOSING:"closing",CLOSED:"closed",OPENING:"opening",OPENED:"opened"},v={CONFIRMATION:"confirmation",CANCELLATION:"cancellation"},w=function(){var a=document.createElement("div").style;return void 0!==a.animationName||void 0!==a.WebkitAnimationName||void 0!==a.MozAnimationName||void 0!==a.msAnimationName||void 0!==a.OAnimationName}();m.prototype.open=function(){var a,c=this;c.state!==u.OPENING&&c.state!==u.CLOSING&&(a=c.$modal.attr("data-"+p+"-id"),a&&c.settings.hashTracking&&(o=b(window).scrollTop(),location.hash=a),n&&n!==c&&i(n),n=c,e(),c.$bg.addClass(c.settings.modifier),c.$overlay.addClass(c.settings.modifier).show(),c.$wrapper.show().scrollTop(0),h(function(){g(c,u.OPENING)},function(){g(c,u.OPENED)},c))},m.prototype.close=function(a){var c=this;c.state!==u.OPENING&&c.state!==u.CLOSING&&(c.settings.hashTracking&&c.$modal.attr("data-"+p+"-id")===location.hash.substr(1)&&(location.hash="",b(window).scrollTop(o)),h(function(){g(c,u.CLOSING,!1,a)},function(){c.$bg.removeClass(c.settings.modifier),c.$overlay.removeClass(c.settings.modifier).hide(),c.$wrapper.hide(),f(),g(c,u.CLOSED,!1,a)},c))},m.prototype.getState=function(){return this.state},m.prototype.destroy=function(){var a,c=b[p].lookup;i(this),this.$wrapper.remove(),delete c[this.index],a=b.grep(c,function(a){return!!a}).length,0===a&&(this.$overlay.remove(),this.$bg.removeClass(k("is",u.CLOSING)+" "+k("is",u.OPENING)+" "+k("is",u.CLOSED)+" "+k("is",u.OPENED)))},b[p]={lookup:[]},b.fn[p]=function(a){var c,d;return this.each(function(e,f){d=b(f),null==d.data(p)?(c=new m(d,a),d.data(p,c.index),c.settings.hashTracking&&d.attr("data-"+p+"-id")===location.hash.substr(1)&&c.open()):c=b[p].lookup[d.data(p)]}),c},b(document).ready(function(){b(document).on("click","[data-"+p+"-target]",function(a){a.preventDefault();var c=a.currentTarget,d=c.getAttribute("data-"+p+"-target"),e=b("[data-"+p+"-id="+d+"]");b[p].lookup[e.data(p)].open()}),b(document).find("."+q).each(function(a,c){var d=b(c),e=d.data(p+"-options");e?("string"==typeof e||e instanceof String)&&(e=j(e)):e={},d[p](e)}),b(document).on("keydown."+q,function(a){n&&n.settings.closeOnEscape&&n.state===u.OPENED&&27===a.keyCode&&n.close()}),b(window).on("hashchange."+q,l)})});
var $jq = jQuery.noConflict();
var jQuery = $$$;

$jq(function() {

  //Front client area functions
  //vars
  var order_data, customer_data, invoice_data;

  String.prototype.capitalize = function() {
    return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
  };

  Number.prototype.formatMoney = function(c, d, t){
    var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
  };

  $jq(".input-cap").on('input', function(evt) {
    var input = $jq(this);
    var start = input[0].selectionStart;

    $jq(this).val(function (_, val) {
      return val.capitalize();
    });
    input[0].selectionStart = input[0].selectionEnd = start;
  });

  $jq(".input-upper").on('input', function(evt) {
    var input = $jq(this);
    var start = input[0].selectionStart;
    $jq(this).val(function(_, val){
      return val.toUpperCase();
    });
    input[0].selectionStart = input[0].selectionEnd = start;
  });

  $jq('#f-rfc').bind('keypress', function (event) {
    var keyCode = event.keyCode || event.which
    if (keyCode == 8 || (keyCode >= 35 && keyCode <= 40)) {
      return;
    }

    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

    if (!regex.test(key)) {
      event.preventDefault();
      return false;
    }
  });


  //form-two functions

  /* Validate forms */
  function fillInvoiceContainer(order_data, customer_data, products){
    $jq('#invoice-id').hide();
    $jq('#invoice-date').hide();
    //receptor
    $jq('#receptor-nombre').text(customer_data.RazonSocial);
    $jq('#receptor-rfc').text(customer_data.RFC);
    $jq('#receptor-direccion').text(customer_data.Calle + ' ' + customer_data.Numero + ' ' + customer_data.Interior);
    $jq('#receptor-direccion-zone').text(customer_data.Colonia + '. CP: ' + customer_data.CodigoPostal);
    $jq('#receptor-direccion-zone-city').text(customer_data.Ciudad + ', ' + customer_data.Estado
    + ', México.');

    //taxes
    calculate_tax = 0.16;
    tax = Number(1 + calculate_tax); //1.16 (variable in future)

    //products
    var subtotal = 0;
    var taxes    = 0;
    var discount = Number(order_data.total_discount);
    var ieps = 0;

    var r = new Array(), j = -1;
    for (var key=0, size=products.length; key<size; key++){

      unit_price = Number(products[key]['base_price']);
      // unit_price = products[key]['price'] - Number(products[key]['price'] * calculate_tax);

      unit_subtotal = products[key]['qty'] * unit_price;

      if(products[key]['iepsconfig'] == 1 && products[key]['usaIeps'] == 1){
          // alert('sistá usando ieps: iepsconfig=' + products[key]['iepsconfig'] + ' usaIeps=' + products[key]['usaIeps']);
          ieps_cuota = products[key]['iepscalc'] / 100;
          ieps = Number(ieps) + Number(unit_subtotal * ieps_cuota);
      }

      r[++j] ='<tr><td>';
      r[++j] = products[key]['name'];
      r[++j] = '</td><td>$';
      r[++j] = (unit_price).formatMoney(2, '.', ',');
      r[++j] = '</td><td>';
      r[++j] = Math.round(products[key]['qty']);
      r[++j] = '</td><td>$';
      r[++j] = (unit_subtotal).formatMoney(2, '.', ',');
      r[++j] = '</td></tr>';

      subtotal = Number(subtotal) + Number(unit_subtotal);
      taxes    = Number(taxes) + Number(products[key]['total_tax']);

      unit_price = 0;
    } //fin de ciclo de productos
    $jq('#datails-body').html(r.join(''));

    var grand_total = Number(order_data.total);
    // var total_iva = grand_total;
    var payment_method;

    // if(order_data.payment_details.method_id == "paypal"){
    //     payment_method = "Pago con Tarjeta";
    // }else{
    //     payment_method = "Depósito en Cuenta";
    // }

    if(discount > 0){
      //   pre_total = Number((subtotal-discount)/tax);
      //   total_iva = Number((subtotal-discount) - pre_total);
      //   total = Math.round(pre_total + total_iva);

      pre_subtotal = Number(subtotal - discount);
      total_iva = Number(pre_subtotal*calculate_tax);
      total = Number(pre_subtotal+total_iva+ieps);

      $jq('#td-discount #invoice-discount').text('$'+discount.formatMoney(2, '.', ','));
      $jq('#td-discount').css({'display':'table-row'});


    }else{
      total_iva = grand_total-subtotal;
      total = Number(subtotal+total_iva+ieps);
    }


    $jq('#invoice-pmethod').text(payment_method); //order_data.payment_details.paid (para saber si está pagado)
    $jq('#invoice-subtotal').text('$'+subtotal.formatMoney(2, '.', ','));
    $jq('#invoice-iva').text('$'+(total_iva).formatMoney(2, '.', ','));
    console.log(ieps);
    if (ieps > 0) {
        $jq('#ieps').show();
        $jq('#invoice-ieps').text('$'+(ieps).formatMoney(2, '.', ','));
    }
    $jq('#invoice-total').text('$'+(total).formatMoney(2, '.', ','));

  }

  function fillFormTwo(data){
    //contacto
    $jq('#uid').val(data.UID);
    $jq('#general-nombre').val(data.Contacto.Nombre);
    $jq('#general-apellidos').val(data.Contacto.Apellidos);
    $jq('#general-email').val(data.Contacto.Email);

    $jq('#fiscal-rfc').val(data.RFC);
    $jq('#fiscal-nombre').val(data.RazonSocial);
    $jq('#fiscal-calle').val(data.Calle);
    $jq('#fiscal-exterior').val(data.Numero);
    $jq('#fiscal-interior').val(data.Interior);
    $jq('#fiscal-colonia').val(data.Colonia);
    $jq('#fiscal-delegacion').val(data.Delegacion);
    $jq('#fiscal-municipio').val(data.Ciudad);
    $jq('#fiscal-estado').val(data.Estado);

    let pais = data.Pais; // esto lo debe devolver el API.
    console.log(pais);

    if (pais == undefined || pais == '') {
        $jq('#fiscal-pais').val('MEX');
    } else {
        $jq('#fiscal-pais').val(pais);
    }

    $jq('#fiscal-cp').val(data.CodigoPostal);
    $jq('#fiscal-telefono').val(data.Contacto.Telefono);

    if (pais != "MEX") {
        $jq('#fiscal-numregidtrib').val(data.NumRegIdTrib);
        $jq('#field-numregidtrib').css({'display':'table'});
    }

    $jq('#step-two [type=input]').removeAttr('readonly');
  }

  function enableFormTwo(b){
    if(b == true){
      $jq('#general-nombre').removeAttr('readonly');
      $jq('#general-apellidos').removeAttr('readonly');
      $jq('#general-email').removeAttr('readonly');

      $jq('#fiscal-rfc').removeAttr('readonly');
      $jq('#fiscal-nombre').removeAttr('readonly');
      $jq('#fiscal-calle').removeAttr('readonly');
      $jq('#fiscal-exterior').removeAttr('readonly');
      $jq('#fiscal-interior').removeAttr('readonly');
      $jq('#fiscal-colonia').removeAttr('readonly');
      $jq('#fiscal-ciudad').removeAttr('readonly');
      $jq('#fiscal-delegacion').removeAttr('readonly');
      $jq('#fiscal-municipio').removeAttr('readonly');
      $jq('#fiscal-estado').removeAttr('readonly');
      $jq('#fiscal-pais').removeAttr('readonly');
      $jq('#fiscal-cp').removeAttr('readonly');
      $jq('#fiscal-telefono').removeAttr('readonly');
      $jq('#step-two-button-edit').val('Cancelar');
      var $labels = $jq("#f-step-two-form label[for]");
      $labels.css({'border-color':'#67BA2F'});

    }else{
      $jq('#general-nombre').attr('readonly','readonly');
      $jq('#general-apellidos').attr('readonly','readonly');
      $jq('#general-email').attr('readonly','readonly');

      $jq('#fiscal-rfc').attr('readonly','readonly');
      $jq('#fiscal-nombre').attr('readonly','readonly');
      $jq('#fiscal-calle').attr('readonly','readonly');
      $jq('#fiscal-exterior').attr('readonly','readonly');
      $jq('#fiscal-interior').attr('readonly','readonly');
      $jq('#fiscal-colonia').attr('readonly','readonly');
      $jq('#fiscal-ciudad').attr('readonly','readonly');
      $jq('#fiscal-delegacion').attr('readonly','readonly');
      $jq('#fiscal-municipio').attr('readonly','readonly');
      $jq('#fiscal-estado').attr('readonly','readonly');
      $jq('#fiscal-pais').attr('readonly','readonly');
      $jq('#fiscal-cp').attr('readonly','readonly');
      $jq('#fiscal-telefono').attr('readonly','readonly');
      $jq('#step-two-button-edit').val('Editar');
      var $labels = $jq("#f-step-two-form label[for]");
      $labels.css({'border-color':'#c2c2c2'});
    }
  }

  function validateForm(form, step){
    if(step == 1){

      var rfc_item   = $jq("#f-rfc");
      var order_item = $jq("#f-num-order");
      var email_item = $jq("#f-email");

      if(rfc_item.val().length == 0){
        $jq("label[for='"+rfc_item.attr('id')+"']").addClass("input_error");
        rfc_item.addClass("input_error");
      }else{
        $jq("label[for='"+rfc_item.attr('id')+"']").removeClass("input_error");
        rfc_item.removeClass("input_error");
      }

      if(order_item.val().length == 0){
        $jq("label[for='"+order_item.attr('id')+"']").addClass("input_error");
        order_item.addClass("input_error");
      }else{
        $jq("label[for='"+order_item.attr('id')+"']").removeClass("input_error");
        order_item.removeClass("input_error");
      }

      if(email_item.val().length == 0){
        $jq("label[for='"+email_item.attr('id')+"']").addClass("input_error");
        email_item.addClass("input_error");
      }else{
        $jq("label[for='"+email_item.attr('id')+"']").removeClass("input_error");
        email_item.removeClass("input_error");
      }

      if(rfc_item.val().length > 13 || rfc_item.val().length < 12){
        $jq("label[for='"+rfc_item.attr('id')+"']").addClass("input_error");
        rfc_item.addClass("input_error");
        return false;
      }

      if( rfc_item.val().length > 0 && order_item.val().length > 0 && email_item.val().length > 0 ){
        rfc_item.removeClass("input_error");
        order_item.removeClass("input_error");
        email_item.removeClass("input_error");
        $jq("label[for='"+rfc_item.attr('id')+"']").removeClass("input_error");
        $jq("label[for='"+order_item.attr('id')+"']").removeClass("input_error");
        $jq("label[for='"+email_item.attr('id')+"']").removeClass("input_error");

        jQuery('#f-step-one-form .error_msj').text('').hide();
        return true;
      }else{
        jQuery('#f-step-one-form .error_msj').text('Por favor completa y/o corrige los datos.').show();
      }


    }else if(step == 2){

      var isValid = [];
      var chkForInvalidAmount = [];

      $jq('#f-step-two-form .f-input').each(function () {

        var item = $jq(this);

        if(item.attr('id') == "fiscal-delegacion" || item.attr('id') == "fiscal-interior" ){
          return;
        }

        if(item.val().length == 0){
          $jq("label[for='"+item.attr('id')+"']").addClass("input_error");
          item.addClass("input_error");
          if ($jq( "#fiscal-pais option:selected" ).val() != 'MEX') {
              isValid.push("false");
          }
        }else{
          $jq("label[for='"+item.attr('id')+"']").removeClass("input_error");
          item.removeClass("input_error");
          isValid.push("true");
        }

      });

      var valid = $jq.inArray( "false", isValid );

      if(valid == -1){
        $jq('#f-step-two-form .error_msj').text('').hide();
        return true;
      }else{
        $jq('#f-step-two-form .error_msj').text('Por favor completa y/o corrige los datos.').show();
      }

    }else{

    }

    return false;
  }

  $jq('#step-two-button-edit').click(function(e){
    e.preventDefault();
    var b = $jq(this).attr('data-b');

    if(b == 1){
      enableFormTwo(true);
      $jq(this).attr('data-b', 0);
      $jq("#f-step-two-form #apimethod").val("update");
      $jq("#step-two-button-next").val("Actualizar");
    }else{
      fillFormTwo(customer_data.Data);
      enableFormTwo(false);
      $jq(this).attr('data-b', 1);
      $jq("#f-step-two-form #apimethod").val("create");
      $jq("#step-two-button-next").val("Siguiente");
    }

  });

  $jq('.f-back').click(function(e){
    e.preventDefault();
    var form = $jq(this).attr("data-f");
    clearData(form);
  });

  $jq("#select-payment-t").change(function(){
    var selected_method = $jq( "#select-payment-t option:selected" ).val();

    if(selected_method == 03 || selected_method == 04 || selected_method == 28){
      $jq("#num-cta-box").fadeIn('fast');
    }else{
      $jq("#num-cta-box").fadeOut('fast');
    }
  });

  $jq("#fiscal-pais").change(function(){
    var selected_country = $jq( "#fiscal-pais option:selected" ).val();

    if(selected_country != 'MEX'){
        // show numregidtrib
        $jq('#fiscal-numregidtrib').val(data.NumRegIdTrib);
        $jq('#field-numregidtrib').css({'display':'table'});
    }else{
        // hide numregidtrib
        $jq('#fiscal-numregidtrib').val('');
        $jq('#field-numregidtrib').css({'display':'none'});
    }
  });

  function clearData(step){
    if(step == 2){
      $jq("#f-step-two-form").trigger("reset");
      $jq("#step-two").stop().hide();
      $jq(".customer-data").css({"background-color":"#9B9B9B"});
      $jq(".search-order").css({"background-color":"#942318"});

      $jq("#step-one").stop().fadeIn('slow');
    }else if(step == 3){
      $jq("#step-three").stop().hide();
      $jq(".verify-order").css({"background-color":"#9B9B9B"});
      $jq(".customer-data").css({"background-color":"#9B9B9B"});
      $jq('.search-order').css({ "background-color" : '#942318' });
      $jq("#step-two").stop().fadeIn('slow');
    }
  }

  //fill invoice form

  function enableFormTwo(b){
    if(b == true){
      $jq('#general-nombre').removeAttr('readonly');
      $jq('#general-apellidos').removeAttr('readonly');
      $jq('#general-email').removeAttr('readonly');

      $jq('#fiscal-rfc').removeAttr('readonly');
      $jq('#fiscal-nombre').removeAttr('readonly');
      $jq('#fiscal-calle').removeAttr('readonly');
      $jq('#fiscal-exterior').removeAttr('readonly');
      $jq('#fiscal-interior').removeAttr('readonly');
      $jq('#fiscal-colonia').removeAttr('readonly');
      $jq('#fiscal-ciudad').removeAttr('readonly');
      $jq('#fiscal-delegacion').removeAttr('readonly');
      $jq('#fiscal-municipio').removeAttr('readonly');
      $jq('#fiscal-estado').removeAttr('readonly');
      $jq('#fiscal-pais').removeAttr('readonly');
      $jq('#fiscal-cp').removeAttr('readonly');
      $jq('#fiscal-telefono').removeAttr('readonly');
      $jq('#step-two-button-edit').val('Cancelar');
      $jq("#f-step-two-form label[for]").css({'border-color':'#67BA2F'});
      $jq("#f-step-two-form .f-input").css({'border-color':'#67BA2F'});

    }else{
      $jq('#general-nombre').attr('readonly','readonly');
      $jq('#general-apellidos').attr('readonly','readonly');
      $jq('#general-email').attr('readonly','readonly');

      $jq('#fiscal-rfc').attr('readonly','readonly');
      $jq('#fiscal-nombre').attr('readonly','readonly');
      $jq('#fiscal-calle').attr('readonly','readonly');
      $jq('#fiscal-exterior').attr('readonly','readonly');
      $jq('#fiscal-interior').attr('readonly','readonly');
      $jq('#fiscal-colonia').attr('readonly','readonly');
      $jq('#fiscal-ciudad').attr('readonly','readonly');
      $jq('#fiscal-delegacion').attr('readonly','readonly');
      $jq('#fiscal-municipio').attr('readonly','readonly');
      $jq('#fiscal-estado').attr('readonly','readonly');
      $jq('#fiscal-pais').attr('readonly','readonly');
      $jq('#fiscal-cp').attr('readonly','readonly');
      $jq('#fiscal-telefono').attr('readonly','readonly');
      $jq('#step-two-button-edit').val('Editar');
      $jq("#f-step-two-form label[for]").css({'border-color':'#c2c2c2'});
      $jq("#f-step-two-form .f-input").css({'border-color':'#c2c2c2'});
    }
  }


  //STEP ONE
  $jq('#f-step-one-form').submit(function(e){
    e.preventDefault();

    if( !validateForm($jq(this), 1) ) {
      return false;
    }

    $jq('.f-welcome-container').fadeOut('fast');
    $jq("#step-one .loader_content").show();

    form_data = $jq(this).serializeArray();

    data = {
      action : 'step_one',
      csrf   : form_data[0].value,
      rfc    : form_data[1].value,
      order  : form_data[2].value,
      email  : form_data[3].value,
    }
    var action_url = $jq('#siteurl').val() + 'index.php/facturacion/index/one';
    $jq.post(action_url, data, function(response) {
      $jq("#step-one .loader_content").hide();

      /*
      var customerData = getCookie('customer');
      var orderData    = getCookie('order');
      */
      if(response.error == 400){
        var inst = $jq('[data-remodal-id=respuesta-paso-uno]').remodal();
        $jq('#message-response-one').text(response.message);
        inst.open();
        return false;
      }

      if(response.error == 300){
        $jq('#result-msg-title').text(response.message);

        // $jq('#btn-success-email').stop().show().attr('data-invoice', response.metadata.uid);
        $jq('#btn-success-pdf').stop().show().attr('href','https://factura.com/api/publica/invoice/'+response.data.order_local.invoice_id+'/pdf');
        $jq('#btn-success-xml').stop().show().attr('href','https://factura.com/api/publica/invoice/'+response.data.order_local.invoice_id+'/xml');
        $jq('#step-one').stop().hide();
        $jq('#step-four').stop().fadeIn('slow');
        return false;
      }

      customer_data = response.data.customer;
      order_data = response.data.order;

      if(customer_data.status == "success"){
        $jq('#f-step-two-form').children('#fiscal-rfc').val(customer_data.Data.RFC);
      }

      if(customer_data.status != "error"){
        fillFormTwo(customer_data.Data);
      }else{
        $jq('#step-two-button-edit').hide();
        enableFormTwo(true);
      }

      $jq('#step-one').stop().hide();
      $jq('#step-two').stop().fadeIn('slow');

    }, 'json');

    return false;
  });

  //STEP TWO
  $jq('#f-step-two-form').submit(function(e){
    e.preventDefault();

    $jq("#step-two .loader_content").show();

    if( !validateForm($jq(this), 2) ) {
      $jq("#step-two .loader_content").hide();
      return false;
    }

    form_data = $jq(this).serializeArray();

    data = {
      action        : 'create_client',
      csrf          : form_data[0].value,
      api_method    : form_data[1].value,
      uid           : form_data[2].value,
      g_nombre      : form_data[3].value,
      g_apellidos   : form_data[4].value,
      g_email       : form_data[5].value,
      f_telefono    : form_data[6].value,
      f_nombre      : form_data[7].value,
      f_rfc         : form_data[8].value,
      f_calle       : form_data[9].value,
      f_exterior    : form_data[10].value,
      f_interior    : form_data[11].value,
      f_colonia     : form_data[12].value,
      f_municipio   : form_data[13].value,
      f_estado      : form_data[14].value,
      f_pais        : form_data[15].value,
      f_cp          : form_data[16].value,
      f_numregidtrib : form_data[17].value,
    }

    var action_url = $jq('#siteurl').val() + 'index.php/facturacion/index/two';
    $jq.post(action_url, data, function(response) {
      $jq("#step-two .loader_content").hide();

      var customer_data = response.data.customer.Data;
      var order_data    = response.data.order;
      var products      = response.data.line_items;

      if(response.error != 200){
        var inst = $jq('[data-remodal-id=respuesta-paso-dos]').remodal();
        $jq('#message-response-dos').text(response.message);
        inst.open();
      }else{
        fillInvoiceContainer(order_data, customer_data, products);
        $jq('#step-two').stop().hide();
        $jq('#step-three').stop().fadeIn('slow');
      }
    }, 'json');
  });

  //STEP THREE
  $jq('#step-three-button-next').click(function(e){
    e.preventDefault();
    $jq("#step-three .loader_content").show();

    // forma de pago
    var selected_method_t = $jq( "#select-payment-t option:selected" ).val();
    var selected_method_t_text = $jq( "#select-payment-t option:selected" ).text();

    if(selected_method_t == 0){
      $jq("#step-three .loader_content").hide();
      var inst = $jq('[data-remodal-id=respuesta-paso-dos]').remodal();
      $jq('#message-response-dos').text('Por favor selecciona una forma de pago.');
      inst.open();
      return false;
    }

    // metodo de pago
    var selected_method = $jq( "#select-payment-m option:selected" ).val();
    var selected_method_text = $jq( "#select-payment-m option:selected" ).text();

    if(selected_method == 0){
      $jq("#step-three .loader_content").hide();
      var inst = $jq('[data-remodal-id=respuesta-paso-dos]').remodal();
      $jq('#message-response-dos').text('Por favor selecciona un método de pago.');
      inst.open();
      return false;
    }

    var num_cta_method  = $jq( "#f-num-cta" ).val();

    if(selected_method_t == 3 || selected_method_t == 4 || selected_method_t == 28){
      if(num_cta_method == ""){
        $jq("#step-three .loader_content").hide();
        var inst = $jq('[data-remodal-id=respuesta-paso-dos]').remodal();
        $jq('#message-response-dos').text('Por favor ingresa los últimos 4 dí­gitos de tu cuenta o tarjeta.');
        inst.open();
        return false;
      }
    }

    data = {
      action        : 'generate_invoice',
      payment_m     : selected_method,
      payment_t     : selected_method_text,
      t_payment_m     : selected_method_t,
      t_payment_t     : selected_method_t_text,
      num_cta_m     : num_cta_method
    }

    var action_url = $jq('#siteurl').val() + 'index.php/facturacion/index/three';
    $jq.post(action_url, data, function(response) {
      $jq("#step-three").stop().hide();
      $jq("#step-three .loader_content").hide();


      if(response.data.invoice.status == 'success'){
        $jq('#btn-success-pdf').stop().show().attr('href','https://factura.com/api/publica/invoice/'+response.data.invoice.INV.UID+'/pdf');
        $jq('#btn-success-xml').stop().show().attr('href','https://factura.com/api/publica/invoice/'+response.data.invoice.INV.UID+'/xml');
      }else{
        $jq("#result-msg-title").text(response.data.invoice.message);

        //   $jq('#btn-success-email').stop().hide();
        $jq('#btn-success-pdf').stop().hide();
        $jq('#btn-success-xml').stop().hide();
      }

      $jq("#step-four").stop().fadeIn("slow");
    }, 'json');


  });

});
