window.units = {};

function putSortable(e)
{
	const elmId = $(e.detail.origin.container).attr('id');
	let unit;
	let table;
	const arr = elmId.split(/-/);
	if (elmId.match(/^editable-config/)) {
		unit = 'config';
		table = arr[2];
	} else if (elmId.match(/^editable-application-database/)) {
		unit = 'application_database';
		table = arr[3];
	} else if (elmId.match(/^editable-application-table/)) {
		unit = 'application_table.' + $('[name="selector-application-table"]:checked').val();
		table = arr[3];
	} else {
		return;
	}
	let hash = {};
	for (let row of e.detail.origin.items)
	{
		if ($(row).hasClass('d-none')) continue;
		const i = $(row).find('[name="id"]').text();
		hash[i] = window.units[unit][table][i];
	}
	window.units[unit][table] = hash;
	$.ajax({
		type: 'POST',
		url: siteUrl + 'api/console/put_table/' + unit + '/' + table,
		data: 'data=' + JSON.stringify(window.units[unit][table]),
		success: (vars) => {
			drawIndex(elmId, window.units[unit][table]);
			$('#modal-edit').modal('hide');
		},
	});
}

function putModalEdit()
{
	const elmId = $('#modal-edit').data('elmId');
	const unit = $('#modal-edit').data('unit');
	const table = $('#modal-edit').data('table');
	const row = $('#modal-edit').data('row');
	const column = $('#modal-edit').data('column');
	const field = $('#modal-edit .modal-data').val().trim();

	if (column === 'id') {
		let hash = {};
		for (let i in window.units[unit][table])
		{
			if (i === row) {
				hash[field] = window.units[unit][table][i];
			} else {
				hash[i] = window.units[unit][table][i];
			}
		}
		window.units[unit][table] = hash;
	} else {
		window.units[unit][table][row][column] = field;
	}
	$.ajax({
		type: 'POST',
		url: siteUrl + 'api/console/put_table/' + unit + '/' + table,
		data: 'data=' + JSON.stringify(window.units[unit][table]),
		success: (vars) => {
			drawIndex(elmId, window.units[unit][table]);
			$('#modal-edit').modal('hide');
		},
	});
}

function putModalDelete()
{
	const elmId = $('#modal-delete').data('elmId');
	const unit = $('#modal-delete').data('unit');
	const table = $('#modal-delete').data('table');
	const row = $('#modal-delete').data('row');
	delete window.units[unit][table][row];
	$.ajax({
		type: 'POST',
		url: siteUrl + 'api/console/put_table/' + unit + '/' + table,
		data: 'data=' + JSON.stringify(window.units[unit][table]),
		success: (vars) => {
			drawIndex(elmId, window.units[unit][table]);
			$('#modal-delete').modal('hide');
		},
	});
}

function putModalAdd()
{
	const elmId = $('#modal-add').data('elmId');
	const unit = $('#modal-add').data('unit');
	const table = $('#modal-add').data('table');
	const id = $('#modal-add .modal-data').val().trim();
	const columns = $('#' + elmId).data('columns');
	if (Array.isArray(window.units[unit][table])) {
		window.units[unit][table] = {};
	}
	window.units[unit][table][id] = {};
	for (let i in columns) {
		window.units[unit][table][id][columns[i]] = '';
	}
	$.ajax({
		type: 'POST',
		url: siteUrl + 'api/console/put_table/' + unit + '/' + table,
		data: 'data=' + JSON.stringify(window.units[unit][table]),
		success: (vars) => {
			drawIndex(elmId, window.units[unit][table]);
			$('#modal-add').modal('hide');
		},
	});
}

function showModalEdit(elmId, row, column, field)
{
	let unit;
	let table;
	const arr = elmId.split(/-/);
	if (elmId.match(/^editable-config/)) {
		unit = 'config';
		table = arr[2];
	} else if (elmId.match(/^editable-application-database/)) {
		unit = 'application_database';
		table = arr[3];
	} else if (elmId.match(/^editable-application-table/)) {
		unit = 'application_table.' + $('[name="selector-application-table"]:checked').val();
		table = arr[3];
	} else {
		return;
	}
	$('#modal-edit').data('elmId', elmId);
	$('#modal-edit').data('unit', unit);
	$('#modal-edit').data('table', table);
	$('#modal-edit').data('row', row);
	$('#modal-edit').data('column', column);
	$('#modal-edit .modal-title').text(unit + '.' + table + '.' + row + '.' + column);
	$('#modal-edit .modal-data').val(field);
	$('#modal-edit').modal('show');
	$('#modal-edit .modal-data').focus().select();
}

