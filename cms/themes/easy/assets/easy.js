
$(document).ready(function() {
  $('#burderMenu').click(function() {
    showHideSidebar($(this).data('target'));
  });
  $('.ejs-sidebar-background').click(function () {
    showHideSidebar($(this).data('target'));
  });
});
/**
*
*/
function showHideSidebar(targetId) {
  if ($(targetId).hasClass("ejs-sidebar-status-mobile-show")) {
    $(targetId).children('.ejs-sidebar-background').animate({ opacity: '0' }, 500);
    $(targetId).children('.ejs-sidebar-content').animate({left:'-101vw'}, 500, function() {
      $(targetId).addClass("ejs-sidebar-status-mobile-hide").removeClass("ejs-sidebar-status-mobile-show");
    })
  } else {
    $(targetId).children('.ejs-sidebar-background').css({ opacity: '0' });
    $(targetId).addClass("ejs-sidebar-status-mobile-show").removeClass("ejs-sidebar-status-mobile-hide");
    $(targetId).children('.ejs-sidebar-content').css({ left: '-101vw' });
    $(targetId).children('.ejs-sidebar-background').animate({ opacity: '0.4' }, 500);
    $(targetId).children('.ejs-sidebar-content').animate({ left: '0vw' }, 500, function () {
      
    });
    
  }
}
 