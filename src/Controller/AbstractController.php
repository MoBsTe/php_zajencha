<?php 

declare(strict_types=1);

namespace App\Controller;

use App\Request;
use App\View;
use App\Database;



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

    protected function redirect(string $to, array $params): void
    {
        $location = $to;
        if (count($params)) {
            $queryParams = [];
            foreach ($params as $key => $value) {
                $queryParams[] = urlencode($key) . '=' . urlencode($value);
            }
            $queryParams = implode('&', $queryParams);
            $location .= '?' . $queryParams;
        }
        header("Location: $location");
        exit;
    }
}