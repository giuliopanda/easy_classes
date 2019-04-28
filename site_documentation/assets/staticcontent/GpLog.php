<div class="gp-doc-container gp-content">
  <a href="<?php echo GpRouter::getInstance()->getLink("/index.php?page=api&id=GpLog-test"); ?>" class="float-right">Vai al test</a>
    <h2>Gestione dei log</h2>
    <p>Gestione dei log</p>
    <h3>Istanziare la classe</h3>
    <code><pre>$log = GpLog::getInstance();
Gp::log();</pre></code>
    <h3><b>set</b>: Aggiunge un log</h3>
    <code><pre>set($group, $msgType, $msg = "", $params = "", $path = true)</pre></code>
    <p>Aggiunge un log ad un gruppo di messaggi.</p>
    <p> <b>$group</b> String Il nome del file in cui scrivere e il gruppo di dati da scrivere
      <b>$msgType</b> String Il tipo di messaggio è un testo libero tutto maiuscolo
      <b>$msg</b> String [Opzionale] Il messaggio da scrivere
      <b>$params</b> Array [Opzionale] Eventuali parametri da memorizzare
      <b>$path</b> Boolean [Opzionale] se inserire dopo il messaggio i file che hanno portato a chiamare quel messggio
    </p>
    <h3><b>get</b>: Ritorna il gruppo di messaggi salvato</h3>
    <code><pre>get($group) </pre></code>
    <h3><b>write</b>: Scrive su file un log</h3>
   <code><pre>write($group = "system", $msgType = "", $msg = "", $params = "", $path = true)</pre></code>
    
    <p>Scrive un log nella directory impostata da $load->setPath("logs", array([...])); <br />
    La struttura con cui viene scritto un log è la seguente:</p>
    <code><pre>YYYYMMGGHHMMSS UNIQID IP MSG_TYPE MSG PATH JSON_PARAMS</pre></code>
    <p>La funzione write scrive su file tutti i log presenti in un determinato gruppo. Se è presente un $msgType allora prima di scrivere i log su file aggiunge il nuovo log alla coda. Una volta scritti i log di quel gruppo vengono cancellati</p>
    <p>I file vengono ruotati quando un file supera la dimensione impostata su  
    Gp::data()->get('config.log.size', '1024').
    Vengono tenuti un numero massimo di file secondo l'impostazione Gp::data()->get('config.log.max_files', '5')</p>

    <h3><b>load</b>: Legge un log</h3>
    <code><pre>load($fileName, $filterTimeStart = 0, $filterTimeEnd = 99999999999999, $limitStart = 0, $limit = 10000)</pre></code>
    <p>Il fileName deve essere senza l'estensione. L'unico filtro accettato è il range di date. cosa che permette un'enorme velocità di esecuzione anche per quantità di dati molto elevata (100.000 righe di log al secondo con il mio portatile). Dei dati trovati divide in paginazione perché altrimenti ha problemi di memoria con troppi dati. </p>

    <h3><b>getDataLog</b>: Stampa il data log in un elemento html</h3>
    <code><pre>echo Gp::log()->getDataLog();</pre></code>
    <p>Tutti i moduli dovrebbero avere nel primo elemento html che li contiene questa dicitura così da poterla collegare ai log del gruppo system. In pratica in questo modo è possibile capire quale gruppo di log genera una determinata parte della pagina html.</p>
    <b>Esempio:</b><br>
     <code><pre>&lt;table class=&quot;table&quot; &lt;?php echo Gp::log()-&gt;getDataLog(); ?&gt;&gt;</pre></code>
    <p>E' possibile configurare se stampare il data-log="$pointerHTML" oppure no settando: 
    <code><pre>Gp::data()->set('config.log.printDataLog', true);</pre></code>

     <h3><b>count</b>: Ritorna il numero di righe di un log filtrate per date</h3>
    <code><pre>count($fileName, $filterTimeStart = 0, $filterTimeEnd = 99999999999999)</pre></code>


    <h3>I log di sistema</h3>
  <p>Esistono due log di sistema che sono system e error. System mostra oltre agli errori tutti i moduli e i plugin (action o listener) caricati nel sito. System è utile per capire cosa sta succedendo durante il caricamento di una pagina.</p>
  <p> Error mostra invece gli errori e li salva su file. Si può decidere se si vuole che gli errori vengano salvati su file impostando Gp::data()->set('config.log.write_error', true). In questo caso vengono scritti i fatal error e gli errori nelle configurazioni del sito.</p>
</div>