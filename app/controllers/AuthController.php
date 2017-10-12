<?php



class AuthController extends BaseController 



{



    public function getLogin()

    {



        return View::make('login');



    }



    public function getLoginDemo()

    {



        Session::put('demo-login', true);



        return View::make('loginDemo');



    }





    public function postLogin()



    {



        $validator = Validator::make(



            Input::all(),



            array(



                'user' => 'required',



                'pass' => 'required'



                ),



            array(



                'user.required' => 'Username is required.',



                'pass.required' => 'Password is required.',



                )



            );



        if ($validator->fails()) {



            return Redirect::route('login')->with('errors', $validator->errors());



        } else {



            $auth = Auth::attempt(array(



                'email' => Input::get('user'),



                'password' => Input::get('pass'),

                'sport' => 'GroupWorkout',



            ));







            if ($auth === false) {



                return Redirect::route('login')->with('error', 'Could not login to your account.');



            } else {



                Session::put('locked', false);



                return Redirect::route('authentication');



            }



        }



    }



    public function postLoginDemo()



    {



        $validator = Validator::make(



            Input::all(),



            array(



                'user' => 'required',



                'pass' => 'required'



                ),



            array(



                'user.required' => 'Username is required.',



                'pass.required' => 'Password is required.',



                )



            );



        if ($validator->fails()) {



            return Redirect::route('demo')->with('errors', $validator->errors());



        } else {



            $auth = Auth::attempt(array(



                'email' => Input::get('user'),



                'password' => Input::get('pass'),

                'sport' => 'GroupWorkout',



                ));







            if ($auth === false) {



                return Redirect::route('demo')->with('error', 'Could not login to your account.');



            } else {



                Session::put('locked', false);



                Session::put('demo-login', true);



                return Redirect::route('authentication');



            }



        }



    }





    public function logout() 

    {

        Auth::logout();



        // $session = Sessions::find(Session::getId());



        // $session->out = time();



        // $session->save();



        if (Session::has('demo-login')) {



            Session::forget('demo-login');



            return Redirect::to('demo');

        }



        Session::invalidate(0);



        return Redirect::route('login');



    }



    public function logoutDemo() 



    {



        Auth::logout();







        $session = Sessions::find(Session::getId());



        $session->out = time();



        $session->save();



        Session::invalidate(0);







        return Redirect::route('demo');



    }



}