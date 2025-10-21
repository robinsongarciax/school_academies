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
                  }, {
                    extend:'pdfHtml5', className:'btn btn-sm btn-outline-primary mb-2'
                  }]
    });


    $('#dataTable2').DataTable({
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
                  }, {
                    extend:'pdfHtml5', className:'btn btn-sm btn-outline-primary mb-2'
                  }],
        columnDefs: [
            {targets: 0, visible: false}
        ],
        order: [0, 'desc']
    });

    var schoolCourseName = $('#schoolCourseName').text();
    var teacherName = $('#teacherName').text();
    var schedule = $('#schedule').text();
    // confirmed students table
    $('#tableConfirmedStudents').DataTable({
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
        buttons: [
            {
                extend:'excelHtml5', className:'btn btn-sm btn-outline-primary mb-2'
            }, {
                extend:'pdfHtml5', 
                className:'btn btn-sm btn-outline-primary mb-2',
                title: 'Curso escolar : ' + schoolCourseName,
                messageTop: 'Profesor (a): ' + teacherName + '\t' + 'Horario: ' + schedule
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
        buttons: [
            {
                extend:'excelHtml5', 
                className:'btn btn-sm btn-outline-primary mb-2'
            }, {
                extend:'pdfHtml5',
                className:'btn btn-sm btn-outline-primary mb-2'
            }
        ]
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
            }
        }
    });

    // confirmed students table
    $('#confirmedStudents').DataTable({
        language: {
            "emptyTable": "No hay alumnos inscritos",
            "lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 al 0 de 0 registros",
            "search": "Búsqueda",
            "paginate": {
                "first": "Inicio",
                "last": "Final",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
  
}); // end jquery
