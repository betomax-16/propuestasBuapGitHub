$(document).ready(function(){

  $('#txtBusqueda').keyup(function() {
    if ($(this).val().length >= 2) {
      var usuario = {'nombre' : $('#txtBusqueda').val()};
      var url = '/propuestasBuapGitHub/web/app_dev.php/usuario/autocomplete';
      $.post(url, usuario, function(response) {
        $( "#txtBusqueda" ).autocomplete({
          source: response.datos,
          select: function(event, ui) {
            event.preventDefault();
            var form = $('#formBuscar');
            var url = form.attr('action').replace('ID_USER',ui.item.value);
            form.attr('action', url);
            form.submit();
          },
          focus: function(event, ui) {
            event.preventDefault();
            $("#txtBusqueda").val(ui.item.label);
          }
        });
      });
    }
  });

});
