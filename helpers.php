<?php
function formaterTweet(string $contenu): string
{
    // 1. Échappement XSS
    $html = htmlspecialchars($contenu, ENT_QUOTES, 'UTF-8');

    // 2. Gras : **texte** → <strong>texte</strong>
    $html = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $html);

    $html = preg_replace('/_(.+?)_/s', '<em>$1</em>', $html);


    $html = preg_replace(
        '/@([a-zA-Z0-9_]{1,50})/',
        '<a class="mention" href="/controllers/recherche.php?q=$1">@$1</a>',
        $html
    );

    $html = nl2br($html);

    return $html;
}
