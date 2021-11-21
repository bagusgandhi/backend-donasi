<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index(){
        $campaigns = Campaign::latest()->when(request()->q, function($campaigns){
            $campaigns = $campaigns->where('title', 'like', '%'. request()->q . '%');
        })->paginate(10);
        return view('admin.campaign.index', compact('campaigns'));
    }
    public function create(){
        $categories = Category::latest()->get();
        return view('admin.campaign.create', compact('categories'));
    }
    public function store(Request $request){
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'title' => 'required',
            'category_id'       => 'required',
            'target_donation'   => 'required|numeric',
            'max_date'          => 'required',
            'description'       => 'required'
        ]);
        $image = $request->file('image');
        $image->storeAs('public/campaigns', $image->hashName());

        $campaigns = Campaign::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'target_donation' => $request->target_donation,
            'max_date' => $request->max_date,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
            'image' => $image->hashName()
        ]);

        if($campaigns){
            return view('admin.campaign.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            return view('admin.campaign.index')->with(['error' => 'Data Gagal Disimpan!']);

        }
    }
    public function edit(Campaign $campaign){
        $categories = Category::latest()->get();
        return view('admin.campaign.edit', compact('campaign', 'categories'));
    }
    public function update(Request $request, Campaign $campaign){
        $this->validate($request, [
            'title' => 'required',
            'category_id'       => 'required',
            'target_donation'   => 'required|numeric',
            'max_date'          => 'required',
            'description'       => 'required'
        ]);
        if($request->file('image') == ''){
            $campaign = Campaign::findOrFail($campaign->id);
            $campaign->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'target_donation' => $request->target_donation,
                'max_date' => $request->max_date,
                'description' => $request->description,
                'user_id' => auth()->user()->id,
            ]);
        }else{
            Storage::disk('local')->delete('publi/campaigns/'.basename($campaign->image));
            $image = $request->file('image');
            $image->storeAs('public/campaigns', $image->hashName());
            $campaign = Campaign::findOrFail($campaign->id);
            $campaign->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'target_donation' => $request->target_donation,
                'max_date' => $request->max_date,
                'description' => $request->description,
                'user_id' => auth()->user()->id,
                'image' => $image->hashName()
            ]);
        }
        
        if($campaign){
            return redirect()->route('admin.campaign.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            return redirect()->route('admin.campaign.index')->with(['error' => 'Data Gagal Diupdate!']);
            
        }
    }
    
    public function destroy($id){
        $campaign = Campaign::findOrFail($id);
        Storage::disk('local')->delete('publi/campaigns/'.basename($campaign->image));
        $campaign->delete();
        if($campaign){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}   
