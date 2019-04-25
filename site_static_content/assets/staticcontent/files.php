<div class="gp-doc-container gp-content">
   
    <h1>La struttura delle cartelle</h1>

    <p>Il framework ha una struttura di cartelle preimpostata, ma modificabile dall'init.php. Solo i nomi delle due cartelle principali sono impostate nell'index.php. I file sono cmsDir e siteDir</p>


    <h3>cmsDir</h3>
    <p>E' la cartella in cui si trova i file del cms da cui si parte. Al suo interno troviamo:</p>
    
    <p><b>/init.php</b><br>
    le personalizzazioni da eseguire all'inizio del cms incluse la struttura delle cartelle interna del cms di seguito descritta:</p>

    <p><b>/pages</b><br>
    Le pagine sono i controller e sono caricati a scelta dal router</p>

    <p><b>/assets</b><br>
    Ci sono tutte le risorse del sito.</p> 
   
    <p class="ml-4"><b>/assets/config.php</b><br>
    Le configurazioni del sito</p>
    <p class="ml-4"><b>/assets/router.php</b><br>
    La personalizzazione di come viene gestito il router</p>
    <p class="ml-4"><b>/assets/function.php</b><br>
    Dopo aver inizializzato tutto il cms viene caricato function.php con le personalizzazioni. La differenza con Init è che di tutte le risorse che si trovano in assets si può fare l'override mentre dell'init no. <br>In function è possibile inserire gli eventi personalizzati:</p>
     <pre>$listener = GpListener::getInstance();
function afterRis($result, $cData, $type) {
    $result['/'] = 'Home page';
    return $result;
}
$listener->add('module_menu_event', 'afterRis');
</pre>
    <br><b>/classes</b>
    <p>Si trovano tutte le classi che gestiscono il cms</p>


    <h3>themes</h3>
    <p>Ci sono le cartelle con i template. Il nome del tema è configurato nel siteDir/assets/config.php:</p>
    <code><pre>Gp::data()->set('config.template', "easy");</pre></code>

     <p class="ml-4"><b>/easy</b><br>
   E' il template di base.</p>




    <h3>siteDir</h3>
    <p>Sitedir è il percorso in cui vengono inseriti tutti gli override del sito. In particolare fa l'override di assets, modules, pages e themes</p>
