<div class="gp-doc-container gp-content">
   
    <h2>ELENCO DEGLI EVENTI</h2>
    <p>Gli eventi vengono inseriti in function.php</p>

    <h3><b>systemOnAfterRender</b>: Dopo aver renderizzato il sito</h3>
    <code><pre>function fnSystemOnAfterRender($site) {
    $site .= "fine";
    return $site;
}
$listener->add('systemOnAfterRender', 'fnSystemOnAfterRender');
</pre></code>
 

    <h3><b>logOnFatalHandler</b>: Quando si genera un errore fatal error</h3>

    <code><pre>function fnLogOnFatalHandler($error) {
    var_dump ($error);
    exit();
}
$listener->add('logOnFatalHandler', 'fnLogOnFatalHandler');
</pre></code>

    <h3><b>[nome_modulo]_event</b>: Dopo che è stato eseguito un modulo</h3>
    <p>Viene passato il risultato, eventuali parametri in GpRegistry e l'action
    <code><pre>function afterRis($result, $cData, $action) {
    $result['/'] = 'Home page';
    return $result;
}
$listener->add('module_menu_event', 'afterRis');
</pre></code>


   <h3><b>routerParse</b>: fa il parsing di un link</h3>
    <p>Viene passata la pagina che si sta caricando e ritorna un'array con  filename: il file della pagina da caricare, il link e page e info che contiene al suo interno un array con altre informazioni</p>

    <h3><b>routerBuild</b>: Ricostruisce il link del rooter</h3>


</div>