<div class="gp-doc-container gp-content">
<?php
echo "<h3>GP Log</h3>";
echo "<p>Memorizza un log</p>";
?><pre>Gp::log()->set( 'test_log', "INFO", "Un test di un log con parametri", array('name'=>"Jhon Foo"));
echo (Gp::log()->get( 'test_log'));
</pre>

<div class="row php-test">
    <div class="col">
    <p><b>Risultato:</b></p>
    <?php
    Gp::log()->set( 'test_log', "INFO", "Un test di un log con parametri", array('name'=>"Jhon Foo"));
    var_dump (Gp::log()->get( 'test_log'));
    ?>
    </div>
    <div class="col">
    <p><b>Risultato atteso:</b></p>
    <pre class="xdebug-var-dump" dir="ltr">
<b>array</b> <i>(size=1)</i>
  0 <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=6)</i>
      'msgType' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'INFO'</font> <i>(length=4)</i>
      'msg' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Un test di un log con parametri'</font> <i>(length=31)</i>
      'params' <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=1)</i>
          'name' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Jhon Foo'</font> <i>(length=8)</i>
      'time' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'20190425181634'</font> <i>(length=14)</i>
      'in' <font color="#888a85">=&gt;</font> 
        <b>array</b> <i>(size=6)</i>
          0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/gate_point/classes/GpLoad.php:201'</font> <i>(length=60)</i>
          1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/site_static_content/modules/staticcontent/staticcontent.php:11'</font> <i>(length=89)</i>
          2 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/gate_point/classes/GpLoad.php:269'</font> <i>(length=60)</i>
          3 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/site_static_content/pages/api.php:5'</font> <i>(length=62)</i>
          4 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/gate_point/classes/GpLoad.php:201'</font> <i>(length=60)</i>
          5 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/index.php:46'</font> <i>(length=39)</i>
      'pointerHtml' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'5cc1dd6253beb'</font> <i>(length=13)</i>
</pre>
    </div>
</div>

<p>Due modi per scrivere un log in un file</p>
<pre>Gp::log()->write( 'test_log'); // scrive i log fino ad ora impostati
Gp::log()->write( 'test_log', "INFO", "Secondo log"); // aggiunge un log e scrive tutto su file
$testLog = Gp::log()->read('test_log');
var_dump ($testLog[0]);
</pre>

<div class="row php-test">
    <div class="col">
    <p><b>Risultato:</b></p>
    <?php
        Gp::log()->write('test_log'); // scrive i log fino ad ora impostati
        Gp::log()->write( 'test_log', "INFO", "Secondo log"); // aggiunge un log e scrive tutto su file
        $testLog = Gp::log()->load('test_log');
        var_dump ($testLog[0]);
    ?>
    </div>
    <div class="col">
    <p><b>Risultato atteso:</b></p>
    <pre class="xdebug-var-dump" dir="ltr">
<b>array</b> <i>(size=8)</i>
  'count' <font color="#888a85">=&gt;</font> <small>int</small> <font color="#4e9a06">1</font>
  'time' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'YYYYMMGGHHMMSS'</font> <i>(length=14)</i>
  'uniqId' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'UNIQID'</font> <i>(length=13)</i>
  'ip' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'127.0.0.1'</font> <i>(length=9)</i>
  'msgType' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'INFO'</font> <i>(length=4)</i>
  'msg' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Un test di un log con parametri'</font> <i>(length=31)</i>
  'in' <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=6)</i>
      0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/gate_point/classes/GpLoad.php:201'</font> <i>(length=60)</i>
      1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/site_static_content/modules/staticcontent/staticcontent.php:11'</font> <i>(length=89)</i>
      2 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/gate_point/classes/GpLoad.php:269'</font> <i>(length=60)</i>
      3 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/site_static_content/pages/api.php:5'</font> <i>(length=62)</i>
      4 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/gate_point/classes/GpLoad.php:201'</font> <i>(length=60)</i>
      5 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'[...]/index.php:46'</font> <i>(length=39)</i>
  'params' <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=1)</i>
      'name' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'Jhon Foo'</font> <i>(length=8)</i>
</pre>
    </div>
</div>



</div>