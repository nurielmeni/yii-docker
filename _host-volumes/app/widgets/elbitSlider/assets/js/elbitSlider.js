var elbitSlider = (function ($) {
  var max = 0;
  var current = 0;
  var slider, sliderItems, btnLeft, btnRight;

  function setActive(start, count) {
    for (var i = 0; i < sliderItems.length; i++) {
      var key = $(sliderItems[i]).attr("key");
      if (i >= start && i < count + start) {
        $(sliderItems[i]).show();
        $(slider)
          .find('.inner-arrows-slider .btn-dots[key="' + key + '"]')
          .addClass("active");
      } else {
        $(sliderItems[i]).hide();
        $(slider)
          .find('.inner-arrows-slider .btn-dots[key="' + key + '"]')
          .removeClass("active");
      }
    }

    current <= 0 ? btnRight.hide() : btnRight.show();
    current >= sliderItems.length - visibleSlideNum()
      ? btnLeft.hide()
      : btnLeft.show();
  }

  function visibleSlideNum() {
    var vNum = $(".item-client:visible").length;
    return typeof vNum === "number" ? vNum : 0;
  }

  function slideRight(num) {
    num = num || 1;
    if (current - num < 0) return;

    current -= num;
    setActive(current, max);
  }

  function slideLeft(num) {
    num = num || 1;
    if (current + max >= sliderItems.length) return;

    current += num;
    setActive(current, max);
  }

  function init(config) {
    max = config.max || 1;

    slider = $("#" + config.name);
    sliderItems = $(slider).find(".item-client");

    btnRight = $(slider).find(".arrows-slider span.btn-arrows.next > i");
    btnLeft = $(slider).find(".arrows-slider span.btn-arrows.prev > i");

    btnRight.on("click", function () {
      slideRight();
    });
    btnLeft.on("click", function () {
      slideLeft();
    });

    // Mobile events
    MobileEvent.createListener("#" + config.name, "swiped-right", function () {
      slideRight();
    });
    MobileEvent.createListener("#" + config.name, "swiped-left", function () {
      slideLeft();
    });

    setActive(current, max);
  }

  return {
    init,
    slideLeft,
    slideRight,
  };
})(jQuery);
