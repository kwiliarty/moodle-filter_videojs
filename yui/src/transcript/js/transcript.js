"use_strict";
/*jslint browser: true*/
/*global M*/
/*global Y*/

var VJS;

M.filter_videojs = M.filter_videojs || {};
VJS = M.filter_videojs.transcript = {};

VJS.init = function (clips) {
    if (typeof VJS.initialized === 'undefined') {
        Y.Get.load(['http://eik.local/videojs/dist/video-js/video-js.css', 'http://eik.local/videojs/dist/video-js/video.js'], function (err) {
            if (err) {
                Y.log('Error loading CSS: ' + err[0].error, 'error');
                return;
            }
            Y.log('CSS loaded successfully');
            videojs.options.flash.swf = "http://eik.local/videojs/dist/video-js/video-js.swf";
            console.log(VJS);
            VJS.buildClips();
        });
        // VJS.setInitialized();
        VJS.initialized = 'initialized';
        VJS.videos = [];
    }
    VJS.videos.push(JSON.parse(clips));
}

VJS.buildClips = function () {
    for (video in VJS.videos) {
        if (!VJS.videos.hasOwnProperty(video)) {
            continue;
        }
        //VJS.videos[video].player = videojs(VJS.videos[video].id);
        //console.debug(VJS.videos[video].player);
        console.log(videojs(VJS.videos[video].id));
        console.log("hello");
    }
    console.log(VJS);
    console.log(Video);
}
