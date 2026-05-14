<?php
use Yajra\DataTables\Facades\DataTables;

if (! function_exists('datatables')) {
    function datatables($query = null)
    {
        $dt = app('datatables'); // koristi Yajra service
        if (is_null($query)) {
            return $dt;
        }
        return $dt->of($query);
    }
}