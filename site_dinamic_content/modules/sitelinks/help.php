<div class="gp-doc-container gp-content">
<h3>sitelinks</h3>
<p>Il modulo si collega al database e al router per gestire dinamicamente le pagine del sito</p>

<h3>getLinkFromPage: Dati page e view (impostati in una url) ritorna la nuova pagina da caricare (colonna link del database) </h3>
<p><?php echo Gp::route()->getSite(); ?>/ajax/sitelinks?action=getLinkFromPage&parsePage=api&parseView=new</p>
<p><b>p</b> è page e <b>w</b> è la view</p>
<p> $load->module('sitelinks','getLinkFromPage', $array) </p>
<p><b>$array</b>: (page:'',view:'')

<h3>getPageFromLink: data una page la cerca nella colonna link e ritorna la riga corrispondente dalla tabella</h3>
<p><?php echo Gp::route()->getSite(); ?>/ajax/sitelinks?action=getPageFromLink&parsePage=newapi</p>
p><b>p</b> è page</p>
<p> $load->module('sitelinks','getPageFromLink', $array) </p>
<p><b>$array</b>: (page:'')

<h3>La struttura della tabella </h3>
<p>Link è la pagina da caricare mentre page e view sono i parametri passati dall'url.</p>
<pre>
CREATE TABLE `gp_pages` (
  `id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `page` varchar(255) NOT NULL,
  `view` varchar(250) NOT NULL,
  `title` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `access` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
</pre>
</div>