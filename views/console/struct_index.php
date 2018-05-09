<table class="table table-sm">
  <thead>
    <tr>
      <th>id</th>
<?php foreach ($vars['data'] as $column): ?>
      <th><?php h($column); ?></th>
<?php endforeach; ?>
    <tr>
  </thead>
  <tbody id="<?php h($vars['id']); ?>-data">
    <tr class="d-none">
      <td class="edit" name="id"></td>
<?php foreach ($vars['data'] as $column): ?>
      <td class="edit" name="<?php h($column); ?>"></td>
<?php endforeach; ?>
    <tr>
  </tbody>
</table>
