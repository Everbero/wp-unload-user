function notifyUserLogoff() {
  var data = {
    action: "setUserLogoffTimeout",
  };

  jQuery.post(ajax_prop.ajax_url, data, function (response) {});
  console.log('evoked offline stat');

}
function notifyUserOnline() {
  var data = {
    action: "setUserOnline",
  };
  jQuery.post(ajax_prop.ajax_url, data, function (response) {});
  console.log('evoked online stat');

}

// ready
(function ($) {
  
  
  window.onbeforeunload = function () {
    return notifyUserLogoff();
  };

  notifyUserOnline();
})(jQuery);
