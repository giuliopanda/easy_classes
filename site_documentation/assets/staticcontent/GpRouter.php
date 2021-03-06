
<div class="gp-doc-container gp-content">
    <a href="<?php echo GpRouter::getInstance()->getLink("/index.php?page=api&id=GpRouter-test"); ?>" class="float-right">Vai al test</a>
    <h2>Gestione del rooter </h2>
    <h3>Istanziare la classe</h3>
    <code><pre>$rooter = GpRouter::getInstance();
$rooter = Gp::route();

$rooter = new GpRouter($dirRoot = "", $scheme = "", $site= "");</pre></code>
    <p>I parametri sono opzionali e vengono calcolati in automatico, ma se serve si possono sovrascrivere </p>
    <p> <b>Esempio:</b>
    <code><pre>$rooter = GpRouter::getInstance();

$rooter->setConfig(dirname(__FILE__), 'https', 'www.miosito.it');</pre></code>
    </p>

    <h3><b>getCurrentLink</b>: Ritorna la pagina corrente</h3>
    <code><pre>echo $rooter->getCurrentLink($getUri = true);</pre></code>
    <p><b>$getUri</b>Boolean|String  Se è boolean identifica se deve tornare l'url con o senza la query. Se è una stringa aggiunge al link corrente nuovi parametri<br>
    </p>
    <code><pre>echo $rooter->getCurrentLink('?id=2');</pre></code>
    <p>E' possibile rimuovere parametri passandoli vuoti:</p>
    <code><pre>echo $rooter->getCurrentLink('?id=&cmd=');</pre></code>
    <h3><b>getSite</b>: Ritorna la home del sito</h3>
    <code><pre>echo $rooter->getSite();</pre></code>
    

    <h3><b>getDir</b>: Ritorna la directory principale del sito con lo slash alla fine</h3>
    <code><pre>echo $rooter->getCurrentLink($getUri = true);</pre></code>
    <p>Ritorna la directory principale del sito con lo slash alla fine. 
    Di default ritorna la directory da cui è stata chiamata per la prima volta il setConfig (sempre se senza parametri).</p>


    <h3><b>parseUrl</b>: fa il parsing di un url</h3>
    <code><pre>$rooter->parseUrl($link = "", $useFunction = true); 
    </pre></code>
    <p><b>$link</b>: il link da passare<br>
    <b>$useFunction</b>: Boolean dice se usare la funzione personalizzata myrouterParse oppure no</p>
    <p> Fa il parsing di una url e ne ritorna un array con i vari valori. Può fare un parsing di un link assoluto o relativo. In quest'ultimo caso bisogna far partire l'url dallo slash esempio: /index.php?a=1&b=2<br>
    Si può aggiungere una funzione per parsing di link in htaccess attraverso la funzione setFnRewrite('myrouterBuild', 'myrouterParse')</p>
    <p> <b>Esempio:</b>
    <code><pre>var_dump ($rooter->parseUrl();

array
    'scheme' => string 'http' 
    'host' => string 'localhost' (il sito)
    'path' => array (il percorso relativo dal sito principale (Se il sito è su una sottocartella parte da lì))
    'query' => array (le interrogazioni con i punti interrogativi)
    'filename' => string 'index.php'

    </pre></code></p>

    <h3><b>getLink</b>: Crea un link</h3>
    <code><pre>$rooter->getLink($query = "", $useFunction = true); 
            </pre></code>
    <p><b>$query</b>:  I parametri della query da elaborare in formato testo o array<br>
    <b>$useFunction</b>: Boolean dice se usare la funzione personalizzata myrouterParse oppure no</p>
    <p> <b>Esempio:</b>
    <code><pre>$router->getLink('/index.php?page=mia_pagina&id=23');
        </pre></code>
    <p>La pagina corrente con i request ridefiniti</p>
    <code><pre>$router->getLink($registry->get('request'))
        </pre></code>
    </p>

    <h3><b>setFnRewrite</b>: Imposta le funzioni per ampliare la gestione degli url</h3>
    <code><pre>$router->setFnRewrite( $fnBuild, $fnParse);</pre></code>
    <p>Le funzioni impostate possono essere inserite in una classe passando $fnBuild e $fnParse come array il cui primo valore è l'istanza della classe. <br> Si può passare un valore nullo se non si vuole settare nessuna funzione.</p>
    <p> <b>Esempio:</b>
<code><pre>/* Questo esempio converte il parametro $_GET['page'] in /page? e ne riconverte il parseUrl inserendo nella
    query il primo frammento del percorso relativo. */
    
$router->setFnRewrite('myrouterBuild', 'myrouterParse');

function myrouterBuild($query, $routerClass) {
    $parse = $routerClass->parseUrl($query);
    if (array_key_exists('page', $parse['query'])) {
        $page = $parse['query']['page'];
        unset($parse['query']['page']);
        return "/".$page.$routerClass->implodeQuery($parse['query']);
    }
}

function myrouterParse($parseUrl, $routerClass) {
    if (count($parseUrl['path']) > 0) {
        $parseUrl['page'] = array_shitf($parseUrl['path']);
    }
    return $parseUrl;
}
        </pre></code>
    </p>

    <h3><b>isActive</b>: Verifica se un link è uguale al link della url</h3>
    <code><pre>isActive($link, $currentLink = "", $whichQueryCheck = false)</pre></code>
    <p> <b>$link</b>: è il link da comparare<br> 
        <b>$currentLink</b>:  Se vuoto prende il link della pagina, altrimnti fa il parsing del link passato<br> 
        <b>$whichQueryCheck</b>: è un array con le query da verificare
    </p>


    <h3><b>pathToQuery</b>: Converte un link scritto con il path (quindi con l'url rewrite attivo) in un array di query</h3>
    <code><pre>pathToQuery($parseUrl, [args...])</pre></code>
    <p> 
        <b>$parseUrl</b>: è l'array derivante dal parsing di un url $this->parseUrl($link); <br> 
        <b>[args]</b>:  Sono i nomi delle variabili a cui deve essere associato il parsing.<br> 
    </p>
    <p><b>Esempio:</b><br>
    <pre>$link = "sito.it/post/23";
$newParseUrl = $pathToQuery($this->parseUrl($link), "pagina", "id");
var_dump ($newParseUrl);
</pre>
    </p>
    <p>In generale questa funzione è pensata per essere usata dentro la funzione del parsing per l'url rewrite. </p>
    <pre>$router = GpRouter::getInstance();
$router->setFnRewrite('routerBuild', 'routerParse');
function routerParse($parseUrl, $routerClass) {
   return $routerClass->pathToQuery($parseUrl, "page", "view");
}
</pre>

    <h3><b>queryToPath</b>: Converte una query in un link</h3>
    <code><pre>pathToQuery($parseUrl)</pre></code>
    <p> 
        <b>$parseUrl</b>: è l'array derivante dal parsing di un url $this->parseUrl($link); <br> 
        <b>[args]</b>:  Sono i nomi delle variabili a cui deve essere associato il parsing.<br> 
    </p>
    <p>Questa funzione è pensata per essere usata dentro la funzione di build dell'url rewrite</p>
    <pre>
function routerBuild($query, $routerClass) {
    if (GpRegistry::getInstance()->get('config.htaccess', true)) {
        $parse = $routerClass->parseUrl($query, false);
        $query = $routerClass->queryToPath($parse, "page", "view");
    }
    return $query;
}
</pre>


</div>