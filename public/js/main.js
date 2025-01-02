function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ' ' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function sendAjaxMessage(blockId, url){
  
    $('#' + blockId +' .sendmessage').hide().text('');
    $('#' + blockId +' .errormessage').hide().text('');

    const str = $('#' + blockId + " form").serialize();

    $.ajax({
      type: 'POST',
      url: url,
      dataType: 'json',
      data: str,
      success: function(data) {
        if(data.success == true) {
                  
          $('#' + blockId + " form").hide();
          $('#' + blockId +' .sendmessage').fadeIn().html(data.message);
        }
        else {
                  
          $('#' + blockId +' .errormessage').fadeIn().html(data.message);                        
        }
      }
    });
  
}

jQuery(document).ready(function($) {
  "use strict";

  $('#test').scrollToFixed();

  $('.res-nav_click').click(function() {
    $('.main-nav').slideToggle();
    return false
  });


  //Contact
  $("#sendApply").submit(function(e) {
        e.preventDefault();
        $('#sendmessage').hide().text('');
        $('#errormessage').hide().text('');
        var str = $(this).serialize();
        var result = '';
        
        $.ajax({
          type: "POST",
          url: "/wp-content/themes/gagara/scripts/sendMessage.php",
          data: str,
          success: function(msg) {
            if(msg == 'OK') {
              result = '<span>Ваша заявка отправлена! Мы свяжемся с Вами в ближайшее время.</span>';
              $("#sendApply").hide();
              $('#sendmessage').fadeIn().html(result);
            }
            else {
              result = msg;
              $('#errormessage').fadeIn().html(result);
                        
            }
          }
        });
      });

  $(".extra_check").on( "change", function() {
        var basicPrice = parseInt($('.totalPrice').attr("data-basicPrise"));
        var totalPrice = basicPrice;
        var targetString = "Лендинг";
        
        $( ".extra_check" ).each(function() {
          if(this.checked){
            var extraPrice = parseInt($(this).val());
            totalPrice += extraPrice;
            targetString += " + " + $(this).attr("data-name");
          }
            
        });

        $('.totalPrice').text(number_format(totalPrice));
        /*$('input[name=sum]').val(totalPrice);*/
        $('input[name=targets]').val(targetString);
         

      });

  $(".extra-land-check").on( "change", function() {
        var basicPrice = parseInt($('.totalPrice').attr("data-basicPrise"));
        var totalPrice = basicPrice;
        var hrefString = "/landing-order/";
        var counter = 0;
        
        $( ".extra-land-check" ).each(function() {
          if(this.checked){
            counter++;
            var extraPrice = parseInt($(this).val());
            totalPrice += extraPrice;
            hrefString += (counter == 1) ? "?" + $(this).attr("data-getName") + "=1" : "&" + $(this).attr("data-getName") + "=1";
          }
            
        });

        $('.totalPrice').text(number_format(totalPrice));
        $('#servServiceLink').attr("href", hrefString);
         

      });


  $(".categoryStartQuest").click(function(e) {
        e.preventDefault();

        var category_id = $(this).data('category');

        $.ajax({
          type: "POST",
          url: "/wp-content/themes/gagara/scripts/startQuest.php",
          data: {category_id: category_id},
          dataType: 'json',
          success: function(data) {
            if(data.success == true) {
              window.location.href = data.message;
            }
            else {
              alert(data.message);
                        
            }
          }
        });
      });



  $(".quest-form").submit(function(e) {
        e.preventDefault();
        $('.sendSiteMessage').hide().text('');
        $('.errorSiteMessage').hide().text('');

        var str = $(this).serialize();
        var result = '';
        
        $.ajax({
          type: "POST",
          url: "/wp-content/themes/gagara/scripts/checkQuest.php",
          data: str,
          success: function(msg) {
            if(msg == 'OK') {
              result = '<span>Ответ верен!</span>';
              $('.sendSiteMessage').fadeIn().html(result);
              location.reload();
            }
            else {
              result = msg;
              $('.errorSiteMessage').fadeIn().html(result);
                        
            }
          }
        });
      });



    /*new*/

    $(".getHint .getHintButton").click(function(e) {
        e.preventDefault();
        $('.getHint .errorMessage').hide().text('');

        const that = $(this);
        const url = that.data('url');
        const _token = that.data('token');

        
        $.ajax({
          type: "POST",
          url: url,
          data: {_token: _token},
          dataType: 'json',
          success: function(data) {
            if(data.success == true) {
                      
              that.attr("disabled", true);;
              $('.getHint .sendMessage').fadeIn().html(data.output);
            }
            else {
                      
              $('.getHint .errorMessage').fadeIn().html(data.output);                        
            }
          }
        });
      });




});
