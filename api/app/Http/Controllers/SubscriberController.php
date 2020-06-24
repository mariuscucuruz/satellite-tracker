<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;

class SubscriberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Route handler for new submissions / subscribers.
     *
     * @param Request $request
     * @return Subscriber
     * @throws \Illuminate\Validation\ValidationException
     */
    public function subscribe(Request $request)
    {
        $validatedData = $this->validate($request, [
            'email'     => 'bail|email:rfc,dns',
            'noradId'   => 'bail|required',
            'satId'     => 'numeric',
            'location'  => 'max:255',
            'lat'       => 'bail|required|numeric',
            'lng'       => 'bail|required|numeric',
            'alt'       => 'integer',
        ]);

        // make it clear
        $subscription = new Subscriber();
            $subscription->email    = $request->email;
            $subscription->satId    = $request->satId ?? 0;
            $subscription->noradId  = $request->noradId ?? 0;
            $subscription->location = $request->location ?? 'none';
            $subscription->latitude = $request->lat ?? 0;
            $subscription->longitude= $request->lng ?? 0;
            $subscription->altitude = $request->alt ?? 0;

        /**
         * @todo maybe have some validation on location?
         */

        $subscription->save();

        return $subscription;
    }

    /**
     * Route handler for cancelling submissions / subscribers.
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function remove(Request $request)
    {
        $validatedData = $this->validate($request, [
            'email'     => 'bail|email:rfc,dns',
            'noradId'   => 'bail|required',
            'satId'     => 'numeric',
            'lat'       => 'bail|required|numeric',
            'lng'       => 'bail|required|numeric',
        ]);

        $subscription = Subscriber::where('email', $request['email'])->first();

        return $subscription->delete();
    }
}
