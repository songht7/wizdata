/**
** royal.api: http://dimsemenov.com/plugins/royal-slider/documentation/#options
**/

var cfg={
    arrowsNav: true,
    loop: true,
    keyboardNavEnabled: true,
    controlsInside: false,
    imageScaleMode: 'fill',
    imageScalePadding:0,
    imageAlignCenter:true,
    arrowsNavAutoHide: false,
    autoScaleSlider: true,
    autoPlay: {
        // autoplay options go gere
        enabled: true,
        pauseOnHover: true
      },
    autoScaleSliderWidth: 960,     
    autoScaleSliderHeight: 350,
    controlNavigation: 'bullets',
    thumbsFitInViewport: false,
    navigateByClick: true,
    startSlideId: 0,
    //autoPlay: false,
    transitionType:'move',
    globalCaption: true,
    deeplinking: {
      enabled: true,
      change: false
    },
    imgWidth: 2000,
    imgHeight: 545
  }
var slider="";
$(function(){
  setRoyal();
  //reSizeForSlide();
  // $(window).resize(function (event) {
  //     reSizeForSlide();
  // });
});
function reSizeForSlide () {
  var winWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
  if(winWidth<=415){
      cfg['imageScaleMode']='fit-if-smaller';
      cfg['imageAlignCenter']=false;
      cfg['imgWidth']=1000;
      cfg['imgHeight']=400;
  }else{
      cfg['imageScaleMode']='fill';
      cfg['imageAlignCenter']=true;
      cfg['imgWidth']=2000;
      cfg['imgHeight']=545;
  }
  if(slider){
    console.log(slider);
    //slider.destroy();
  }
  setRoyal();
}
function setRoyal(){
  var sliderBox=$(".royalSlider");
  if(sliderBox.hasClass('justOne')){cfg['loop']=false;cfg['arrowsNav']=false;cfg['autoScaleSlider']=false;cfg['controlNavigation']="none";}
  sliderBox.royalSlider(cfg);
  slider=sliderBox.data('royalSlider');
}