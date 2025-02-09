<?php

namespace App\Http\Controllers;

use App\Models\ImageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageFileController extends Controller
{
    public function index()
    {
        $images = ImageFile::all();
        return view('image-file.index', compact('images'));
    }

    public function create()
    {
        $image = new ImageFile;
        return view('image-file.form', compact('image'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg|max:10000',
        ]);
    
        if ($request->hasFile('files'))
        {
            foreach ($request->file('files') as $key => $file)
            {
                $fileName = mb_strtolower(time() . '_' . uniqid() . '_' . $key . '.' . $file->getClientOriginalExtension());
                $file->storeAs('image-files', $fileName, 'public');

                ImageFile::create([
                    'image' => $fileName,
                ]);
            }
        }
    
        return redirect()->route('image-file.index')->with('success', 'Imagens salvas com sucesso.');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        $image = ImageFile::findOrfail($id);

        $filePath = 'image-files/' . $image->image;

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        
        $image->delete();

        return redirect()->route('image-file.index')->with('success', 'Dados removidos com sucesso.');
    }
}
