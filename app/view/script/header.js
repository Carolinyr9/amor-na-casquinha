$(document).ready(function() {
    $('#toggleBtn').click(function () {
    const nav = $('.navigation');
    
    if (nav.is(':visible')) {
        console.log('hide');
        nav.hide(); 
        nav.removeClass('active');
    } else {
        console.log('show');
        nav.css('display', 'flex').hide().show();
        nav.addClass('active');
    }
    });

});