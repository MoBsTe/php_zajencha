<div>
    <div class="message">
        <?php
        if (!empty($params['before'])) {

            switch ($params['before']) {
                case 'created':
                    echo "Notatka zostala utworzona";
                    break;
            }
        }
        ?>
    </div>
    <b>
        <?php
        echo $params['resultList'] ?? ""; ?>
    </b>
    <h3> Lista Notatek</h3>
</div>