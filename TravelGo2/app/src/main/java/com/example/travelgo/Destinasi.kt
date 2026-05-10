package com.example.travelgo

// Gunakan data class sederhana saja
data class Destinasi(
    val id: Int,
    val nama: String,
    val lokasi: String,
    val deskripsi: String,
    val harga: String,
    val kategori: String,
    val gambar: Int // Untuk menyimpan R.drawable.nama_gambar
)