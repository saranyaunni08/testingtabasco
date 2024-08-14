<?php

// app/Http/Controllers/EditDeleteAuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EditDeleteAuth;
use App\Models\room;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Session;
Use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;





class EditDeleteAuthController extends Controller
{  public function showLogin()
    {
        $title = 'Edit/Delete Authentication'; // Define your title here
        return view('auth.edit_delete_login', compact('title'));
    }
    

    public function authenticate(Request $request, $roomId, $buildingId) {
        $username = $request->input('username');
        $password = $request->input('password');
    
        // Fetch authentication record
        $auth = DB::table('edit_delete_auth')
                    ->where('username', $username)
                    ->first();
    
        // Verify password if record found
        if ($auth && Hash::check($password, $auth->password)) {
            // Store roomId and buildingId in session or pass them as parameters
            $request->session()->put('edit_delete_auth', true);
    
            // Redirect to the edit page with the roomId and buildingId
            $redirectUrl = route('admin.rooms.edit', ['roomId' => $roomId, 'buildingId' => $buildingId]);
            return redirect($redirectUrl)->with('success', 'Authenticated successfully.');
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
    
        // Redirect to the login route
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
    
    

    public function showEditPage(Request $request, $roomId , $building_id)
    {
        session(['building_id' => $building_id]);

        if (!$request->session()->has('edit_delete_auth')) {
            return redirect()->route('admin.edit_delete_auth.show_login');
        }
    
        $room = Room::find($roomId);
        
        if (!$room) {
            return redirect()->back()->withErrors(['Room not found']);
        }
    
        // Return the view without setting headers here
        return view('rooms.edit', compact('room'));
    }
    
    public function deleteShops(Request $request, $roomId, $buildingId)
{
    // Validate credentials
    $credentials = EditDeleteAuth::where('username', $request->username)->first();

    if ($credentials && Hash::check($request->password, $credentials->password)) {
        // Find the room and delete it
        $room = Room::where('id', $roomId)->where('building_id', $buildingId)->first();

        if ($room) {
            $room->delete();
            $redirectUrl = $request->input('redirect_url', route('admin.rooms.index')); // Use an existing route name
            return redirect($redirectUrl)->with('success', 'Room deleted successfully.');
        } else {
            return redirect()->back()->withErrors(['Room not found']);
        }
    } else {
        return redirect()->back()->withErrors(['Invalid credentials']);
    }
}

    public function destroyFlat(Request $request, $roomId, $buildingId)
    {
        // Validate credentials
        $credentials = EditDeleteAuth::where('username', $request->username)->first();
    
        if ($credentials && Hash::check($request->password, $credentials->password)) {
            // Find the room and delete it
            $room = Room::where('id', $roomId)->where('building_id', $buildingId)->first();
    
            if ($room) {
                $room->delete();
                return redirect($request->redirect_url)->with('success', 'Room deleted successfully.');
            } else {
                return redirect($request->redirect_url)->with('error', 'Room not found.');
            }
        } else {
            return redirect($request->redirect_url)->with('error', 'Invalid credentials.');
        }
    }
    
    public function deleteTableSpace(Request $request, $roomId, $buildingId)
    {
        // Check if the user has the authentication session
        if (!$request->session()->has('edit_delete_auth')) {
            return redirect()->route('admin.edit_delete_auth.show_login');
        }
    
        // Find the room with the given IDs
        $room = Room::where('id', $roomId)
                    ->where('building_id', $buildingId)
                    ->first();
    
        // Handle the case where the room is not found
        if (!$room) {
            return redirect()->back()->withErrors(['Room not found']);
        }
    
        // Soft delete the room
        $room->delete();
        $redirectUrl = $request->input('redirect_url', route('admin.table-spaces.index')); // Default to a route if no redirect URL is provided
    
        return redirect($redirectUrl)->with('success', 'Room deleted successfully.');
    }
    
    public function deleteKiosk(Request $request, $roomId, $buildingId)
    {
        // Check if the user has the authentication session
        if (!$request->session()->has('edit_delete_auth')) {
            return redirect()->route('admin.edit_delete_auth.show_login');
        }
    
        // Find the room with the given IDs
        $room = Room::where('id', $roomId)
                    ->where('building_id', $buildingId)
                    ->first();
    
        // Handle the case where the room is not found
        if (!$room) {
            return redirect()->back()->withErrors(['Room not found']);
        }
    
        // Soft delete the room
        $room->delete();
        $redirectUrl = $request->input('redirect_url', route('kiosks.index')); // Default to a route if no redirect URL is provided
    
        return redirect($redirectUrl)->with('success', 'Room deleted successfully.');
    }
    public function deleteChairSpace(Request $request, $roomId, $buildingId)
    {
        // Check if the user has the authentication session
        if (!$request->session()->has('edit_delete_auth')) {
            return redirect()->route('admin.edit_delete_auth.show_login');
        }
    
        // Find the room with the given IDs
        $room = Room::where('id', $roomId)
                    ->where('building_id', $buildingId)
                    ->first();
    
        // Handle the case where the room is not found
        if (!$room) {
            return redirect()->back()->withErrors(['Room not found']);
        }
    
        // Soft delete the room
        $room->delete();
        $redirectUrl = $request->input('redirect_url', route('admin.chair-spaces.index')); // Default to a route if no redirect URL is provided
    
        return redirect($redirectUrl)->with('success', 'Room deleted successfully.');
    }
      
    
    
    
}
