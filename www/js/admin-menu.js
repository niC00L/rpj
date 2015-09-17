$(function () {
    $(".button-collapse").sideNav({
        edge: 'right'
    });

    var x = $.cookie('menu');

    $("header").addClass(x);
    $("main").addClass(x);
    $("#admin-nav").addClass(x);

    $("#admin-toggle").click(function () {   
        $("header, main, #admin-nav").addClass('w-trans');        
        $("header").toggleClass('admin-open');
        $("main").toggleClass('admin-open');
        $("#admin-nav").toggleClass('admin-open');
        
        if ($("#admin-nav").hasClass('admin-open')) {
            $.cookie('menu', 'admin-open', { path: '/' });
        }
        else {
            $.cookie('menu', 'admin-closed', { path: '/' });
        }                        
        console.log($.cookie('menu'));
    });    
    console.log(x);
});
