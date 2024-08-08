

$('.btn-modal').on('click', function () {
	var title = $(this).attr('modal-title');
	var dataURL = $(this).attr('href');
	$('.modal-title').text(title);
	$('#addEditModal .modal-body').load(dataURL, function () {
		$('#addEditModal').modal({show:true});
		// Table students
    $('#students').DataTable({
      destroy: true,
      language: {
        "emptyTable": "Sin datos disponibles",
        "lengthMenu": "Mostrar _MENU_ registros",
        "info": "Mostrando _START_ al _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 al 0 de 0 registros",
        "search": "Búsqueda:",
        "paginate": {
          "first": "Inicio",
          "last": "Final",
          "next": "Siguiente",
          "previous": "Anterior"
        }
      },
      "pageLength": 50
    });
	});
})
