$(document).ready(function() {
  $('.btn-delete').click(function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    var fila = $('#comentario-'+id);
    var form = $('#form-delete');
    var url = form.attr('action').replace('ID_COM',id);
    var data = form.serialize();
    bootbox.confirm(message, function(res){
      if (res == true) {
        $.post(url, data, function(result){
          fila.fadeOut();
          $('#message').removeClass('hidden');
          $('#message').text(result.message);
        }).fail(function(){
          alert('ERROR');
          fila.show();
        });
      }
    });
  });
});
