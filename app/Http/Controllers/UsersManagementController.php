<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersManagementController extends Controller
{

    /*  ------------------------- Merchants ------------------------------- */
    public function all_users()
    {
        return User::all();
    }

    public function get_user($id)
    {
        $user = User::find($id);
        if($user)
        {
            return [
                'message' => 'User found',
                'User' => $user
            ];
        }
        else
        {
            return response([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function approve_subscription($id)
    {
        $user = User::find($id);
        if($user and $user->membership_status != 'active') {
            $user->membership_status = 'active';
            $user->save();  
            return [
                'message' => 'Membership approved',
                'User' => $user
            ];
        }
        else if (!$user)
        {
            return response([
                'message' => 'User not found'
            ], 404);
        }
        else
        {
            return [
                'message' => 'Membership already approved'
            ];
        }

    }


    /*  ------------------------- Skills ------------------------------- */
    public function all_skills()
    {
        return Skill::all();
    }

    public function get_skill($id)
    {
        $skill = Skill::find($id);
        if($skill)
        {
            return [
                'message' => 'Skill found',
                'Skill' => $skill
            ];
        }
        else
        {
            return response([
                'message' => 'Skill not found'
            ], 404);
        }
    }


    /*  ------------------------- Volunteers ------------------------------- */
    public function all_volunteers()
    {
        return Volunteer::all();
    }

    public function get_volunteer($id)
    {
        $volunteer = Volunteer::find($id);
        if($volunteer)
        {
            return [
                'message' => 'volunteer found',
                'volunteer' => $volunteer
            ];
        }
        else
        {
            return response([
                'message' => 'volunteer not found'
            ], 404);
        }
    }
}
