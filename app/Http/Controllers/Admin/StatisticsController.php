<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Enums\DocumentStatus;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = Document::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as approuvees,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as rejetees,
            type
        ', [DocumentStatus::APPROUVEE->value, DocumentStatus::REJETEE->value])
        ->groupBy('type')
        ->get();

        return view('admin.statistics.index', compact('stats'));
    }
}
