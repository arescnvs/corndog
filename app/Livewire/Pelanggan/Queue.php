<?php

namespace App\Livewire\Pelanggan;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Pesanan;
use Carbon\Carbon;

#[Layout('pelanggan')]
class Queue extends Component
{
    public $pesanan;
    public $idPesanan;
    public $statusPembayaran;
    public $statusPesanan;
    public $noAntrean;
    public $metodePengantaran = 'ditempat';
    public $estimasiWaktu;

    public function mount($id)
    {
        $this->idPesanan = $id;
        $this->refreshStatus();
    }

    public function refreshStatus()
    {
        $this->pesanan = Pesanan::with('pembayaran', 'pelanggan')->find($this->idPesanan);

        if ($this->pesanan) {
            $this->statusPembayaran = $this->pesanan->pembayaran->statusPembayaran ?? 'Belum Dibayar';
            $this->statusPesanan = $this->pesanan->statusPesanan ?? 'Menunggu';
            $this->noAntrean = $this->pesanan->noAntrean;
            $this->metodePengantaran = $this->pesanan->lokasiGPS ? 'dikirim' : 'ditempat';
            $this->estimasiWaktu = $this->hitungEstimasi();
        }
    }

    private function hitungEstimasi()
    {
        // Tidak ada estimasi sebelum dibayar
        if (! $this->pesanan || $this->statusPembayaran !== 'Sudah Dibayar') {
            return null;
        }

        $waktuPesan = Carbon::parse($this->pesanan->tanggalPesanan);
        $now = Carbon::now();

        // Hitung estimasi waktu dinamis berdasarkan status
        switch ($this->statusPesanan) {
            case 'Diproses':
                // Diperlukan sekitar 10 menit untuk menyiapkan pesanan
                $estimasi = $waktuPesan->copy()->addMinutes(10);
                $label = 'Estimasi selesai pembuatan';
                break;

            case 'Selesai':
                // Tambah 5 menit persiapan sebelum dikirim
                $estimasi = $waktuPesan->copy()->addMinutes(15);
                $label = 'Estimasi mulai pengantaran';
                break;

            case 'Sedang Dikirim':
                // Estimasi waktu tiba tergantung jarak, kita asumsikan ±25 menit setelah dikirim
                $estimasi = $waktuPesan->copy()->addMinutes(40);
                $label = 'Estimasi pesanan tiba';
                break;

            case 'Sudah Sampai':
                // Sudah selesai, tidak perlu estimasi
                return 'Pesanan sudah tiba di tujuan';

            default:
                return null;
        }

        // Jika waktu estimasi sudah lewat, ubah jadi 'sebentar lagi'
        if ($estimasi->isPast()) {
            return "{$label}: sebentar lagi";
        }

        // Format jam estimasi (contoh: 10:45 WIB)
        return "{$label}: sekitar pukul " . $estimasi->format('H:i');
    }

    public function render()
    {
        return view('livewire.pelanggan.queue', [
            'statusPembayaran' => $this->statusPembayaran,
            'statusPesanan' => $this->statusPesanan,
            'noAntrean' => $this->noAntrean,
            'metodePengantaran' => $this->metodePengantaran,
            'estimasiWaktu' => $this->estimasiWaktu,
        ]);
    }
}
