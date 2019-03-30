
/** TOOGLE SIDEBAR  
 *  gli eventi della funzione 
*/
$(document).ready(function() {
  $('#burderMenu').click(function() {
    showHideSidebar($(this).data('target'));
  });
  $('.ejs-sidebar-background').click(function () {
    showHideSidebar($(this).data('target'));
  });
});
/**
* La funzione hce fa sparire o riapparire la sidebar quando si preme il burger button
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
 

/**
 * TODO: Cambio la pagina caricando solo la sezio
  function processAjaxData(response, urlPath){
     document.getElementById("content").innerHTML = response.html;
     document.title = response.pageTitle;
     window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath);
  }
  window.onpopstate = function(e){
      if(e.state){
          document.getElementById("content").innerHTML = e.state.html;
          document.title = e.state.pageTitle;
      }
  };

*/