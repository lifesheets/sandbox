<?php

/**
 * Видаляє потенційно небезпечні скрипти та символи зі строки.
 *
 * @param string|null $string Вхідний рядок для обробки.
 * @return string Очищений рядок.
 */

function remove_script(?string $string = null): string
{
    if ($string === null) {
        return '';
    }

    // Видалення неприпустимих символів (NULL, керуючі символи та DEL).
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $string);

    // Чорний список небезпечних тегів та атрибутів
    $blacklist = [
        // Небезпечні теги.
        'vbscript', 'expression', 'applet', 'xml', 'blink', 'embed', 'object', 
        'frameset', 'ilayer', 'layer', 'bgsound',
        // Обробники подій.
        'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 
        'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 
        'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 
        'onblur', 'onbounce', 'oncellchange', 'onchange', 'oncontextmenu', 
        'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 
        'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 
        'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 
        'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 
        'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 
        'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 
        'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 
        'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 
        'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 
        'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 
        'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 
        'onstart', 'onstop', 'onsubmit', 'onunload'
    ];

    foreach ($blacklist as $keyword) {
        $string = preg_replace('/' . preg_quote($keyword, '/') . '/iu', '', $string);
    }

    return $string;
}

