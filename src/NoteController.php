<?php

declare(strict_types=1);

namespace App;

use App\Exception\NotFoundException;

require_once('AbstractController.php');



class NoteController extends AbstractController
{
    public function createAction(){
        if($this->request->hasPost()){
            $noteData = [
                'title' => $this->request->postParams('title'),
                'description' => $this->request->postParams('description'),
            ];
            $this->database->createNote($noteData);
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
}