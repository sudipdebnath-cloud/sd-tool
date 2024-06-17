jQuery(document).ready(function($) {
    function updateCountdown(timer) {
        var endDate = new Date(timer.data('date')).getTime();
        var now = new Date().getTime();
        var distance = endDate - now;

        if (distance < 0) {
            timer.html('<span>The countdown has ended!</span>');
            return;
        }

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        var daysString = String(days).padStart(3, '0');
        var hoursString = String(hours).padStart(2, '0');
        var minutesString = String(minutes).padStart(2, '0');
        var secondsString = String(seconds).padStart(2, '0');

        timer.find('.days span').each(function(index) {
            $(this).text(daysString.charAt(index));
        });
        timer.find('.hours span').each(function(index) {
            $(this).text(hoursString.charAt(index));
        });
        timer.find('.minutes span').each(function(index) {
            $(this).text(minutesString.charAt(index));
        });
        timer.find('.seconds span').each(function(index) {
            $(this).text(secondsString.charAt(index));
        });
    }

    function initializeCountdown() {
        $('.countdown-timer').each(function() {
            var timer = $(this);
            setInterval(function() {
                updateCountdown(timer);
            }, 1000);
            updateCountdown(timer);
        });
    }

    initializeCountdown();
});