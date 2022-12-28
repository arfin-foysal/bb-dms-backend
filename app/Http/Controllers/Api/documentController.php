<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\document;
use App\Models\sub_catagory;
use App\Models\sub_sub_catagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class documentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authId=Auth::user()->id;
        $data = document::where('user_id','=', $authId)->get();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $document = new document();
            $request->validate([
                'name' => 'required',
                'user_id' => 'required',
                'catagory_id' => 'required',
                'sub_catagory_id' => 'nullable',
                'sub_sub_catagory_id' => 'nullable',
                'description' => 'nullable',
                'status' => 'required',
                'file' => 'required|mimes:csv,txt,xlx,xls,pdf,docx,doc,jpg,png,jpeg,gif,svg',
            ]);

            $filename = "";
            if ($request->hasFile('file')) {
                $filename = $request->file('file')->store('file', 'public');
            } else {
                $filename = Null;
            }
            $document->name = $request->name;
            $document->user_id = $request->user_id;
            $document->catagory_id = $request->catagory_id;
            $document->sub_catagory_id = $request->sub_catagory_id;
            $document->sub_sub_catagory_id = $request->sub_sub_catagory_id;
            $document->description = $request->description;
            // $document->admin_status = $request->admin_status;
            $document->status = $request->status;
            $document->file = $filename;
            $document->save();

            $data = [
                'status' => true,
                'message' => 'Document created successfully.',
                'status code' => 200,
                // 'data' => $document,
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = document::find($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    

        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'file' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
            ]);

            $document = document::findOrFail($id);
            $destination = public_path("storage\\" . $document->file);

            $filename = "";
            if ($request->hasFile('file')) {
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $filename = $request->file('file')->store('file', 'public');
            } else {
                $filename = $request->image;
            }

            $document->name = $request->name;    
            $document->description = $request->description;
      
            $document->file = $filename;

            $data = $document->save();


            $data = [
                'status' => true,
                'message' => 'Document Update Successfully.',
                'status code' => 200,
                'data' => $document,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $document = document::findOrFail($id);
            $destination = public_path("storage\\" . $document->file);
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $result = $document->delete();
            $data = [
                'status' => true,
                'message' => 'Document Delate Successfully.',
                'status code' => 200,
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        $document = document::findOrFail($id);
        $file = public_path("storage\\" . $document->file);
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($file, $document->name, $headers);
    }


    public function showCategoryDocument($id)

    {

        $authId=Auth::user()->id;
        

        $data = document::where('catagory_id', $id)
            ->where('sub_catagory_id', null)
            ->where('sub_sub_catagory_id', null)
            ->where('user_id','=',$authId)
            ->with('user')->get();

        return response()->json($data);
    }

    public function showSubCategory($id)
    {
        $data = sub_catagory::where('catagory_id', $id)->with('document')->get();
        return response()->json($data);
    }

    public function showSubCategoryDocument($id)
    {
        $data = document::where('sub_catagory_id', $id)
            ->where('sub_sub_catagory_id', null)
            ->with('user')->with('catagory')->get();
        return response()->json($data);
    }
    public function showSubSubCategory($id)
    {
        $data = sub_sub_catagory::where('sub_catagory_id', $id)->with('document')->get();
        return response()->json($data);
    }
    public function showSubSubCategoryDocument($id)
    {
        $data = document::where('sub_sub_catagory_id', $id)

            ->with('user')->with('catagory')->get();
        return response()->json($data);
    }
}
