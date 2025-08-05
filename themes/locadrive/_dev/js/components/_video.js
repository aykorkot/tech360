jQuery(function ($)
{
    var MYplayer;
    var Player = (function () {
        //private static
        var defaults = {
            events: {},
            playerVars: {
                modestbranding: 0,
                controls: 1, //remove controls
                loop: 1,
                showinfo: 0,
                enablejsapi: 1,
                iv_load_policy: 3
            }
        };

        var constructor = function (options) {
            this.options = $.extend(defaults, options);

            if (this.options.autoPlay) {
                this.options.events['onReady'] = function (event) {
                    event.target.playVideo()
                }
            }
            this.player = new YT.Player(this.options.id, this.options);
            MYplayer = this.player;
        }

        return constructor;
    })() //function(){
    $(document).ready(function () {
        setTimeout(function() {
            var ytbId = $("#videohistory .cover").find('img').fadeOut(500).attr('data-videoId');
            $("#videohistory .cover").addClass("p-iframe");
            myPlayer = new Player({
                id: 'videoIframeInner',
                changeVideo: '.videoGal',
                autoPlay: true,
                videoId: ytbId,
                playerVars: {
                    rel: 0,
                    loop: 1,
                    showinfo: 0,
                    controls: 0,
                    mute: 1,
                    ecver: 2
                }
            });
        }, 3000);


        $("#play-button").click(function() {
            MYplayer.playVideo(); // Supposons que myPlayer est votre objet vidéo
        });

    });
});

// Inject YouTube API script
var tag = document.createElement("script");
tag.src = "//www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// VIMEO VIDEO
$(document).ready(function() {
    if ($("#vimeo-vid").length) {
        var iframe = $('#vimeo-vid')[0];

        var player = $f(iframe);

        $('#play-button').on('click', function() {
            player.api('play');
        });
    }
});

//HTML5 VIDEO
$(document).ready(function() {
    $(".tv_video").each(function() {
        var video1 = this;
        video1.currentTime = 0;

        var parentContainer = $(video1).closest(".collection-bg");

        parentContainer.find(".mute-button").click(function() {
            if ($(this).hasClass("stop")) {
                $(video1).prop('muted', false);
                $(this).removeClass("stop");
            } else {
                $(video1).prop('muted', true);
                $(this).addClass("stop");
            }
        });

        parentContainer.find(".play-button").click(function() {
            $(this).hide();
            $(this).siblings(".pause-button").show();
            video1.play();
        });

        parentContainer.find(".pause-button").click(function() {
            $(this).siblings(".play-button").show();
            $(this).hide();
            video1.pause();
        });
    });
});