function showModalDelete(elmId, row)
{
	let unit;
	let table;
	const arr = elmId.split(/-/);
	if (elmId.match(/^editable-config/)) {
		unit = 'config';
		table = arr[2];
	} else if (elmId.match(/^editable-application-database/)) {
		unit = 'application_database';
		table = arr[3];
	} else if (elmId.match(/^editable-application-table/)) {
		unit = 'application_table.' + $('[name="selector-application-table"]:checked').val();
		table = arr[3];
	} else {
		return;
	}
	$('#modal-delete').data('elmId', elmId);
	$('#modal-delete').data('unit', unit);
	$('#modal-delete').data('table', table);
	$('#modal-delete').data('row', row);
	$('#modal-delete .modal-title').text(unit + '.' + table + '.' + row);
	$('#modal-delete').modal('show');
	$('#modal-delete .modal-data').focus().select();
}


function showModalAdd(elmId)
{
	let unit;
	let table;
	const arr = elmId.split(/-/);
	if (elmId.match(/^editable-config/)) {
		unit = 'config';
		table = arr[2];
	} else if (elmId.match(/^editable-application-database/)) {
		unit = 'application_database';
		table = arr[3];
	} else if (elmId.match(/^editable-application-table/)) {
		unit = 'application_table.' + $('[name="selector-application-table"]:checked').val();
		table = arr[3];
	} else {
		return;
	}
	$('#modal-add').data('elmId', elmId);
	$('#modal-add').data('unit', unit);
	$('#modal-add').data('table', table);
	$('#modal-add .modal-title').text(unit + '.' + table);
	$('#modal-add .modal-data').val('');
	$('#modal-add').modal('show');
	$('#modal-add .modal-data').focus().select();
}

function drawIndex(elmId, data)
{
	$('#' + elmId + ' > *:gt(0)').remove();
	for (var id in data) {
		let elm = $('#' + elmId + ' *:first').clone(true);
		elm.removeClass('d-none');
		elm.find('[name="id"]').text(id);
		for (let name in data[id]) {
			elm.find('[name="' + name + '"]').text(data[id][name]);
		}
		$('#' + elmId).append(elm);
	}
}

function drawImage(elmId, data)
{
	$('#' + elmId).attr('src', data.base64.value);
}

function drawSelector(elmId, data)
{
	$('#' + elmId + ' > *:gt(0)').remove();
	for (var id in data) {
		let elm = $('#' + elmId + ' *:first').clone(true);
		elm.removeClass('d-none');
		elm.find('input').prop('disabled', false).val(id).attr('id', elmId + '-' + id);
		elm.find('label').text(id).attr('for', elmId + '-' + id);
		$('#' + elmId).append(elm);
	}
}

