$(function () {
    $.nette.init();
    $('.materialboxed').materialbox();
    $('.parallax').parallax();
    $('.modal-trigger').leanModal();
    $('select').material_select();

    $('.toggle-menu').sideNav({
        edge: $(this).data('side'), // Choose the horizontal origin
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
});
