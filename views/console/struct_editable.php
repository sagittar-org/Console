<div class="text-right">
  <button class="add btn btn-default btn-sm">Add</button>
</div>
<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th>id</th>
<?php foreach ($vars['data'] as $column): ?>
        <th><?php h($column); ?></th>
<?php endforeach; ?>
        <th style="width:1px;">Actions</th>
      <tr>
    </thead>
    <tbody class="sortable" id="<?php h($vars['id']); ?>-data" data-columns='<?php echo json_encode($vars['data']); ?>'>
      <tr class="d-none">
        <td class="edit" name="id"></td>
<?php foreach ($vars['data'] as $column): ?>
        <td class="edit" name="<?php h($column); ?>"></td>
<?php endforeach; ?>
        <td>
          <button class="delete btn btn-default btn-sm">Delete</button>
        </td>
      <tr>
    </tbody>
  </table>
</div>
