
<div class="gp-doc-container gp-content">
    <h2>Moduli</h2>
    <p> I moduli contengono tutte le funzioni del progetto. Questi possono essere richiamati in qualsiasi momento da php dall'esterno attraverso la pagina ajax.php</p>
    <h3>Richiamare un modulo</h3>
    <code><pre> $load->module([nome_modulo], [nome_funzione], [parametri_funzione])</pre></code>
    <p><b>nome_modulo</b>: il nome del modulo da richiamare. E' il nome della cartella del modulo<br>
    <b>nome_funzione</b>: Il nome della funzione della classe<br>
    <b>parametri_funzione</b>: un array con i parametri della funzione. Accetta anche un riferimento ad un array di GpRegister oppure un oggetto. Se vengono passati più parametri rispetto a quelli richiesti dalla funzione, questi vengono filtrati così da non dare errore. 
    </p>
    <p>Quando si carica un modulo prima si prova a caricarlo da site/modules poi da cms/modules</p>

<p>E' possibile caricare il riferimento della classe:</p>
<code><pre> $load->module([nome_modulo])</pre></code>
<p>Le classi dei moduli vengono caricati secondo un singleton, per cui se viene caricato due volte in realtà si fa sempre riferimento alla stessa classe</p>

    <p>In alternativa è possibile richiamare i moduli da link: site.com/ajax?id=[nome_modulo]&action=[function] & [i parametri della funzione]</p>
    <p>Ad esempio questa pagina è caricabile dal link: <a href="<?php echo GpRouter::getInstance()->getSite();?>/ajax?id=staticcontent&action=html&pageName=moduli"><?php echo GpRouter::getInstance()->getSite();?>/ajax?id=staticcontent&action=html&pageName=moduli</a></p>
   
    <p>Le variabili usate nell'url rewrite (id e page) sono usate </p>

    <h3>Creare un modulo</h3>
    <p>I moduli sono salvati dentro la cartella modules/[nome_modulo].<br> All'interno della cartella ci deve essere un file php che si chiama come il nome del modulo. <br>All'interno del file la classe il cui nome è module_[nome_modulo].</p>
    
    

</div>