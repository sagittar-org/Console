<button class="add btn btn-default btn-sm">Add</button>
<table class="table table-sm">
  <thead>
    <tr>
      <th>id</th>
<?php foreach ($vars['data'] as $column): ?>
      <th><?php h($column); ?></th>
<?php endforeach; ?>
      <th>Actions</th>
    <tr>
  </thead>
  <tbody id="<?php h($vars['id']); ?>-data" data-columns='<?php echo json_encode($vars['data']); ?>'>
    <tr class="d-none">
      <td class="edit" name="id"></td>
<?php foreach ($vars['data'] as $column): ?>
      <td class="edit" name="<?php h($column); ?>"></td>
<?php endforeach; ?>
      <td><button class="delete btn btn-default btn-sm">Delete</button></td>
    <tr>
  </tbody>
</table>
