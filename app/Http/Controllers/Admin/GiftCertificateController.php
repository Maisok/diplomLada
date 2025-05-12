<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftCertificate;
use Illuminate\Http\Request;

class GiftCertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = GiftCertificate::query()->orderBy('created_at', 'desc');
        
        // Поиск по коду
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('code', 'like', "%{$search}%");
        }
        
        // Фильтрация по статусу
        if ($request->has('status')) {
            $status = $request->input('status');
            
            if ($status === 'active') {
                $query->where('is_used', false)
                      ->where('expires_at', '>', now());
            } elseif ($status === 'used') {
                $query->where('is_used', true);
            } elseif ($status === 'expired') {
                $query->where('is_used', false)
                      ->where('expires_at', '<=', now());
            }
        }
        
        $certificates = $query->paginate(10);
        
        return view('admin.certificates.index', compact('certificates'));
    }

    public function update(Request $request, GiftCertificate $certificate)
    {
        $validated = $request->validate([
            'is_used' => 'required|boolean'
        ]);
        
        $certificate->update(['is_used' => $validated['is_used']]);
        
        return back()->with('success', 'Статус сертификата успешно обновлен');
    }
}