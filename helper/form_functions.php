<?php

function showAlertWarning($message) {
    echo '<div class="alert alert-warning" role="alert">';
    echo $message;
    echo '</div>';
}

function createRadioButtons($name, $options, $checkedValue = null)
{
    $html = '';
    foreach ($options as $value => $label) {
        $checked = ($value == $checkedValue) ? 'checked' : '';
        $html .= "<div class='form-check'>";
        $html .= "<input class='form-check-input' type='radio' name='$name' id='$value' value='$value' $checked>";
        $html .= "<label class='form-check-label' for='$value'>$label</label>";
        $html .= "</div>";
    }
    return $html;
}

function showSuccess($message) {
    echo '<div class="alert alert-success" role="alert">';
    echo $message;
    echo '</div>';
}
