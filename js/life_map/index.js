
var lifeSpanMonth = 1020; // 85year

//浏览器变化宽度的动作。
function win_resize() {
    var $wind = $(window);
    var W = $wind.outerWidth();
    var H = $wind.outerHeight();

    H -= 80; //减去工具栏高度

    var area = W * H;

    if ($('#month') !== undefined) {
        var w = W /7;
        var h = H /6;

        $('div#lifeMap div.day').css('width', w + 'px');
        $('div#lifeMap div.day').css('height', h + 'px');
    }

    if ($('#year') !== undefined) {
        var w = W /3;
        var h = H /4;

        $('div#lifeMap div.monthInYear').css('width', w + 'px');
        $('div#lifeMap div.monthInYear').css('height', h + 'px');
    }

    if ($('#month-life') !== undefined) {
        for (var w = 1; w < 100; w++) {
            if (w * w * lifeSpanMonth > area) {
                break;
            }
        }

        w -= 2;  //减去边框宽度

        $('div#lifeMap div.month').css('width', w + 'px');
        $('div#lifeMap div.month').css('height', w + 'px');
    }

    if ($('#year-life') !== undefined) {
        for (var w = 1; w < 100; w++) {
            if (w * w * (lifeSpanMonth/12) > area) {
                break;
            }
        }

        w -= 2;  //减去边框宽度

        $('div#lifeMap div.year').css('width', w + 'px');
        $('div#lifeMap div.year').css('height', w + 'px');
    }
}

$(function () {
    $(window).resize(function () {
        win_resize();
    });

    win_resize();
});

//end file