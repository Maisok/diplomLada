<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceQuestion;
use App\Models\QuestionNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ServiceQuestionController extends Controller
{
    public function store(Request $request, Service $service)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);
        
        $question = $service->questions()->create([
            'user_id' => auth()->id(),
            'question' => $request->question,
        ]);
        
        return redirect()->back()->with('success', 'Вопрос успешно добавлен');
    }
    
    public function update(Request $request, Service $service, ServiceQuestion $question)
    {
        $this->authorize('update', $question);
        
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);
        
        $question->update(['question' => $request->question]);
        
        return redirect()->back()->with('success', 'Вопрос успешно обновлен');
    }

    public function destroy(Service $service, ServiceQuestion $question)
    {
        $this->authorize('delete', $question);
        
        $question->delete();
        
        return back()->with('success', 'Вопрос удален');
    }
    
    public function close(Service $service, ServiceQuestion $question)
    {
        $this->authorize('close', $question);
        
        $question->update(['is_closed' => true]);
        
        return back()->with('success', 'Обсуждение закрыто');
    }
}