$(() => {
	drawSelector('selector-permission', {readonly:null, editable:null, both:null});
	$('[name="selector-permission"]').on('click', (e) => {
		$('[href="#tab-category-config"]').addClass('d-none');
		$('[href="#tab-layer-actual"]').addClass('d-none');
		$('[href="#tab-layer-application"]').addClass('d-none');
		$('[href="#tab-layer-request"]').addClass('d-none');
		switch (e.target.value) {
		case 'readonly':
			$('[href="#tab-layer-actual"]').removeClass('d-none');
			$('[href="#tab-layer-request"]').removeClass('d-none');
			break;
		case 'editable':
			$('[href="#tab-category-config"]').removeClass('d-none');
			$('[href="#tab-layer-application"]').removeClass('d-none');
			break;
		case 'both':
			$('[href="#tab-category-config"]').removeClass('d-none');
			$('[href="#tab-layer-actual"]').removeClass('d-none');
			$('[href="#tab-layer-application"]').removeClass('d-none');
			$('[href="#tab-layer-request"]').removeClass('d-none');
			break;
		}
	});
	sortable('.sortable', {forcePlaceholderSize:true});
	$('.sortable').on('sortupdate', function(e) {
		putSortable(e);
	});
	$('.edit').on('click', (e) => {
		showModalEdit(
			$(e.target).closest('tbody').attr('id'),
			$(e.target).closest('tr').find('[name="id"]').text(),
			$(e.target).attr('name'),
			$(e.target).text()
		);
	});
	$('.delete').on('click', (e) => {
		showModalDelete(
			$(e.target).closest('tbody').attr('id'),
			$(e.target).closest('tr').find('[name="id"]').text(),
			$(e.target).attr('name'),
			$(e.target).text()
		);
	});
	$('.add').on('click', (e) => {
		showModalAdd(
			$(e.target).closest('.struct').find('tbody').attr('id'),
		);
	});
	$('#modal-edit-save').on('click', () => {
		putModalEdit();
	});
	$('#modal-delete-delete').on('click', () => {
		putModalDelete();
	});
	$('#modal-add-add').on('click', () => {
		putModalAdd();
	});
	$('#modal-add input').on('keydown', (e) => {
		if (e.keyCode === 13) {
			putModalAdd();
		}
	});
	$('[href="#tab-category-config"]').on('click', () => {
		$.ajax({
			'url': siteUrl + 'api/console/get_config',
			'dataType': 'json',
			'success': (vars) => {
				window.units.config = vars;
				drawIndex('editable-config-db-data', vars.db);
				drawIndex('editable-config-languages-data', vars.languages);
				drawIndex('editable-config-actors-data', vars.actors);
			},
		});
	});
	$('[href="#tab-layer-actual"]').on('click', () => {
		$.ajax({
			'url': siteUrl + 'api/console/get_actual_database',
			'dataType': 'json',
			'success': (vars) => {
				drawIndex('index-actual-database-tables-data', vars.tables);
				drawIndex('index-actual-database-references-data', vars.references);
				drawImage('image-actual-database-er_diagram-data', vars.er_diagram);
				drawSelector('selector-actual-table', vars.tables);
			},
		});
	});
	$('[name="selector-actual-table"]').on('click', (e) => {
		$.ajax({
			'url': siteUrl + 'api/console/get_actual_table/' + e.target.value,
			'dataType': 'json',
			'success': (vars) => {
				drawIndex('index-actual-table-primary_keys-data', vars.primary_keys);
				drawIndex('index-actual-table-columns-data', vars.columns);
			},
		});
	});
	$('[href="#tab-layer-application"]').on('click', () => {
		$.ajax({
			'url': siteUrl + 'api/console/get_application_database',
			'dataType': 'json',
			'success': (vars) => {
				window.units.application_database = vars;
				drawIndex('editable-application-database-tables-data', vars.tables);
				drawIndex('editable-application-database-references-data', vars.references);
				drawIndex('editable-application-database-unset-data', vars.unset);
			},
		});
		$.ajax({
			'url': siteUrl + 'api/console/get_actual_database',
			'dataType': 'json',
			'success': (vars) => {
				drawSelector('selector-application-table', vars.tables);
			},
		});
	});
	$('[name="selector-application-table"]').on('click', (e) => {
		$.ajax({
			'url': siteUrl + 'api/console/get_application_table/' + e.target.value,
			'dataType': 'json',
			'success': (vars) => {
				window.units['application_table.' + e.target.value] = vars;
				drawIndex('editable-application-table-actions-data', vars.actions);
				drawIndex('editable-application-table-columns-data', vars.columns);
				drawIndex('editable-application-table-unset-data', vars.unset);
			},
		});
	});
	$('[href="#tab-layer-request"]').on('click', () => {
		$.ajax({
			'url': siteUrl + 'api/console/get_config',
			'dataType': 'json',
			'success': (vars) => {
				window.units.config = vars;
				drawSelector('selector-request-actor', vars.actors);
			},
		});
	});
	$('[name="selector-request-actor"]').on('click', (e) => {
		$.ajax({
			'url': siteUrl + 'api/console/get_request_database/' + e.target.value,
			'dataType': 'json',
			'success': (vars) => {
				drawIndex('index-request-database-tables-data', vars.tables);
				drawIndex('index-request-database-references-data', vars.references);
				drawImage('image-request-database-er_diagram-data', vars.er_diagram);
				drawSelector('selector-request-alias', Object.assign(vars.tables, vars.references));
				$('#selector-request-action > *:gt(0)').remove();
				$('#index-request-table-primary_keys-data > *:gt(0)').remove();
				$('#index-request-table-actions-data > *:gt(0)').remove();
				$('#index-request-table-columns-data > *:gt(0)').remove();
			},
		});
	});
	$('[name="selector-request-alias"]').on('click', (e) => {
		$.ajax({
			'url': siteUrl + 'api/console/get_request_table/' + $('[name="selector-request-actor"]:checked').val() + '/' + e.target.value,
			'dataType': 'json',
			'success': (vars) => {
				drawSelector('selector-request-action', vars.actions);
				$('#index-request-table-primary_keys-data > *:gt(0)').remove();
				$('#index-request-table-actions-data > *:gt(0)').remove();
				$('#index-request-table-columns-data > *:gt(0)').remove();
			},
		});
	});
	$('[name="selector-request-action"]').on('click', (e) => {
		$.ajax({
			'url': siteUrl + 'api/console/get_request_table/' + $('[name="selector-request-actor"]:checked').val() + '/' + $('[name="selector-request-alias"]:checked').val() + '/' + e.target.value,
			'dataType': 'json',
			'success': (vars) => {
				drawIndex('index-request-table-primary_keys-data', vars.primary_keys);
				drawIndex('index-request-table-actions-data', vars.actions);
				drawIndex('index-request-table-columns-data', vars.columns);
			},
		});
	});
	$('[href="#tab-application-table"]').on('click', () => {
		$.ajax({
			'url': siteUrl + 'api/console/get_application_database',
			'dataType': 'json',
			'success': (vars) => {
				drawSelector('selector-application-table', vars.tables);
			},
		});
	});
});
