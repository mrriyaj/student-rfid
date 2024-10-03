<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // List all students (optional)
    public function index()
    {
        $students = Student::all();
        return Inertia::render('Students/Index', [
            'students' => $students
        ]);
    }

    // Display the form to create a new student
    public function create()
    {
        return Inertia::render('Students/Create');
    }

    // Store the newly created student in the database
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'rfid_number' => 'required|unique:students',
        ]);

        Student::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'rfid_number' => $request->rfid_number,
            'status' => 'active',  // Default status
        ]);

        return redirect()->route('students.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }

    public function generateCard($id)
    {
        $student = Student::findOrFail($id);

        // Create an RFID card image
        $img = Image::canvas(300, 150, '#ffffff');

        // Add text and other elements to the image
        $img->text('Student Name: ' . $student->full_name, 150, 50, function ($font) {
            $font->size(20);
            $font->color('#000000');
            $font->align('center');
            $font->valign('center');
        });

        $img->text('RFID: ' . $student->rfid_number, 150, 100, function ($font) {
            $font->size(15);
            $font->color('#000000');
            $font->align('center');
            $font->valign('center');
        });

        // Save the image
        $path = storage_path('app/public/rfid_cards/' . $student->rfid_number . '.png');
        $img->save($path);

        return response()->download($path);
    }
}
