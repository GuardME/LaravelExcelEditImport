<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all();
        return view('customer', compact('users'));
    }

    public function export()
    {
        // return Excel::dwonload(new UsersExport(), 'users.xlsx');
        return Excel::download(new UsersExport, 'users.xlsx');
        return redirect()->route('home');
    }

    public function import(Request $request)
    {
        $users = Excel::toCollection(new UsersImport(), $request->file('import_file'));
        foreach ($users[0] as $user) {
            User::where('id', $user[0])->update([
                'name' => $user[1],
                'email' => $user[2],
            ]);
        }
        return redirect()->route('home');
    }
}
