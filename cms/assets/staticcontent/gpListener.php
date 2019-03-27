
<div class="gp-doc-container gp-content">
    <h2>Gestione degli eventi V 2</h2>

    <h3>Istanziare la classe</h3>
    <code><pre>$listener = GPListener::getInstance();</pre></code>
   

    <h3><b>add</b>: Aggiunge una funzione ad un evento</h3>
    <code><pre>$listener->add($event, $fn);</pre></code>
    <p>
        <b>$event</b> E' un testo e definisce il nome dell'evento a cui associare la funzione
        <b>$fn</b> String|Array E' la funzione da associare. Se la funzione associata è in una classe si passa un'array il cui primo valore è l'istanza della classe.
    </p>
    <p><b>Esempio:</b>:
        <code><pre>$myEvent = new myEvents();
$listener->add("custom-event", array($myEvent, 'eventMethod'));
$listener->add("custom-event", 'fnEvent');

class myEvents
{
    function eventMethod($val) {
        return $val+4;
    }
}
function fnEvent($val) {
    return $val*2;
}
</pre></code>
    </p>

    <h3><b>invoke</b>: Esegue le funzioni associate ad un evento</h3>
    <code><pre>$listener->invoke($event, $return, [args]);</pre></code>
    <p><b>$event</b> String il nome dell'evento<br>
        <b>$return</b> E' il valore elaborato<br>
        Quando si esegue un evento vengono eseguite tutte le funzioni ad esso associate. Ogni funzione eseguita ritorna un valore che verrà poi passato come primo valore alla funzione succssiva. 
    </p>
    <p><b>Esempio:</b>
        <code><pre>$return = $listener->invoke('custom-event',2);</pre></code>
    </p>
    <p>Eseguito sull'esempio precedente aggiungerà prima 4 al parametro passato (2) quindi sulla seconda funzione verrà moltiplicato per 2 il risultato passata (6). Il risultato è quindi 12</p>

    <h3><b>showEvents</b>: Mostra le funzioni per un determinato evento </h3>
    <code><pre>$listener->showEvents($event); 
    </pre></code>

    <h3><b>remove</b>: Rimuove una o più funzioni da un evento</h3>
    <code><pre>$listener->remove($event, $fn = false); 
        </pre></code>
    <p>Se <b>$fn</b> non viene passato vengono rimosse tutte le funzioni da un evento.<br>
        Se <b>$fn</b> è una funzione di una classe bisogna passare la stessa istanza di classe
    </p>
  
</div>
