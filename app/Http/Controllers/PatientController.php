<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = User::patients()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('patients.create');
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
        User::create(
            //Con only en vez de all(), aseguramos que se capturan SOLO los datos que se piden.
            $request->only('name', 'email', 'dni', 'address', 'phone') //En PHP se pueden concatenar arrays...
            + ['rol' => 'patient', 'password' => bcrypt($request->input('password'))] 
        );

        $notification = 'El paciente se ha registrado correctamente.';
        return redirect('/patients')->with(compact('notification'));
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
 
        $patient = User::patients()->findOrFail($id);
        return view('patients.edit', compact('patient'));
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

        $user = User::patients()->findOrFail($id);
        //Con only en vez de all(), aseguramos que se capturan SOLO los datos que se piden.
        $data = $request->only('name', 'email', 'dni', 'address', 'phone');
        $password = $request->input('password');
        //Si hay una password escrita, vamos a concatenar este nuevo elemento.
        if($password)
            $data['password'] = bcrypt($password);
        
        $user->fill($data);  
        $user->save(); //Update del registro

        $notification = 'La información del paciente se ha actualizado correctamente.';
        return redirect('/patients')->with(compact('notification'));

    }

    public function destroy(User $patient)
    {
        $patientName=$patient->name;
        $patient->delete();
        $notification = "El paciente $patientName se ha eliminado correctamente.";
        return redirect('patients')->with(compact('notification'));
    }
}
