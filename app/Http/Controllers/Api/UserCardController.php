<?php

namespace App\Http\Controllers\api;

use App\Models\Card;
use App\Models\UserCard;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Http\Requests\StoreUserCardRequest;
use App\Http\Requests\UpdateUserCardRequest;

class UserCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function  remove(Request $request){
        $data=$request->all();
        $rules = [
            'id'=>'required|exists:user_cards,id'
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if($validation->fails())
        {
        return ResponseService::validationErrorResponse($validation->errors()->first());
        }
        else{
            UserCard::where('id',$data['id'])->delete();
            return response()->json([
                'code'=>200,
                'message'=>'Card deleted successfully',
            ], 200);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data=$request->all();
        $rules = [
            'card_type'=>'required|string',
            'expiry'=>'required|date',
            'card_digits'=>'required|min:4|max:4'
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if($validation->fails())
        {
        return ResponseService::validationErrorResponse($validation->errors()->first());
        }
        else{

            $card=UserCard::create([
            'card_type'=>$data["card_type"],
            'expiry'=>$data['expiry'],
            'code'=>$data['card_digits'],
            'user_id'=>auth()->user()->id
            ]);
            return response()->json([
                'code'=>200,
                'message'=>'Card added Successfully',
                'card'=>$card,
            ]);

        }

    }


    public function showCards(){
        return response()->json([
            'code'=>200,
            'message'=>'Cards fetched successfully',
            'cards' =>UserCard::select('id','card_type','code', 'expiry')->get()
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=$request->all();
        $rules = [
            'card_type'=>'required|exists:cards,card_type|min:4|max:4',
            'expiry'=>'required|date',
            'code'=>'required'
        ];

        // Create a validator instance
        $validation = Validator::make($data, $rules);

        if($validation->fails())
        {
        return ResponseService::validationErrorResponse($validation->errors()->first());
        }
        else{

            $userCard=UserCard::create([
            'card_type'=>$data["card_type"],
            'code'=>$data["code"],
            'expiry'=>$data["expiry"],
            'user_id'=>auth()->user()->id
            ]);
            return ResponseService::successResponse('Card created successfully',$userCard);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserCard $userCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserCard $userCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserCardRequest $request, UserCard $userCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserCard $userCard)
    {
        //
    }
}
