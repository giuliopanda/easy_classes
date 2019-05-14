<table class="table table-bordered table-striped"> 
    <thead>
        <tr>
        <?php foreach ($cData->get('items.0') as $key=>$_value) : ?>
            <th scope="col"><?php echo $key; ?></th>
        <?php endforeach; ?>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($cData->get('items') as $item): ?>
        <tr>
        <?php foreach ($item as $value) : ?>
            <td><?php echo $value; ?></td>
        <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>