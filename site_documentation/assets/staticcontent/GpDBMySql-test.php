<div class="gp-doc-container gp-content">
<?php
$ip = "localhost";
$name = "admin";
$psw = "admin";
$dbName = "test_class";
echo "<h2> MYSQL DATABASE</h2>";
$db = Gp::db();
if ($db->error) {
    echo "error: ".$db->error;
    exit();
}
$query ="
        SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
        SET AUTOCOMMIT = 0;
        START TRANSACTION;
        SET time_zone = \"+00:00\";

        CREATE TABLE `gp_users` (
          `id` int(11) NOT NULL,
          `username` varchar(250) NOT NULL,
          `email` varchar(250) NOT NULL,
          `password` varchar(250) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        ALTER TABLE `gp_users`
          ADD PRIMARY KEY (`id`),
          ADD UNIQUE KEY `username` (`username`);

        ALTER TABLE `gp_users`
          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        COMMIT;";

if ($db->error) {
    echo nl2Br("<b>Non sono riuscito a connettermi. </b>
      1. Verifica i dati di accesso
      2. Crea un database chiamato $dbName");
} else {
    echo "<h3>Crea una connessione scrivendo</h3>";
    echo "<p>\$db = new GpDBMySql($ip, $name, $psw, $dbName);</p>";
    $tables = $db->getTables();
    if (!in_array('gp_users', $tables)) {
        echo "<p style=\"color:red\">La tabella non esiste</p>";
        echo nl2Br($query);
    } else {
        $fields = $db->describes('gp_users');
        echo "<h3 >I campi di una tabella</h3>";
        echo "<p>\$db->describes('gp_users');</p>";
        var_dump ($fields);

        echo "<h3 >Inserisco i dati</h3>";
        $db->query("TRUNCATE gp_users");
        $arrayInsert = array(
            array('username' =>'Pippo', 'email'=>'pippo@gmail.com ', 'password'=>'gf'),
            array('username' =>'Pluto', 'email'=>'Pluto@gmail.com ', 'password'=>'podfgi'),
            array('username' =>'Paperino', 'email'=>'Paperino@gmail.com ', 'password'=>'gfdvc'),
            array('username' =>'Minni', 'email'=>'Minni@gmail.com ', 'password'=>'12345'),
            array('username' =>'Pippo', 'email'=>'pippo@gmail.com ', 'password'=>'54321')
        );

        foreach ($arrayInsert as $value) {
            echo " id: ". $db->insert('gp_users', $value);
            if ($db->error) {
                print " <span style=\"color:red\">La query ha dato errore</span>";
            }
            print "<br>";
        }

        echo "<h3 >Estraggo i dati</h3>";
        echo "<p>\$db->getResults(\"SELECT * FROM \".\$db->quoteName('gp_users'));</p>";
        $list = $db->getResults("SELECT * FROM ".$db->quoteName('gp_users'));
        foreach ($list as $key => $value) {
            var_dump ($value);
        } 
        
        echo "<h3 >Estraggo una singola riga</h3>";
        echo "<p>\$db->getRow(\"SELECT * FROM \".\$db->quoteName('gp_users'))</p>";
        $list = $db->getRow("SELECT * FROM ".$db->quoteName('gp_users'), 0);
        foreach ($list as $key => $value) {
            print ("<p>".$key.": ".$value."</p>");
        } 
        echo "<h3 >Aggiorno una riga</h3>";
        echo "<p>\$db->update('gp_users', array('username' =>'UserName Update'), array('id'=>1))</p>";
        $update = $db->update('gp_users',  array('username' =>'UserName Update'), array('id'=>1));
        if ($db->error) {
            print " <span style=\"color:red\">La query di aggiornamento ha dato errore</span>";
        }
        echo "<h3 >Estraggo il singolo valore appena modificato</h3>";
        echo "<p>\$db->getRow(\"SELECT * FROM \".\$db->quoteName('gp_users')) ORDER BY ID DESC</p>";
        $list = $db->getVar("SELECT username FROM ".$db->quoteName('gp_users')." ORDER BY ID ASC");
        
        print ("<p>getVar: ".$list."</p>");
        
        echo "<h3 >Rimuovo una riga</h3>";
        echo "<p>\$db->delete('gp_users', array('username' =>'UserName Update'))</p>";
        $delete = $db->delete('gp_users',  array('username' =>'UserName Update'));
        var_dump ($delete);
        if ($db->error) {
            print " <span style=\"color:red\">La query di delete ha dato errore</span>";
        }

        $db->close();
    }
}
?>
</div>