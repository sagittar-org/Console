<?php foreach ($vars as $id => $struct): ?>
<div class="struct" id="<?php h($id); ?>">
<?php load_view('console/struct_'.preg_replace('/-.*/', '', $id), ['id' => $id, 'data' => $struct], '  '); ?>
</div>
<?php endforeach; ?>
