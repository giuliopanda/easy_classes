<div class="gp-doc-container gp-content">
   
    <h1>CMS</h1>
    <p>Il cms con le classi e i moduli principali</p>

    <h1>SITE</h1>
    <p>I dati del sito personalizzati</p>

    <h2>/pages</h2>
    <p>Le pagine sono i controller e sono caricati a scelta dal router</p>

    <h2>/assets</h2>
    <p>Ci sono tutte le risorse del sito.</p> 
    <p>Nella directory principale si trovano le classi configurabili</p>
 
    <h3>/assets/router.php</h3>
    <p>La personalizzazione di come viene gestito il router</p>
    
    <h2>/classes</h2>
    <p>Si trovano tutte le classi che gestiscono il cms</p>

    <h2>/themes</h2>
    <p>Ci sono le cartelle con i template</p>


    <h3>/themes/[miotema]/function.php</h3>
    <p>Viene caricato all'inizio e serve per aggiungere codice all'inizializzazione della pagina, come ad esempio dei listener</p>
   
    <pre>$listener = GpListener::getInstance();
function afterRis($result, $cData, $type) {
    $result['/'] = 'Home page';
    return $result;
}
$listener->add('module_menu_event', 'afterRis');
</pre>