
<div class="gp-doc-container gp-content">
   
    <a href="<?php echo GpRouter::getInstance()->getLink("/index.php?page=api&id=GpRegistry-test"); ?>" class="float-right">Vai al test</a>
    <h2 >Gestione register</h2>

    <h3>Istanziare la classe</h3>
    <code><pre>$data = GpRegistry::getInstance();
$data = Gp::data();
    </pre></code>
    
    <p>GpRegistry serve per memorizzare dati in una classe singleton raggiungibile ovunque. I dati vengono salvati come
        path con il punto.
    <code><pre>$nome_variabile = array('ramo1'=>"val", "ramo2"=>"val2");</pre></code>
    verrà richiamato come:
    <code><pre>$data->get('nome_variabile.ramo1');</pre></code>
    </p><br>
   
    <h3><b>set</b>: Imposta i valori </h3>
    <code><pre>$data->set($path, $data);</pre></code>

    <p><b>$path</b>: I nomi della variabile da salvare. Vengono segnati come path.<br>
    <b>$data</b>: i dati da salvare. Possono essere variabili singole, array o oggetti. Una volta inseriti nel registry verranno comunque richiamati come path (con il punto). Se si aggiunge un oggetto/classe non viene convertito in un array. Se lo si vuole convertire bisogna fare prima il cast in array: <br>
    <code><pre>$data->set( 'dataArray', (array) $obj); </pre></code>
    </p>
    <p><b>Esempio</b>:
    </p> 
    <code><pre>$data->set( 'site_name', "Nome del sito");
// Array
$data->set( 'access', array("ip"=>"localhost","user"=>"admin", "psw"=>"nimda"));

$data->set('country.[]', 'italia');
$data->set('country.Francia', 'Francia');
$data->set('country.[]', 'Spagna');
var_dump ($data->get('country'));

// Object
$obj = new StdClass();
$obj->title = "MY TITLE";
$obj->author = "admin";
$data->set( 'article', $obj);

// per cancellare una variabile
$data->set('access', null);
    </pre></code>


    <h3><b>get</b>: Richiama i dati</h3>
    <code><pre>echo $data->get($path);</pre></code>
    <p>Ritorna i dati o false se il dato non è settato</p>

    <code><pre>$data->get('country.[]', 'italia');
$data->get('access.ip');
$data->get('article.title').
    </pre></code>
    <p> E' possibile richiamare i dati $_GET e $_POST attraverso request, oppure i dati salvati in sessione attraverso session</p>
   
    <h3><b>has</b>: verifica se una variabile è impostata</h3>
    <code><pre>$data->has($path);</pre></code>
    <p>Torna false se la variabile non è impostata oppure è null o ""</p>

    <h3><b>for</b>: Permette di ciclare un array</h3>
    <code><pre>list($key, $currentData) = $data->for($path);</pre></code>
    <p>Torna false quando ha finito di ciclare. </p>
    <code><pre>$data->set( 'access', array("ip"=>"localhost","user"=>"admin", "psw"=>"admin"));
while ( list($key, $currentData) = $data->for('access') ) {
    echo ("&lt;p&gt;".$key." > ".$currentData."&lt;/p&gt;");
}</pre></code>

    <h3><b>break</b>: Interrompe un ciclo</h3>
    <code><pre> $data->break($path = false);</pre></code>
    <p>Interrompe un ciclo. Si può interrompere il ciclo in cui è inserito il break non passando il path, oppure un ciclo specifico passando il path che si sta ciclando </p>
    <p><b>Esempio:</b>
    <code><pre>
    $data->set( 'access', array("ip"=>"localhost","user"=>"admin", "psw"=>"admin"));
    while ( list($key, $currentData) = $data->for('access') ) {
        var_dump ($currentData);
        $data->break();
    }</pre></code>
    </p>


    <h3><b>build</b>: Passa i paramentri ad una funzione per elaborarli. Ritorna una stringa.</h3>
    <code><pre> $data->build($data, $fn);</pre></code>
    <p> <b>$data</b>: Se è una stringa è il path del registry, altrimenti può essere un array o un oggetto di dati<br>
    <b>$fn</b>  String|Array E' la funzione da associare. Se la funzione associata è in una classe si passa un'array il cui primo valore è l'istanza della classe.</p>
    <p><b>Esempio:</b>
        <code><pre>
    $obj = new StdClass();
    $obj->title = "MY TITLE";
    $obj->author = "admin";
    $obj->text = "lorem ipsum dolor sit amet";
    $data->set( 'article', (array)$obj);
    echo $data->build('article', 'drawArticle');

    function drawArticle($data) {
    echo "&lt;div style=\"background:#F2F2F2; border:1px solid #CCC; padding:10px; margin:10px;\"&gt;";
        echo "&lt;h2&gt;".$data-&gt;get('title')."&lt;/h2&gt;";
        echo "&lt;p&gt;".$data-&gt;get('text')."&lt;/p&gt;";
        echo "&lt;/div&gt;";
    }  
    </pre></code>
    </p>

    <h3><b>requestQueue</b>: Carica e salva i dati in sessione</h3>
    <code><pre>requestQueue($path, $default = "")</pre></code>

    <p> 
        <b>path</b>>: string Il percorso in cui sono stati memorizzati i dati.
        <b>default</b>: mixed Se non ci sono dati nel path ritorna il valore impostato
        Carica i dati dal $this->getRequest(); e se esistono li salva in sessione. Se non esistono cerca i dati nella sessione.Se i dati nella sessione non esistono salva in sessione i dati del default.       
    </p>

    <h3><b>clearQueue</b>: Cancella i dati in sessione</h3>
    <code><pre>$data->clearQueue();</pre></code>
    <p>La sessione di GpRegistry viene salvata a partire da _GpRegistry</p>
</div>
