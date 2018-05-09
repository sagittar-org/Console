<table class="table table-hover table-sm">
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
      <td name="id"></td>
<?php foreach ($vars['data'] as $column): ?>
      <td name="<?php h($column); ?>"></td>
<?php endforeach; ?>
    <tr>
  </tbody>
</table>
