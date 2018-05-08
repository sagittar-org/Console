<?php
class Console
{
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
		$this->actual_database = new \pieni\Sync\Handler('actual_database', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Proto\ActualDatabase', ['database' => $this->database, 'db' => $this->db]],
		]);
		$this->application_database = new \pieni\Sync\Handler('application_database', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Sync\Excel', ['path' => FCPATH.'/sync/excel']],
			['\pieni\Proto\ApplicationDatabase', ['actual_database' => $this->actual_database]],
		]);
		$this->request_database = new \pieni\Sync\Handler('request_database', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Proto\RequestDatabase', ['application_database' => $this->application_database]],
		]);
		$this->actual_table = new \pieni\Sync\Handler('actual_table', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Proto\ActualTable', ['database' => $this->database, 'db' => $this->db]],
		]);
		$this->application_table = new \pieni\Sync\Handler('application_table', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Sync\Excel', ['path' => FCPATH.'/sync/excel']],
			['\pieni\Proto\ApplicationTable', ['actual_table' => $this->actual_table]],
		]);
		$this->request_table = new \pieni\Sync\Handler('request_table', [
			['\pieni\Sync\Json', ['path' => FCPATH.'/sync/json']],
			['\pieni\Proto\RequestTable', ['request_database' => $this->request_database, 'application_table' => $this->application_table]],
		]);
	}

	public function index()
	{
		$vars['struct'] = [
			'tab-category' => [
				'config' => [
					'tab-config' => [
						'db' => [
							'index-config-db' => \pieni\Proto\Config::$columns['db'],
						],
						'languages' => [
							'index-config-languages' => \pieni\Proto\Config::$columns['languages'],
						],
						'actors' => [
							'index-config-actors' => \pieni\Proto\Config::$columns['actors'],
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
										'unset' => [
											'index-application-database-unset' => \pieni\Proto\ApplicationDatabase::$columns['unset'],
										],
									],
								],
								'table' => [
									'selector-application-table' => null,
									'tab-application-table' => [
										'actions' => [
											'index-application-table-actions' => \pieni\Proto\ApplicationTable::$columns['actions'],
										],
										'unset' => [
											'index-application-table-unset' => \pieni\Proto\ApplicationTable::$columns['unset'],
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

	public function get_request_database($actor)
	{
		return $this->request_database->get($actor);
	}

	public function get_request_table($actor, $alias, $action = 'dummy')
	{
		return $this->request_table->get("{$actor}.{$alias}.{$action}");
	}
}
