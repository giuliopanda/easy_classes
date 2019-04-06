
<div class="gp-doc-container gp-content">
    <h2>Moduli</h2>
    <p> I moduli contengono tutte le funzioni del progetto</p>
    <h3>Richiamare un modulo</h3>
    <code><pre> $load->module([nome_modulo], [nome_funzione], [parametri_funzione])</pre></code>
    <p>Quando si carica un modulo prima si prova a caricarlo da site/modules poi da cms/modules</p>
    <p>In alternativa è possibile richiamare i moduli da link: site.com/ajax?id=[nome_modulo]&action=[function] & [i parametri della funzione]</p>
    <p>Ad esempio questa pagina è caricabile dal link: <a href="<?php echo GpRouter::getInstance()->getSite();?>/ajax?id=staticcontent&action=html&pageName=moduli"><?php echo GpRouter::getInstance()->getSite();?>/ajax?id=staticcontent&action=html&pageName=moduli</a></p>

    <h3>Creare un modulo</h3>
    <p>I moduli sono salvati dentro la cartella modules/[nome_modulo].<br> All'interno della cartella ci deve essere un file php che si chiama come il nome del modulo. <br>All'interno del file la classe il cui nome è module_[nome_modulo].</p>


</div>