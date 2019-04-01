<html>
    <?php 
    $item = $this->cData;
    $router = gpRouter::getInstance();
    GPLoad::getInstance()->require('theme', "head.php");
    ?>
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
          <div class="osp-logo">EASY_CLASSES</div>
        </div>
        <div class="osp-col-header-center"></div>
        <div class="osp-col-header-right"></div>
      </header>
      <div class="ecs-body">
        <?php if ($item->has('navbar')) : ?>
          <nav id="sidebar" class="ecs-sidebar ejs-sidebar-status-mobile-hide">
            <div class="ejs-sidebar-content">
              <div class="list-group">
                  <?php 
                  $router = gpRouter::getInstance(); 
                  $registry = gpRegistry::getInstance();
                  ?>
                <?php while (list($key, $value) = $item->for('navbar')): ?>
            
                  <?php  
                    if ($router->isActive($key, $router->getLink($registry->get('request')), array('page', 'view'))) {
                      echo "<a class=\"list-group-item list-group-item-action active\" href=\"".$router->getLink($key)."\">".$value."</a> "; 
                    } else {
                      echo "<a class=\"list-group-item list-group-item-action\" href=\"".$router->getLink($key)."\">".$value."</a> "; 
                    };
                  ?>
                <?php endwhile; ?>
              </div>
            </div>
            <div class="ejs-sidebar-background" data-target="#sidebar"></div>
          </nav>
        <?php endif; ?>
        <?php if ($item->has('content')) : ?>
          <main class="ecs-content">
            <?php echo $item->get('content'); ?>
          </main>
        <?php endif; ?>
      </div>
      <footer>…</footer>
    </div>  
  </body>
</html>