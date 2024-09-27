$(function($) {
    $(".table-link").css("cursor","pointer").click(function() {
        location.href = $(this).data("href");
    });
});