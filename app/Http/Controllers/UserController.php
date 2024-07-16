<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage (Register).
     */
    public function register(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
            'mobile_verified' => 'required|boolean',
            'profil_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle the profile photo upload
        if ($request->hasFile('profil_photo')) {
            $file = $request->file('profil_photo');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_photos'), $fileName);
            $profilePhotoPath = 'uploads/profile_photos/' . $fileName;
        } else {
            $profilePhotoPath = null;
        }

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile_no' => $request->mobile_no,
            'mobile_verified' => $request->mobile_verified,
            'profil_photo' => $profilePhotoPath,
        ]);

        // Create a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a response
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Login a user and return a token.
     */
    public function login(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check user credentials
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        $subscriptions = SubscriptionsModel::where('user_id', $user->id)->get();



        // Return a response
        return response()->json([
            'message' => 'Login successful',
            'data' => $user,
            'subscriptions' => $subscriptions,
            'token' => $token
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Data retrieved successfully',
            'data' => $user
        ], 200);
    }


    public function sendOTP(Request $request)
    {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Extract mobile number from request
        $mobileNumber = $request->input('mobile_number');

        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://www.fast2sms.com/dev/bulkV2',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
          
          
          "route" : "otp",
          "variables_values" : "' . $otp . '",
          "flash" : 0,
          "numbers" : "' . $mobileNumber . '"
          }',
                CURLOPT_HTTPHEADER => array(
                    'authorization: VzvTXagY7K1t0jfp3F8lQ9rAoPyxHN4umDecGCJMwdBEbIiSZqwIpLci4W1nJtNTBeq2ZOfUz5Gk0VFs',
                    'Content-Type: application/json',
                ),
            )
        );
        // Execute cURL request and capture response
        $response = curl_exec($curl);

        // Close cURL session
        curl_close($curl);

        // Check if curl request was successful
        if ($response === false) {
            return response()->json([
                'message' => 'Failed to send OTP'
            ], 500);
        } else {
            // Assuming success, return OTP and success message
            return response()->json([
                'status' => 200,
                'message' => 'OTP sent successfully',
                'otp' => $otp
            ], 200);
        }
    }

    public function loginWithMobileNumber(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the user by mobile number
        $user = User::where('mobile_no', $request->mobile_no)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Log in the user directly
        Auth::login($user);

        // Return a response with login success message, user data, and subscriptions
        return response()->json([
            'message' => 'Login successful',
            'data' => $user,
        ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
