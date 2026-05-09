package com.example.travelgo

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView

class DestinasiAdapter(private val listDestinasi: List<Destinasi>) :
    RecyclerView.Adapter<DestinasiAdapter.ViewHolder>() {

    class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val imgWisata: ImageView = view.findViewById(R.id.imgWisata)
        val tvNama: TextView = view.findViewById(R.id.tvNamaWisata)
        val tvLokasi: TextView = view.findViewById(R.id.tvLokasi)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        // Kita pakai layout item_destinasi yang akan kita buat setelah ini
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_destinasi, parent, false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val data = listDestinasi[position]
        holder.tvNama.text = data.nama
        holder.tvLokasi.text = data.lokasi
        holder.imgWisata.setImageResource(data.gambar)

    holder.itemView.setOnClickListener {
        val intent = android.content.Intent(holder.itemView.context, DetailActivity::class.java)

        // "Bungkus" data untuk dikirim ke halaman detail
        intent.putExtra("NAMA_DESTINASI", data.nama)
        intent.putExtra("LOKASI_DESTINASI", data.lokasi)
        intent.putExtra("HARGA_DESTINASI", data.harga)
        intent.putExtra("GAMBAR_DESTINASI", data.gambar)

        // Mulai pindah halaman
        holder.itemView.context.startActivity(intent)
    }
}


    override fun getItemCount(): Int = listDestinasi.size
}