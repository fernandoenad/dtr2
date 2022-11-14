!function($){
  var docCookies = {
  getItem: function (sKey) {
    if (!sKey) { return null; }
    return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
  },
  setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
    if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
    var sExpires = "";
    if (vEnd) {
      switch (vEnd.constructor) {
        case Number:
          sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
          break;
        case String:
          sExpires = "; expires=" + vEnd;
          break;
        case Date:
          sExpires = "; expires=" + vEnd.toUTCString();
          break;
      }
    }
    document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
    return true;
  },
  removeItem: function (sKey, sPath, sDomain) {
    if (!this.hasItem(sKey)) { return false; }
    document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
    return true;
  },
  hasItem: function (sKey) {
    if (!sKey) { return false; }
    return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
  },
  keys: function () {
    var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
    for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) { aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]); }
    return aKeys;
  }
};

var popUp = $('<div id="uams-announcements-modal" class="modal fade"><div class="modal-dialog"><div class="modal-content">' +
'<div class="modal-header"><h4>Announcements</h4></div>' +
'<div class="modal-body"></div>' +
'<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>' +
'</div></div></div>');
$(document.body).append(popUp);

$(popUp).modal({show: false});


function fetchAnnouncement(cb) {
    cb(window['uams-announcements'] || []);
}

function updateAnnouncement(announcements) {
   var popUpBody = $(popUp).find('.modal-body');

   popUpBody.empty();

   if (announcements.length) {
       for (var i = 0; i < announcements.length; i++) {
         popUpBody.append(
           $('<p><span class="text-muted">'+announcements[i].date  +'</span><br/>'+announcements[i].message+'</p>')
         );
       }
   } else {
       popUpBody.append(
         $('<p>No update available</p>')
       );
   }
}

fetchAnnouncement(function(announcements) {
    updateAnnouncement(announcements);

    var c = docCookies.getItem('uis-announcements');
    if (announcements.length && (!c || ((new Date(c)).valueOf() < (new Date(announcements[0].date)).valueOf()))) {
        docCookies.setItem('uis-announcements', (new Date(announcements[0].date)).toDateString());
        $(popUp).modal();
    }
});

$('#uams-support-menu').append($('<li><a href="#" data-target="#uams-announcements-modal" data-toggle="modal">Announcements</a></li>'))
;



}(jQuery);
