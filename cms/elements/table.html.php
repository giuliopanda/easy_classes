<table <?php $this->attr('table'); ?>>
    <thead <?php  $this->attr('thead'); ?>>
        <tr>
        <?php foreach ($this->structure as $cols) : ?>
        <th <?php $this->attr('th'); ?>><?php echo $cols->label; ?></th>
        <?php endforeach; ?>

        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->data as $dt) {
            $row = array();
            foreach ($this->structure as $structure) { 
              $field = $structure->field;
              $row[] = '<td>' . $dt->$field . '</td>';
            } 
           ?><tr <?php $this->attr('tr'); ?>><?php echo implode ("",$row); ?></tr><?php
        }
        ?>
    </tbody>
</table>