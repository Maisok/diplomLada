<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceQuestion;
use App\Models\ServiceAnswer;
use App\Models\QuestionNotification;

class ServiceAnswerController extends Controller
{
    public function store(Request $request, ServiceQuestion $question)
    {
        $request->validate([
            'answer' => 'required|string|max:1000',
        ]);
        
        $answer = $question->answers()->create([
            'user_id' => auth()->id(),
            'answer' => $request->answer,
        ]);
        
        // Создаем уведомление для автора вопроса
        if ($question->user_id !== auth()->id()) {
            QuestionNotification::create([
                'user_id' => $question->user_id,
                'question_id' => $question->id,
                'is_read' => false,
            ]);
        }
        
        return redirect()->back()->with('success', 'Ответ успешно добавлен');
    }
    
    public function update(Request $request, ServiceAnswer $answer)
    {
        $this->authorize('update', $answer);
        
        $request->validate([
            'answer' => 'required|string|max:1000',
        ]);
        
        $answer->update(['answer' => $request->answer]);
        
        return redirect()->back()->with('success', 'Ответ успешно обновлен');
    }
    
    public function destroy(ServiceAnswer $answer)
    {
        $this->authorize('delete', $answer);
        
        $answer->delete();
        
        return redirect()->back()->with('success', 'Ответ успешно удален');
    }
}