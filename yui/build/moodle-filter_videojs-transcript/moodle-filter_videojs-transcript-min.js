YUI.add("moodle-filter_videojs-transcript",function(e,t){"use_strict";var n;M.filter_videojs=M.filter_videojs||{},n=M.filter_videojs.transcript={},n.init=function(t){typeof n.initialized=="undefined"&&(e.Get.load(["http://eik.local/videojs/dist/video-js/video-js.css","http://eik.local/videojs/dist/video-js/video.js"],function(t){if(t)return;videojs.options.flash.swf="http://eik.local/videojs/dist/video-js/video-js.swf",e.on("domready",function(){n.buildClipMenu()})}),n.initialized="initialized",n.videos=[]);var r=JSON.parse(t.clips);n.videos[r.id]=r},n.buildClipMenu=function(){n.players=e.all(".video-js"),n.players.each(function(t){var r=n.videos[t._node.id].clips;if(r.length>0){var i=e.Node.create("<ul></ul>");t.insert(i,"before");for(var s=0;s<r.length;s++){var o=r[s],u=s+1,a=o.params.label,f=": ";a==""&&(f="");var l=e.Node.create("<a href='#'>Clip "+u+f+a+"</a>");console.log(l);var c=e.Node.create("<li>"+l._node.outerHTML+"</li>");i.append(c)}}})}},"@VERSION@",{requires:["base","node","get"]});
