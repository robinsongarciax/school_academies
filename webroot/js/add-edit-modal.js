

$('.btn-modal').on('click', function () {
	var title = $(this).attr('modal-title');
	var dataURL = $(this).attr('href');
    $('.modal-title').text(title);
	$('.modal-body').load(dataURL,function(){
		$('#addEditModal').modal({show:true});
	});
})
