var MobileEvent = (function() {

    function createListener(target, type, cb) {
        var touch = {
            startX: 0,
            startY: 0,
            endX: 0,
            endY: 0,
        }

        var targetEl = document.querySelector(target);
        if (!targetEl) return;

        targetEl.addEventListener(
            'touchstart',
            function (event) {
                touch.startX = event.targetTouches[0].screenX;
                touch.startY = event.targetTouches[0].screenY;
            },
            false
        );

        targetEl.addEventListener(
            'touchend',
            function (event) {
                touch.endX = event.changedTouches[0].screenX;
                touch.endY = event.changedTouches[0].screenY;
                handleGesure(touch, type, cb);
            },
            false
        );
    }

    function handleGesure(touch, type, cb) {
      switch (type) {
        case 'swiped-left':
          if (touch.endX < touch.startX) {
            console.log(type);
            cb();
          }
          break;
        case 'swiped-right':
          if (touch.endX > touch.startX) {
            console.log(type);
            cb();
          }
          break;
        case 'swiped-down':
          if (touch.endY < touch.startY) {
            console.log(type);
            cb();
          }
          break;
        case 'swiped-up':
          if (touch.endY > touch.startY) {
            console.log(type);
            cb();
          }
          break;
        case 'tap':
          if (touch.endY == touch.startY) {
            console.log('tap!');
            cb();
          }
          break;
      }
    }

    return {
        createListener
    }
})();