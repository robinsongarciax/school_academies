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
})(jQuery); // End of use strict