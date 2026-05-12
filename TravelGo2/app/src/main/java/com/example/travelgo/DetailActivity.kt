package com.example.travelgo

import android.os.Bundle
import android.widget.ImageButton
import android.widget.ImageView
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity

class DetailActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_detail)

        // 1. Inisialisasi View (Pastikan ID sesuai dengan activity_detail.xml)
        val imgDetail = findViewById<ImageView>(R.id.imgDetail)
        val tvNama = findViewById<TextView>(R.id.tvNamaDetail)
        val tvLokasi = findViewById<TextView>(R.id.tvLokasiDetail)
        val tvHarga = findViewById<TextView>(R.id.tvHargaDetail)
        val tvDeskripsi = findViewById<TextView>(R.id.tvDeskripsiDetail)
        val btnBack = findViewById<ImageButton>(R.id.btnBack)

        // 2. Tangkap data dari Activity sebelumnya (Intent)
        val nama = intent.getStringExtra("NAMA_DESTINASI")
        val lokasi = intent.getStringExtra("LOKASI_DESTINASI")
        val harga = intent.getStringExtra("HARGA_DESTINASI")
        val gambar = intent.getIntExtra("GAMBAR_DESTINASI", 0)

        // 3. Pasang data ke View
        tvNama.text = nama
        tvLokasi.text = lokasi // Perbaikan: tadinya tvLokasiDetail (error)
        tvHarga.text = harga
        imgDetail.setImageResource(gambar)

        // Deskripsi (Bisa kustom atau ambil dari data destinasi)
        tvDeskripsi.text = "Selamat datang di $nama! Nikmati pengalaman luar biasa di $lokasi dengan harga terbaik."

        // 4. Fungsi tombol kembali
        btnBack.setOnClickListener {
            finish() // Menutup activity ini dan kembali ke MainActivity
        }
    }
}