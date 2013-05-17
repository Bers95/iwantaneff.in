      var _gauges = _gauges || [];

      $(function () {

          $("#projTemplate").tmpl(projData).appendTo("section");

          // <DETAILS> POLYFILL
          $('details').details();

          // CREATE TOOLTIPS
          $('a[target~="_blank"]').simpleTooltip({
              title: 'Opens in new window!'
          });

          $('.herounit').simpleTooltip({
              title: $(this).attr('title')
          });

          var nativeDetailsSupport = $.fn.details.support;
          if (!nativeDetailsSupport) {
              $('html').addClass('no-details');
          } else {
              $('span').css('display', 'block')
          }


      });