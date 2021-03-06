<?php
class Console
{
	public function put_table($unit, $table)
	{
		$arr = explode('.', $unit);
		$handler = $arr[0];
		$name = isset($arr[1]) ? $arr[1] : '';
		$data = $this->$handler->drivers[0]->get($name);
		$data[$table] = json_decode($_POST['data'], true);
		$this->$handler->drivers[0]->put($data, time(), $name);
	}

	public function __construct()
	{
		$this->config = new \pieni\Sync\Handler('config', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Sync\Excel', ['path' => FCPATH.'/sync/excel']],
			['\pieni\Proto\Config', []],
		]);
		$config = $this->config->get();
		$this->database = $config['db']['database']['value'];
		$this->db = new mysqli($config['db']['host']['value'], $config['db']['user']['value'], $config['db']['password']['value']);
		$this->actual_database = $actual_database = new \pieni\Sync\Handler('actual_database', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Proto\ActualDatabase', ['database' => $this->database, 'db' => $this->db]],
		]);
		$this->application_database = $application_database = new \pieni\Sync\Handler('application_database', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Sync\Excel', ['path' => FCPATH.'/sync/excel']],
			['\pieni\Proto\ApplicationDatabase', ['actual_database' => $actual_database]],
		]);
		$this->filter_database = $filter_database = new \pieni\Sync\Handler('filter_database', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Sync\Excel', ['path' => FCPATH.'/sync/excel']],
			['\pieni\Proto\FilterDatabase', []],
		]);
		$this->request_database = $request_database = new \pieni\Sync\Handler('request_database', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Proto\RequestDatabase', ['actual_database' => $actual_database, 'application_database' => $application_database, 'filter_database' => $filter_database]],
		]);
		$this->actual_table = $actual_table = new \pieni\Sync\Handler('actual_table', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Proto\ActualTable', ['database' => $this->database, 'db' => $this->db]],
		]);
		$this->application_table = $application_table = new \pieni\Sync\Handler('application_table', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Sync\Excel', ['path' => FCPATH.'/sync/excel']],
			['\pieni\Proto\ApplicationTable', ['config' => $this->config, 'actual_database' => $actual_database, 'actual_table' => $actual_table]],
		]);
		$this->filter_table = $filter_table = new \pieni\Sync\Handler('filter_table', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Sync\Excel', ['path' => FCPATH.'/sync/excel']],
			['\pieni\Proto\FilterTable', []],
		]);
		$this->request_table = $request_table = new \pieni\Sync\Handler('request_table', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Proto\RequestTable', ['request_database' => $request_database, 'actual_table' => $actual_table, 'application_table' => $application_table, 'filter_table' => $filter_table]],
		]);
	}

	public function index()
	{
		$vars['struct'] = [
			'selector-permission' => null,
			'tab-category' => [
				'config' => [
					'tab-config' => [
						'db' => [
							'editable-config-db' => \pieni\Proto\Config::$columns['db'],
						],
						'languages' => [
							'editable-config-languages' => \pieni\Proto\Config::$columns['languages'],
						],
						'actors' => [
							'editable-config-actors' => \pieni\Proto\Config::$columns['actors'],
						],
					],
				],
				'schema' => [
					'tab-layer' => [
						'actual' => [
							'tab-actual' => [
								'database' => [
									'tab-actual-database' => [
										'tables' => [
											'index-actual-database-tables' => \pieni\Proto\ActualDatabase::$columns['tables'],
										],
										'references' => [
											'index-actual-database-references' => \pieni\Proto\ActualDatabase::$columns['references'],
										],
									],
								],
								'table' => [
									'selector-actual-table' => null,
									'tab-actual-table' => [
										'primary_keys' => [
											'index-actual-table-primary_keys' => \pieni\Proto\ActualTable::$columns['primary_keys'],
										],
										'columns' => [
											'index-actual-table-columns' => \pieni\Proto\ActualTable::$columns['columns'],
										],
									],
								],
							],
						],
						'application' => [
							'tab-application' => [
								'database' => [
									'tab-application-database' => [
										'tables' => [
											'editable-application-database-tables' => \pieni\Proto\ApplicationDatabase::$columns['tables'],
										],
										'references' => [
											'editable-application-database-references' => \pieni\Proto\ApplicationDatabase::$columns['references'],
										],
									],
								],
								'table' => [
									'selector-application-table' => null,
									'tab-application-table' => [
										'actions' => [
											'editable-application-table-actions' => \pieni\Proto\ApplicationTable::$columns['actions'],
										],
										'columns' => [
											'editable-application-table-columns' => \pieni\Proto\ApplicationTable::$columns['columns'],
										],
									],
								],
							],
						],
						'filter' => [
							'tab-filter' => [
								'database' => [
									'tab-filter-database' => [
										'filters' => [
											'editable-filter-database-filters' => \pieni\Proto\FilterDatabase::$columns['filters'],
										],
									],
								],
								'table' => [
									'selector-filter-table' => null,
									'tab-application-table' => [
										'filters' => [
											'editable-filter-table-filters' => \pieni\Proto\FilterTable::$columns['filters'],
										],
									],
								],
							],
						],
						'request' => [
							'selector-request-actor' => null,
							'tab-request' => [
								'database' => [
									'tab-request-database' => [
										'tables' => [
											'index-request-database-tables' => \pieni\Proto\RequestDatabase::$columns['tables'],
										],
										'references' => [
											'index-request-database-references' => \pieni\Proto\RequestDatabase::$columns['references'],
										],
									],
								],
								'table' => [
									'selector-request-alias' => null,
									'selector-request-action' => null,
									'tab-request-table' => [
										'primary_keys' => [
											'index-request-table-primary_keys' => \pieni\Proto\RequestTable::$columns['primary_keys'],
										],
										'actions' => [
											'index-request-table-actions' => \pieni\Proto\RequestTable::$columns['actions'],
										],
										'columns' => [
											'index-request-table-columns' => \pieni\Proto\RequestTable::$columns['columns'],
										],
									],
								],
							],
						],
					],
				],
			],
		];
		return $vars;
	}

	public function get_config()
	{
		return $this->config->get();
	}

	public function get_actual_database()
	{
		return $this->actual_database->get();
	}

	public function get_actual_table($name)
	{
		return $this->actual_table->get($name);
	}

	public function get_application_database()
	{
		return $this->application_database->get();
	}

	public function get_application_table($name)
	{
		return $this->application_table->get($name);
	}

	public function get_filter_database()
	{
		return $this->filter_database->get();
	}

	public function get_filter_table($name)
	{
		return $this->filter_table->get($name);
	}

	public function get_request_database($actor)
	{
		return $this->request_database->get($actor);
	}

	public function get_request_table($actor, $alias, $action = 'dummy')
	{
		return $this->request_table->get("{$actor}.{$alias}.{$action}");
	}
}
