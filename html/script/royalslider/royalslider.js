//$(function () {
//    $('.royalSlider').royalSlider({
//        arrowsNav: false,
//        loop: false,
//        numImagesToPreload:1,
//        keyboardNavEnabled: true,
//        controlsInside: false,
//        imageScaleMode: 'fill',
//        arrowsNavAutoHide: false,
//        autoScaleSlider: false,
//        controlNavigation: 'bullets',
//        thumbsFitInViewport: false,
//        navigateByClick: true,
//        startSlideId: 0,
//        autoPlay: false,
//        transitionType: 'move',
//        globalCaption: true,
//        deeplinking: {
//            enabled: true,
//            change: false
//        }
//    });

//});

function royalSlider() {
    $('.royalSlider').royalSlider({
        //fullscreen: {
        //    enabled: true,
        //    nativeFS: false
        //},
        //arrowsNav: true,
        arrowsNav: false,
        loop: false,
        numImagesToPreload: 1,
        keyboardNavEnabled: true,
        controlsInside: false,
        imageScaleMode: 'fill',
        arrowsNavAutoHide: false,
        autoScaleSlider: false,
        controlNavigation: 'bullets',
        thumbsFitInViewport: false,
        navigateByClick: true,
        startSlideId: 0,
        autoPlay: false,
        transitionType: 'move',
        globalCaption: true,
        deeplinking: {
            enabled: true,
            change: false
        }
    });
    var slider = $(".royalSlider").data('royalSlider');
}