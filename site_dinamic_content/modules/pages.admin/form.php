<form action="<?php echo Gp::route()->getCurrentLink('?cmd=&idform='); ?>" method="post">
<?php $fields = $cData->get('fields'); ?>
<?php foreach ($fields as $field) : ?>
  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-2 col-form-label"><?php echo $field['label']; ?></label>
    <div class="col-sm-10">
      <?php if ($field['type'] == 'text') : ?>
        <input type="text" name="form[<?php echo $field['name']; ?>]" class="form-control <?php echo $cData->get('errors.'.$field['name']); ?>" id="input<?php echo $field['name']; ?>" placeholder="<?php echo $field['label']; ?>" value="<?php echo $cData->get('item.'.$field['name']); ?>">
      <?php elseif ($field['type'] == 'page') : ?>
        <select name="form[<?php echo $field['name']; ?>]" class="form-control" onChange="paChangeSelect(this)">
          <?php foreach ($field['options'] as $opt) : ?>
            <option value="<?php echo $opt['value']; ?>"><?php echo $opt['label']; ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
<?php /** A SECONDA DEL PAGE CARICO IL CONTENUTO PRINCIPALE DA MODIFICARE */ ?>
<div id="customForm"></div>

    <input type="hidden" name="form[id]" value="<?php echo $cData->get('item.id'); ?>">
    <button type="submit" name="cmd" value="save" class="btn btn-primary">Salva</button>
    <button type="submit" name="cmd" value="close" class="btn btn-warning">Annulla</button>
</form>

<script>
// pages.admin/form.php
function paChangeSelect(that) {
  var jqxhr = $.ajax( {
    url: _LINK_AJAX,
    data:{
      pageFile:$(that).val(),
      id:'pages.admin',
      action:'getForm'
    }
  })
  .done(function(response) {
    $('#customForm').empty().append(response);
    console.log( response );
  })
  .fail(function() {
    //alert( "error" );
  })
  .always(function() {
    //alert( "complete" );
  });
}
</script>