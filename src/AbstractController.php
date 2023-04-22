<?php 

declare(strict_types=1);

namespace App;

use App\Request;

include_once('./src/view.php');
require_once('./config/config.php');
require_once('./src/Database.php');


abstract class AbstractController{
    const DEFAULT_ACTION = 'list';
    protected Request $request;
    protected View $view;
    protected static array $configuration = [];
    protected Database $database;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->view = new View();
        $this->database = new Database(self::$configuration);
    }
    
    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function run(): void {
        $action = $this->action() . 'Action';
        if (!method_exists($this, $action)) {
            $action = self::DEFAULT_ACTION . 'Action';
        }
        $this->$action();
    }

    private function action(): string
    {
       return $this->request->getParams('action', self::DEFAULT_ACTION);
    }
}