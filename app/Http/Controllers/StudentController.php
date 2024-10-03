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

        // Create a base canvas for the card
        $img = Image::canvas(600, 400);

        // Draw background with two colors (simulating a gradient with a split background)
        $img->rectangle(0, 0, 600, 200, function ($draw) {
            $draw->background('#ff9966');  // Top half color
        });

        $img->rectangle(0, 200, 600, 400, function ($draw) {
            $draw->background('#ff5e62');  // Bottom half color
        });

        // Add a border around the card
        $img->rectangle(0, 0, 599, 399, function ($draw) {
            $draw->border(5, '#ffffff');  // White border
        });

        // Add the student’s name in large font at the top center
        $img->text('Student Name: ' . $student->full_name, 300, 50, function ($font) {
            $font->size(30);
            $font->color('#ffffff');  // White text
            $font->align('center');
            $font->valign('top');
        });

        // Add the RFID number in a bold font below the name
        $img->text('RFID: ' . $student->rfid_number, 300, 100, function ($font) {
            $font->size(20);
            $font->color('#ffff00');  // Yellow text
            $font->align('center');
            $font->valign('top');
        });

        // Draw a simulated barcode (using small rectangles to mimic a barcode look)
        for ($i = 0; $i < 10; $i++) {
            $img->rectangle(50 + $i * 20, 300, 60 + $i * 20, 360, function ($draw) use ($i) {
                $draw->background($i % 2 == 0 ? '#000000' : '#ffffff');  // Alternating black and white bars
            });
        }

        // Insert the student's photo or a placeholder image (optional)
        $studentPhoto = Image::make(public_path('images/student_placeholder.png'))->resize(100, 100);
        $img->insert($studentPhoto, 'top-left', 20, 20);  // Top-left corner for the student photo

        // Add your logo (if you have one)
        $logo = Image::make(public_path('images/logo.jpg'))->resize(100, 100);
        $img->insert($logo, 'bottom-right', 20, 20);  // Bottom-right corner for the logo

        // Add a separator line (using rectangle as GD doesn't support line width)
        $img->rectangle(0, 150, 600, 160, function ($draw) {
            $draw->background('#ffffff');  // White separator line
        });

        // Save the image to the public directory
        $path = storage_path('app/public/rfid_cards/' . $student->rfid_number . '.png');
        $img->save($path);

        return response()->download($path);
    }

}
