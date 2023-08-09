(function($) {
    "use strict";
    // hide menu on mobile
    if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
          $('.sidebar .collapse').collapse('hide');
        };
    }

    $('#criterio_academia-año-de-nacimiento').on('change', function(e) {
        if($(this).is(':checked')) {
            $('#collapseBirthDate').addClass('show');
            $('#collapseSchoolGrade').removeClass('show');
        }
    });

    $('#criterio_academia-grado-escolar').on('change', function(e) {
        if($(this).is(':checked')) {
            $('#collapseBirthDate').removeClass('show');
            $('#collapseSchoolGrade').addClass('show');
        }
    });

    if ( $('#criterio_academia-grado-escolar').is(':checked') ){
        $('#collapseBirthDate').removeClass('show');
        $('#collapseSchoolGrade').addClass('show');
    }

    $('form').on('submit', function(){
        $("button[type=submit]").attr("disabled", "true");
        $("button[type=submit]").html('<span id="loading-span" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...')
    });
})(jQuery); // End of use strict
