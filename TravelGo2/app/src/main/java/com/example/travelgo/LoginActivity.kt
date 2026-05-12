package com.example.travelgo

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity

class LoginActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        val btnMasuk = findViewById<Button>(R.id.btnMasuk)
        val tvBelumPunyaAkun = findViewById<TextView>(R.id.tvToRegister)

        // 1. Klik Masuk -> Ke Home
        btnMasuk.setOnClickListener {
            val intent = Intent(this, MainActivity::class.java)
            startActivity(intent)
            finish()
        }

        /// Jika klik DAFTAR/BELUM PUNYA AKUN -> Ke REGISTER
        tvBelumPunyaAkun.setOnClickListener {
            val intent = Intent(this, RegisterActivity::class.java)
            startActivity(intent)
            // Jangan di-finish(), supaya user bisa balik ke Login kalau gak jadi daftar
        }
}   }