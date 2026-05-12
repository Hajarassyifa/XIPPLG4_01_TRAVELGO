package com.example.travelgo

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import androidx.appcompat.app.AppCompatActivity
import androidx.viewpager2.widget.ViewPager2
import com.example.travelgo.OnboardingAdapter

class GetStartedActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_get_started)

        // Gunakan try-catch atau pastikan ID-nya sama persis dengan di XML
        val viewPager = findViewById<ViewPager2>(R.id.viewPager)
        val btnMulai = findViewById<Button>(R.id.btnMulai)

        val images = listOf(
            R.drawable.img_onboarding1,
            R.drawable.img_onboarding2,
            R.drawable.img_onboarding3
        )

        val adapter = OnboardingAdapter(images)
        viewPager.adapter = adapter

        btnMulai.setOnClickListener {
            // Kita arahkan ke MainActivity dulu supaya kamu bisa lihat hasilnya jalan
            val intent = Intent(this, LoginActivity::class.java)
            startActivity(intent)
            finish() // Agar user tidak bisa balik ke Get Started lagi
        }
    }
}