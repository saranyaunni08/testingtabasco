<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    // <div class="container">
    //     <h1>Add New Building</h1>
    //     <form method="POST">
    //         @csrf
    //         <div class="form-group">
    //             <label for="name">Building Name</label>
    //             <input type="text" class="form-control" id="name" name="name" required>
    //         </div>
    //         <div class="form-group">
    //             <label for="address">Address</label>
    //             <input type="text" class="form-control" id="address" name="address" required>
    //         </div>
    //         <div class="form-group">
    //             <label for="floors">Number of Floors</label>
    //             <input type="number" class="form-control" id="floors" name="floors" min="1" required>
    //         </div>
    //         <div class="form-group">
    //             <label for="amenities">Amenities</label>
    //             <input type="text" class="form-control" id="amenities" name="amenities">
    //         </div>

    //         <h2>Add Rooms</h2>
    //         <div id="rooms">
              
    //         </div>
    //         <button type="button" class="btn btn-primary" onclick="addRoom()">Add Room</button>

    //         <button type="submit" class="btn btn-success mt-3">Save Building</button>
    //     </form>
    // </div>

    // <script>
    //     let roomCount = 1;

    //     function addRoom() {
    //         roomCount++;
    //         let html = `
    //             <div class="form-row mt-3" id="room${roomCount}">
    //                 <div class="col">
    //                     <input type="text" class="form-control" name="rooms[${roomCount}][room_number]" placeholder="Room Number" required>
    //                 </div>
    //                 <div class="col">
    //                     <select class="form-control" name="rooms[${roomCount}][type]" required>
    //                         <option value="">Select Room Type</option>
    //                         <option value="Apartment">Apartment</option>
    //                         <option value="Business Space">Business Space</option>
    //                         <!-- Add more room types as needed -->
    //                     </select>
    //                 </div>
    //                 <div class="col">
    //                     <input type="number" class="form-control" name="rooms[${roomCount}][size]" placeholder="Room Size (sq ft)" min="1" required>
    //                 </div>
    //                 <div class="col">
    //                     <select class="form-control" name="rooms[${roomCount}][occupancy_status]" required>
    //                         <option value="">Select Occupancy Status</option>
    //                         <option value="Occupied">Occupied</option>
    //                         <option value="Vacant">Vacant</option>
    //                         <!-- Add more occupancy statuses as needed -->
    //                     </select>
    //                 </div>
    //                 <div class="col">
    //                     <button type="button" class="btn btn-danger" onclick="removeRoom(${roomCount})">Remove</button>
    //                 </div>
    //             </div>
    //         `;
    //         document.getElementById('rooms').insertAdjacentHTML('beforeend', html);
    //     }

    //     function removeRoom(roomId) {
    //         document.getElementById(`room${roomId}`).remove();
    //     }
    // </script>

}
