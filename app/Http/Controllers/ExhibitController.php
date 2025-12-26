<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExhibitController extends Controller
{
    public function index(Museum $museum)
    {
        $exhibits = $museum->exhibits()->with('user')->orderBy('created_at', 'desc')->get();
        return view('exhibits.index', compact('museum', 'exhibits'));
    }

    public function create(Museum $museum)
    {
        return view('exhibits.create', compact('museum'));
    }

    public function store(Request $request, Museum $museum)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $validated['museum_id'] = $museum->id;
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            
            $image->storeAs('exhibits', $filename, 'public');
            $validated['image_filename'] = $filename;
        }

        Exhibit::create($validated);
        
        return redirect()->route('museums.show', $museum)
            ->with('success', 'Экспонат успешно добавлен');
    }

    public function edit(Museum $museum, Exhibit $exhibit)
    {
        if ($exhibit->museum_id !== $museum->id) {
            abort(404);
        }

        if (!auth()->user()->is_admin && $exhibit->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для редактирования этого экспоната');
        }
        
        return view('exhibits.edit', compact('museum', 'exhibit'));
    }

    public function update(Request $request, Museum $museum, Exhibit $exhibit)
    {
        if ($exhibit->museum_id !== $museum->id) {
            abort(404);
        }

        if (!auth()->user()->is_admin && $exhibit->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для редактирования этого экспоната');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($request->hasFile('image')) {
            if ($exhibit->image_filename) {
                $oldPath = 'exhibits/' . $exhibit->image_filename;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('exhibits', $filename, 'public');
            $validated['image_filename'] = $filename;
        }
        
        $exhibit->update($validated);
        
        return redirect()->route('museums.show', $museum)
            ->with('success', 'Экспонат успешно обновлен');
    }

    public function destroy(Museum $museum, Exhibit $exhibit)
    {
        if ($exhibit->museum_id !== $museum->id) {
            abort(404);
        }
        
        if (!auth()->user()->is_admin && $exhibit->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для удаления этого экспоната');
        }

        if ($exhibit->image_filename) {
            $path = 'exhibits/' . $exhibit->image_filename;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $exhibit->delete();
        
        return redirect()->route('museums.exhibits.index', $museum)
            ->with('success', 'Экспонат успешно удален');
    }
}