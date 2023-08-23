const preescolar_arr = new Array('---', 'Bambolino 3', 'Kinder 1', 'Kinder 2', 'Kinder 3');
const primaria_arr = new Array('---', '1o. de Primaria', '2o. de Primaria', '3o. de Primaria', '4o. de Primaria', '5o. de Primaria', '6o. de Primaria');
const secundaria_arr = new Array('---', '1o. de Secundaria', '2o. de Secundaria', '3o. de Secundaria');

const sections = new Map();
sections.set('Preescolar', preescolar_arr);
sections.set('Primaria', primaria_arr);
sections.set('Secundaria', secundaria_arr);

$('#student-level').on('change', function() {
	section_levels = sections.get(this.value);
	if (section_levels != null) {
		$('#school-level').empty();
		for (var i = 0; i < section_levels.length; i++) {
			$('#school-level').append($('<option>', { 
	        	value: i == 0 ? '' : section_levels[i],
	        	text : section_levels[i]
	    	}));
		}
	}
});

$(document).ready(function() {
	section_levels = sections.get($('#student-level').find(":selected").val());
	console.info($('#student-level').find(":selected").val());
	if (section_levels != null) {
		$('#school-level').empty();
		for (var i = 0; i < section_levels.length; i++) {
			$('#school-level').append($('<option>', { 
	        	value: i == 0 ? '' : section_levels[i],
	        	text : section_levels[i]
	    	}));
		}
	}
});