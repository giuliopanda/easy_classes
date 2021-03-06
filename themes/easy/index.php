<html>
  <?php 
  $item = $cData;
  $router = GpRouter::getInstance();
  Gp::load()->require('theme', "head.php");
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
      <?php echo $item->get('header'); ?>
      <div class="ecs-body">
        <?php if ($item->has('navbar')) : ?>
          <nav id="sidebar" class="ecs-sidebar ejs-sidebar-status-mobile-hide">
            <div class="ejs-sidebar-content">
                <?php while (list($key, $value) = $item->for('navbar')): ?>
                  <?php echo $value; ?>
                <?php endwhile; ?>
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
    </div>  
       <div class="clearfix"></div>
      <footer >
              <?php echo $item->get('footer'); ?>
      </footer>
  </body>
</html>