{if $product_video && $product_video.active == 1 && $product_video.video_code != ""}
    {if $product_video.type == 1}
        <!-- YouTube Video -->
        <div class="video-container youtube">
            <div class="vid-bloc">
                <div id="videohistory" class="h-video">
                    <div class="cover">
                        <a href="https://www.youtube.com/embed/{$product_video.video_code}" data-fslightbox="gallery">
                            <img width="800" height="800" src="https://i.ytimg.com/vi/{$product_video.video_code}/maxresdefault.jpg" data-videoid="{$product_video.video_code}">
                        </a>
                    </div>
                    <div id="videoIframeInner"></div>
                </div>
            </div>
            <div class="buttons-video">
                <div class="button-video active first-play" id="play-button">
                    <svg focusable="false" aria-hidden="true" viewBox="0 0 24 24" title="PlayArrow" width="50" height="50">
                        <path d="M8 5v14l11-7z"></path>
                    </svg>
                </div>
            </div>
         </div>
    {elseif $product_video.type == 2}
        <!-- Vimeo Video -->
        <div class="video-container vimeo">
            <div class="v-video">

                <a href="https://player.vimeo.com/video/{$product_video.video_code}" data-fslightbox="gallery"></a>
                <iframe id="vimeo-vid" src="https://player.vimeo.com/video/{$product_video.video_code}?autoplay=1&loop=1&muted=1&api=1" width="640" height="800" frameborder="0" allowfullscreen></iframe>
            </div>

            <div class="buttons-video">
                <div class="button-video active first-play" id="play-button">
                    <svg focusable="false" aria-hidden="true" viewBox="0 0 24 24" title="PlayArrow" width="50" height="50">
                        <path d="M8 5v14l11-7z"></path>
                    </svg>
                </div>
            </div>
       </div>
    {elseif $product_video.type == 3}
        <!-- Video Link -->
        <div class="video-container">
            <video width="640" height="800" controls>
                <source src="{$product_video.video_code}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    {/if}
{/if}
