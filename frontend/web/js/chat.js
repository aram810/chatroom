$(document).ready(function() {
	setInterval(function() {
		reloadChat('', false)
	}, 3000);

	$('#send_message').on('click', function(e) {
		e.preventDefault();

		let message = $('textarea[name="new_message"]').val();

		reloadChat(message, true);
	});
});

function reloadChat(message, clearChat) {
	let url = $('#update_url').val(),
		lastMessage = $('#messages').find('> ul > li').last(),
		lastMessageId = lastMessage.data('id');

	$.ajax({
		url: url,
		data: {
			message: message,
			lastMessageId: lastMessageId
		},
		type: 'POST',
	}).done(function(res) {
		let roomId = $('#roomId').val();
		// console.log(xhr.getResponseHeader('activeUser' + roomId));

		if (true === clearChat) {
			$('textarea[name="new_message"]').val('');
		}

		$('#messages').find('> ul').append(res.html);
		$('#active_users_count').text(res.activeUsersCount);
	}).fail(function() {
		console.log('fail');
	});
}
