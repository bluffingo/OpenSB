(function(g){var window=this;var D4=function(a){return"_"+(0,window.encodeURIComponent)(a).replace(/[.!~*'()%]/g,function(a){return pqa[a]})};var E4=function(a,b){this.l=a;this.j=null;if(g.D&&!g.xc(9)){F4||(F4=new g.Li);this.j=F4.get(a);this.j||(b?this.j=window.document.getElementById(b):(this.j=window.document.createElement("userdata"),this.j.addBehavior("#default#userData"),window.document.body.appendChild(this.j)),F4.set(a,this.j));try{this.j.load(this.l)}catch(c){this.j=null}}};
var G4=function(a){try{a.j.save(a.l)}catch(b){throw"Storage mechanism: Quota exceeded";}};var H4=function(a){return a.j.XMLDocument.documentElement};var I4=function(){var a=g.Ej("visibilityState",window.document);return!(!a||"visible"==a)};var J4=function(a,b){var c;b?c=null:(c=g.Su(a))||(c=new E4(a||"UserDataSharedStore"),c=c.isAvailable()?c:null);this.j=c?new g.qo(c):null};
var qqa=function(){K4.push(g.z("player-playback-start",g.r(L4,!0)));K4.push(g.z("player-autonav-change-request",rqa));K4.push(g.z("player-autonav-pause-request",sqa));(0,g.A)(["check","uncheck","change"],function(a){M4.push(g.L(N4,a,O4))});K4.push(g.z("page-scroll",P4));M4.push(g.L(g.E("watch8-secondary-actions"),"click",tqa,!0));M4.push(g.L(window.document.body,"focus",Q4,!0));M4.push(g.L(window.document.body,"blur",uqa,!0));K4.push(g.z("yt-www-comments-sharebox-open",Q4));M4.push(g.L(window.document,
"visibilitychange",R4));var a=g.KA();a.addEventListener("onVolumeChange",R4);a.addEventListener("autonavcancel",vqa);g.x("PREFETCH_AUTONAV")&&a.addEventListener("onStateChange",S4)};var tqa=function(){T4=g.Ya(function(){var a=g.E("watch-action-panels");if(a){for(var b=g.P("pause-resume-autoplay"),c=!1,d=0;d<b.length;d++)if(g.I(b[d],"yt-uix-button-toggled")){c=!0;break}!c&&g.I(a,"hid")?(U4=!1,g.$a(T4)):U4=!0;L4()}},500)};
var wqa=function(){V4=window.document.activeElement&&"IFRAME"==window.document.activeElement.tagName.toUpperCase();L4()};var P4=function(){W4=-200>X4.getBoundingClientRect().top;L4()};var Q4=function(a){if(a&&("INPUT"==a.target.tagName||"TEXTAREA"==a.target.tagName)){if("autoplay-checkbox"==a.target.id)return;Y4=!0}L4()};var R4=function(){if(Z4()){var a=g.KA();$4=I4()&&(a.isMuted()||0==a.getVolume());L4()}};var sqa=function(a){a5=a;L4()};var xqa=function(){b5=!0;L4();c5=0};
var d5=function(){b5=!1;L4();c5&&g.Za(c5);c5=g.y(xqa,144E5-g.Xd())};var uqa=function(a){I4()||(!a||"INPUT"!=a.target.tagName&&"TEXTAREA"!=a.target.tagName||(Y4=!1),L4())};var L4=function(a){e5=W4||V4||Y4||$4||a5||U4||b5;var b;b=f5()?e5?3:2:1;var c=g.KA(),d=Z4();d&&!c.setAutonav&&g.ab(Error("Player is ready but setAutonav is not"),"WARNING");d&&(a||g5!=b)&&(c.setAutonavState(b),g5=b);h5()};var Z4=function(){var a=g.KA();return a&&a.isReady()};
var O4=function(){var a=f5(),b=N4.checked;b!=a&&(a=g.cd({state:b?"enabled":"disabled"}),g.Jj("autoplay",a,void 0),a=g.Rf.getInstance(),b||g.Nh(141,!0),g.Nh(140,!b),a.save(),i5.set("autonav_disabled",!b),L4())};var rqa=function(a){N4.checked=a;O4()};var f5=function(){return!g.Wf(g.Rf.getInstance(),140)};var h5=function(){if(g.x("AUTONAV_EXTRA_CHECK")){var a=f5(),b=!i5.get("autonav_disabled"),c="";a!=b&&(c="Cookie does not match localstorage value cookie:"+a+" LocalStorage:"+b);c&&g.ab(Error(c),"WARNING")}};
var S4=function(a){if(0==a&&f5()&&!e5&&(a=g.F("autoplay-bar"),a=g.F("spf-link",a))){var b=g.Oq(a);b.autonav=1;b.feature="related-auto";b.playnext=1;var c=g.Xd();0<c&&(b.lact=c);g.Wz(a.href,!!g.x("PREBUFFER_AUTONAV"),b)}};var vqa=function(a){a.cancel&&a.userTriggered&&g.x("AUTOPLAY_GUIDED_HELP_NEXT_CANCEL")&&j5()};var j5=function(){var a=g.Rf.getInstance();g.Wf(0,146)||g.Wf(0,141)||(g.Fn(),g.Gn(6223778),g.Nh(146,!0),a.save())};
var pqa={".":".2E","!":".21","~":".7E","*":".2A","'":".27","(":".28",")":".29","%":"."},F4=null;g.t(E4,g.ao);g.h=E4.prototype;g.h.isAvailable=function(){return!!this.j};g.h.set=function(a,b){this.j.setAttribute(D4(a),b);G4(this)};g.h.get=function(a){a=this.j.getAttribute(D4(a));if(!g.la(a)&&null!==a)throw"Storage mechanism: Invalid value was encountered";return a};g.h.remove=function(a){this.j.removeAttribute(D4(a));G4(this)};g.h.qb=function(){return H4(this).attributes.length};
g.h.fc=function(a){var b=0,c=H4(this).attributes,d=new g.xi;d.next=function(){if(b>=c.length)throw g.qh;var d;d=c[b++];if(a)return(0,window.decodeURIComponent)(d.nodeName.replace(/\./g,"%")).substr(1);d=d.nodeValue;if(!g.la(d))throw"Storage mechanism: Invalid value was encountered";return d};return d};g.h.clear=function(){for(var a=H4(this),b=a.attributes.length;0<b;b--)a.removeAttribute(a.attributes[b-1].nodeName);G4(this)};J4.prototype.set=function(a,b,c,d){c=c||31104E3;this.remove(a);if(this.j)try{this.j.set(a,b,(0,g.G)()+1E3*c);return}catch(e){}var f="";if(d)try{f=(0,window.escape)(g.ho(b))}catch(k){return}else f=(0,window.escape)(b);g.ae(a,f,c,window.document.domain)};J4.prototype.get=function(a,b){var c=void 0,d=!this.j;if(!d)try{c=this.j.get(a)}catch(e){d=!0}if(d&&(c=g.ce(a))&&(c=(0,window.unescape)(c),b))try{c=g.fo(c)}catch(f){this.remove(a),c=void 0}return c};
J4.prototype.remove=function(a){this.j&&this.j.remove(a);g.Ok(a,"/",window.document.domain)};var M4=[],K4=[],X4=null,N4=null,k5=0,T4=0,c5=0,e5=!1,W4=!1,Y4=!1,U4=!1,V4=!1,$4=!1,a5=!1,b5=!1,g5=1,i5=new J4("yt.autonav");g.ub(g.Ql({name:"www/autoplayrenderer",deps:["www/watch"],page:"watch",init:function(){X4=g.E("player");if(N4=g.E("autoplay-checkbox"))g.KA()?(k5=g.Ya(wqa,500),d5(),K4.push(g.z("USER_ACTIVE",d5)),N4.checked=f5(),qqa(),P4(),R4(),h5(),g.x("AUTOPLAY_GUIDED_HELP_NEXT_WATCH")&&j5()):g.ab(Error("Autoplay player is missing"),"WARNING")},dispose:function(){g.M(M4);M4.length=0;g.kb(K4);c5&&(g.Za(c5),c5=0);K4.length=0;N4=X4=null;W4=!1;g.$a(k5);var a=g.KA();a&&(a.removeEventListener("onStateChange",S4),a.removeEventListener("onVolumeChange",
R4));b5=a5=$4=U4=V4=Y4=W4=e5=!1}}));})(_yt_www);
