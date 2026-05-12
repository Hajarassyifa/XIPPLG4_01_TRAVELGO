package com.example.travelgo

import android.content.Intent
import android.os.Bundle
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.viewpager2.widget.ViewPager2

class MainActivity : AppCompatActivity() {

    private lateinit var rvDestinasi: RecyclerView
    private lateinit var bannerViewPager: ViewPager2
    private lateinit var adapter: DestinasiAdapter
    private var listWisata = ArrayList<Destinasi>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        // 1. Inisialisasi View
        rvDestinasi = findViewById(R.id.rvDestinasi)
        bannerViewPager = findViewById(R.id.bannerViewPager)

        // 2. Setup Banner & Data
        setupBanner()
        prepareData()

        // Tampilan Rekomendasi di bawah banner (List Vertikal)
        showRecyclerView(listWisata)

        // 3. Setup Kategori & Navigasi
        setupCategoryButtons()
    }

    private fun setupBanner() {
        val images = listOf(
            R.drawable.img_onboarding1,
            R.drawable.img_onboarding2,
            R.drawable.img_onboarding3
        )
        bannerViewPager.adapter = OnboardingAdapter(images)
    }

    private fun setupCategoryButtons() {
        // Fungsi helper untuk kategori
        fun initCategory(id: Int, name: String, iconRes: Int, categoryFilter: String) {
            val layout = findViewById<LinearLayout>(id)
            layout?.let {
                it.findViewById<TextView>(R.id.tvCategoryName).text = name
                it.findViewById<ImageView>(R.id.imgCategory).setImageResource(iconRes)

                it.setOnClickListener {
                    if (categoryFilter == "Semua") {
                        // Jika klik 'Semua', pindah ke halaman SemuaDestinasi
                        val intent = Intent(this, SemuaDestinasi::class.java)
                        startActivity(intent)
                    } else {
                        // Jika klik kategori lain, filter di halaman ini saja
                        filterData(categoryFilter)
                    }
                }
            }
        }

        initCategory(R.id.catAll, "Semua", R.drawable.ic_all, "Semua")
        initCategory(R.id.catBeach, "Pantai", R.drawable.ic_beach, "Pantai")
        initCategory(R.id.catMountain, "Gunung", R.drawable.ic_mountain, "Gunung")
        initCategory(R.id.catHistory, "Sejarah", R.drawable.ic_history, "Sejarah")
        initCategory(R.id.catFood, "Kuliner", R.drawable.ic_culinary, "Kuliner")
    }

    private fun filterData(kategori: String) {
        val filteredList = if (kategori == "Semua") {
            listWisata
        } else {
            val temp = listWisata.filter { it.kategori == kategori }
            ArrayList(temp)
        }
        showRecyclerView(filteredList)
    }

    private fun prepareData() {
        listWisata.clear()
        listWisata.add(Destinasi(1, "Gunung Bromo", "Jawa Timur", "Sunrise terbaik.", "Rp 1.200.000", "Gunung", R.drawable.img_onboarding1))
        listWisata.add(Destinasi(2, "Pantai Kuta", "Bali", "Pantai ikonik.", "Rp 500.000", "Pantai", R.drawable.img_onboarding2))
        listWisata.add(Destinasi(3, "Candi Borobudur", "Magelang", "Candi terbesar.", "Rp 750.000", "Sejarah", R.drawable.img_onboarding3))
        listWisata.add(Destinasi(4, "Pempek Palembang", "Palembang", "Kuliner khas.", "Rp 50.000", "Kuliner", R.drawable.img_onboarding1))
    }

    private fun showRecyclerView(data: ArrayList<Destinasi>) {
        // Menggunakan Vertical List (Gambar kiri, Teks kanan)
        rvDestinasi.layoutManager = LinearLayoutManager(this)
        adapter = DestinasiAdapter(data)
        rvDestinasi.adapter = adapter
    }
}
