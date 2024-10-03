$(() => {
    $("#toggleBtn").click(function() {
        if ($(".navigation").hasClass('active')) {
            $(".navigation").removeClass("active");
        } else {
            $(".navigation").addClass("active");
        }
    });
});