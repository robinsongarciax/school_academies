// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
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
    dom: 'Blfrtip',
    buttons: [{
      extend:'excelHtml5', className:'btn btn-sm btn-outline-primary mb-2'
    },{
      extend:'pdfHtml5', className:'btn btn-sm btn-outline-primary mb-2'
    }]
  });
  $('#academiesTable').DataTable({
    columnDefs: [{
      'visible': false,
      'targets': [0] 
    }],
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
    dom: 'Blfrtip',
    buttons: [{
      extend:'excelHtml5', className:'btn btn-sm btn-outline-primary mb-2'
    },{
      extend:'pdfHtml5', className:'btn btn-sm btn-outline-primary mb-2'
    }]
  });
  $('#modalDataTable').DataTable({
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
      },
    }
  });
});
