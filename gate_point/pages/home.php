<h1>Benvenuti in gate point</h1>
<p>Per iniziare ad usare Gate Point crea una cartella nella quale creerai il tuo sito e chiamala [nome_sito]</p>
<p>Crea al suo interno le cartelle pages, assets, modules</p>
<p>In <b>pages</b> si inseriranno le logiche delle pagine caricate</p>
<p>In <b>assets</b> le risorse. Qui vanno anche i file <b>config.php</b>, <b>function.php</b> e <b>router.php</b>che vengono caricati durante l'inizializzazione del sito</p>
<p>In <b>modules</b> vengono inseriti i moduli. Questi sono i componenti del sito richiamabili internamente tramite php o esternamente tramite la pagina ajax.php.</p>
<br>
<p>Per configurare i percorsi delle cartelle correttamente vai sull'index.php e nelle prime righe cambia i percorsi delle cartelle in cui si trovano i vari contenuti</p>
<pre>
$config['cmsDir'] = "gate_point"; // il cms appena caricato (non deve essere cambiato)
$config['siteDir'] = "[nome_sito]";// il sito che si sta costruendo
$config['template'] = "easy"; // il template che si sta usando
</pre>
