<?php 
$order = '<span> <select class="ecs-form-ctrl"> <option>1</option> <option value="2" selected="selected">2</option> <option value="3">3</option> <option value="4">4</option> </select> </span>';
$btnTrash = '<span class="ecs-btn-svg ecs-btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg> </span>';

?>
<div class="ecs-content ecs-ml1 ecs-mr1">
  <div class="ecs-d-flex  ecs-align-items-center">
    <h3>Progetto utenti > Anagrafica > ricerca</h3>
  </div>
  <h5>FILTRI DI RICERCA</h5>
  <div class="ecs-clearfix">
    <ul class="ecs-list-col">
      <li>
        <span class="ecs-bg-2">
          <span>
           <select class="ecs-form-ctrl">
            <option>DATA CREAZIONE</option>
            </select>
          </span>
          <span> TIPO </span>
          <span>
           <select class="ecs-form-ctrl">
            <option>DATA</option>
            </select>
          </span>
          
          <span class="ecs-pull-right">
            <?php echo $btnTrash." ".$order ; ?>
          </span>
        </span>
        <ul class="ecs-list-col">
            <li>
              <span>
                <span>LABEL</span>
                <span class="ecs-flex-grow-1"><input class="ecs-form-ctrl ecs-w100"></span>
              </span>
            </li>
            <li>
              <span>
                <span>DESCRIPTION</span>
                <span class="ecs-flex-grow-1">
                <textarea class="ecs-form-ctrl ecs-w100"></textarea>
                </span>
              </span>
            </li>
            <li>
              <span>
                <span>DOVE 'DATA CREAZIONE' È</span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option>MAGGIORE</option>
                    <option>MINORE</option>
                    <option>UGUALE</option>
                    <option>COMPRESA</option>
                  </select>
                </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option >ANAGRAFICA.DATA_CREAZIONE</option>
                  </select>
                </span>
                <span class="ecs-pull-right">
                  <span class="ecs-btn-svg ecs-btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"></path></svg></span>
                  <span class="ecs-btn">+</span>
                </span>
              <span>
            </li>
          </ul>
      </li>
    </ul>
    <div  class="ecs-btn ecs-btn-1 ecs-pull-right">AGGIUNGI NUOVO</div>
  </div>

</div>
