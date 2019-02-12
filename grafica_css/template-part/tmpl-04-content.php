<?php 
$order = '<span> <select class="ecs-form-ctrl"> <option value="1">1</option> <option value="2" selected="selected">2</option> <option value="3">3</option> <option value="4">4</option> </select> </span>';
$btnTrash = '<span class="ecs-btn-svg ecs-btn-danger"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg> </span>';

?>
<div class="ecs-content ecs-ml1 ecs-mr1">
  <div class="ecs-d-flex  ecs-align-items-center">
    <h3>Progetto utenti > Anagrafica > elenco</h3>
  </div>
  <h5>SELECT</h5>
  <div class="ecs-clearfix">
    <ul class="ecs-list-col">
      <li>
        <span>
          <span href="#" class="ecs-flex-grow-1">
            <select class="ecs-form-ctrl">
            <option>ANAGRAFICA:ID</option>
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
              <option>ANAGRAFICA:ID</option>
              <option selected="selected">ANAGRAFICA:username</option>
              <option>ANAGRAFICA:data_registrazione</option>
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
              <option>ANAGRAFICA:ID</option>
              <option>ANAGRAFICA:username</option>
              <option selected="selected">ANAGRAFICA:data_registrazione</option>
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
              <option>ANAGRAFICA:ID</option>
              <option>ANAGRAFICA:username</option>
              <option>ANAGRAFICA:data_registrazione</option>
              <option selected="selected">RECAPITI:email</option>
            </select>
          </span>
          <span class="ecs-pull-right">
            <?php echo $btnTrash." ".$order ; ?>
        </span>
      </li>
      <li>
        <span>
          <input class="ecs-form-ctrl ecs-flex-grow-1" value="UPPER(RTRIM(LastName)) + ', ' + FirstName AS Name">
          <span class="ecs-pull-right">
            <?php echo $btnTrash." ".$order ; ?>
        </span>
      </li>
    </ul>
    <div  class="ecs-btn ecs-btn-1 ecs-pull-right">AGGIUNGI NUOVO</div>
  </div>

  <div class="ecs-d-flex ecs-flex-row ecs-justify-content-between ecs-align-items-center ecs-mt3">
    <h5>FROM</h5>
    <div>
      <a href="#">nascondi tutto</a>
    </div>
  </div>
  <div class="ecs-clearfix">
    <ul class="ecs-list-col">
      <li>
        <span>
          <span>
          <select class="ecs-form-ctrl">
            <option value="1"></option>
            <option value="2" >LEFT JOIN</option>
            <option value="3">RIGHT JOIN</option>
            <option value="4">INNER JOIN</option>
          </select>
          </span>
          <span href="#" class="ecs-btn-svg ecs-btn-light ecs-flex-grow-1">
            ANAGRAFICA
          </span>
          <span class="ecs-pull-right">
            <?php echo $btnTrash." ".$order ; ?>
        </span>
      </li>
      <li>
        <span class="ecs-bg-2">
          <span>
          <select class="ecs-form-ctrl">
            <option value="1">Principale</option>
            <option value="2" selected="selected">LEFT JOIN</option>
            <option value="3">RIGHT JOIN</option>
            <option value="4">INNER JOIN</option>
          </select>
          </span>
          <span>
            RECAPITI
          </span>
          <span class="ecs-pull-right">
            <span class="ecs-btn">ANNULLA</span>
            <span class="ecs-btn">SALVA</span>
            <?php echo $btnTrash." ".$order ; ?>
            </span>
        </span>
          <ul class="ecs-list-col">
            <li>
              <span>
                <span> ON </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option value="1">recapiti.user_id</option>
                    <option value="1">SCRIVI UNA QUERY</option>
                  </select>
                </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option value="1">=</option>
                  </select>
                </span>
                <span>
                  <select class="ecs-form-ctrl">
                    <option value="1">anagrafica.id</option>
                  </select>
                </span>
                <span class="ecs-pull-right">
                  <?php echo $btnTrash ; ?>
                  <span class="ecs-btn">+</span>
                 
                </span>
              <span>
            </li>
          </ul>
      </li>
      <li>
        <span>
          <span>
          <select class="ecs-form-ctrl">
            <option value="2" >LEFT JOIN</option>
            <option value="3">RIGHT JOIN</option>
            <option value="4">INNER JOIN</option>
          </select>
          </span>
          <input class="ecs-form-ctrl ecs-flex-grow-1"
            value="SOTTOSCRIZIONE ON sottoscrizione.id = SELECT s.id FROM sottoscrizione as s ORDER BY s.id DESC LIMIT 1;" >
          <span class="ecs-pull-right">
           <?php echo $btnTrash." ".$order ; ?>
        </span>
      </li>
    </ul>
    <div type="submit" class="ecs-btn ecs-btn-1 ecs-pull-right">AGGIUNGI NUOVO</div>
  </div>

  <div class="ecs-d-flex ecs-flex-row ecs-justify-content-between ecs-align-items-center ecs-mt3">
    <h5>WHERE</h5>
    <div>
      <a href="#">nascondi tutto</a>
    </div>
  </div>
 <div class="ecs-clearfix">
    <ul class="ecs-list-col">
      <li>
        <span>
          <span>
          <select class="ecs-form-ctrl">
            <option value="1"></option>
            <option value="2" >OR</option>
          </select>
          </span>
          <span>
            <select class="ecs-form-ctrl">
              <option value="1">recapiti.user_id</option>
            </select>
          </span>
          <span>
            <select class="ecs-form-ctrl">
              <option value="1">=</option>
            </select>
          </span>
          <span>
            <select class="ecs-form-ctrl">
              <option value="1">anagrafica.id</option>
            </select>
          </span>
          <span class="ecs-pull-right">
            <span class="ecs-btn-svg ecs-btn-danger">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg>          </span>
            </span>
        </span>
      </li>
      <li>
        <span>
          <span>
          <select class="ecs-form-ctrl">
            <option value="1">AND</option>
            <option value="2" >OR</option>
          </select>
          </span>
          <span href="#" class="ecs-btn-svg ecs-btn-light ecs-flex-grow-1">
            ()
          </span>
          <span class="ecs-pull-right">
            <span class="ecs-btn-svg ecs-btn-danger">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg>          </span>
            </span>
        </span>

        <ul class="ecs-list-col">
          <li>
            <span>
              <span>  <select class="ecs-form-ctrl">
                <option value="1"></option>
                <option value="2" >OR</option>
              </select> </span>
              <span>
                <select class="ecs-form-ctrl">
                  <option value="1">recapiti.user_id</option>
                </select>
              </span>
              <span>
                <select class="ecs-form-ctrl">
                  <option value="1">=</option>
                </select>
              </span>
              <span>
                <select class="ecs-form-ctrl">
                  <option value="1">anagrafica.id</option>
                </select>
              </span>
              
              <span >
                <span class="ecs-btn-svg ecs-btn-danger">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg>
                </span>
              </span>
            
          </li>
          <li>
            <span>
              <span>  <select class="ecs-form-ctrl">
                <option value="1">OR</option>
                <option value="2" >AND</option>
              </select> </span>
              <span>
                <select class="ecs-form-ctrl">
                  <option value="1">recapiti.user_id</option>
                </select>
              </span>
              <span>
                <select class="ecs-form-ctrl">
                  <option value="1">=</option>
                </select>
              </span>
              <span>
                <select class="ecs-form-ctrl">
                  <option value="1">anagrafica.id</option>
                </select>
              </span>
              
              <span >
                <span class="ecs-btn-svg ecs-btn-danger">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg>          
                </span>
              </span>
              <span class="ecs-pull-right">
                <span class="ecs-btn">+</span>
              </span>
            </span>
          </li>
        </ul>
      </li>
      <li>
        <span>
          <span>  <select class="ecs-form-ctrl">
            <option value="1">AND</option>
            <option value="2" >OR</option>
          </select> </span>
          <span>
            <select class="ecs-form-ctrl">
              <option value="1">recapiti.status</option>
            </select>
          </span>
          <span>
            <select class="ecs-form-ctrl">
              <option value="1">=</option>
            </select>
          </span>
          <span>
            <select class="ecs-form-ctrl">
              <option value="1">URL.search_status</option>
            </select>
          </span>
          
          <span class="ecs-pull-right">
            <span class="ecs-btn-svg ecs-btn-danger">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg> 
            </span>
          </span>
        </span>
     
      </li>
        
    </ul>
    <div type="submit" class="ecs-btn ecs-btn-1 ecs-pull-right">AGGIUNGI NUOVO</div>
  </div>


  <div class="ecs-d-flex ecs-flex-row ecs-justify-content-between ecs-align-items-center ecs-mt3">
    <h5>GROUP BY</h5>
    <div>
      <a href="#">mostra tutto</a>
    </div>
  </div>

  <div class="ecs-d-flex ecs-flex-row ecs-justify-content-between ecs-align-items-center ecs-mt3">
    <h5>HAVING</h5>
    <div>
      <a href="#">mostra tutto</a>
    </div>
  </div>

  <div class="ecs-d-flex ecs-flex-row ecs-justify-content-between ecs-align-items-center ecs-mt3">
    <h5>WINDOW</h5>
    <div>
      <a href="#">mostra tutto</a>
    </div>
  </div>

  <div class="ecs-d-flex ecs-flex-row ecs-justify-content-between ecs-align-items-center ecs-mt3">
    <h5>ORDER BY</h5>
    <div>
      <a href="#">mostra tutto</a>
    </div>
  </div>

  <div class="ecs-d-flex ecs-flex-row ecs-justify-content-between ecs-align-items-center ecs-mt3">
    <h5>LIMIT</h5>
    <div>
      <a href="#">mostra tutto</a>
    </div>
  </div>

</div>