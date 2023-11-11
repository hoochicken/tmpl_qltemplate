jQuery(document).ready(function () {
  function setCookie(c_name,value,exdays,domain) {
    if (domain != "") {domain = "; domain=" + domain}

    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()) + "; path=/" + domain;

    document.cookie = c_name + "=" + c_value;
  }

  var $jb_cookie = jQuery(".jb-cookie"),
    cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)jbcookies\s*\=\s*([^;]*).*$)|^.*$/, "$1");

  if (cookieValue === "") { // NO EXIST
    $jb_cookie.delay(1000).slideDown("fast");

  }

  jQuery(".jb-accept").click(function() {
    setCookie("jbcookies","yes", 7,"");
    $jb_cookie.slideUp("slow");
    jQuery(".jb-cookie-decline").fadeIn("slow", function() {});
  });

  jQuery(".jb-decline").click(function() {
    jQuery(".jb-cookie-decline").fadeOut("slow", function() {
      jQuery(".jb-cookie-decline").find(".hasTooltip").tooltip("hide");
    });
    setCookie("jbcookies","",0,"");
    $jb_cookie.delay(1000).slideDown("fast");
  });
});