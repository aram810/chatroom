$(document).ready(function() {
	$('#room-access_with_token').on('change', function() {
		if ($(this).prop('checked')) {
			$('#room-access_level').val(1);
		}
	});

	$('#room-access_level').on('change', function() {
		if (0 == $(this).val()) {
			$('#room-access_with_token').prop('checked', false);
		}
	});
});
