<html>
  <?php GPLoad::getInstance()->require('theme', "head.php"); ?>
    <body>
      <svg display="none">
      <symbol id="svgBurgerMenu"  viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="menu">
        <rect x="2" y="4" width="16" height="1"></rect>
        <rect x="2" y="9" width="16" height="1"></rect>
        <rect x="2" y="14" width="16" height="1"></rect>
      </symbol>
    </svg>
    <div class="ecs-container">
      <header class="osp-header ecs-clearfix">
        <div class="osp-col-header-left">
          <div class="ecs-only-cell" id="burderMenu" data-target="#sidebar">
            <svg class="osp-search"><use xlink:href="#svgBurgerMenu"></use></svg>
          </div>
          <div class="osp-logo">Title</div>
        </div>
        <div class="osp-col-header-center"></div>
        <div class="osp-col-header-right"></div>

      </header>
      <div class="ecs-body">
        <nav id="sidebar" class="ecs-sidebar ejs-sidebar-status-mobile-hide">
          <div class="ejs-sidebar-content">NAV</div>
          <div class="ejs-sidebar-background" data-target="#sidebar"></div>
        </nav>
        <main class="ecs-content">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sit amet diam et mauris sagittis vulputate aliquam
          nec sem. In consequat rhoncus purus, quis pharetra felis mollis ut. Nunc sed magna eu est hendrerit efficitur non eget
          erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris sagittis
          aliquam ipsum, eget ullamcorper purus venenatis ut. Aliquam a ornare libero, vitae congue enim. Vestibulum mollis
          rhoncus augue, a suscipit ipsum. Cras congue pharetra eleifend.
          
          Maecenas ut fermentum neque. Integer convallis scelerisque urna, ac ultricies ex. Morbi quis erat cursus, tempor erat
          a, rutrum lacus. Fusce tristique pulvinar nibh. Fusce sit amet diam consectetur, gravida augue dapibus, porta nulla.
          Fusce molestie vitae urna eget pharetra. Curabitur pellentesque pharetra erat, non vehicula odio viverra nec. Etiam et
          erat id erat interdum ornare. Ut eu eros a nulla vestibulum dapibus nec et diam. Maecenas a laoreet eros, at ornare
          risus. Nullam a purus mi. Sed gravida lorem turpis, ut gravida ligula facilisis vel. Suspendisse potenti. Proin a
          mattis lacus. Cras sollicitudin turpis ut ipsum pulvinar accumsan sit amet id urna.
        </main>

      </div>
      <footer>…</footer>
    </div>  
  </body>
</html>