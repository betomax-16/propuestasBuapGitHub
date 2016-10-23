$(document).ready(function() {
  $('.btn-delete').click(function(e){
    e.preventDefault();
    var fila = $(this).parents('tr');
    var id = fila.data('id');
    var form = $('#form-delete');
    var url = form.attr('action').replace('ID_USER',id);
    var data = form.serialize();
    bootbox.confirm(message, function(res){
      if (res == true) {
        $('#delete-progress').removeClass('hidden');
        $.post(url, data, function(result){
          $('#delete-progress').addClass('hidden');
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
