<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\media;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class productController extends Controller
{

    public function productCard(Request $request)
    {
        $defaultImg = '127.0.0.1:8000/public/files/8d94d0492df410546024e3e8f3294378/img_404.png';

        try {
            $city = $request->city;
            $type = $request->type;
            $limit = $request->limit;
            $offset = $request->offset;
            $where = $request->where;

            if (isset($request->where)) {

                $products = DB::table('product')
                    ->select('id', 'type', 'model', 'width', 'height', 'diameter', 'designed', 'country')
                    ->where('type', $type)
                    ->where('model', 'like', '%' . $where . '%')
                    ->orWhere('designed', 'like', '%' . $where . '%')
                    ->orWhere('brand', 'like', '%' . $where . '%')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

            } else {
                $products = DB::table('product')
                    ->select('id', 'type', 'model', 'width', 'height', 'diameter', 'designed', 'country')
                    ->where('type', $type)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }

            foreach ($products as $product) {
                $sumOfProductCount = DB::table('user_ads')->where('product_id', $product->id)->where('city', $city)->sum('count');
                $product->ads = DB::table('user_ads')->where('product_id', $product->id)->count();
                if ($sumOfProductCount > 0) {

                    $sumOfProductCost = DB::table('user_ads')->where('product_id', $product->id)->where('city', $city)->sum('cost');
                    $productAvg = $sumOfProductCost / $sumOfProductCount;
                    $product->average = floor($productAvg);
                } else {
                    $productAvg = 0;
                    $product->average = $productAvg;
                }

                $media = DB::table('media')->where('owner', $product->id)->where('main', 1)->get();

                if (!$media->isEmpty()) {
                    $mediaId = $media[0]->id;
                    $src = env('APP_URL') . 'public/files/' . media::find($mediaId)->folder->name . "/" . media::find($mediaId)->name;
                    $product->src = $src;
                } else {
                    $product->src = $defaultImg;
                }

            }
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }


    }
}
