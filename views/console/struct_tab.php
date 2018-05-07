<nav>
  <div class="nav nav-tabs">
<?php foreach ($vars['data'] as $id => $struct): ?>
    <a class="nav-link" data-toggle="tab" href="#<?php h("{$vars['id']}-{$id}"); ?>"><?php h($id); ?></a>
<?php endforeach; ?>
  </div>
</nav>
<div class="tab-content">
<?php foreach ($vars['data'] as $id => $struct): ?>
  <div class="tab-pane" id="<?php h("{$vars['id']}-{$id}"); ?>">
<?php load_view('console/struct', $struct, '    '); ?>
  </div>
<?php endforeach; ?>
</div>