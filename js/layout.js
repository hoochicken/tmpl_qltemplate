jQuery(function ($) {
  let headerTop = $('#logo');
  let headerBottom = $('#header-menu');
  let heightHeaderTop = headerTop.height();
  let headerBottomClone = headerBottom.clone();
  headerBottomClone.addClass('clone');
  $(window).scroll(function () {
    if ($(window).scrollTop() > heightHeaderTop) {
      $('body').append(headerBottomClone)
      headerBottomClone.addClass('container-fixed-top').addClass('wrapper');
    } else {
      headerBottomClone.removeClass('container-fixed-top').remove();
    }
  });
});