<?php
use yii\helpers\Html;
?>

<div class="youtube-player-wrapper" hidden>
    <span class="close-player"></span>
    <div id="<?= $name ?>"></div>
</div>

<?php
$js = <<<JS
    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var $name;
    function onYouTubeIframeAPIReady() {
        $name = new YT.Player('$name', {
            height: '$height',
            width: '$width',
            videoId: '$videoId',
            playerVars: {
                'playsinline': 1
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        var thisPlayer = event.target;
        thisPlayer.stopVideo();

        var closeButton = document.querySelector('.youtube-player-wrapper > .close-player');
        var playerWrapper = document.querySelector('.youtube-player-wrapper');
        var playButton = document.getElementById('$playButtonId');

        
        closeButton.addEventListener('click', function() {
            console.log('Youtube Player: Close pressed');
            thisPlayer.stopVideo();
            playerWrapper.hidden = true;
        });
        
        playButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            console.log('Youtube Player: Play pressed');
            thisPlayer.playVideo();
            playerWrapper.hidden = false;
        });
        
        // Make sure the video is stopped at start.
        closeButton.click();
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            console.log('Youtube Player: PLAYING');
        }
        console.log('Youtube Player: state',YT.PlayerState);
    }
JS;

$this->registerJs($js, yii\web\View::POS_END);
?>