<?php

namespace App\Traits;

trait SanitizesCsv
{
    protected function sanitizeCsvField($field)
    {
        $field = (string) $field;
        $triggers = ['=', '+', '-', '@'];
        if (in_array(substr($field, 0, 1), $triggers)) {
            return "'" . $field;
        }
        return $field;
    }
}
