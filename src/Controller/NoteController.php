<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;

class NoteController extends AbstractController
{
    public function createAction()
    {
        if ($this->request->hasPost()) {
            $noteData = [
                'title' => $this->request->postParams('title'),
                'description' => $this->request->postParams('description'),
            ];
            $this->database->createNote($noteData);
            $this->redirect('/', ['before' => 'created']);
            // header('Lovation: /?before=created');
            // exit;
        }
        $this->view->render('create');
    }

    public function showAction()
    {
        $noteId = (int) $this->request->getParams('id');
        if (!$noteId) {
            $this->redirect('/', ['error' => 'missingNoteId']);
        }
        try {
            $note = $this->database->getNote($noteId);
        } catch (NotFoundException $e) {
            $this->redirect('/', ['error' => 'noteNotFoud']);
        }
        $this->view->render('show', ['note' => $note]);
    }

    public function listAction()
    {
        $this->view->render('list', [
            'notes' => $this->database->getNotes(),
            'before' => $this->request->getParams('before'),
            'error' => $this->request->getParams('error'),
        ]);
    }

    public function editAction()
    {

        if ($this->request->isPost()) {
            $noteId = (int) $this->request->postParams('id');
            $noteData = [
                'title' => $this->request->postParams('title'),
                'description' => $this->request->postParams('description'),
            ];
            $this->database->editNotes($noteId, $noteData);
            $this->redirect('/', ['before' => 'created']);
        }

        $noteId = (int) $this->request->getParams('id');
        if (!$noteId) {
            $this->redirect('/', ['error' => 'missingNoteId']);
        }
        try {
            $note = $this->database->getNote($noteId);
        } catch (NotFoundException $e) {
            $this->redirect('/', ['error' => 'notNotFound']);
        }
        $this->view->render('edit', ['note' => $note]);
    }

    public function deleteAction()
    {
        if ($this->request->isPost()) {
            $noteId = (int) $this->request->postParams('id');
            $this->database->deleteNotes($noteId);
            $this->redirect('/', ['before' => 'deleted']);
        }
        $noteId = (int) $this->request->getParams('id');
        if (!$noteId) {
            $this->redirect('/', ['error' => 'missingNoteId']);
        }
        try {
            $note = $this->database->getNote($noteId);
        } catch (NotFoundException $e) {
            $this->redirect('/', ['error' => 'notNotFound']);
        }
        $this->view->render('delete', ['note' => $note]);
    }
}