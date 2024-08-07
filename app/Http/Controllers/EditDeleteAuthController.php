<?php

// app/Http/Controllers/EditDeleteAuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EditDeleteAuth;
use App\Models\room;
use Illuminate\Support\Facades\Hash; 


class EditDeleteAuthController extends Controller
{  public function showLogin()
    {
        $title = 'Edit/Delete Authentication'; // Define your title here
        return view('auth.edit_delete_login', compact('title'));
    }
    

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $user = EditDeleteAuth::where('username', $credentials['username'])->first();
    
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $request->session()->put('edit_delete_auth', true);
            return redirect($request->input('redirect_url'))->with('success', 'Authenticated successfully');
        }
    
        return redirect()->back()->withErrors(['Invalid credentials']);
    }
    
    public function logout(Request $request)
{
    // Store the current building_id in the session or another method to capture it
    $buildingId = $request->session()->get('building_id', null);

    // Perform the logout logic
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirect to the desired route
    return redirect()->route('flats.index', ['building_id' => $buildingId]);
}
    
    

    public function showEditPage(Request $request, $roomId , $building_id)
    {
        session(['building_id' => $building_id]);

        if (!$request->session()->has('edit_delete_auth')) {
            return redirect()->route('edit_delete_auth.show_login');
        }
    
        $room = Room::find($roomId);
        
        if (!$room) {
            return redirect()->back()->withErrors(['Room not found']);
        }
    
        // Return the view without setting headers here
        return view('rooms.edit', compact('room'));
    }
    
    public function deleteRoom(Request $request, $roomId)
    {
        if (!$request->session()->has('edit_delete_auth')) {
            return redirect()->route('edit_delete_auth.show_login');
        }

        // Find and delete the room
        $room = Room::find($roomId);
        
        if (!$room) {
            return redirect()->back()->withErrors(['Room not found']);
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully');
    }
    
}
