<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Auth;
use Response;
use App\User;


class ShopController extends Controller
{
    public function index()
    {
        return view("shop");
    }

    public function index_admin()
    {
        $products = Product::all();
        $users = User::all();

        return view('admin', [
            "users" => $users,
            "products" => $products
        ]);
    }

    public function create_product(Request $req)
    {
        $product = new Product;
        $product->desc = $req->content;
        $product->category = "tutorial";
        $product->name = $req->name;
        $product->price = $req->price;
        
        if ($req->file('file'))
        {
            $file = $req->file('file');
            $filename = time() . '.' . $req->file('file')->extension();
            $filePath = public_path() . '/download/';
            $file->move($filePath, $filename);
            $product->file = $filename;
        }

        $product->save();
        return back();
    }

    public function get_products()
    {
        $products = Product::all();
        return response()->json(["data" => $products]);
    }

    public function purchase_product(Request $req)
    {
        $product_id = $req->item_id;
        $product = Product::find($product_id)->toArray();
        $user = Auth::user()->toArray();

        if (empty($product)) return response()->json(["status" => "Продукт не был найден..."]);
        if ((float)$product["price"] > (float)$user["points"]) return back()->with("error", "Не достаточно баллов...");

        if (is_null($product["file"])) return back()->with('error', 'Something went wrong...');
        $file = public_path("download/".$product["file"]);
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        
        $floor = (float)$user["points"] - (float)$product["price"];
        
        $_user = User::find($user["id"]);
        $_user->points = $floor;
        $_user->save();

        return response()->download($file, $product["name"].".pdf", $headers);
    }

    public function delete_product(Request $req)
    {
        $status = Product::find($req->item_id)->delete();
        return back()->with("status", "Successfully deleted.");
    }
}
