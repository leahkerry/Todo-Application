<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth:sanctum');
    // }

    public function index()
    {
        $todos = auth()->user()->todos()->orderBy('created_at', 'desc')->get();
        return response()->json($todos, 200);
        // return Todo::all();
    }

    public function show($id)
    {
        $todo = Todo::findOrFail($id);
        if ($todo->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }
        return response()->json($todo, 200);
        // 
        // return $todo;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'sometimes|nullable|date',
        ]);

        $todo = auth()->user()->todos()->create([
            'title' => $validated['title'],
            'completed' => false, 
            'due_date' => $validated['due_date'] ?? null,
        ]);
        return response()->json($todo, 201);
        // return Todo::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);
        if ($todo->user_id !== auth()->id()) {
            if ($todo->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'Forbidden'
                ], 403);
            }
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'completed' => 'boolean',
            'due_date' => 'sometimes|nullable|date',
        ]);

        $todo->update($validated);
        return response()->json($todo, 200);
        // $todo = Todo::findOrFail($id);

        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'due_date' => 'sometimes|nullable|date',
        // ]);

        // $todo->update($request->all());

        // return $todo;
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        if ($todo->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }
        
        $todo->delete();

        return response()->noContent();
    }
}