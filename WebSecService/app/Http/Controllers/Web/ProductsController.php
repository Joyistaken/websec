<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;

class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {

		$query = Product::select("products.*");

		$query->when($request->keywords, 
		fn($q)=> $q->where("name", "like", "%$request->keywords%"));

		$query->when($request->min_price, 
		fn($q)=> $q->where("price", ">=", $request->min_price));
		
		$query->when($request->max_price, fn($q)=> 
		$q->where("price", "<=", $request->max_price));
		
		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null) {
        // Only Employees and Admins can edit products
		if(!auth()->user()->hasAnyRole(['Employee', 'Admin'])) abort(401);

		$product = $product??new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null) {
		// Only Employees and Admins can save products
		if(!auth()->user()->hasAnyRole(['Employee', 'Admin'])) abort(401);

		$this->validate($request, [
	        'code' => ['required', 'string', 'max:32'],
	        'name' => ['required', 'string', 'max:128'],
	        'model' => ['required', 'string', 'max:256'],
	        'description' => ['required', 'string', 'max:1024'],
	        'price' => ['required', 'numeric', 'min:0.01'],
	        'stock_quantity' => ['required', 'integer', 'min:0'],
	    ]);

		$product = $product??new Product();
		$product->fill($request->all());
		$product->save();

		return redirect()->route('products_list');
	}

	public function delete(Request $request, Product $product) {
		// Check for delete_products permission
		if(!auth()->user()->hasPermissionTo('delete_products')) abort(401);

		$product->delete();

		return redirect()->route('products_list');
	}
	
	public function buy(Request $request, Product $product)
	{
	    // Must be logged in and have buy_products permission
	    if(!auth()->check() || !auth()->user()->hasPermissionTo('buy_products')) {
	        return redirect()->route('login');
	    }
	    
	    $user = auth()->user();
	    
	    // Check if product is in stock
	    if($product->stock_quantity <= 0) {
	        return redirect()->back()
	            ->withErrors('This product is out of stock');
	    }
	    
	    // Check if user has enough credit
	    if($user->credit < $product->price) {
	        return redirect()->route('products.insufficient_credit', ['product' => $product->id]);
	    }
	    
	    DB::beginTransaction();
	    try {
	        // Reduce user's credit
	        $user->credit -= $product->price;
	        $user->save();
	        
	        // Reduce product stock
	        $product->stock_quantity -= 1;
	        $product->save();
	        
	        // Create purchase record
	        Purchase::create([
	            'user_id' => $user->id,
	            'product_id' => $product->id,
	            'price_paid' => $product->price
	        ]);
	        
	        DB::commit();
	        
	        return redirect()->route('purchases.list')
	            ->with('success', "You have successfully purchased {$product->name}");
	    } catch (\Exception $e) {
	        DB::rollback();
	        return redirect()->back()
	            ->withErrors('An error occurred during the purchase. Please try again.');
	    }
	}
	
	public function insufficientCredit(Request $request, Product $product)
	{
	    return view('products.insufficient_credit', compact('product'));
	}
	
	public function listPurchases(Request $request)
	{
	    // Must be logged in and have view_purchases permission
	    if(!auth()->check() || !auth()->user()->hasPermissionTo('view_purchases')) {
	        return redirect()->route('login');
	    }
	    
	    $user = auth()->user();
	    $purchases = $user->purchases()->with('product')->latest()->get();
	    
	    return view('products.purchases', compact('purchases'));
	}
} 