<?php

declare(strict_types=1);

namespace App;

include_once('./src/view.php');
require_once('./config/config.php');
require_once('./src/Database.php');

use App\Exception\NotFoundException;

class Controller
{
    const DEFAULT_ACTION = 'list';
    private array $request;
    private View $view;
    private static array $configuration = [];
    private Database $database;

    public function __construct(array $request)
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

                $data = $this->getRequestPost();
                if (!empty($data)) {
                    $noteDate = [
                        'title' => $data['title'],
                        'description' => $data['description'],
                    ];
                    $this->database->createNote($noteDate);
                    header('Location: /?before=created');
                }

                break;
            case 'show':
                $page = 'show';
                $data = $this->getRequestGet();
                $noteId = (int) $data['id'] ?? null;
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
                $data = $this->getRequestGet();
                $viewParams = [
                    'notes' => $this->database->getNotes(),
                    'before' => $data['before'] ?? null,
                    'error' => $data['error'] ?? null,
                ];
                break;
        }
        $this->view->render($page, $viewParams);
    }

    private function action(): string
    {
        $data = $this->getRequestGet();
        return $data['action'] ?? self::DEFAULT_ACTION;
    }

    private function getRequestPost(): array
    {
        return $this->request['post'] ?? [];
    }

    private function getRequestGet(): array
    {
        return $this->request['get'] ?? [];
    }
}