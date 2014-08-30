
$(document).ready(function()
    {
        // Scrollspy the navigation.
        $('#navigation .nav').scrollspy()

        // Fix the navigation at the top of the screen on scroll.
        var $win = $(window)
        var $nav = $('#navigation')
        var navTop = $nav.length && $nav.offset().top
        var isFixed = 0

        processScroll()

        $win.on('scroll', processScroll)

        function processScroll() {
            var i, scrollTop = $win.scrollTop()
            if (scrollTop >= navTop && !isFixed)
            {
                isFixed = 1
                $nav.addClass('nav-fixed')
            }
            else if (scrollTop <= navTop && isFixed)
            {
                isFixed = 0
                $nav.removeClass('nav-fixed')
            }
        }
    });

