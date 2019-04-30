<h4>Log</h4>
<table class="table" <?php echo Gp::log()->getDataLog(); ?>>
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Date</th>
      <th scope="col">msg type</th>
      <th scope="col">Message</th>
      <th scope="col">file</th>
      <?php if (Gp::data()->get('request.ajax','0') == 0 && $logType =="system") : ?>
      <th scope="col">html</th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $key=>$item): ?>
        <tr data-loguniqid="<?php echo $item['uniqId']; ?>" <?php echo ($item['msgType'] == "ERROR") ? 'class="table-danger"' : ''; ?>>
        <td><?php echo $item['count']; ?></td>
        <td><?php echo $item['time']; ?></td>
        <td><?php echo $item['msgType']; ?></td>
        <td class="text-break"><?php echo $item['msg']; ?></td>
        <td class="text-break"><?php echo $item['in'][0]; ?></td>
        <?php if ($item['pointerHtml'] && $item['msgType'] != "ERROR" && Gp::data()->get('request.ajax','0') == 0 && $logType =="system") : ?>
          <td><button onclick="findLogElement('<?php echo $item['pointerHtml']; ?>')" class="btn btn-light">Mostra l'elemento</button></td>
        <?php else: ?>
          <td></td>
        <?php endif; ?>  
        </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php if (Gp::data()->get('request.ajax','0') == 0 && $logType =="system") : ?>
<script>
  function findLogElement(logId) {
    $el = $("[data-log='" + logId + "']"); 
    if ($el) {
      var rect = $el.offset();
      $('#alertDivForLog').stop().remove();
      $divAppend = $('<div id="alertDivForLog"></div>');
      $divAppend.css({top:rect.top+"px", left:rect.left+"px", width:$el.width()+"px", height:$el.height()+"px", position:'absolute', border:'2px solid #F00', zIndex:'999'});
      $('body').append($divAppend);
      $divAppend.animate({opacity:0}, 2000, function() {
         $('#alertDivForLog').remove();
      });
      $('html,body').animate({
            scrollTop: $el.offset().top
      }, 'fast');

    } else {
      alert("Element doesn't exist");
    }
  }
</script>
<?php endif; ?>