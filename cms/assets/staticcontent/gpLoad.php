
<div class="gp-doc-container gp-content">
    <h2>Gestione del caricamento dei file</h2>
    <p> per ora GPLoad non fa parte di easy_classes... </p>
    <h3>Istanziare la classe</h3>
    <code><pre>$load = GPLoad::getInstance();</pre></code>
   

    <h3><b>setPath</b> Aggiunge un percorso e i possibili ovveride</h3>
    <code><pre>$load->setPath($varName, $path, $override = "", $defaultFilename = "")</pre></code>
    <p>
        <b>$varName</b> E' il nome da richiamare del percorso<br>
        <b>$path</b> il percorso relativo dalla directory principale senza lo slash finale
	    <b>$override</b> il percorso relativo dalla directory principale senza lo slash finale
	    <b>$defaultFilename</b>  Il nome del file di default se non dovesse trovarsi quello proposto  
    </p>
    <p><b>Esempio</b>:<br>
    MyPage cerca il file prima dentro la directory ''themes/easy/pages' e se non lo trova cerca dentro 'pages'
        <code><pre>$load->setPath('myPage', 'pages', 'themes/easy/pages');</pre></code>
    </p>
    
     <h3><b>get</b> Ritornano le configurazioni impostate di una url o del suo ovveride</h3>
    <code><pre>$load->get($varName, $override = false)</pre></code>

    <h3><b>getUri</b>: Ritorna il link relativo alla pagina settata</h3>
    <code><pre>$load->getUri($varName);</pre></code>
    <p><b>$varName</b>  Il nome settato in setPath
    </p>
    <p><b>Esempio:</b><br>
    Ritorna il percorso principale caricabile dal sito www.miosito.xx/pages
        <code><pre>$load->setPath('myPage', 'pages', 'themes/easy/pages');
echo $load->getUri('myPage');</pre></code>
    </p>
   
    <h3><b>getPath</b>: Ritorna la stringa con il percorso del file </h3>
   
    <code><pre>$load->getPath($varName, $fileName); 
    </pre></code>
     <p>Ritorna il percorso del file che trova. L'ordine di ricerca è:<br>
      prima l'override / nome del file passato <br>
      poi path / nome del file passato <br>
      override / nome del file di default <br>
      path / nome del file di default<br>
    </p>

    <h3><b>require </b>: Esegue il require di un file</h3>
    <code><pre>$load->require($varName, $fileName, $data = false, $requireOnce = false, $variable = false);
        </pre></code>
  <p>
     <b>$varName</b>String  il nome del gruppo di directory da richiamare <br>
	 <b>$fileName</b> String  Il nome del file da richiamare<br>
	 <b>$data</b> Mixed Il path di gpRegistry oppure un array o un oggetto<br>
	 <b>$requireOnce</b> Boolean  Se usare require_once o require<br>
	 <b>$variable </b> String Il nome della variabile in cui settare i dati. di default i dati sono settati in $this->cData<br>
     </p>
  
    <h3><b>module </b>: Esegue un modulo</h3>
 <code><pre>$load->module($moduleName, $returnType = "html", $data = false);
    </pre></code>
    <p> I file dei moduli sono dentro la cartella /modules/[nomemodulo]/[nomemodulo].php</p>
    <p> La cartella modules è modificabile impostando setPAth('_modules', [nuova_path])) </p>
    <p>Il file nomemodulo deve contenere una funzione che si chiama module_[nomemodulo]$item, $returnType);</p>
    <p>$item è un'itanza di gpRegistry con i dati inviati al modulo. Per la versione ajax viene inviato l'intero request</p>
    <p>Una volta generato il modulo questo viene passato ad un evento [nomemodulo]_event in cui viene passato il risultato del modulo, i dati passati al modulo e il returnType.</p>
</div>
