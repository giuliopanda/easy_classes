<div class="gp-doc-container gp-content">
<?php
echo "<h1>Add Listener</h1>";

$listener = GPListener::getInstance();
$myEvent = new myEvents();

echo "<h3>Eseguo un singolo evento</h3>";
$listener->add("event1", array($myEvent, 'eventMethod1'));
$return = "1";
echo "<p>valore prima di invocare l'evento: <b>'".$return ."'</b></p>";
$return = $listener->invoke('event1', "NEW VALUE");
echo "<p>Dopo aver invocato l'evento: <b>".$return ."</b></p>";

echo "<br><hr><br><h3>Eseguo più eventi insieme</h3>";
// aggiunge un evento di una classe
echo "<p>ggiunge ad un evento una funzione interna ad una classe: <b>\$listener->add(\"event2\", array(\$myEvent, 'eventMethod2'));</b></p>";
$listener->add("event2", array($myEvent, 'eventMethod2'));
// aggiunge una funzione che non è in una classe
echo "<p>ggiunge ad un evento una funzione: <b>\$listener->add(\"event2\", 'fnEvent');</b></p>";
$listener->add("event2", 'fnEvent');
$return = $listener->invoke('event2',2);
echo "<p>Invoco gli eventi. Il primo valore che viene passato, viene aggiornato e poi passato alle altre funzioni. Gli altri valori vengono passati sempre uguali<b>\$listener->invoke('event2',2);</b> Il risultato: ".$return ." </p>";
echo "<p>Mostro le funzioni chiamate per un evento: <b>\$listener->showEvents('event2')</b></p>";
var_dump($listener->showEvents('event2'));
echo "<p>Rimuovo un evento: <b>\$listener->remove('event2', array(\$myEvent, 'eventMethod2'));</b></p>";
$listener->remove('event2', array($myEvent, 'eventMethod2'));
echo "<p>Mostro di nuovo le funzioni chiamate per un evento: <b>\$listener->showEvents('event2')</b></p>";
var_dump($listener->showEvents('event2'));
echo "<p>Rimuovo tutti gli eventi: <b>\$listener->remove('event2');</b></p>";
$listener->remove('event2');
echo "Invoco l'evento senza che ci siano funzioni associate. Il risultato è il primo parametro passato: <b>\$listener->invoke('event2',2, 3);</b> \\\\".$listener->invoke('event2',2, 3);


/**
 * Eventi
 */
class myEvents 
{
    function eventMethod1($val1) {
        return $val1;
    }
    function eventMethod2($value) {
        return $value+4;
    }
}

function fnEvent($return) {
   return  $return*2;
}
?></div>