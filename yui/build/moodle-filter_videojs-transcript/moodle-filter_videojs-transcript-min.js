YUI.add("moodle-filter_videojs-transcript",function(e,t){"use_strict";var n;M.filter_videojs=M.filter_videojs||{},n=M.filter_videojs.transcript={},n.init=function(t){typeof n.initialized=="undefined"&&(e.Get.load([t.sources.css_source,t.sources.js_source],function(r){if(r)return;videojs.options.flash.swf=t.sources.swf_source,e.on("domready",function(){n.buildClipMenu()})}),n.initialized="initialized",n.videos=[]);var r=JSON.parse(t.clips);n.videos[r.id]=r},n.buildClipMenu=function(){n.players=e.all(".video-js"),n.players.each(function(t){t.setData("out",""),t.setData("in",0),t.setData("playerID",t._node.id),t.setData("srctypes","");var r=videojs(t.get("id"));if(r.techName==="Flash"){alert("The Video.js Flash player does not support all of the Video.js Filter functionality. Please try a different browser.");return}r.ready(function(){this.load()}),r.on("timeupdate",function(){this.currentTime()<t.getData("in")&&this.currentTime(t.getData("in"));if(t.getData("out")==="")return;this.currentTime()>t.getData("out")&&(this.currentTime(t.getData("out")),this.pause())});var i=n.videos[t.getData("playerID")].clips;if(i.length>0){var s=e.Node.create("<ol></ol>");s.addClass("video-js-cliplist"),e.one("#"+t.getData("playerID")).insert(s,"before");for(var o=0;o<i.length;o++){var u=o+1,a=i[o].params,f=a.label;f===""&&(f="Clip");var l=e.Node.create("<a href='#' class='filter-vjs-cliplink clip"+u+"'>"+f+"</a>");l.setData("params",a),l.setData("playerID",t._node.id),l.setData("clipSrc","http://kevinwiliarty.com/openvideo/remote-conbowling.ogv"),l.setData("clipNumber",u);var c=e.Node.create("<li></li>");c.append(l),s.append(c),o===0&&(t.setData("in",a.in),t.setData("out",a.out),t.setData("srctypes",a.srctypes))}}r=videojs(t.getData("playerID")),r.load(),t.getData("srctypes")!=""&&r.src(t.getData("srctypes"))}),e.on("domready",function(){linkList=e.all(".filter-vjs-cliplink"),linkList.each(function(e){e.on("click",function(e){e.preventDefault(),n.playClip(this)})})})},n.playClip=function(t){var n=t.getData("params"),r=t.getData("playerID"),i=t.getData("clipNumber"),s=".clip"+i,o=t.ancestor("ol"),u=videojs(r);u.load();var a=e.one("#"+r);o.all(".filter-vjs-cliplink").setStyle("fontWeight","normal"),o.one(s).setStyle("fontWeight","bold"),a.setData("in",n.in),a.setData("out",n.out),u.ready(function(){u.src(n.srctypes),u.bigPlayButton.hide(),u.controlBar.show(),u.play()})}},"@VERSION@",{requires:["base","node","event","get"]});
