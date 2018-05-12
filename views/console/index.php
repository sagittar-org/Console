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
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea class="modal-data form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="modal-edit-save" accesskey="S">Save</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="modal-delete" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Deleting <span class="modal-title"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="modal-delete-delete">Delete</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="modal-add" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input class="modal-data form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="modal-add-add">Add</button>
      </div>
    </div>
  </div>
</div>
