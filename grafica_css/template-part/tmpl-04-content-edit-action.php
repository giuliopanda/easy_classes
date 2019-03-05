<?php 
$order = '<span> <select class="ecs-form-ctrl"> <option>1</option> <option value="2" selected="selected">2</option> <option value="3">3</option> <option value="4">4</option> </select> </span>';
$btnTrash = '<span class="ecs-btn-svg ecs-btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg> </span>';

?>
<div class="ecs-content ecs-ml1 ecs-mr1">
  <div class="ecs-d-flex  ecs-align-items-center">
    <h3>Progetto utenti > Anagrafica > FORM</h3>
  </div>
  <div class="ecs-clearfix">
    <ul class="ecs-list-col">
      <li>
        <span>
          <span href="#" class="ecs-flex-grow-1">
            <select class="ecs-form-ctrl">
            <option>ANAGRAFICA.ID</option>
            </select>
          </span>
          <span class="ecs-pull-right">
            <?php echo $btnTrash." ".$order ; ?>
          </span>
        </span>
      </li>
      <li>
        <span>
         
          <span href="#" class="ecs-flex-grow-1">
            <select class="ecs-form-ctrl">
              <option>ANAGRAFICA.ID</option>
              <option selected="selected">ANAGRAFICA.USERNAME</option>
              <option>ANAGRAFICA.DATA_REGISTRAZIONE</option>
            </select>
          </span>
          <span class="ecs-pull-right">
            <?php echo $btnTrash." ".$order ; ?>
          </span>
        </span>
      </li>
      <li>
        <span>
          <span class="ecs-flex-grow-1">
            <select class="ecs-form-ctrl">
              <option>ANAGRAFICA.ID</option>
              <option>ANAGRAFICA.USERNAME</option>
              <option selected="selected">ANAGRAFICA.DATA_REGISTRAZIONE</option>
            </select>
          </span>
          <span class="ecs-pull-right">
            <?php echo $btnTrash." ".$order ; ?>
          </span>
        </span>
      </li>
      <li>
        <span class="ecs-bg-2">
          <span class="ecs-flex-grow-1">
            <select class="ecs-form-ctrl">
              <option>ANAGRAFICA:ID</option>
              <option>ANAGRAFICA.USERNAME</option>
              <option>ANAGRAFICA.DATA_REGISTRAZIONE</option>
              <option selected="selected">RECAPITI.EMAIL</option>
            </select>
          </span>
          <span class="ecs-pull-right">
            <?php echo $btnTrash." ".$order ; ?>
          </span>
        </span>
        <ul class="ecs-list-col">
            <li>
              <span>
                <span> Mostra come: </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option >Testo</option>
                    <option>Email</option>
                    <option>Icona</option>
                    <option>Personalizza</option>
                  </select>
                </span>
              <span>
            </li>
            <li>
              <span>
                <span> Aggiungi filtri testo: </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option>Maiuscolo</option>
                    <option>Minuscolo</option>
                    <option>Prima lettera maiuscola</option>
                    <option selected="selected"> PERSONALIZZA</option>
                  </select>
                </span>
                <span class="ecs-flex-grow-1" >
                  <textarea class="ecs-form-ctrl ecs-w100">[RECAPITI.EMAIL] + ' ' + UPPER(RTRIM(ANAGRAFICA.USERNAME))</textarea>
                </span>
              <span>
            </li>
            <li>
              <span>
                <span> Mostra se: </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option>ANAGRAFICA:ID</option>
                    <option>ANAGRAFICA.USERNAME</option>
                    <option>ANAGRAFICA.DATA_REGISTRAZIONE</option>
                    <option selected="selected">RECAPITI.EMAIL</option>
                  </select>
                </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option>DIVERSO</option>
                    <option>MAGGIORE</option>
                  </select>
                </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option>VUOTO</option>
                    <option>ANAGRAFICA:ID</option>
                    <option>ANAGRAFICA.USERNAME</option>
                    <option>ANAGRAFICA.DATA_REGISTRAZIONE</option>
                    <option >RECAPITI.EMAIL</option>
                  </select>
                </span>
              <span>
            </li>
          </ul>

      </li>
     
    </ul>
    <div  class="ecs-btn ecs-btn-1 ecs-pull-right">AGGIUNGI NUOVO</div>
  </div>

</div>
