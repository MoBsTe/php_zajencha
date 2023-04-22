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

    public function createAction()
    {

        if($this->request->hasPost()){
            $noteData = [
                'title' => $this->request->postParams('title'),
                'description' => $this->request->postParams('description'),
            ];
            $this->database->createNote($noteDate);
            header('Location: /?before=created');
            exit;
        }
        $this->view->render('create');

    }

    public function showAction() {
        $noteId = (int) $this->request->getParams('id');
        if (!$noteId) {
            header('Location: /?error-missingNoteId');
            exit;
        }
        try {
            $note = $this->database->getNote($noteId);
        } catch (NotFoundException $e) {
            header('Location: /?error-noteNotFound');
            exit;
        }
        $this->view->render('show', ['note'=> $note]);
    }


    public function listAction(){
        $this->view->render('list', [
        'notes' => $this->database->getNotes(),
        'before' => $this->request->getParams('before'),
        'error' => $this->request->getParams('error'),
        ]);
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