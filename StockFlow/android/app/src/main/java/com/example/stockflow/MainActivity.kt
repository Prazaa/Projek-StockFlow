package com.example.stockflow

import android.content.Intent
import android.graphics.Color
import android.graphics.Typeface
import android.os.Bundle
import android.view.ViewGroup
import android.widget.LinearLayout
import android.widget.ScrollView
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import android.view.Gravity

class MainActivity : AppCompatActivity() {

    private val screens = listOf(
        "Beranda (pemilik)" to "fragment_beranda_owner",
        "Beranda (staf)" to "fragment_beranda_staff",
        "Barang" to "fragment_barang",
        "Barang (batch)" to "fragment_barang_batch",
        "Barang (staf)" to "fragment_barang_staff",
        "Detail barang" to "fragment_detail_barang",
        "Detail batch" to "fragment_detail_batch",
        "Riwayat" to "fragment_riwayat",
        "Laporan" to "fragment_laporan",
        "Notifikasi" to "fragment_notifikasi",
        "Pengguna" to "fragment_pengguna",
        "Pengaturan" to "fragment_pengaturan",
        "Profil" to "fragment_profil",
        "Perlu ditangani" to "fragment_perlu_ditangani",
        "Skema data" to "fragment_data_skema",
        "Akses ditolak" to "fragment_akses_ditolak",
        "Tidak ditemukan" to "fragment_tidak_ditemukan",
        "Form: akun" to "form_akun",
        "Form: barang" to "form_barang",
        "Form: batch" to "form_batch",
        "Form: penyesuaian" to "form_penyesuaian",
        "Form: stok keluar" to "form_stok_keluar",
        "Login" to "activity_login",
    )

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        val screenId = intent.getStringExtra("screen")
        if (screenId == null) {
            showPicker()
        } else {
            val resId = resources.getIdentifier(screenId, "layout", packageName)
            if (resId != 0) setContentView(resId) else showPicker()
        }
    }

    private fun showPicker() {
        val dp = resources.displayMetrics.density
        val pad = (16 * dp).toInt()
        val box = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
            setBackgroundColor(Color.rgb(246, 249, 246))
        }
        val title = TextView(this).apply {
            text = "Pilih halaman (mode QA)"
            textSize = 18f
            gravity = Gravity.CENTER
            setTypeface(typeface, Typeface.BOLD)
            setPadding(pad, pad, pad, pad / 2)
        }
        val list = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
            screens.forEach { (label, id) ->
                addView(TextView(this@MainActivity).apply {
                    text = label
                    textSize = 16f
                    setPadding(pad, pad, pad, pad)
                    setOnClickListener {
                        startActivity(
                            Intent(this@MainActivity, MainActivity::class.java)
                                .putExtra("screen", id)
                        )
                    }
                })
            }
        }
        val scroll = ScrollView(this).apply { addView(list) }
        box.addView(title)
        box.addView(
            scroll,
            LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT
            )
        )
        setContentView(box)
    }
}
