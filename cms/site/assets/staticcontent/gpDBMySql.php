
<div class="gp-doc-container gp-content">
    <a href="<?php echo gpRouter::getInstance()->getLink("/index.php?page=api&view=gpDBMySql-test"); ?>" class="float-right">Vai al test</a>
    <h2>Interfacciarsi con il Database MySql</h2>

    <h3>Istanziare la classe</h3>
    <code><pre>$db = new gpDBMySql($ip, $name, $psw, $dbName);</pre></code>
    <p> E' possibile connettersi ad un database mysql attraverso la classe gpDBMySql</p>
    <p> <b>Esempio:</b>
    <code><pre>$db->setPrefix('prefisso')
    if (!$db->error) {
    ...
    }</pre></code>
    </p>


    <h3>setPrefix: Impostare un prefisso</h3>
    <code><pre>$db->setPrefix('prefisso')</pre></code>
    <p>Se le tabelle hanno un prefisso puoi settarlo</p>

    <h3>query: Eseguire una query generica sul database</h3>
        <code><pre>$query = $db->query($sql);</pre></code>
    <p>Esegue una query generica. Ogni volta che si esegue una query se c'è stato un qualche problema viene impostato db->error a true, altrimenti risulterà false. <br> Se le tabelle hanno un prefisso si può scrivere <b>#_</b> al posto del prefisso.</p>
    <p> <b>Esempio:</b>
    <code><pre>$query = $db->query("SELECT * FROM #__tabella");
if (!$db->error) {
    ...
}</pre></code>
</p>

    <h3>getResults: Esegue una query Select</h3>
    <code><pre>$db->getResults($sql);
    </pre></code>
    <p> <b>Esempio:</b>
    <code><pre>
$rows = $db->getResults("SELECT * FROM #__tabella");
if (!$db->error) {
    ...
}</pre></code></p>

    <h3>getRow: Esegue una query Select e ritorna una singola riga</h3>
    <code><pre>
    $db->getRow($sql, $offset = 0);
    </pre></code>
    <p><b>$sql</b> il testo della query <br>
    <b>$offset</b> La riga da estrarre </p>
    <p><b>Esempio:</b> </p>
    <code><pre>
$row = $db->getRow("SELECT * FROM #__tabella");
if (!$db->error) {
    ...
}</pre></code>

    <h3>getVar: Esegue una query Select e ritorna un singolo valore</h3>
    <code><pre>
        $db->getVar($sql, $offset = 0);
        </pre></code>
    <p><b>$sql</b> il testo della query <br>
        <b>$offset</b> La riga da estrarre </p>
    <p><b>Esempio:</b> </p>
    <code><pre>
$id = $db->getVar("SELECT id FROM #__tabella LIMIT 4", 3);
if (!$db->error) {
    ...
}</pre></code>

    <h3>getTables: L'elenco delle tabelle del database</h3>
    <p> Torna l'elenco delle tabelle del database in un array</p>
<code><pre>
$db->getTables($cache = true);
</pre></code>

    <h3>describes: Ritornano i campi di una tabella</h3>
<code><pre>
$db->describes($tableName, $cache = true);
</pre></code>
<p> Torna un array con:<br>
    <b>fields</b>: che contiene l'elenco dei campi della tabella come chiave dell'array, e il tipo di campo come value dell'array<br>
    <b>key</b>: La chiave primaria della tabella. La tabella deve avere una chiave primaria formata da un solo campo.<br>
    </p>

    <h3>insert: Inserisce i valori nel db</h3>
    <code><pre>
$db->insert($table, $data);
</pre></code>
<p>Inserisce una query e ritorna l'id inserito. Verifica prima i campi se esistono per cui se si passa un campo che non esiste nella tabella questo viene automaticamente scartato.</p>
<p>
    <b>$table</b>: Il nome della tabella<br>
    <b>$data</b>: Un array contenente i valori da inserire<br>
    
</p>
<p> <b>Esempio:</b>
<code><pre>
$data = array('username' =>'Pippo', 'email'=>'pippo@gmail.com ', 'password'=>'gf');
$id = $db->insert('gp_users', $data);
if ($db->error) {
    // C'è stato un problema.
}
</pre></code>
</p>

    <h3>update: Inserisce i valori nel db</h3>
<code><pre>
$db->update($table, $data, $where);
</pre></code>
<p>Aggiorna i dati inseriti in $data secondo la clausola $where. Le chiavi degli array sono i nomi dei campi. <br>Verifica prima i campi se esistono per cui se si passa un campo che non esiste nella tabella questo viene automaticamente scartato.<br> Se $where non ha clausole l'update non viene eseguito</p>
<p>
    <b>$table</b>: Il nome della tabella<br>
    <b>$data</b>: Un array contenente i valori da modificare<br>
    <b>$where</b>: Un array contente le clausole dei dati da modificare
</p>
<p><b>Esempio:</b>
<code><pre>
$update = $db->update('gp_users', array('username' =>'UserName Updated'), array('id'=>1));
if ($db->error) {
    print " <span style=\"color:red\">La query di aggiornamento ha dato errore</span>";
}
</pre></code>
</p>

    <h3>delete: </h3>
<code><pre>
$db->delete($table, $where);
</pre></code>
<p>Elimina le righe di una tabella</p>
<p>
    <b>$table</b>: Il nome della tabella<br>
    <b>$where</b>: Un array contente le clausole dei dati da modificare
</p>
<p> <b>Esempio:</b>
<code><pre>
$db->delete('gp_users', array('username' =>'UserName Update'));
</pre></code>
</p>

    <h3>insertid: Ritorna l'ultima query inserita</h3>
    <code><pre>$db->insertiId();</pre></code>

    <h3>quote:  Quota una stringa</h3>
    <code><pre>$db->quote($str);</pre></code>

    <h3>quoteName:  Quota una variabile</h3>
    <code><pre>$db->quoteName($str);</pre></code>

    <h3>prepare: Interessante, ma non qui!</h3>
    <h3>multyQuery: </h3>
    <code><pre>$db->multyQuery($query);</pre></code>
    <p>Esegue più query divise da punto e virgola. L'esecuzione è asincrona</p>


    <h3>close: Chiude la connessione</h3>
    <code><pre>$db->close($str);</pre></code>

</div>
