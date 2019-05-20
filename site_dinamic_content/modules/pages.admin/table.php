<?php $route = Gp::route(); ?>
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
        <?php $link = $route->getCurrentLink('?cmd=edit&idform='.$item['id']); ?>
        <?php foreach ($item as $value) : ?>
            <td><a href="<?php echo $link; ?>"><?php echo $value; ?></a></td>
        <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>