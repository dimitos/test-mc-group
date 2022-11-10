var vpChanged = false;

function changeViewPort() {
    var ww = ($(window).width() < window.screen.width) ? $(window).width() : window.screen.width,
        mw = 375,
        ratio = ww / mw,
        curScroll = $(window).scrollTop();
    if (ww < mw) {
        $("#viewport").attr("content", "viewport-fit=cover, initial-scale=" + ratio + ", maximum-scale=" + ratio + ", minimum-scale=" + ratio + ", user-scalable=yes, width=" + ww);
        vpChanged = true;
    } else if (vpChanged) {
        $("#viewport").attr("content", "viewport-fit=cover, width=device-width");
        vpChanged = false;
    }
    $(window).scrollTop(curScroll);
}

changeViewPort();
$(window).on("resize", changeViewPort);
