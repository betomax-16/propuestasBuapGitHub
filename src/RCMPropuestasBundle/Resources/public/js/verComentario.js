$(document).ready(function() {
  $('.panel-default').click(function() {
    $('#notification-progress').removeClass('hidden');
    var url = '/propuestasBuapGitHub/web/app_dev.php/comentario/notificacion_vista';
    var data = {'id':$(this).attr('name')};
    var redirect = $(this).attr('id');
    $.post(url, data, function (response) {
      if (response.message == 'success') {
        $(location).attr('href',redirect);
      }
    }).fail(function () {
      alert('ERROR');
    });
  });
});
