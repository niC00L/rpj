$(function () {
	$.nette.init();
	$('.materialboxed').materialbox();
	$('.parallax').parallax();
	$('.modal-trigger').leanModal();
	$('select').material_select();
	$('.slider').slider();
	$('.carousel').carousel();

//TINYMCE
	var toolbarSmall = [
		'undo redo | fontsizeselect | bold italic underline ',
		'formatselect | alignleft, aligncenter, alignright, alignjustify ',
		' bullist, numlist, outdent, indent | removeformat | link image',
	];
	var toolbarMedium = [
		'undo redo | formatselect | fontsizeselect | bold italic underline ',
		' alignleft, aligncenter, alignright, alignjustify | bullist, numlist, outdent, indent | removeformat | link image',
	];
	var toolbarBig = [
		'undo redo | formatselect | fontsizeselect | bold italic underline | alignleft, aligncenter, alignright, alignjustify | bullist, numlist, outdent, indent | removeformat | link image',
	];

	if ($('.tinymce').width() < 500) {
		var toolbar = toolbarSmall;
	} else if ($(window).width() < 870) {
		var toolbar = toolbarMedium;
	} else {
		var toolbar = toolbarBig;
	}

	tinymce.init({
		selector: '.tinymce',
		toolbar: toolbar,
		menubar: false,
		inline: true,
	});

	var tinyMce = $('.tinymce');
	if (tinyMce) {
		var orText = $('.mced textarea').text();
		if (orText) {
			tinyMce.html(orText);
		}
		$('body').click(function () {
			var editedText = tinyMce.html()
			$('.mced textarea').text(editedText);
		});
	}

//Others
	$('.toggle-menu').sideNav({
		edge: $(this).data('side'), // Choose the horizontal origin
	});
});
function readURL(input, output, bcg) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			if (bcg == 1) {
				$(output).css('background-image', 'url(' + e.target.result + ')');
			} else {
				$(output).attr('src', e.target.result);
			}
		}
		reader.readAsDataURL(input.files[0]);
	}
}
