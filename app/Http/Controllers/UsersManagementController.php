<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use App\Models\Volunteer;
use App\Models\VolunteerSchedule;
use App\Models\VolunteerAssignment;
use App\Models\Collection;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Distribution;
use App\Models\Beneficiary;
use App\Models\DistributionBeneficiary;
use App\Models\DistributionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Arr;

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
        if ($user) {
            return [
                'message' => 'User found',
                'User' => $user
            ];
        } else {
            return response([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function approve_merchant_subscription($id)
    {
        $user = User::find($id);
        if ($user and $user->membership_status != 'active') {
            $user->membership_status = 'active';
            $user->save();
            return [
                'message' => 'Membership approved',
                'User' => $user
            ];
        } else if (!$user) {
            return response([
                'message' => 'User not found'
            ], 404);
        } else {
            return [
                'message' => 'Membership already approved'
            ];
        }
    }

    public function reject_merchant_subscription($id)
    {

        $user = User::find($id);
        if ($user and $user->membership_status != 'rejected') {
            $user->membership_status = 'rejected';
            $user->save();
            return [
                'message' => 'Membership rejected',
                'User' => $user
            ];
        } else if (!$user) {
            return response([
                'message' => 'User not found'
            ], 404);
        } else {
            return [
                'message' => 'Membership already rejected'
            ];
        }
    }

    public function delete_merchant($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return [
                'message' => 'User deleted',
                'User' => $user
            ];
        } else {
            return response([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function request_service($id, Request $request)
    {
        $fields = $request->validate([
            'task_type' => 'required',
            'start_time' => 'required',
        ]);

        $start_hour = Carbon::parse($request->start_time)->hour;
        $possible_volunteers = Volunteer::where('availability_start', '<=', $start_hour)
            ->where('availability_end', '>', $start_hour)
            ->get();

        $possible_volunteers_ = [];
        foreach ($possible_volunteers as $volunteer) {
            $skill = Skill::find($volunteer->skill_id);
            if ($skill->name == $request->task_type) {
                $possible_volunteers_[] = $volunteer;
            }
        }

        $pool_of_choice = [];
        foreach ($possible_volunteers_ as $volunteer) {
            if ($this->volunteer_is_available($volunteer->id, $request->start_time)) {
                $pool_of_choice[] = $volunteer;
            }
        }

        //print_r($possible_volunteers);
        if (count($pool_of_choice) > 0) {
            $chosen_volunteer = Arr::random($pool_of_choice);
            $schedule = VolunteerSchedule::create([
                'volunteer_id' => $chosen_volunteer->id,
                'schedule_day' => (new DateTime($request->start_time))->format("Y-m-d   "),
                'schedule_status' => "Planned"
            ]);

            $assignment = VolunteerAssignment::create([
                'user_id' => $id,
                'schedule_id' => $schedule->id,
                'task_type' => "Plumber", //$request->task_type,
                'start_time' => $request->start_time,
                'assignment_status' => 'Assigned'
            ]);

            return [
                'message' => 'Service requested, volunteer selected',
                'volunteer' => $chosen_volunteer
            ];
        } else {
            return [
                'message' => 'No volunteer found for service'
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
        if ($skill) {
            return [
                'message' => 'Skill found',
                'Skill' => $skill
            ];
        } else {
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
        if ($volunteer) {
            return [
                'message' => 'volunteer found',
                'volunteer' => $volunteer
            ];
        } else {
            return response([
                'message' => 'volunteer not found'
            ], 404);
        }
    }

    public function approve_volunteer_subscription($id)
    {
        $volunteer = Volunteer::find($id);
        if ($volunteer and $volunteer->membership_status != 'active') {
            $volunteer->membership_status = 'active';
            $volunteer->save();
            return [
                'message' => 'Membership approved',
                'Volunteer' => $volunteer
            ];
        } else if (!$volunteer) {
            return response([
                'message' => 'Volunteer not found'
            ], 404);
        } else {
            return [
                'message' => 'Membership already approved'
            ];
        }
    }

    public function reject_volunteer_subscription($id)
    {
        $volunteer = Volunteer::find($id);
        if ($volunteer and $volunteer->membership_status != 'rejected') {
            $volunteer->membership_status = 'rejected';
            $volunteer->save();
            return [
                'message' => 'Membership rejected',
                'Volunteer' => $volunteer
            ];
        } else if (!$volunteer) {
            return response([
                'message' => 'Volunteer not found'
            ], 404);
        } else {
            return [
                'message' => 'Membership already rejected'
            ];
        }
    }

    public function delete_volunteer($id)
    {
        $volunteer = Volunteer::find($id);
        if ($volunteer) {
            $volunteer->delete();
            return [
                'message' => 'Volunteer deleted',
                'Volunteer' => $volunteer
            ];
        } else {
            return response([
                'message' => 'Volunteer not found'
            ], 404);
        }
    }

    public function get_schedule($id)
    {
        $schedule = VolunteerSchedule::where('volunteer_id', $id)
            ->where('schedule_day', date('Y-m-d'))
            ->first();
        if ($schedule) {
            $assignments = VolunteerAssignment::where('schedule_id', $schedule->id)->get();
            return [
                'message' => 'Schedule found',
                'schedule' => $schedule,
                'assignments' => $assignments
            ];
        } else {
            return response([
                'message' => 'No schedule found today'
            ], 404);
        }
    }

    public function get_all_schedules($id)
    {
        $schedules = VolunteerSchedule::where('volunteer_id', $id)->get();
        $list = [];
        foreach ($schedules as $schedule) {
            $assignments = VolunteerAssignment::where('schedule_id', $schedule->id)->get();
            $list[] = [
                'schedule' => $schedule,
                'assignments' => $assignments
            ];
        }

        if ($list != []) {
            return [
                'message' => 'All schedules',
                'schedules' => $list
            ];
        } else {
            return [
                'message' => 'No schedule found'
            ];
        }
    }

    public function volunteer_is_available($id, $dateTime)
    {
        $volunteer = Volunteer::find($id);
        $schedules = VolunteerSchedule::where('volunteer_id', $id)->get();
        foreach ($schedules as $schedule) {
            $assignments = VolunteerAssignment::where('schedule_id', $schedule->id)->get();
            foreach ($assignments as $assignment) {
                $interval = (new DateTime($assignment->start_time))->diff(new DateTime($dateTime));
                $hoursDifference = ($interval->days * 24) + $interval->h;
                if ($interval->invert) {
                    $hoursDifference = -$hoursDifference;
                }
                if (abs($hoursDifference) < 2) {
                    return False;
                }
            }
        }
        return True;
    }

    public function recruit_volunteer($type, $start_time)
    {

        $start_hour = Carbon::parse($start_time)->hour;
        $possible_volunteers = Volunteer::where('availability_start', '<=', $start_hour)
            ->where('availability_end', '>', $start_hour)
            ->get();

        $pool_of_choice = [];
        foreach ($possible_volunteers as $volunteer) {
            if ($this->volunteer_is_available($volunteer->id, $start_time)) {
                $pool_of_choice[] = $volunteer;
            }
        }

        //print_r($possible_volunteers);
        if (count($pool_of_choice) > 0) {
            $chosen_volunteer = Arr::random($pool_of_choice);
            $schedule = VolunteerSchedule::where('volunteer_id', $chosen_volunteer->id)
                                         ->where('schedule_day', (new DateTime($start_time))->format("Y-m-d"))
                                         ->first();
            if(!$schedule)
            {
                $schedule = VolunteerSchedule::create([
                    'volunteer_id' => $chosen_volunteer->id,
                    'schedule_day' => (new DateTime($start_time))->format("Y-m-d"),
                    'schedule_status' => "Planned"
                ]);
    
                $assignment = VolunteerAssignment::create([
                    'schedule_id' => $schedule->id,
                    'task_type' => $type,
                    'start_time' => $start_time,
                    'assignment_status' => 'Assigned'
                ]);
            } else 
            {
                $assignment = VolunteerAssignment::create([
                    'schedule_id' => $schedule->id,
                    'task_type' => $type,
                    'start_time' => $start_time,
                    'assignment_status' => 'Assigned'
                ]);
            }
            //echo $chosen_volunteer->id;
        }
    }

    /*  ------------------------- Collections ------------------------------- */

    public function all_collections()
    {
        return Collection::all();
    }

    public function get_collection($id)
    {
        $collection = Collection::find($id);
        if ($collection) {
            $collection['products'] = $collection->products;
            return [
                'message' => 'collection found',
                'collection' => $collection,
            ];
        } else {
            return response([
                'message' => 'collection not found'
            ], 404);
        }
    }

    public function create_collection(Request $request)
    {
        $fields = $request->validate([
            'user_ids' => 'required|string',
            'scheduled_time' => 'required',
            'volunteers_count' => 'required',
            'route' => 'required|string',
        ]);

        $collection = Collection::create($fields);

        for($i=0; $i < $collection->volunteers_count; $i++)
        {
            $this->recruit_volunteer("Collection", $request->scheduled_time);
        }

        return [
            'message' => 'Collection created',
            'collection' => $collection
        ];
    }

    public function start_collection($id)
    {
        $collection = Collection::find($id);
        if ($collection) {
            $collection->update([
                'collection_status' => 'In Progress'
            ]);

            return [
                'message' => 'Collection started',
                'collection' => $collection
            ];
        } else {
            return response([
                'message' => 'Collection not found'
            ], 404);
        }
    }

    public function close_collection($id)
    {
        $collection = Collection::find($id);
        if ($collection) {
            $collection->update([
                'collection_status' => 'Completed'
            ]);
            return [
                'message' => 'Collection closed',
                'collection' => $collection
            ];
        } else {
            return response([
                'message' => 'Collection not found'
            ], 404);
        }
    }

    public function delete_collection($id)
    {
        $collection = Collection::find($id);
        if ($collection) {
            $collection->delete();
            return [
                'message' => 'Collection deleted',
                'collection' => $collection
            ];
        } else {
            return response([
                'message' => 'Collection not found'
            ], 404);
        }
    }

    public function add_product_to_collection(Request $request, $id)
    {
        $collection = Collection::find($id);
        if ($collection) {
            $fields = $request->validate([
                'product_name' => 'required|string',
                'barcode' => 'nullable',
                'category' => 'nullable',
                'expiration_date' => 'nullable',
                'user_id' => 'required|integer',
                'stock_id' => 'required|integer',
                'quantity_collected' => 'required|integer',
            ]);

            $quantity = $fields['quantity_collected'];
            unset($fields['quantity_collected']);

            $product = Product::create($fields);
            $collection->products()->create([
                'product_id' => $product['id'],
                'quantity_collected' => $quantity,
            ]);
            return [
                'message' => "$quantity entries of {$product->product_name} added to collection",
                'collection' => $collection,
                'product' => $product,
            ];
        } else {
            return response([
                'message' => 'Collection not found'
            ], 404);
        }
    }

    public function generate_collection_report($id, Request $request)
    {
        $request->validate([
            'nb_volunteers' => 'required|integer',
            'products' => 'required',
            'quantities' => 'required'
        ]);

        $products = $request->products; // array of product names (strings)
        $quantities = $request->quantities; // array of quantity of each product (ints)
        $nb_volunteers = $request->nb_volunteers;
        $collection = Collection::find($id);
        $data = [
            'nb_volunteers' => $nb_volunteers,
            'collection' => $collection,
            'products' => $products,
            'quantities' => $quantities
        ];
        $pdf = PDF::loadView('collection_report', $data);
        $report_name = "Collection_" . $collection->scheduled_time . "_report.pdf";
        return $pdf->download($report_name);
    }


    /*  ------------------------- Stocks ------------------------------- */
    public function all_stocks()
    {
        return Stock::all();
    }

    public function get_product_quantity($id, $product_name)
    {
        $stock = Stock::find($id);
        $products = $stock->products;
        $quantity = 0;

        foreach ($products as $product) {
            if ($product->product_name == $product_name) {
                $quantity += 1;
            }
        }
        return $quantity;
    }

    public function get_stock($id)
    {
        $stock = Stock::find($id);
        if ($stock) {
            $products = [];
            foreach ($stock->products as $product) {
                $products[] = [
                    'id' => $product->id,
                    'product' => $product->product_name,
                    'quantity' => $this->get_product_quantity($id, $product->product_name)
                ];
            };
            return [
                'message' => 'stock found',
                'stock' => $stock,
                'stock_products' => $products
            ];
        } else {
            return response([
                'message' => 'stock not found'
            ], 404);
        }
    }

    public function find_product($id, Request $request)
    {
        $searched_term = $request->searched_term;
        $products = Product::where('product_name', 'LIKE', "%{$searched_term}%")
            ->where('stock_id', $id)
            ->get();
        return [
            'message' => 'Found products',
            'products' => $products,
        ];
    }

    public function create_stock(Request $request)
    {
        $field = $request->validate([
            'warehouse_location' => 'required|string',
        ]);

        $stock = Stock::create($field);
        return [
            'message' => 'Stock created',
            'stock' => $stock
        ];
    }



    /*  ------------------------- Products ------------------------------- */
    public function all_products()
    {
        return Product::all();
    }

    public function get_product($id)
    {
        $product = Product::find($id);
        if ($product) {
            return [
                'message' => 'product found',
                'product' => $product
            ];
        } else {
            return response([
                'message' => 'product not found'
            ], 404);
        }
    }

    public function create_product(Request $request)
    {
        $fields = $request->validate([
            'product_name' => 'required|string',
            'barcode' => 'nullable',
            'category' => 'nullable',
            'expiration_date' => 'nullable',
            'user_id' => 'required|integer',
            'stock_id' => 'required|integer'
        ]);

        $product = Product::create($fields);

        return [
            'message' => 'Product created',
            'product' => $product
        ];
    }


    /*  ------------------------- Distributions ------------------------------- */

    public function all_distributions()
    {
        return Distribution::all();
    }

    public function get_distribution($id)
    {
        $distribution = Distribution::with(['beneficiaries', 'products'])->find($id);

        if ($distribution) {
            return [
                'message' => 'distribution found',
                'distribution' => $distribution
            ];
        } else {
            return response([
                'message' => 'distribution not found'
            ], 404);
        }
    }

    public function create_distribution(Request $request)
    {
        $fields = $request->validate([
            'beneficiary_ids' => 'required', // an array
            'product_ids' => 'required', // an array
            'scheduled_time' => 'required',
            'volunteers_count' => 'required|int',
            'route' => 'required|string',
        ]);

        $distribution = Distribution::create([
            'scheduled_time' => $request->scheduled_time,
            'route' => $request->route,
            'distribution_status' => 'Scheduled',
            'volunteers_count' => $request->volunteers_count,
        ]);

        $distribution->refresh();
        foreach ($request->beneficiary_ids as $beneficiary_id) {
            $distribution_beneficiary = DistributionBeneficiary::create([
                'distribution_id' => $distribution->id,
                'beneficiary_id' => $beneficiary_id,
            ]);
        };

        foreach ($request->product_ids as $product_id) {
            $distribution_product = DistributionProduct::create([
                'distribution_id' => $distribution->id,
                'product_id' => $product_id,
                'quantity_distributed' => 0,
            ]);
        };

        for($i=0; $i < $distribution->volunteers_count; $i++)
        {
            $this->recruit_volunteer("Distribution", $request->scheduled_time);
        }


        return [
            'message' => 'distribution created',
            'distribution' => $distribution
        ];
    }

    public function start_distribution($id)
    {
        $distribution = Distribution::find($id);
        if ($distribution) {
            $distribution->update([
                'distribution_status' => "In Progress"
            ]);
            return [
                'message' => 'distribution started',
                'distribution' => $distribution
            ];
        } else {
            return response([
                'message' => 'distribution not found'
            ], 404);
        }
    }

    public function close_distribution($id)
    {
        $distribution = Distribution::find($id);
        if ($distribution) {
            $distribution->update([
                'distribution_status' => "Completed"
            ]);
            return [
                'message' => 'distribution closed',
                'distribution' => $distribution
            ];
        } else {
            return response([
                'message' => 'distribution not found'
            ], 404);
        }
    }

    public function delete_distribution($id)
    {
        $distribution = Distribution::find($id);
        if ($distribution) {
            $distribution->delete();
        } else {
            return response([
                'message' => 'distribution not found'
            ], 404);
        }
    }


    public function generate_distribution_report($id, Request $request)
    {
        // $request->validate([
        //     'products' => 'required',
        //     'beneficiaries_ids' => 'required',
        //     'quantities' => 'required'
        // ]);

        $beneficiaries_ids = $request->beneficiaries_ids; //array of beneficiary ids
        $products = $request->products; //array of product names
        $quantities = $request->quantities; //array of products' quantities
        $distribution = Distribution::find($id);
        $data = [
            'distribution' => $distribution,
            'products' => $products,
            'quantities' => $quantities,
            'beneficiaries_ids' => $beneficiaries_ids,
        ];

        $pdf = PDF::loadView('distribution_report', $data);
        $report_name = "Distribution_" . $distribution->scheduled_time . "_report.pdf";
        return $pdf->download($report_name);
    }

    /*  ------------------------- Beneficiaries ------------------------------- */

    public function all_beneficiaries()
    {
        return Beneficiary::all();
    }

    public function get_beneficiary($id)
    {
        $beneficiary = Beneficiary::find($id);
        if ($beneficiary) {
            return [
                'message' => 'beneficiary found',
                'beneficiary' => $beneficiary
            ];
        } else {
            return response([
                'message' => 'beneficiary not found'
            ], 404);
        }
    }
}
