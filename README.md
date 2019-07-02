# gate point
E' uno studio su come risolvere alcuni algoritmi che spesso si ritrovano nell'ossatura di un progetto web.

E' un progetto di studio e non utilizzare in progetti in produzione


Alcuni dei problemi che sto affrontando nello specifico sono:

**GESTIONE DEI PROGETTI**

Il problema di quando io lavoro con codice personalizzato è che spesso non riesco nel tempo a riutilizzare il codice scritto anche se uso sempre la stessa ossatura.
 Nel personalizzare il codice per i singoli progetti non ho il tempo di costruire i moduli in modo facilmente riutilizabile.
 Per provare a risolvere questo problema, ho pensato di creare una struttura tipo framework in una cartella e tutte le personalizzazioni in un'altra.
Così la struttura delle cartelle è del tipo
- CMSDIR
- SITO 1
- SITO 2
- SITO 3
- TEMPLATE
index.php

Sull'index.php si indica qual'è la cartella del cms e quale del sito. Sulla configurazione del sito si indica quale template si usa.

Se poi un sito diventa un progetto completo e lo si vuole pacchettizare, si copiano le cartelle dentro la directory del CMS e così si può creare un ulteriore livello di personalizzazione. 

**APPROCCIO RESTful API**

Ogni modulo del sito è possibile chiamarlo tramite php o tramite http. Questo oltre a permettere una maggiore flessibilità impone una struttura più controllata e la possibilità di fare test diretti alle funzioni appena create.

**GIT E DOCUMENTAZIONE**

Non è facile lavorare ad un progetto pubblicabile, per cui sto cercando di lavorare con ordine su git e all'interno del progetto di creare una documentazione solida e chiara sull'uso del lavoro.