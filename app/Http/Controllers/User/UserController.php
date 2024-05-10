<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
// use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index()
    {
        return view('user.landing');
    }

    public function login(Request $request)
    {
        $name = Mahasiswa::where('email', $request->name)->first();

        if (!$name) {
            return redirect()->back()->with(['pesan' => 'Email tidak terdaftar']);
        }

        $password = Hash::check($request->password, $name->password);

        if (!$password) {
            return redirect()->back()->with(['pesan' => 'Password tidak sesuai']);
        }

        if (Auth::guard('mahasiswa')->attempt(['nama' => $request->nama, 'password' => $request->password])) {
            return redirect()->back();
        } else {
            return redirect()->back()->with(['pesan' => 'Akun tidak terdaftar!']);
        }
    }

    public function formRegister()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'nim' => ['required'],
            'nama' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with(['pesan' => $validate->errors()]);
        }

        $nama = Mahasiswa::where('nama', $request->nama)->first();

        if ($nama) {
            return redirect()->back()->with(['pesan' => 'Username sudah terdaftar']);
        }

        Mahasiswa::create([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('pekat.index');
    }

    public function logout()
    {
        Auth::guard('mahasiswa')->logout();

        return redirect()->back();
    }

    public function storePengaduan(Request $request)
    {
        if (!Auth::guard('masyarakat')->user()) {
            return redirect()->back()->with(['pesan' => 'Login dibutuhkan!'])->withInput();
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'isi_laporan' => ['required'],
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate);
        }

        if ($request->file('foto')) {
            $data['foto'] = $request->file('foto')->store('assets/pengaduan', 'public');
        }

        date_default_timezone_set('Asia/Bangkok');

        //     $pengaduan = Pengaduan::create([
        //         'tgl_pengaduan' => date('Y-m-d h:i:s'),
        //         'nik' => Auth::guard('masyarakat')->user()->nik,
        //         'isi_laporan' => $data['isi_laporan'],
        //         'foto' => $data['foto'] ?? '',
        //         'status' => '0',
        //     ]);

        //     if ($pengaduan) {
        //         return redirect()->route('pekat.laporan', 'me')->with(['pengaduan' => 'Berhasil terkirim!', 'type' => 'success']);
        //     } else {
        //         return redirect()->back()->with(['pengaduan' => 'Gagal terkirim!', 'type' => 'danger']);
        //     }
        // }

        // public function laporan($siapa = '')
        // {
        //     $terverifikasi = Pengaduan::where([['nik', Auth::guard('masyarakat')->user()->nik], ['status', '!=', '0']])->get()->count();
        //     $proses = Pengaduan::where([['nik', Auth::guard('masyarakat')->user()->nik], ['status', 'proses']])->get()->count();
        //     $selesai = Pengaduan::where([['nik', Auth::guard('masyarakat')->user()->nik], ['status', 'selesai']])->get()->count();

        //     $hitung = [$terverifikasi, $proses, $selesai];

        //     if ($siapa == 'me') {
        //         $pengaduan = Pengaduan::where('nik', Auth::guard('masyarakat')->user()->nik)->orderBy('tgl_pengaduan', 'desc')->get();

        //         return view('user.laporan', ['pengaduan' => $pengaduan, 'hitung' => $hitung, 'siapa' => $siapa]);
        //     } else {
        //         $pengaduan = Pengaduan::where([['nik', '!=', Auth::guard('masyarakat')->user()->nik], ['status', '!=', '0']])->orderBy('tgl_pengaduan', 'desc')->get();

        //         return view('user.laporan', ['pengaduan' => $pengaduan, 'hitung' => $hitung, 'siapa' => $siapa]);
        //     }
    }
}
