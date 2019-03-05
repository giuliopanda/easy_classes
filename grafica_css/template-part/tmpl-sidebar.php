<ul class="ecs-list-col ecs-border-none ecs-list-icon-big">
<?php
$query = $_SERVER['PHP_SELF'];
$path = pathinfo( $query );
?>
  <li>
    <a href="tmpl-02-project.php" class="<?php echo ($path['basename'] == "tmpl-02-project.php") ? 'ecs-active' : ''; ?>">
      <svg class="icon-search"><use xlink:href="#svgProjectBoxClose"></use></svg>
      <span>Elenco viste</span>
    </a>
  </li>
  <li>
    <a href="tmpl-05-set-dati.php" class="<?php echo ($path['basename'] == "tmpl-05-set-dati.php") ? 'ecs-active' : ''; ?>" >
      <svg class="icon-search"><use xlink:href="#svgDatabase"></use></svg>
      <span>Set di Dati</span>
    </a>
  </li>
</ul>