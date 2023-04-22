<div class="show">
    <?php $note = $params['note'] ?? null; ?>
    <?php if ($note): ?>
        <ul>
            <li>Id:
                <?php echo (int) $note['id'] ?>
            </li>
            <li>Tytul:
                <?php echo htmlentities($note['title']) ?>
            </li>
            <li>Opis:
                <?php echo htmlentities($note['description']) ?>
            </li>
            <li>Utworzono:
                <?php echo htmlentities($note['datatime']) ?>
            </li>
            <li> <a href="/"> <button>Powrit do listy notatek</button></a></li>
        </ul>
    <?php else: ?>
        <div>Brak notatek do wyswietlenia</div>
    <?php endif; ?>
</div>