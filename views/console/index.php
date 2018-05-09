<div class="container">
<h2>Prototyping console</h2>
<?php load_view('console/struct', $vars['struct'], '  '); ?>
</div>
<script src="<?php pub('console.js'); ?>"></script>

<!-- Modal -->
<div class="modal" id="modal-edit" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea class="modal-data form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
