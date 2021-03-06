<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = User::doctors()->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     $rules = [
        'name' => 'required|min:3',
        'email' =>'required|email',
        'dni' => 'digits:8',
        'address' => 'min:5',
        'phone' => 'min:6'
    ];
    $this->validate($request, $rules);
    
    /** Se podría hacer así
     * $doctor = new doctor();
        $doctor->name = $request->input('name');
        $doctor->email = $request->input('email');
        .......
        $doctor->save(); // Insertar en BD
     */
    //También con un array asociativo con los campos que quermos almacenar en la BD    
        User::create(
            //Con only en vez de all(), aseguramos que se capturan SOLO los datos que se piden.
            $request->only('name', 'email', 'dni', 'address', 'phone') //En PHP se pueden concatenar arrays...
            + ['rol' => 'doctor', 'password' => bcrypt($request->input('password'))] 
        );

        $notification = 'El médico se ha registrado correctamente.';
        return redirect('/doctors')->with(compact('notification'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //findOrFail busca un médico que tenga el id que le pasamos
        $doctor = User::doctors()->findOrFail($id);
        return view('doctors.edit', compact('doctor'));
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
        $rules = [
            'name' => 'required|min:3',
            'email' =>'required|email',
            'dni' => 'digits:8',
            'address' => 'min:5',
            'phone' => 'min:6'
        ];
        $this->validate($request, $rules);

        $user = User::doctors()->findOrFail($id);
        //Con only en vez de all(), aseguramos que se capturan SOLO los datos que se piden.
        $data = $request->only('name', 'email', 'dni', 'address', 'phone');
        $password = $request->input('password');
        //Si hay una password escrita, vamos a concatenar este nuevo elemento.
        if($password)
            $data['password'] = bcrypt($password);
        
        $user->fill($data);  
        $user->save(); //Update del registro

        $notification = 'La información del médico se ha actualizado correctamente.';
        return redirect('/doctors')->with(compact('notification'));
    }

    public function destroy(User $doctor)
    {
        $doctorName=$doctor->name;
        $doctor->delete();
        $notification = "El médico $doctorName se ha eliminado correctamente";
        return redirect('doctors')->with(compact('notification'));
    }
}
