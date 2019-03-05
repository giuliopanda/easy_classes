<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Crea un nuovo progetto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="javascript/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/reset.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="sidebar.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/button.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/flex.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/form.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/list.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/content.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/utility.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/table.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="framework-css/pagination.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="tmpl-01.css" />
</head>
<body class="ecs ">
  <style>
  </style>
  <script>
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
  </script>
  <svg display="none">
    <symbol id="svgBurgerMenu" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="menu">
      <rect x="2" y="4" width="16" height="1"></rect>
      <rect x="2" y="9" width="16" height="1"></rect>
      <rect x="2" y="14" width="16" height="1"></rect>
    </symbol>
    <symbol id="svgProjectBoxClose" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M11.499 12.03v11.971l-10.5-5.603v-11.835l10.5 5.467zm11.501 6.368l-10.501 5.602v-11.968l10.501-5.404v11.77zm-16.889-15.186l10.609 5.524-4.719 2.428-10.473-5.453 4.583-2.499zm16.362 2.563l-4.664 2.4-10.641-5.54 4.831-2.635 10.474 5.775z"/></symbol>
    <symbol  id="svgProjectBoxOpen" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M11.5 23l-8.5-4.535v-3.953l5.4 3.122 3.1-3.406v8.772zm1-.001v-8.806l3.162 3.343 5.338-2.958v3.887l-8.5 4.534zm-10.339-10.125l-2.161-1.244 3-3.302-3-2.823 8.718-4.505 3.215 2.385 3.325-2.385 8.742 4.561-2.995 2.771 2.995 3.443-2.242 1.241v-.001l-5.903 3.27-3.348-3.541 7.416-3.962-7.922-4.372-7.923 4.372 7.422 3.937v.024l-3.297 3.622-5.203-3.008-.16-.092-.679-.393v.002z"/></symbol>
    <symbol id="svgDatabase"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 18.055v2.458c0 1.925-4.655 3.487-10 3.487-5.344 0-10-1.562-10-3.487v-2.458c2.418 1.738 7.005 2.256 10 2.256 3.006 0 7.588-.523 10-2.256zm-10-3.409c-3.006 0-7.588-.523-10-2.256v2.434c0 1.926 4.656 3.487 10 3.487 5.345 0 10-1.562 10-3.487v-2.434c-2.418 1.738-7.005 2.256-10 2.256zm0-14.646c-5.344 0-10 1.562-10 3.488s4.656 3.487 10 3.487c5.345 0 10-1.562 10-3.487 0-1.926-4.655-3.488-10-3.488zm0 8.975c-3.006 0-7.588-.523-10-2.256v2.44c0 1.926 4.656 3.487 10 3.487 5.345 0 10-1.562 10-3.487v-2.44c-2.418 1.738-7.005 2.256-10 2.256z"/></symbol>
  </svg>
  <div class="ecs-container">
    <header class="osp-header ecs-clearfix">
      <?php require('template-part/tmpl-01-header-top.php'); ?>
    </header>
    <div class="ecs-body">
      <nav id="sidebar" class="ecs-sidebar ejs-sidebar-status-mobile-hide">
        <div class="ejs-sidebar-content">
          <div class="ecs-pr1">
            <?php 
            $active = ($_REQUEST['action'] ) ? $_REQUEST['action'] : 'edit-list-show';
            require('template-part/tmpl-sidebar.php'); ?>
          </div>
        </div>
        <div class="ejs-sidebar-background" data-target="#sidebar"></div>
      </nav>
      <main class="ecs-content">
      <p>
       Le connesioni sono legate ai singoli progetti e istanze del progetto. quindi è dentro lo stesso progetto.
      Per ora non pensiamo alle istanze! pensiamo solo ai progetti nella loro versione più semplice!

      Nei progetti pubblicati i dati delle tabelle sono bloccati, ma ci sono i metadata per estenderli.

      </p> 
        <pre><code>
{ 
  "connection": {
      "host":"127,0,0,1",
      "user":"amdin",
      "pwd":"admin",
      "database":"ecs"
  }
  "tables": {
      "ecs_users": {
          "primary_key":"id",
          "fields":{
            "id":{"label":"id", "type":"int","length":"11"},
            "username":{"label":"username", "type":"varchar", "length":"250" },
            "password":{"label":"username", "type":"varchar", "length":"250"}
          }
      }
  }
}


        </code></pre>
      </main>

    </div>
    <footer>…</footer>
  </div>  
</body>
</html>