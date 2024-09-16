<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use App\Models\Volunteer;
use App\Models\Collection;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Distribution;
use App\Models\Beneficiary;
use App\Models\DistributionBeneficiary;
use App\Models\DistributionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDF;

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

    public function subscription_reminder() {}


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


    /*  ------------------------- Collections ------------------------------- */

    public function all_collections()
    {
        return Collection::all();
    }

    public function get_collection($id)
    {
        $collection = Collection::find($id);
        if ($collection) {
            return [
                'message' => 'collection found',
                'collection' => $collection
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
                'status' => 'In Progress'
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
                'status' => 'Completed'
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

    public function generate_collection_report($id, Request $request)
    {
        $products = $request->products;
        $quantities = $request->quantities;
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

        $quantity = $request->validate([
            'quantity' => 'required|integer',
        ]);
        for ($i=0; i < $quantity; $i++)
        {
            $product = Product::create($fields);
        }
        return [
            'message' => 'Product created',
            'product' => $product,
            'quantity' => $quantity
        ];
    }


    /*  ------------------------- Distributions ------------------------------- */

    public function all_distributions()
    {
        return Distribution::all();
    }

    public function get_distribution($id)
    {
        $distribution = Distribution::find($id);
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
            'route' => 'required|string',
            'distribution_status' => 'required|string'
        ]);

        $distribution = Distribution::create([
            'scheduled_time' => $request->scheduled_time,
            'route' => $request->route,
            'distribution_status' => $request->distribution_status
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
                'quantity_distributed' => 0
            ]);
        };


        return [
            'message' => 'distribution created',
            'distribution' => $distribution
        ];
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
