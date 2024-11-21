<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\KonfigController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\PosJuriController;
use App\Http\Controllers\JuriKategoriController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\KatPesertaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DiskualifikasiController;

use App\Http\Controllers\WilayahController;
use App\Http\Controllers\JenisRetribusiController;
use App\Http\Controllers\ObjekRetribusiController;
use App\Http\Controllers\WajibRetribusiController;
use App\Http\Controllers\RegistrasiKarcisController;
use App\Http\Controllers\PenyerahanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\KarcisController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogKunjunganController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('/daftar-peserta/{id?}', [MainController::class, 'daftarPeserta'])->name('daftar-peserta');
Route::get('/form-pendaftaran-peserta/{id_lomba?}/{id_peserta?}', [MainController::class, 'formPendaftaranPeserta'])->name('form-pendaftaran-peserta');
Route::post('/daftar-umum', [MainController::class, 'daftarUmum'])->name('daftar-umum');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/main/{tahun?}', [MainController::class, 'main'])->middleware(['auth', 'verified'])->name('main');
Route::get('/main2/{tahun?}', [MainController::class, 'main2'])->middleware(['auth', 'verified'])->name('main2');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin')->group(function () {
        //User
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user');
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/store', [UserController::class, 'store'])->name('user.store');
            //Route::get('/show/{id}', [UserController::class, 'show'])->name('user.show');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::post('/update', [UserController::class, 'update'])->name('user.update');
            Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        });


        //konfig
        Route::prefix('konfig')->group(function () {
            Route::get('/', [KonfigController::class, 'index'])->name('konfig');
            Route::get('/create', [KonfigController::class, 'create'])->name('konfig.create');
            Route::post('/store', [KonfigController::class, 'store'])->name('konfig.store');
            //Route::get('/show/{id}', [KonfigController::class, 'show'])->name('konfig.show');
            Route::get('/edit/{id}', [KonfigController::class, 'edit'])->name('konfig.edit');
            Route::post('/update', [KonfigController::class, 'update'])->name('konfig.update');
            Route::delete('/destroy/{id}', [KonfigController::class, 'destroy'])->name('konfig.destroy');
        });

        //lomba
        /*Route::prefix('lomba')->group(function () {
            Route::get('/', [LombaController::class, 'index'])->name('lomba');
            Route::get('/create', [LombaController::class, 'create'])->name('lomba.create');
            Route::post('/store', [LombaController::class, 'store'])->name('lomba.store');
            Route::get('/show/{id}', [LombaController::class, 'show'])->name('lomba.show');
            Route::get('/edit/{id}', [LombaController::class, 'edit'])->name('lomba.edit');
            Route::post('/update', [LombaController::class, 'update'])->name('lomba.update');
            Route::delete('/destroy/{id}', [LombaController::class, 'destroy'])->name('lomba.destroy');
        });

        //lomba
        Route::prefix('kat_peserta')->group(function () {
            Route::get('/', [KatPesertaController::class, 'index'])->name('kat_peserta');
            Route::get('/create', [KatPesertaController::class, 'create'])->name('kat_peserta.create');
            Route::post('/store', [KatPesertaController::class, 'store'])->name('kat_peserta.store');
            Route::get('/show/{id}', [KatPesertaController::class, 'show'])->name('kat_peserta.show');
            Route::get('/edit/{id}', [KatPesertaController::class, 'edit'])->name('kat_peserta.edit');
            Route::post('/update', [KatPesertaController::class, 'update'])->name('kat_peserta.update');
            Route::delete('/destroy/{id}', [KatPesertaController::class, 'destroy'])->name('kat_peserta.destroy');
        });

        //pos juri
        Route::prefix('pos_juri')->group(function () {
            Route::get('/', [PosJuriController::class, 'index'])->name('pos_juri');
            Route::get('/create', [PosJuriController::class, 'create'])->name('pos_juri.create');
            Route::post('/store', [PosJuriController::class, 'store'])->name('pos_juri.store');
            Route::get('/show/{id}', [PosJuriController::class, 'show'])->name('pos_juri.show');
            Route::get('/edit/{id}', [PosJuriController::class, 'edit'])->name('pos_juri.edit');
            Route::post('/update', [PosJuriController::class, 'update'])->name('pos_juri.update');
            Route::delete('/destroy/{id}', [PosJuriController::class, 'destroy'])->name('pos_juri.destroy');
        });

        //juri kategori
        Route::prefix('juri_kategori')->group(function () {
            Route::get('/', [JuriKategoriController::class, 'index'])->name('juri_kategori');
            Route::get('/create', [JuriKategoriController::class, 'create'])->name('juri_kategori.create');
            Route::post('/store', [JuriKategoriController::class, 'store'])->name('juri_kategori.store');
            Route::get('/show/{id}', [JuriKategoriController::class, 'show'])->name('juri_kategori.show');
            Route::get('/edit/{id}', [JuriKategoriController::class, 'edit'])->name('juri_kategori.edit');
            Route::post('/update', [JuriKategoriController::class, 'update'])->name('juri_kategori.update');
            Route::delete('/destroy/{id}', [JuriKategoriController::class, 'destroy'])->name('juri_kategori.destroy');
        });

         //pendaftar
        Route::prefix('pendaftar')->group(function () {
            Route::get('{id_lomba?}/{id_peserta?}', [PendaftarController::class, 'index'])->name('pendaftar');
            Route::get('{id_lomba}/create/{id_peserta?}', [PendaftarController::class, 'create'])->name('pendaftar.create');
            Route::post('/store', [PendaftarController::class, 'store'])->name('pendaftar.store');
            Route::get('/show/{id}', [PendaftarController::class, 'show'])->name('pendaftar.show');
            Route::get('/{id_lomba}/edit/{id}', [PendaftarController::class, 'edit'])->name('pendaftar.edit');
            Route::post('/update', [PendaftarController::class, 'update'])->name('pendaftar.update');
            Route::delete('/destroy/{id}', [PendaftarController::class, 'destroy'])->name('pendaftar.destroy');
        });*/

        //wilayah
        Route::prefix('wilayah')->group(function () {
            Route::get('/', [WilayahController::class, 'index'])->name('wilayah');
            Route::get('/create', [WilayahController::class, 'create'])->name('wilayah.create');
            Route::post('/store', [WilayahController::class, 'store'])->name('wilayah.store');
            Route::get('/show/{id}', [WilayahController::class, 'show'])->name('wilayah.show');
            Route::get('/edit/{id}', [WilayahController::class, 'edit'])->name('wilayah.edit');
            Route::post('/update', [WilayahController::class, 'update'])->name('wilayah.update');
            Route::delete('/destroy/{id}', [WilayahController::class, 'destroy'])->name('wilayah.destroy');
        });

        //jenis retribusi
        Route::prefix('jenis_retribusi')->group(function () {
            Route::get('/', [JenisRetribusiController::class, 'index'])->name('jenis_retribusi');
            Route::get('/create', [JenisRetribusiController::class, 'create'])->name('jenis_retribusi.create');
            Route::post('/store', [JenisRetribusiController::class, 'store'])->name('jenis_retribusi.store');
            Route::get('/show/{id}', [JenisRetribusiController::class, 'show'])->name('jenis_retribusi.show');
            Route::get('/edit/{id}', [JenisRetribusiController::class, 'edit'])->name('jenis_retribusi.edit');
            Route::post('/update', [JenisRetribusiController::class, 'update'])->name('jenis_retribusi.update');
            Route::delete('/destroy/{id}', [JenisRetribusiController::class, 'destroy'])->name('jenis_retribusi.destroy');
        });

        //objek retribusi
        Route::prefix('objek_retribusi')->group(function () {
            Route::get('/', [ObjekRetribusiController::class, 'index'])->name('objek_retribusi');
            Route::get('/create', [ObjekRetribusiController::class, 'create'])->name('objek_retribusi.create');
            Route::post('/store', [ObjekRetribusiController::class, 'store'])->name('objek_retribusi.store');
            Route::get('/show/{id}', [ObjekRetribusiController::class, 'show'])->name('objek_retribusi.show');
            Route::get('/edit/{id}', [ObjekRetribusiController::class, 'edit'])->name('objek_retribusi.edit');
            Route::post('/update', [ObjekRetribusiController::class, 'update'])->name('objek_retribusi.update');
            Route::delete('/destroy/{id}', [ObjekRetribusiController::class, 'destroy'])->name('objek_retribusi.destroy');
        });

        //wajib retribusi
        Route::prefix('wajib_retribusi')->group(function () {
            Route::get('{id_jenis_retribusi?}/{id_objek_retribusi?}', [WajibRetribusiController::class, 'index'])->name('wajib_retribusi');
            Route::get('{id_jenis_retribusi?}/create/{id_objek_retribusi?}', [WajibRetribusiController::class, 'create'])->name('wajib_retribusi.create');
            Route::post('/store', [WajibRetribusiController::class, 'store'])->name('wajib_retribusi.store');
            Route::match(['get', 'post'],'{id_objek_retribusi?}/show/{npwrd}', [WajibRetribusiController::class, 'show'])->name('wajib_retribusi.show');
            Route::get('{id_objek_retribusi}/edit/{npwrd}', [WajibRetribusiController::class, 'edit'])->name('wajib_retribusi.edit');
            Route::post('/update', [WajibRetribusiController::class, 'update'])->name('wajib_retribusi.update');
            Route::delete('/destroy/{npwrd}', [WajibRetribusiController::class, 'destroy'])->name('wajib_retribusi.destroy');
        });
    });

    //pembayaran
    Route::prefix('pembayaran')->group(function () {
        Route::match(['post', 'get'],'/', [PembayaranController::class, 'index'])->name('pembayaran');
        Route::get('/create/bulanan/{npwrd?}', [PembayaranController::class, 'create'])->name('pembayaran.bulanan');
        Route::get('/create/tagihan/{id?}', [PembayaranController::class, 'create'])->name('pembayaran.tagihan');
        Route::post('/store', [PembayaranController::class, 'store'])->name('pembayaran.store');
        Route::get('/show/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
        Route::get('/verifikasi', [PembayaranController::class, 'index'])->name('pembayaran.verifikasi');
        Route::get('/verifikasi/show/{id}', [PembayaranController::class, 'show'])->name('pembayaran.verifikasi.show');
        Route::get('/batal', [PembayaranController::class, 'index'])->name('pembayaran.batal');
        Route::get('/edit/{id}', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
        Route::post('/update', [PembayaranController::class, 'update'])->name('pembayaran.update');
        Route::delete('/destroy/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    });

    //Penilaian
    /*Route::prefix('penilaian')->group(function () {
        Route::get('/{id?}', [PenilaianController::class, 'index'])->name('penilaian');
        Route::get('/create/{id}', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('/search', [PenilaianController::class, 'search'])->name('penilaian.search');
        //Route::post('/store', [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::get('/show/{id}', [PenilaianController::class, 'show'])->name('penilaian.show');
        //Route::get('/edit/{id}', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::post('/update', [PenilaianController::class, 'update'])->name('penilaian.update');
        Route::post('/update-waktu', [PenilaianController::class, 'update_waktu'])->name('penilaian.update.waktu');
        Route::post('/update-pos', [PenilaianController::class, 'update_pos'])->name('penilaian.update.pos');
        Route::get('/update-ulang/{id_pendaftar}/{jml_pos}', [PenilaianController::class, 'update_ulang'])->name('penilaian.update.ulang');
        Route::post('/update-diskualifikasi', [PenilaianController::class, 'update_diskualifikasi'])->name('penilaian.update.diskualifikasi');
        Route::delete('/destroy/{id}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');
    });

    //Diskulaifikasi
    Route::prefix('diskualifikasi')->group(function () {
        Route::get('/{id?}', [DiskualifikasiController::class, 'index'])->name('diskualifikasi');
        Route::get('/create/{id}', [DiskualifikasiController::class, 'create'])->name('diskualifikasi.create');
        Route::post('/search', [DiskualifikasiController::class, 'search'])->name('diskualifikasi.search');
        //Route::post('/store', [DiskualifikasiController::class, 'store'])->name('diskualifikasi.store');
        Route::get('/show/{id}', [DiskualifikasiController::class, 'show'])->name('diskualifikasi.show');
        //Route::get('/edit/{id}', [DiskualifikasiController::class, 'edit'])->name('diskualifikasi.edit');
        Route::post('/update', [DiskualifikasiController::class, 'update'])->name('diskualifikasi.update');
        Route::delete('/destroy/{id}', [DiskualifikasiController::class, 'destroy'])->name('diskualifikasi.destroy');
    });*/

    //Penyerahan
    Route::prefix('penyerahan')->group(function () {
        Route::match(['get', 'post'], '/', [PenyerahanController::class, 'index'])->name('penyerahan');
        Route::get('/create/{jns?}', [PenyerahanController::class, 'create'])->name('penyerahan.create');
        Route::get('/create/{jns?}/{id_registrasi_karcis}', [PenyerahanController::class, 'create_item'])->name('penyerahan.create_item');
        Route::post('/store', [PenyerahanController::class, 'store'])->name('penyerahan.store');
        //Route::get('/show/{id}', [PenyerahanController::class, 'show'])->name('penyerahan.show');
        Route::match(['get', 'post'], '/show/{route?}/{id?}', [PenyerahanController::class, 'show'])->name('penyerahan.show');
        Route::get('/edit/{id}', [PenyerahanController::class, 'edit'])->name('penyerahan.edit');
        Route::post('/update', [PenyerahanController::class, 'update'])->name('penyerahan.update');
        Route::delete('/destroy/{id}', [PenyerahanController::class, 'destroy'])->name('penyerahan.destroy');

        //karcis //tagihan
        
    });

    //Pengembalian
    Route::prefix('pengembalian')->group(function () {
        Route::match(['get', 'post'], '/', [PengembalianController::class, 'index'])->name('pengembalian');
        //Route::get('/create/{jns?}', [PengembalianController::class, 'create'])->name('pengembalian.create');
        //Route::get('/create/{jns?}/{id_registrasi_karcis}', [PengembalianController::class, 'create_item'])->name('pengembalian.create_item');
        Route::post('/store', [PengembalianController::class, 'store'])->name('pengembalian.store');
        //Route::get('/show/{id}', [PenyerahanController::class, 'show'])->name('penyerahan.show');
        //Route::match(['get', 'post'], '/show/{route?}/{id?}', [PengembalianController::class, 'show'])->name('pengembalian.show');
        Route::get('/edit/{id}', [PengembalianController::class, 'edit'])->name('pengembalian.edit');
        //Route::post('/update', [PengembalianController::class, 'update'])->name('pengembalian.update');
        Route::delete('/destroy/{id}', [PengembalianController::class, 'destroy'])->name('pengembalian.destroy');

        //karcis //tagihan
        
    });

    //karcis
    Route::prefix('karcis')->group(function () {
        Route::match(['get', 'post'], '/', [KarcisController::class, 'index'])->name('karcis');
        Route::middleware('koordinator')->group(function(){
            Route::get('/create/{id_registrasi_karcis?}', [KarcisController::class, 'create'])->name('karcis.create');
            Route::post('/store', [KarcisController::class, 'store'])->name('karcis.store');
            Route::get('/show/{id}', [KarcisController::class, 'show'])->name('karcis.show');
            Route::get('/edit/{id}', [KarcisController::class, 'edit'])->name('karcis.edit');
            Route::post('/update', [KarcisController::class, 'update'])->name('karcis.update');
            Route::delete('/destroy/{id}', [KarcisController::class, 'destroy'])->name('karcis.destroy');
        });
    });

    //tagihan
    Route::prefix('tagihan')->group(function () {
        Route::match(['post', 'get'],'/', [TagihanController::class, 'index'])->name('tagihan');
        Route::middleware('koordinator')->group(function(){
            Route::match(['post', 'get'],'/create', [TagihanController::class, 'create'])->name('tagihan.create');
            Route::post('/store', [TagihanController::class, 'store'])->name('tagihan.store');
            Route::get('/show/{id}', [TagihanController::class, 'show'])->name('tagihan.show');
            Route::get('/edit/{id}', [TagihanController::class, 'edit'])->name('tagihan.edit');
            Route::post('/update', [TagihanController::class, 'update'])->name('tagihan.update');
            Route::delete('/destroy/{id}', [TagihanController::class, 'destroy'])->name('tagihan.destroy');
        });
    });

    Route::prefix('pdf')->group(function () {
        Route::get('/registrasi_karcis/{id}', [PdfController::class, 'registrasi_karcis'])->name('pdf.registrasi_karcis');
        Route::get('/piutang', [PdfController::class, 'piutang'])->name('pdf.piutang');
    });

    //log Kunjungan
    Route::prefix('log_kunjungan')->group(function () {
        Route::get('/', [LogKunjunganController::class, 'index'])->name('log_kunjungan');
        Route::get('/create', [LogKunjunganController::class, 'create'])->name('log_kunjungan.create');
        Route::post('/store', [LogKunjunganController::class, 'store'])->name('log_kunjungan.store');
        Route::get('/show/{id}', [LogKunjunganController::class, 'show'])->name('log_kunjungan.show');
        Route::get('/edit/{id}', [LogKunjunganController::class, 'edit'])->name('log_kunjungan.edit');
        Route::post('/update', [LogKunjunganController::class, 'update'])->name('log_kunjungan.update');
        Route::delete('/destroy/{id}', [LogKunjunganController::class, 'destroy'])->name('log_kunjungan.destroy');
    });

    Route::prefix('laporan')->group(function () {
        /*Route::prefix('penerimaan')->group(function () {
            Route::match(['post', 'get'],'/juru_pungut', [LaporanController::class, 'penerimaanJuruPungut'])->name('laporan.penerimaan.juru_pungut');
            Route::match(['post', 'get'],'/jenis', [LaporanController::class, 'penerimaanJenis'])->name('laporan.penerimaan.jenis');
            Route::match(['post', 'get'],'/objek', [LaporanController::class, 'penerimaanObjek'])->name('laporan.penerimaan.objek');
        });*/

        Route::prefix('piutang')->group(function () {
            Route::match(['post', 'get'],'/tagihan', [LaporanController::class, 'piutangTagihan'])->name('laporan.piutang.tagihan');
        });    

        Route::match(['post', 'get'],'/penyerahan/{filter?}', [LaporanController::class, 'penyerahan'])->name('laporan.penyerahan');
        Route::match(['post', 'get'],'/penyerahan/{route}/{id}', [LaporanController::class, 'penyerahan_tersendiri'])->name('laporan.penyerahan.tersendiri');

        Route::prefix('data')->group(function () {
            Route::match(['post', 'get'],'/retribusi', [LaporanController::class, 'dataRetribusi'])->name('laporan.data.retribusi');
            Route::match(['post', 'get'],'/retribusi/pdf/{id?}', [LaporanController::class, 'pdfRetribusi'])->name('laporan.data.retribusi.pdf');
            Route::match(['post', 'get'],'/penerimaan', [LaporanController::class, 'dataPenerimaan'])->name('laporan.data.penerimaan');
            Route::match(['post', 'get'],'/penerimaan/pdf/{id?}', [LaporanController::class, 'pdfPenerimaan'])->name('laporan.data.penerimaan.pdf');
        });  

        /*Route::prefix('serahterima')->group(function () {
            Route::match(['post', 'get'],'/pembayaran', [LaporanController::class, 'serahTerimaPembayaran'])->name('laporan.serahterima.pembayaran');
            Route::match(['post', 'get'],'/pembayaran/pdf/{id?}', [LaporanController::class, 'pdfPembayaran'])->name('laporan.serahterima.pembayaran.pdf');
        });*/    

    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('resetPassword', [MainController::class, 'resetPassword'])->name('password.reset1');
    Route::post('resetPasswordUser', [MainController::class, 'resetPasswordUser'])->name('password.reset2');

    Route::get('rekapHasil/{id_peserta?}', [MainController::class, 'rekapHasil'])->name('rekapHasil');
    Route::get('rekapPos/{id_lomba?}/{id_juri?}', [MainController::class, 'rekapPos'])->name('rekapPos');
});

//require __DIR__.'/auth.php';
