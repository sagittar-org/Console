function drawIndex(elm_id, data)
{
	$('#' + elm_id + ' > *:gt(0)').remove();
	for (var id in data) {
		let elm = $('#' + elm_id + ' *:first').clone(true);
		elm.removeClass('d-none');
		elm.find('[name="id"]').text(id);
		for (let name in data[id]) {
			elm.find('[name="' + name + '"]').text(data[id][name]);
		}
		$('#' + elm_id).append(elm);
	}
}

function drawSelector(elm_id, data)
{
	$('#' + elm_id + ' > *:gt(0)').remove();
	for (var id in data) {
		let elm = $('#' + elm_id + ' *:first').clone(true);
		elm.removeClass('d-none');
		elm.find('input').prop('disabled', false).val(id).attr('id', elm_id + '-' + id);
		elm.find('label').text(id).attr('for', elm_id + '-' + id);
		$('#' + elm_id).append(elm);
	}
}

$(() => {
	$('[href="#tab-category-config"]').on('click', () => {
		$.ajax({
			'url': siteUrl + 'api/console/get_config',
			'dataType': 'json',
			'success': (vars) => {
				drawIndex('index-config-db-data', vars.db);
				drawIndex('index-config-actors-data', vars.actors);
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
				drawIndex('index-application-database-unset-data', vars.unset);
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
				drawIndex('index-application-table-actions-data', vars.actions);
				drawIndex('index-application-table-unset-data', vars.unset);
			},
		});
	});
	$('[href="#tab-layer-request"]').on('click', () => {
		$.ajax({
			'url': siteUrl + 'api/console/get_config',
			'dataType': 'json',
			'success': (vars) => {
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
});
