<script>
  jQuery(document).ready(function ($) {

      $("#dialog_attivita").kendoWindow({
          width: "900px",
          height: "80%",
          title: "<?php _e('Add activity for Customer:', 'wp-smart-crm-invoices-free') ?>",
          visible: false,
          modal: true,
          draggable: false,
          resizable: false,
          pinned: true,
          actions: [

              "Close"
          ],
          close: function () {
              this.title("<?php _e('Add activity for Customer:', 'wp-smart-crm-invoices-free') ?>");
              $('.modal_loader').hide();
              setTimeout(function () {
          $('.k-overlay').hide()
        }, 100);
          }
      })
      $("#data_attivita").kendoDateTimePicker({
          value: new Date(),
          format: $formatTime
      });
      var an_validator = $("#new_activity").kendoValidator({
          rules: {
              hasAnnotation: function (input) {
                  if (input.is("[name=n_annotazioni]")) {
                      var kb = $("#n_annotazioni").val();
                      if (kb == "") {

                          jQuery.playSound("<?php echo WPsCRM_URL ?>inc/audio/double-alert-2")
                          return false;
                      }
            ;
                  }
                  return true;
              }
          },
          messages: {
              hasAnnotation: "<?php _e('You should type some text', 'wp-smart-crm-invoices-free') ?>",
          }
      }).data("kendoValidator");
      //$("._date").kendoDatePicker({

      //})

      $("#saveActivity").click(function () {
          var opener = $('#dialog_attivita').data('from'), activityTimestamp = "";
          if (opener == "clienti")
              id_cliente = '<?php if (isset($ID)) echo $ID ?>'
          else if (opener == 'documenti')
              id_cliente = '<?php if (isset($fk_clienti)) echo $fk_clienti ?>';
          else if (opener == 'list')
              id_cliente = $('#dialog_attivita').data('fkcliente');
          activityTimestamp = $("#data_attivita").data('kendoDateTimePicker').value()
          if (an_validator.validate()) {
              data_attivita = $("#data_attivita").val();
              annotazioni = $("#n_annotazioni").val();
              $('.modal_loader').show();
              $.ajax({
                  url: ajaxurl,
                  data: {
                      action: 'WPsCRM_save_annotation',
                      id_cliente: id_cliente,
                      data_attivita: data_attivita,
                      activityTimestamp: activityTimestamp,
                      annotazioni: annotazioni,
                      security: '<?php echo $update_nonce; ?>',
                  },
                  type: "POST",
                  success: function (response) {
                      console.log(response);
                      if (opener == "clienti") {//ricarico la grid solo se aperto da form clienti
                          setTimeout(function () {
                              $("#tabstrip").kendoTabStrip().data("kendoTabStrip").activateTab($('#tab3'));
                              $.ajax({
                                  url: ajaxurl,
                                  data: {
                                      action: 'WPsCRM_reload_annotation',
                                      id_cliente: id_cliente,
                                  },
                                  type: "POST",
                                  success: function (response) {

                                      //console.log("reload: " + response);
                                      var html = $.parseHTML(response);
                                      var _html = $(html).find('#_timeline').find('.cd-timeline-block')
                                      console.log(_html)

                                      $('#cd-timeline').html(response);

                                      setTimeout(function () {
                                          var divList = $(".cd-timeline-block");
                                          divList.sort(function (a, b) {
                                              var date1 = $(a).data("date");
                                              date1 = date1.split('-');
                                              console.log("eeeee", date1[0], date1[2]);
                                              date1 = new Date(date1[0], date1[1] - 1, date1[2]);
                                              var date2 = $(b).data("date");
                                              date2 = date2.split('-');
                                              date2 = new Date(date2[0], date2[1] - 1, date2[2]);
                                              console.log(date1 < date2);
                                              return date1 < date2;
                                          }).appendTo('#_timeline');
                                          $('#_timeline').fadeIn('fast')
                                      }, 50)
                                  }
                              })
                              $("#dialog_attivita").data('kendoWindow').close();
                          }, 200)

                      } else {
                          noty({
                              text: "<?php _e('Annotation has been added', 'wp-smart-crm-invoices-free') ?>",
                              layout: 'center',
                              type: 'success',
                              template: '<div class="noty_message"><span class="noty_text"></span></div>',
                              //closeWith: ['button'],
                              timeout: 1000
                          });
                          $("#dialog_attivita").data('kendoWindow').close();

                      }

                      $('#new_activity').find(':reset').click();
                      $('#n_annotazioni').val('')
                  }
              })
          }
      });
      $('._reset').click(function () {
          $("#dialog_attivita").data('kendoWindow').close();
      })
  });
</script>
