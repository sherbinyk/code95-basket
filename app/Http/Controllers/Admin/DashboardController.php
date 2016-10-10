<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\facades\View;
use Illuminate\Support\facades\Rediredt;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Sale;
use App\Product;
use App\Invoice;
use App\User;

class DashboardController extends Controller
{
	public function index()
	{
		$now = Carbon::now();

		$sales = Sale::today()->get();

		$critical_products = Product::critical(5)->get();

		$instock_critical_products = $critical_products->where( 'instock_quantity', '>', 0 );

		$outofstock_products = $critical_products->diff( $instock_critical_products );

		$top_seller_sales = $sales->groupBy( 'user_id' )->max();

		$product_sale = $sales->groupBy( 'product_id' )->sort(function($first, $second){ return $first->sum( 'quantity' ) < $second->sum('quantity'); });
		
		return View::make( 'admin.dashboard', compact( 'now', 'sales', 'top_seller_sales', 'product_sale', 'outofstock_products', 'instock_critical_products' ) );
	}
}