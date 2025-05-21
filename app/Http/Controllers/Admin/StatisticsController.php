<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Enums\DocumentStatus;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'mois'); // défaut : mois
        $date = now();

        // Définir les bornes de la période
        switch ($periode) {
            case 'annee':
                $startDate = $date->copy()->startOfYear();
                $endDate = $date->copy()->endOfYear();
                break;
            case 'semaine':
                $startDate = $date->copy()->startOfWeek();
                $endDate = $date->copy()->endOfWeek();
                break;
            case 'mois':
            default:
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                break;
        }

        $stats = Document::selectRaw('
                type,
                COUNT(*) as total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as approuvees,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as rejetees
            ', [DocumentStatus::APPROUVEE->value, DocumentStatus::REJETEE->value])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('type')
            ->get();

        return view('admin.statistics.index', compact('stats', 'periode'));
    }
}
