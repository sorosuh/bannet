<?php

namespace App\Http\Controllers\admin;

use App\folder;
use App\Http\Controllers\Controller;
use App\Http\Requests\addProductRequest;
use App\Http\Requests\editProductRequest;
use App\media;
use App\option;
use App\product;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class productController extends Controller
{
    public function index()
    {
        $products = product::paginate(8);
        return view('admin.products.index', compact('products'));
    }

    public function productInfo($id)
    {
        $defaultImg = env('APP_URL') . 'public/files/8d94d0492df410546024e3e8f3294378/img_404.png';

        //-----------------date
        $curentShmasiYear = Jalalian::now()->getYear();
        $month_timestamp = [];
        for ($i = 1; $i <= 13; $i++) {
            $toGregorian = CalendarUtils::toGregorian($curentShmasiYear, $i, 1);
            $month_timestamp [$i] = $GregorianDate = $toGregorian[0] . '-' . $toGregorian[1] . '-' . $toGregorian[2];
        }
        //-------------------

        $sumPrice = floor(DB::table('user_ads')
            ->where('product_id', '=', $id)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->sum('cost'));
        $count = floor(DB::table('user_ads')
            ->where('product_id', '=', $id)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->sum('count'));
        $avgPrice = floor($sumPrice / $count);

        $avgPriceInMonth = [];
        $sumPriceInMonth = [];
        $countInMonth = [];

        for ($i = 1; $i <= 12; $i++) {
            $sumPriceInMonth[$i] = DB::table('user_ads')
                ->where('product_id', '=', $id)
                ->whereDate('created_at', '>', $month_timestamp[$i])
                ->whereDate('created_at', '<', $month_timestamp[$i + 1])
                ->sum('cost');

            $countInMonth[$i] = DB::table('user_ads')
                ->where('product_id', '=', $id)
                ->whereDate('created_at', '>', $month_timestamp[$i])
                ->whereDate('created_at', '<', $month_timestamp[$i + 1])
                ->sum('count');

            if ($countInMonth[$i] > 0) {
                $avgPriceInMonth[$i] = floor($sumPriceInMonth[$i] / $countInMonth[$i]);
            } else {
                $avgPriceInMonth[$i] = $sumPriceInMonth[$i];
            }
        }

        //-----------------------

        $sumOfBid = DB::table('user_ads')
            ->where('product_id', '=', $id)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->count();
        $sumOfBidInMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $sumOfBidInMonth[$i] = DB::table('user_ads')
                ->where('product_id', '=', $id)
                ->whereDate('created_at', '>', $month_timestamp[$i])
                ->whereDate('created_at', '<', $month_timestamp[$i + 1])
                ->count();
        }

        //--------------------------

        $successBid = DB::table('user_ads')
            ->where('product_id', '=', $id)
            ->where('status', 3)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->count();
        $successBidInMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $successBidInMonth[$i] = DB::table('user_ads')
                ->where('product_id', '=', $id)
                ->where('status', 3)
                ->whereDate('created_at', '>', $month_timestamp[$i])
                ->whereDate('created_at', '<', $month_timestamp[$i + 1])
                ->count();
        }

        //-------------------------

        $currentDate = Carbon::now()->toDateString();

        $failBid = DB::table('user_ads')
            ->where('product_id', '=', $id)
            ->whereDate('created_at', '>', $month_timestamp[1])
            ->where(function ($bid) use ($currentDate) {
                $bid->where('status', 4)->orWhere('expired_at', '<', $currentDate);
            })
            ->count();


        $failBidInMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $failBidInMonth[$i] = DB::table('user_ads')
                ->where('product_id', '=', $id)
                ->whereDate('created_at', '>', $month_timestamp[$i])
                ->whereDate('created_at', '<', $month_timestamp[$i + 1])
                ->where(function ($bid) use ($currentDate) {
                    $bid->where('status', 4)->orWhere('expired_at', '<', $currentDate);
                })
                ->count();
        }


        //------------------------


        $product = product::findOrFail($id);
        $seo = DB::table('product_seo')->select('*')->where('product_id', '=', $id)->get();
        if (isset($seo[0])) $seo = $seo[0];
        $productMedia = DB::table('media')
            ->where([
                'owner' => $id,
                'kind' => 1
            ])
            ->get();

        $mediaPath = [];

        foreach ($productMedia as $m) {
            $src = env('APP_URL') . 'public/files/' . media::findOrFail($m->id)->folder->name . "/" . $m->name;
            array_push($mediaPath, $src);
        }


        // dd($mediaPath);
        return view('admin.products.productInfo', compact(
            [
                'product',
                'seo',
                'avgPrice',
                'sumOfBid',
                'successBid',
                'failBid',
                'mediaPath',
                'avgPriceInMonth',
                'sumOfBidInMonth',
                'successBidInMonth',
                'failBidInMonth'
            ]));
    }

    public function deleteProduct($id)
    {
        $product = product::findOrFail($id);
        $product->delete();
        Session::flash('deleteProduct', 'محصول با موفقیت حذف شد.');
        return redirect('/panel/product');
    }

    public function editProduct(editProductRequest $request, $id)
    {

        $product = product::find($id);
        $product->type = $request->type ? $request->type : $product->type;
        $product->model = $request->model ? $request->model : $product->model;
        $product->designed = $request->designed ? $request->designed : $product->designed;
        $product->brand = $request->brand ? $request->brand : $product->brand;
        $product->country = $request->country ? $request->country : $product->country;
        $product->width = $request->width ? $request->width : $product->width;
        $product->diameter = $request->diameter ? $request->diameter : $product->diameter;
        $product->height = $request->height ? $request->height : $product->height;
        $product->tire_height = $request->tire_height ? $request->tire_height : $product->tire_height;
        $product->color = $request->color ? $request->color : $product->color;
        $product->weight = $request->weight ? $request->weight : $product->weight;
        $product->speed = $request->speed ? $request->speed : $product->speed;
        if ($request->front_using == 'on') $product->front_using = 1; else $product->front_using = 0;
        if ($request->back_using == 'on') $product->back_using = 1; else $product->back_using = 0;
        $product->tubeless = $request->tubeless ? $request->tubeless : $product->tubeless;

        $product->save();


        $product_seo = DB::table('product_seo')
            ->where('product_id', '=', $id)
            ->update([
                'desc' => $request->desc,
                'keywords' => $request->keywords
            ]);
        if ($product_seo == 0) {
            $product_seo = DB::table('product_seo')
                ->insert([
                    'product_id' => $product->id,
                    'desc' => $request->desc,
                    'keywords' => $request->keywords
                ]);
        }


        Session::flash('editProduct', 'محصول با موفقیت ویرایش شد');
        return redirect('/panel/product');

    }

    //addProductRequest
    public function addProduct(addProductRequest $request)
    {

        $product = new product();
        $product->type = $request->type;
        $product->model = $request->model;
        $product->designed = $request->designed;
        $product->brand = $request->brand;
        $product->country = $request->country;
        $product->width = $request->width;
        $product->diameter = $request->diameter;
        $product->height = $request->height;
        $product->tire_height = $request->tire_height;
        $product->color = $request->color;
        $product->weight = $request->weight;
        $product->speed = $request->speed;
        if ($request->front_using == 'on') $product->front_using = 1; else $product->front_using = 0;
        if ($request->back_using == 'on') $product->back_using = 1; else $product->back_using = 0;
        $product->tubeless = $request->tubeless;


        $product->save();


        //---------------------store main image
        $file = $request->file('mainMedia');
        $format = $file->guessClientExtension();
        $name = time() . '.' . $format;
        $dir = folder::where('size', '<=', 1000)->first();
        if ($dir == NULL) {
            $dir = $this->generateDIR();
        }
        $media = new media;
        $media->name = $name;
        $media->owner = $product->id;
        $media->kind = 1;    //zero value means that it's for product
        $media->main = 1;
        $file->move('public/files/' . $dir->name, $name);
        ($request->alt != NULL) ? $media->alt = $request->alt : $media->alt = $name;
        $media->folder_id = $dir->id;
        $dir->size++;
        $dir->save();
        $media->save();
        //-------------------store other image

        foreach ($request->otherMedia as $file) {

            $format = $file->guessClientExtension();
            $name = time() . rand(0, 100) . '.' . $format;
            $dir = folder::where('size', '<=', 1000)->first();
            if ($dir == NULL) {
                $dir = $this->generateDIR();
            }
            $media = new media;
            $media->name = $name;
            $media->owner = $product->id;
            $media->kind = 1;    //zero value means that it's for product
            $media->main = 0;
            $file->move('public/files/' . $dir->name, $name);
            ($request->alt != NULL) ? $media->alt = $request->alt : $media->alt = $name;
            $media->folder_id = $dir->id;
            $dir->size++;
            $dir->save();
            $media->save();

        }

        $product_seo = DB::table('product_seo')->insert([
            'product_id' => $product->id,
            'desc' => $request->desc,
            'keywords' => $request->keywords
        ]);

        Session::flash('addProduct', 'محصول با موفقیت اضافه شد');
        return redirect('/panel/product');
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $option = option::find(1);  //id=1 is for validator
        $value = json_decode($option->option_value);
        $validatedData = $request->validate([
            'media' => 'required | mimes:' . $value->mime,
        ]);

        try {
            $file = $request->file('media');
            $format = $file->guessClientExtension();
            $name = time() . '.' . $format;

            $dir = folder::where('size', '<=', 1000)->first();
            if ($dir == NULL) {
                $dir = $this->generateDIR();
            }

            $media = new media;
            $media->name = $name;
            $media->owner = $request->owner;
            $media->kind = (isset($request->kind)) ? $request->kind : 1;    //zero value means that it's for product
            $media->main = (isset($request->main)) ? $request->main : 0;
            if ($media->main == 1) {
                $n = DB::table('media')
                    ->where('owner', $media->owner)
                    ->where('kind', $media->kind)
                    ->where('main', 1)
                    ->count();

                if ($n >= 1) {
                    return response()->json([
                        'message' => 'there is a main picture for this owner'
                    ]);
                }
            }

            $file->move('public/files/' . $dir->name, $name);
            ($request->alt != NULL) ? $media->alt = $request->alt : $media->alt = $name;
            $media->folder_id = $dir->id;
            $dir->size++;
            $dir->save();
            $media->save();

            return response()->json([
                'status' => 'file stored',
            ]);

        } catch (exeption $e) {
            return $e->getMessage();
        }
    }

    public function generateDIR()
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < 3; $i++) {    //generate random string
            $key .= $keys[array_rand($keys)];
        }

        $dir = new folder;
        $dir->name = hash('md5', $key);
        $dir->size = 0;
        $dir->save();
        return $dir;
    }

}
