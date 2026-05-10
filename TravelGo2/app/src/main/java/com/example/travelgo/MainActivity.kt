package com.example.travelgo

import android.os.Bundle
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.GridLayoutManager
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

        // 2. Setup UI (Isi teks & ikon kategori)
        setupCategoryButtons()

        // 3. Setup Banner Slider
        setupBanner()

        // 4. Ambil data Dummy & Tampilkan
        prepareData()
        showRecyclerView(listWisata)
    }

    private fun setupBanner() {
        val images = listOf(
            R.drawable.img_onboarding1, // Pastikan ini gambar Bali kamu
            R.drawable.img_onboarding2,
            R.drawable.img_onboarding3
        )
        bannerViewPager.adapter = OnboardingAdapter(images)
    }

    private fun setupCategoryButtons() {
        // --- 1. KATEGORI SEMUA ---
        val catAll = findViewById<LinearLayout>(R.id.catAll)
        catAll.findViewById<TextView>(R.id.tvCategoryName).text = "Semua"
        catAll.findViewById<ImageView>(R.id.imgCategory).setImageResource(R.drawable.ic_all) // Ganti ic_all jika ada
        catAll.setOnClickListener { filterData("Semua") }

        // --- 2. KATEGORI PANTAI ---
        val catBeach = findViewById<LinearLayout>(R.id.catBeach)
        catBeach.findViewById<TextView>(R.id.tvCategoryName).text = "Pantai"
        catBeach.findViewById<ImageView>(R.id.imgCategory).setImageResource(R.drawable.ic_beach)
        catBeach.setOnClickListener { filterData("Pantai") }

        // --- 3. KATEGORI GUNUNG ---
        val catMountain = findViewById<LinearLayout>(R.id.catMountain)
        catMountain.findViewById<TextView>(R.id.tvCategoryName).text = "Gunung"
        catMountain.findViewById<ImageView>(R.id.imgCategory).setImageResource(R.drawable.ic_mountain)
        catMountain.setOnClickListener { filterData("Gunung") }

        // --- 4. KATEGORI SEJARAH ---
        val catHistory = findViewById<LinearLayout>(R.id.catHistory)
        catHistory.findViewById<TextView>(R.id.tvCategoryName).text = "Sejarah"
        catHistory.findViewById<ImageView>(R.id.imgCategory).setImageResource(R.drawable.ic_history)
        catHistory.setOnClickListener { filterData("Sejarah") }

        // --- 5. KATEGORI KULINER ---
        val catFood = findViewById<LinearLayout>(R.id.catFood)
        catFood.findViewById<TextView>(R.id.tvCategoryName).text = "Kuliner"
        catFood.findViewById<ImageView>(R.id.imgCategory).setImageResource(R.drawable.ic_culinary)
        catFood.setOnClickListener { filterData("Kuliner") }
    }

    private fun filterData(kategori: String) {
        val filteredList = if (kategori == "Semua") {
            listWisata
        } else {
            // Perbaikan logika filter agar tidak crash
            val temp = listWisata.filter { it.kategori == kategori }
            ArrayList(temp)
        }
        showRecyclerView(filteredList)
    }

    private fun prepareData() {
        listWisata.clear()
        listWisata.add(Destinasi(1, "Gunung Bromo", "Jawa Timur", "Deskripsi...", "Rp 1.2jt", "Gunung", R.drawable.img_onboarding1))
        listWisata.add(Destinasi(2, "Pantai Kuta", "Bali", "Deskripsi...", "Rp 500rb", "Pantai", R.drawable.img_onboarding2))
        listWisata.add(Destinasi(3, "Candi Borobudur", "Magelang", "Deskripsi...", "Rp 750rb", "Sejarah", R.drawable.img_onboarding3))
        listWisata.add(Destinasi(4, "Nasi Goreng", "Bandung", "Deskripsi...", "Rp 50rb", "Kuliner", R.drawable.img_onboarding1))
    }

    private fun showRecyclerView(data: ArrayList<Destinasi>) {
        rvDestinasi.layoutManager = GridLayoutManager(this, 2)
        adapter = DestinasiAdapter(data)
        rvDestinasi.adapter = adapter
    }
}