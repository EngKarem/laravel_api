<?php

namespace App\Http\Controllers;
use App\Models\Material;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Car_image;
use App\Models\Quantity;
use App\Models\Condition;
use App\Models\Station;
use App\Models\History;
use App\Models\User_station;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('phone', 'password');
        $user = User::where('phone', $credentials['phone'])->get();
        if ($user->isEmpty()) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if($user[0]['status']!=1)
        {
            try {
                if (! $token = JWTAuth::attempt(['phone'=>$credentials['phone'],'password'=>$credentials['password']])) {
                    return response()->json(
                        [
                            'error' => 'Invalid credentials',
                        ], 401);
                }
            } catch (JWTException $e) {
                return response()->json(
                    [
                        'error' => 'Could not create token',
                        'msg' => $e->getMessage(),
                    ], 500);
            }
            return response()->json(
                [
                    'token' => $token,
                    'userId' => $user[0]['id'],
                    'isactive'=> $user[0]['isactive']
                ]
            );
        }
        else{
            return response()->json(
                [
                    'error' => 'not authorized',
                ]
                , 401);
        }
    }
    public function checkNumber($num): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }

        $car  = Data::where('number', $num)->get();

        // Check if the car exists
        if ($car->isEmpty()) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        return response()->json($car);
    }
    public function checkoldNumber($oldnum): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $car  = Data::where('oldnum', $oldnum)->get();

        // Check if the car exists
        if ($car->isEmpty()) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        return response()->json($car);
    }
    public function checkQr($qr): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $car  = Data::where('qr', $qr)->get();

        if ($car->isEmpty()) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        return response()->json($car);
    }
    public function getImages($num): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $car  = Data::where('number', $num)->get();

        // Check if the car exists
        if ($car->isEmpty()) {
            return response()->json(['message' => 'Car not found'], 404);
        }
        else{
            $carImages = Car_image::where('car_number', $car[0]['number'])->get();
            return response()->json($carImages);
        }
    }
    public function getCarId($num): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $car  = Data::where('number', $num)->get();

        // Check if the car exists
        if ($car->isEmpty()) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        return response()->json(['Id'=>$car[0]['id']]);
    }
    public function getUserId($phone): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $user  = Data::where('phone', $phone)->get();

        if ($user->isEmpty()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['Id'=>$user[0]['id']]);
    }
    public function addCar(Request $request): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }

        $user = Auth::user();

        if($user->permission === 'تعديل'){
            return response()->json(['message' => 'not allowed to add car'], 403);
        }

        $Conditions  = Condition::all();
        try {
            $validation=Validator::make($request->all(),[
                'number' => 'required|string|unique:data',
                'qr'=> 'required|string|unique:data',
                'Kilo1' => 'required|integer',
                'user' => 'required|string',
                'station' =>'required|integer',
                'owner' => 'string',
                'plate' => 'string',
                'model' => 'string',
                'notes' =>'string',
                'oldnum' =>'string',
                'color' =>'string',
                'ycar' =>'integer',
                'mob' =>'integer',
                'eng' =>'string',
                'iden' =>'string',
                'num_repeat' =>'required|string',
                'quantity' => 'required|integer',
                'material' => 'required|string',
                'city' => 'required|string',
            ]);
            if($validation->fails()){
                return response()->json([
                    'msg'=>'error',
                    'error'=>$validation->errors()
                ]);
            }
            else{
                if($Conditions[0]['repeat_num'] == 'not allowed'){
                    $car = Data::where('oldnum', $request->input('oldnum'))->first();
                    if ($car)
                        return response()->json(['message' => 'this number already exist']);
                }
                $car = new Data([
                    'number' => $request->input('number'),
                    'qr' => $request->input('qr'),
                    'Kilo1' => $request->input('Kilo1'),
                    'user' =>$request->input('user'),
                    'station' => $request->input('station'),
                    'owner' => $request->input('owner'),
                    'plate' => $request->input('plate'),
                    'model' => $request->input('model'),
                    'notes' => $request->input('notes'),
                    'oldnum' => $request->input('oldnum'),
                    'color' => $request->input('color'),
                    'ycar' => $request->input('ycar'),
                    'mob' => $request->input('mob'),
                    'iden' => $request->input('iden'),
                    'quantity' => $request->input('quantity'),
                    'material' => $request->input('material'),
                    'latest_packing' => $request->input('quantity'),
                    'city' => $request->input('city'),
                    'num_repeat' => $request->input('num_repeat'),
                ]);
                $customUpdatedAt = '2023-09-01 00:00:00';
                $car->setUpdatedAtCustom($customUpdatedAt);
                if ($car->save()){
                    $station = Station::find($request->input('station'));

                    if($request->input('material')=='البنزين')
                        $station->update(['petrol' => $station['petrol']-=$request->input('quantity')]);

                    elseif($request->input('material')=='الديزل')
                        $station->update(['diesel' => $station['diesel']-=$request->input('quantity')]);

                    elseif($request->input('material')=='الجاز')
                        $station->update(['gas' => $station['gas']-=$request->input('quantity')]);

                    return response()->json([
                        'message' => 'Car has been added',
                        'car'=>$car
                    ],201);
                }else{
                    return response()->json([
                        'message' => 'Car not Added'
                    ], 500);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add car',
                'e'=>$e->getMessage()
            ], 400);
        }
    }
    public function addKilo(Request $request, $id): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $user = Auth::user();

        if($user->permission === 'اضافة'){
            return response()->json(['message' => 'not allowed to update car'], 403);
        }

        $car = Data::find($id);
        $old_kilo = $car->Kilo1;
        if (!$car)
            return response()->json(['message' => 'Car not found'], 404);
        $validatedData = $request->validate([
            'currentKilo' => 'integer',
            'material' => 'string',
            'quantity' => 'integer',
            'city' => 'string',
            'station' =>'integer'
        ]);
        if($validatedData)
        {
            $newQuantity = $car['quantity'] + $validatedData['quantity'];
            if ($car->update(['Kilo1' => $validatedData['currentKilo'], 'quantity'  => $newQuantity, 'material' => $validatedData['material'],  'city' => $validatedData['city'], 'station' => $validatedData['station'], 'latest_packing'  => $validatedData['quantity']])){
                $station = Station::find($request->input('station'));

                if($request->input('material')=='البنزين')
                    $station->update(['petrol' => $station['petrol']-=$request->input('quantity')]);

                elseif($request->input('material')=='الديزل')
                    $station->update(['diesel' => $station['diesel']-=$request->input('quantity')]);

                elseif($request->input('material')=='الجاز')
                    $station->update(['gas' => $station['gas']-=$request->input('quantity')]);

                $history = new History([
                    'number' => $car->number,
                    'qr' => $car->qr,
                    'current_kilo' => $car->Kilo1,
                    'user' =>$car->user,
                    'station' => $car->station,
                    'owner' => $car->owner,
                    'plate' => $car->plate,
                    'model' => $car->model,
                    'oldnum' => $car->oldnum,
                    'color' => $car->color,
                    'ycar' => $car->ycar,
                    'quantity' => $validatedData['quantity'],
                    'material' => $car->material,
                    'latest_packing' => $car->latest_packing,
                    'city' => $car->city,
                    'old_kilo' => $old_kilo
                ]);

                $history->save();
                return response()->json([
                    'message' => 'Car updated successfully',
                    'car'=>$car
                ]);

            }else{
                return response()->json([
                    'message' => 'Car not updated'
                ], 400);
            }
        }
        return response()->json([
            'message' => 'Car not updated'
        ], 400);
    }

    public function addImages(Request $request, $num): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $uploadedFilenames = [];

            foreach ($images as $image) {
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
                $uploadedFilenames[] = $filename;
                $carImage = new Car_image([
                    'car_number' => $num,
                    'image_path' => $filename,
                ]);
                $carImage->save();
            }
            return response()->json([
                'message' => 'Images uploaded successfully',
                'filenames' => $uploadedFilenames
            ]);
        }
        return response()->json(['error' => 'Image upload failed'], 400);
    }
    public function updateCondition(Request $request): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $cond = Condition::find(1);

        if (!$cond) {
            return response()->json(['message' => 'not found'], 404);
        }

        $validatedData = $request->validate([
            'kilo_difference' => 'integer',
            'days' => 'integer',
        ]);
        $kilo_diff = !isset($validatedData['kilo_difference'])? $cond['kilo_difference']:$validatedData['kilo_difference'];
        $days_diff = !isset($validatedData['days'])? $cond['days']:$validatedData['days'];

        // Update the user data with the validated data
        if ($cond->update(['kilo_difference' => $kilo_diff,'days' => $days_diff])){
            return response()->json([
                'message' => 'Condition updated successfully',
                'condition'=>$cond
            ]);
        }else{
            return response()->json([
                'message' => 'Condition not updated'
            ], 400);
        }
    }
    public function getMaterialQuantities($name): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $quantity  = Quantity::where('material_name', $name)->get();

        if ($quantity->isEmpty()) {
            return response()->json(['message' => 'not found'], 404);
        }
        $liters = $quantity->pluck('liter');
        return response()->json(['quantities' => $liters]);
    }
    public function getCities(): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $Cities  =  City::all();

        if ($Cities->isEmpty()) {
            return response()->json(['message' => 'not found'], 404);
        }
        return response()->json($Cities);
    }
    public function getMaterials(): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $Materials  = Material::all();

        if ($Materials->isEmpty()) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json($Materials);
    }

    public function getUserStations($phone): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $user  = User::where('phone', $phone)->get();

        // Check if the car exists
        if ($user->isEmpty()) {
            return response()->json(['message' => 'User not found'], 404);
        }
        else{
            $stations = User_station::where('user_id', $user[0]['id'])->get();

            if ($stations->isEmpty()) {
                return response()->json(['message' => 'No stations found for this user.'], 404);
            }

            return response()->json($stations);
        }
    }

    public function getConditions(): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $Conditions  = Condition::all();

        if ($Conditions->isEmpty()) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json($Conditions[0]);
    }
    public function getUserData($id): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $user  = User::where('id', $id)->get();

        if ($user->isEmpty()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user[0]);
    }
    public function getCarsNumber($oldnum): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $carCount = Data::where('oldnum', $oldnum)->count();


        return response()->json(['car_count' => $carCount]);
    }

    public function getNotifications(): JsonResponse
    {
        $Conditions  = Condition::all();
        if ($Conditions[0]['maintenance_mode'] == '1'){
            return response()->json(['maintenance_mode'=>"on",'message' => $Conditions[0]['maintenance_message']]);
        }
        $Notifications  =  Notification::all();

        if ($Notifications->isEmpty()) {
            return response()->json(['Notifications' => 'not found'], 404);
        }
        return response()->json($Notifications);
    }
}
