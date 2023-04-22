<?php

declare(strict_types=1);

namespace App;

include_once('./src/view.php');
require_once('./config/config.php');
require_once('./src/Database.php');

use App\Exception\NotFoundException;
use App\Request;

class Controller
{
    const DEFAULT_ACTION = 'list';
    private static array $configuration = [];
    private Database $database;
    private View $view;
    private Request $request;
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

    public function run(): void
    {

        $viewParams = [];

        switch ($this->action()) {
            case 'create';
                $page = 'create';
                if ($this->request->hasPost()) {
                    $noteDate = [
                        'title' => $this->request->postParams('title'),
                        'description' => $this->request->postParams('description'),
                    ];
                    $this->database->createNote($noteDate);
                    header('Location: /?before=created');
                }

                break;
            case 'show':
                $page = 'show';
                $noteId = (int) $this->request->getParams('id');
                if (!$noteId) {
                    header('Location: /?error-missingNoteId');
                    exit;
                    ;
                }
                try {
                    $note = $this->database->getNote($noteId);
                } catch (NotFoundException $e) {
                    header('Location: /?error-noteNotFound');
                    exit;
                }
                $viewParams = [
                    'note' => $note,
                ];
                break;
            default:
                $page = 'list';
                $viewParams = [
                    'notes' => $this->database->getNotes(),
                    'before' => $this->request->getParams('before'),
                    'error' => $this->request->getParams('error'),
                ];
                break;
        }
        $this->view->render($page, $viewParams);
    }

    private function action(): string
    {
       return $this->request->getParams('action', self::DEFAULT_ACTION);
    }
}