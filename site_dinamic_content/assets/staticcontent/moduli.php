
<div class="gp-doc-container gp-content">
    <h2>Moduli</h2>
    <h3>Richiamare un modulo</h3>
    <code><pre> $load->module('nome_modulo', $type, $data)</pre></code>
    <p>Quando si carica un modulo prima si prova a caricarlo da site/modules poi da cms/modules</p>
    <p>In alternativa è possibile richiamare i moduli da link: site.com/ajax?module=[nome_modulo]</p>
    <p>Ad esempio questa pagina è caricabile dal link: <a href="<?php echo GpRouter::getInstance()->getSite();?>/ajax?module=staticcontent&id=moduli"><?php echo GpRouter::getInstance()->getSite();?>/ajax?module=staticcontent&id=moduli</a></p>
    <p>I moduli dovrebbero supportare almeno il parametro type = json o html.</p> 


</div>