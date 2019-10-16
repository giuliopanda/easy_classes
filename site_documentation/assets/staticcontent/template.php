
<div class="gp-doc-container gp-content">
    <h2>Template</h2>
    <p> La struttura delle chiamate è più o meno la seguente:
    l'index chiama una pagina he di solito starà dentro "pages" attraverso GpLoad->require.
    La pagina viene cercata prima dentro al cms poi dentro alla cartella del sito.
     Require ha come caratteristica di passare una variabile registry "privata" <b>$cData</b> (se non specificato altrimenti) nella quale vengono passati dei dati all'interno del require...</p>
     <p>Dentro la pagina si caricano i moduli e si assegnano alle posizioni e infine si passa tutti i dati al template tramite un'istruzione del tipo:</p>
    <code><pre>$load->require('theme', 'index.php', $cData, false);</pre></code>
    <p>Il template non fa nulla di speciale, è un normale php con un oggetto $cData di tipo GpRegistry nel quale sono memorizzati i pezzi di html renderizzati dai moduli. </p>
    <h3>Scegliere il template</h3>
    <p>L'index del template è caricato da un percorso impostato dentro il path 'theme'.
    configurato nell'index.php principale in questo modo:</p>
    <code><pre>$load-&gt;append('theme', 'themes', Gp::data()->get('config.template'));</pre></code>
    <p>La cartella del template è quindi dentro config.template che viene settata dentro assets/config.php:</p>
    <code><pre>Gp::data()->set('config.template', "easy");</pre></code>
    <p>Basta cambiare "easy" con un'altro nome per cambiare la cartella da cui si carica il template. </P>
    <h3>Fare l'override del tema</h3>
    <p>Per definizione tutti i require sono gestiti con un principio di override. il percorso di default per l'override del template è settato dentro init.php ed è la cartella themes dentro la cartella del sito.</p>
     <code><pre>$load-&gt;setPath('themes', array( $config['siteDir'].'/themes', 'themes') ) ;</pre></code>
    <h3>Aggiungere Js e Css nell'head</h3>
    <p>E' possibile personalizzare l'head aggiungendo il codice che si vuole dentro sito/assets/head.php</p>
    <p>All'interno dei moduli o di altre parti di codice si può indicare i link per caricare js o css inserendoli nella variabile di registro head. Ad esempio: </p>

    <code><pre>Gp::data()-&gt;set('head.js.[]', Gp::load()-&gt;getUri('crudata', 'anastasia_form/gpform.js'));
Gp::data()-&gt;set('head.css.[]', Gp::load()-&gt;getUri('crudata', 'anastasia_form/gpform.css'));</pre></code>


    <p>Infine è possibile aggiungere javascript e css inline all'interno dell'head</p>
        <code><pre>Gp::data()-&gt;set('head.script.[]', 'alert("ciao")');
Gp::data()-&gt;set('head.style.[]','body {background:#F2F2F2;}');</pre></code>
oppure 
<code><pre>Gp::data()-&gt;set('head.script.[]', '&lt;script&gt;alert("ciao")&lt;/script&gt;');
Gp::data()-&gt;set('head.style.[]','&lt;style&gt;body {background:#F2F2F2;}')&lt;/style&gt;')</pre></code>