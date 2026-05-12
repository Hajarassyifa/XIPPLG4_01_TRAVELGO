package com.example.travelgo

import android.content.Intent
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView

class DestinasiAdapter(private val listDestinasi: List<Destinasi>) :
    RecyclerView.Adapter<DestinasiAdapter.ViewHolder>() {

    // 1. ViewHolder: Menghubungkan variabel koding dengan ID di XML item_destinasi
    class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val imgWisata: ImageView = view.findViewById(R.id.imgWisata)
        val tvNama: TextView = view.findViewById(R.id.tvNamaWisata)
        val tvLokasi: TextView = view.findViewById(R.id.tvLokasi)
        val tvHarga: TextView = view.findViewById(R.id.tvHarga) // ID sesuai XML horizontal baru
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        // Menghubungkan adapter dengan layout item_destinasi.xml
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_destinasi, parent, false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val data = listDestinasi[position]

        // 2. Memasang data dari list ke dalam View (Tampilan)
        holder.tvNama.text = data.nama
        holder.tvLokasi.text = data.lokasi
        holder.imgWisata.setImageResource(data.gambar)
        holder.tvHarga.text = data.harga // Menampilkan harga agar muncul di list utama

        // 3. Logika Klik: Ketika satu kartu destinasi dipencet
        holder.itemView.setOnClickListener {
            val context = holder.itemView.context
            val intent = Intent(context, DetailActivity::class.java)

            // Mengirim data (Bungkus data) untuk ditampilkan di DetailActivity
            intent.putExtra("NAMA_DESTINASI", data.nama)
            intent.putExtra("LOKASI_DESTINASI", data.lokasi)
            intent.putExtra("HARGA_DESTINASI", data.harga)
            intent.putExtra("GAMBAR_DESTINASI", data.gambar)

            // Berpindah ke halaman detail
            context.startActivity(intent)
        }
    }

    override fun getItemCount(): Int = listDestinasi.size
}
