<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function brand(){
                $brands = Brand::orderby('id','DESC')->paginate(10);
        return view('admin.brands.index',compact('brands'));
    }
    public function brandAdd(){
        return view('admin.brands.brandadd');
    }
    public function brandStore(Request $request){
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status' => 'nullable',

        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->slug);
        $brand->status = $request->has('status') ? 1 : 0;

        if($request->hasFile('image')){
            $image =$request->image;
            $imageName = time() . '_' . uniqid(). "." . $request->image->extension();
            $this->generatethumbnail($image,$imageName,'uploads/brands',124,124);
            $request->image->move(public_path('uploads/brands'),$imageName);
            $brand->image = $imageName;
        }
        $brand->save();
        return redirect()->route('admin.brands')->with('success','Brands Added Sucessfully');


    }

  


    public function generatethumbnail($image,$imageName,$folder,$width =124,$height=124){
            $thumbnailpath = public_path($folder.'/thumbnail');
            if(!file_exists($thumbnailpath)){
                mkdir($thumbnailpath,0755,true);
            }
            image::decode($image)->resize($width,$height)->save($thumbnailpath. '/' . $imageName);
    }


      public function brandEdite($id){
        
      $brand = Brand::findorfail($id);

    return view('admin.brands.brandedit',compact('brand'));

    }
    public function brandUpdate(Request $request,$id){
       

           $brand = Brand::findorfail($id);
        $brand->name = $request->name;
        $brand->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->slug);
        $brand->status = $request->has('status') ? 1 : 0;

        if($request->hasFile('image')){
            if($brand->image){
                @unlink(public_path('uploads/brands/'.$brand->image));
                @unlink(public_path('uploads/brands/thumbnail/'.$brand->image));
                
            }
            $image =$request->image;
            $imageName = time() . '_' . uniqid(). "." . $request->image->extension();
            $this->generatethumbnail($image,$imageName,'uploads/brands',124,124);
            $request->image->move(public_path('uploads/brands'),$imageName);
            $brand->image = $imageName;
        }
        $brand->save();
        return redirect()->route('admin.brands')->with('success','Brands Updated Sucessfully');


    }


}
