<div class="delete">
    <?php $note = $params['note'] ?? null; ?>
    <?php if ($note): ?>
        <ul>
            <li> Id:
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
            <li>
                <a href="/">
                    <button>Powrit do listy notatek</button>
                </a>
            </li>
            <li>
                <form mothod="POST" action="/?action=delete">
                    <input type="text" name="id" value="<?php echo $note['id'] ?>" hidden>
                    <input type="submit" value="Usun">
                </form>
            </li>
        </ul>
    <?php else: ?>
        <div>Brak notatki do wyswietlenia</div>
    <?php endif; ?>
</div>