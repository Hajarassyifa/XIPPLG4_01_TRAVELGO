package com.example.travelgo

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import androidx.appcompat.app.AppCompatActivity

class RegisterActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register) // Pastikan layoutnya benar

        val btnDaftar = findViewById<Button>(R.id.btnDaftar)

        // Klik Daftar -> Balik ke Login
        btnDaftar.setOnClickListener {
            // (Nanti Absari isi logika simpan data di sini)

            val intent = Intent(this, LoginActivity::class.java)
            startActivity(intent)
            finish() // Tutup Register agar user nggak balik lagi ke sini kalau tekan Back
        }
    }
}