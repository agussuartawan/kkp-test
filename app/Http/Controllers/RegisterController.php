<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\Concerns\Has;
use function PHPUnit\Framework\throwException;

class RegisterController extends Controller
{

    public function register(RegisterUserRequest $request): User
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);
            $this->sendEmailVerification(
                $this->generateOtp($user->id)->otp,
                $user->email
            );
            return $user;
        });
    }

    public function requestOtp(Request $request): void
    {
        $request->validate([
            'user_id' => ['required'],
            'email' => ['required']
        ]);

        if (User::where('email_verified_at', '!=', null)->where('id', $request->user_id)->exists()) abort(400, 'user already verified');

        $this->sendEmailVerification(
            $this->generateOtp($request->user_id)->otp,
            $request->email
        );
    }

    public function verificateAccount(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => ['required'],
            'user_id' => ['required']
        ]);

        $otpCode = OtpCode::where('user_id', $request->user_id)
            ->where('otp', $request->otp)
            ->latest()->first();

        if (User::where('email_verified_at', '!=', null)->where('id', $request->user_id)->exists()) abort(400, 'user already verified');

        if ($otpCode && Carbon::now()->isBefore($otpCode->expire_at)) {
            $user = User::find($request->user_id);
            $user->email_verified_at = Carbon::now();
            $user->save();
            return response()->json("email verification success");
        }

        abort(422, 'otp code not valid');
    }


    private function generateOtp($userId): OtpCode
    {
        $otpCode = OtpCode::where('user_id', $userId)->latest()->first();

        if ($otpCode && Carbon::now()->isBefore($otpCode->expire_at)) {
            return $otpCode;
        }

        return OtpCode::create([
           'user_id' => $userId,
           'otp' => rand(123456, 999999),
           'expire_at' => Carbon::now()->addMinutes(5)
        ]);
    }

    private function sendEmailVerification($otpCode, $email): void
    {
        $response = Http::post('https://script.google.com/macros/s/AKfycbxFNsyMXW8chGL8YhdQE1Q1yBbx5XEsq-BJeNF1a6sKoowaL_9DtcUvE_Pp0r5ootgMhQ/exec', [
            'email' => $email,
            'subject' => 'Email Verification (Technical Test KKP)',
            'message' => view('emailbody', compact('otpCode'))->render(),
            'token' => '1dy09eODblmBUCTnIwiY-hbXdzCpZC3jyR4l0ZJgqQqO9L7J3zsZOobdJ'
        ]);
    }

}
