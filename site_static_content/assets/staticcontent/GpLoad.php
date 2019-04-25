
<div class="gp-doc-container gp-content">
    <h2>Gestione del caricamento dei file</h2>
    <h3>Istanziare la classe</h3>
    <code><pre>$load = GpLoad::getInstance();
Gp::load();</pre></code>
    
    <h3><b>setPath</b> Aggiunge un percorso e i possibili ovveride</h3>
    <code><pre>$load->setPath($varName, $path )</pre></code>
    <p>
        <b>$varName</b> E' il nome da richiamare del percorso<br>
        <b>$path</b> il percorso relativo dalla directory principale senza lo slash finale, può essere una stringa o un array di percorsi. In quel caso torna sempre il primo percorso valido.<br>	  
    </p>
    <p><b>Esempio</b>:<br>
    Nell'esempio MyPage imposta un percorso in cui verranno cercati i file prima dentro la directory 'override/pages' e se non si trovano dentro
        <code><pre>$load->setPath('myPage', array('override/pages', 'pages')) ;</pre></code>
    </p>
    
     <h3><b>get</b>Ritorna l'array dei percorsi relativi della prima directory </h3>
    <code><pre>$get->get($varName)</pre></code>

    <h3><b>issetPath</b> verifica se un percorso è stato impostato oppure no</h3>
    <code><pre>$load->issetPath($varName)</pre></code>

    <h3><b>getUri</b>: Ritorna il link relativo alla pagina settata</h3>
    <code><pre>$load->getUri($varName, $filename = "");</pre></code>
    <p>
        <b>$varName</b>  Il nome settato in setPath<br>
        <b>$filename</b>  String|Array Il nome del file. Se è un array ritorna il primo file trovato. Cerca sempre prima il primo file su tutte le directory, poi il secondo file su tutte le directory e così via.
    </p>
    <p><b>Esempio:</b><br>
    Ritorna il link del file cercato o il link della directory caricabile dal sito www.miosito.xx/pages
        <code><pre>$load->setPath('myPage', array('pages', 'themes/easy/pages'));
echo $load->getUri('myPage');</pre></code>
    </p>
   
    <h3><b>append</b>: Aggiunge un segmento ad un path esistente</h3>
    <code><pre>$load->append($newName, $varName, $appendPath);</pre></code>

    <h3><b>getPath</b>: Ritorna la stringa con il percorso del file </h3>
   
    <code><pre>$load->getPath($varName="", $fileName= ""); 
    </pre></code>
    <p>Ritorna il percorso completo del primo file che trova nell'elenco delle directory assegnate<br>
    <p>
        <b>$varName</b>  Il nome settato in setPath<br>
        <b>$filename</b>  String|Array Il nome del file. Se è un array ritorna il primo file trovato. Cerca sempre prima il primo file su tutte le directory, poi il secondo file su tutte le directory e così via.
    </p>
    </p>

    <h3><b>require </b>: Esegue il require di un file</h3>
    <code><pre>$load->require($varName, $fileName = "", $data = false, $requireOnce = false, $variable = false);
        </pre></code>
        <p>Fa il require di un file. Se viene passato solo un parametro questo deve essere il path assoluto</p>
  <p>
     <b>$varName</b>String  il nome del gruppo di directory da richiamare oppure se fileName = "" allora il percorso completo del file da richiamare<br>
	 <b>$fileName</b> String|Array  Opzionale Il nome del file o dei files secondo l'ordine di getPath<br>
	 <b>$data</b>  Mixed Opzionale Il path di GpRegistry oppure un array o un oggetto<br>
	 <b>$requireOnce</b>  Boolean Opzionale Se usare require_once o require<br>
	 <b>$variable </b> String Il nome della variabile in cui settare i dati. di default i dati sono settati in $this->cData<br>
     </p>
  
    <h3><b>module </b>: Esegue un modulo</h3>
 <code><pre>$load->module($moduleName, $returnType = "html", $data = false);
    </pre></code>
    <p> I file dei moduli sono dentro la cartella /modules/[nomemodulo]/[nomemodulo].php</p>
    <p> La cartella modules è modificabile impostando setPAth('_modules', [nuova_path])) </p>
    <p>Il file nomemodulo deve contenere una funzione che si chiama module_[nomemodulo]$item, $returnType);</p>
    <p>$item è un'itanza di GpRegistry con i dati inviati al modulo. Per la versione ajax viene inviato l'intero request</p>
    <p>Una volta generato il modulo questo viene passato ad un evento [nomemodulo]_event in cui viene passato il risultato del modulo, i dati passati al modulo e il returnType.</p>
</div>
