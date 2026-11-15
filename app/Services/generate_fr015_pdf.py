#!/usr/bin/env python3
"""
FR-015 PDF Generator — Generator dokumen resmi Formulir Permintaan Perubahan TI
Menerima satu argumen: path ke file JSON data RFC
Output: path ke file PDF yang dihasilkan (dicetak ke stdout)
"""

import sys
import os
import json
import shutil
import docx
from docx.shared import Inches, Pt
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

# docx2pdf menggunakan Microsoft Word via COM (Windows)
import docx2pdf

BASE_DIR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

TEMPLATE_PATH = os.path.join(BASE_DIR, "resources", "templates", "FR-015.docx")
OUTPUT_DIR    = os.path.join(BASE_DIR, "storage", "app", "public", "pdf_rfc")


def normalize_sig_path(raw_path: str | None) -> str | None:
    """Normalisasi path tanda tangan dari database ke path filesystem absolut."""
    if not raw_path or str(raw_path).lower() in ("none", "null", ""):
        return None
    # Sudah absolut
    p = str(raw_path).replace("/", os.sep).replace("\\", os.sep)
    if os.path.isabs(p):
        # Perbaiki duplikasi public/public
        p = p.replace(os.sep + "public" + os.sep + "public" + os.sep,
                      os.sep + "public" + os.sep)
        return p if os.path.exists(p) else None
    # Relatif: kemungkinan "tanda_tangan/xxx.png" atau "public/tanda_tangan/xxx.png"
    for prefix in ["", "public" + os.sep, "tanda_tangan" + os.sep]:
        candidate = os.path.join(BASE_DIR, "storage", "app", "public", prefix, p)
        candidate = os.path.normpath(candidate)
        if os.path.exists(candidate):
            return candidate
    return None


def checkbox(val: list | str | None, key: str) -> str:
    """Kembalikan ☑ jika key ada di val, selain itu ☐."""
    if isinstance(val, list):
        return "☑" if key in val else "☐"
    if isinstance(val, str):
        return "☑" if val == key else "☐"
    return "☐"


def build_replacements(data: dict) -> dict:
    jenis = data.get("jenis_perubahan") or []
    if isinstance(jenis, str):
        try:
            import json as _json
            jenis = _json.loads(jenis)
        except Exception:
            jenis = [jenis]

    kriteria = data.get("kriteria_risiko") or ""

    return {
        "{no_rfc}":               str(data.get("no_rfc") or ""),
        "{pemohon_nama}":         str(data.get("pemohon_nama") or ""),
        "{pemohon_nip}":          str(data.get("pemohon_nip") or "-"),
        "{unit_kerja}":           str(data.get("unit_kerja") or "-"),
        "{perangkat_daerah}":     str(data.get("perangkat_daerah") or "-"),
        "{no_surat}":             str(data.get("no_surat") or "-"),
        "{kontak}":               str(data.get("kontak") or "-"),
        "{email}":                str(data.get("email") or "-"),
        "{cb_permintaan_baru}":   checkbox(jenis, "permintaan_baru"),
        "{cb_peningkatan}":       checkbox(jenis, "peningkatan_sistem"),
        "{cb_perbaikan}":         checkbox(jenis, "perbaikan_sistem"),
        "{sistem_aplikasi}":      str(data.get("sistem_aplikasi") or "-"),
        "{perubahan_diharapkan}": str(data.get("perubahan_diharapkan") or "-"),
        "{latar_belakang}":       str(data.get("latar_belakang") or "-"),
        "{rincian}":              str(data.get("rincian") or "-"),
        "{cb_malapetaka}":        checkbox(kriteria, "malapetaka"),
        "{cb_sangat_berat}":      checkbox(kriteria, "sangat_berat"),
        "{cb_berat}":             checkbox(kriteria, "berat"),
        "{cb_agak_berat}":        checkbox(kriteria, "agak_berat"),
        "{cb_tidak_berat}":       checkbox(kriteria, "tidak_berat"),
        "{keterangan_risiko}":    str(data.get("keterangan_risiko") or "-"),
        "{rencana_pemulihan}":    str(data.get("rencana_pemulihan") or "-"),
        "{solusi}":               str(data.get("solusi") or "-"),
        "{risiko_perubahan}":     str(data.get("risiko_perubahan") or "-"),
        "{alternatif}":           str(data.get("alternatif") or "-"),
        "{biaya}":                str(data.get("biaya") or "-"),
        "{waktu}":                str(data.get("waktu") or "-"),
        "{lampiran}":             str(data.get("lampiran") or "-"),
        "{tgl_dibuat}":           str(data.get("tgl_dibuat") or "-"),
        "{tgl_diterima}":         str(data.get("tgl_diterima") or "-"),
        "{koordinator_nama}":     str(data.get("koordinator_nama") or "-"),
        "{koordinator_nip}":      str(data.get("koordinator_nip") or "-"),
        "{tgl_disetujui}":        str(data.get("tgl_disetujui") or "-"),
        "{penanggung_jabatan}":   str(data.get("penanggung_jabatan") or "-"),
        "{penanggung_nama}":      str(data.get("penanggung_nama") or "-"),
        "{penanggung_nip}":       str(data.get("penanggung_nip") or "-"),
        "{tgl_dilaksanakan}":     str(data.get("tgl_dilaksanakan") or "-"),
        "{status_pelaksanaan}":   str(data.get("status_pelaksanaan") or ""),
        "{bast}":                 str(data.get("bast") or ""),
        "{agen_nama}":            str(data.get("agen_nama") or "-"),
        "{agen_nip}":             str(data.get("agen_nip") or "-"),
    }


def replace_in_paragraph(p, replacements: dict) -> None:
    """Ganti semua placeholder teks di dalam satu paragraf."""
    full_text = p.text
    changed = False
    for key, val in replacements.items():
        if key in full_text:
            full_text = full_text.replace(key, val)
            changed = True
    if changed:
        # Hapus semua run dan tulis ulang dengan teks pengganti
        for run in p.runs:
            run.text = ""
        if p.runs:
            p.runs[0].text = full_text
        else:
            p.add_run(full_text)


SIG_KEYS = ["{%ttd_pemohon}", "{%ttd_koordinator}", "{%ttd_penanggung}", "{%ttd_agen}"]


def generate_pdf(data: dict, pdf_output_path: str) -> str:
    """Generate PDF FR-015 dari data RFC. Kembalikan path PDF yang dihasilkan."""

    if not os.path.exists(TEMPLATE_PATH):
        raise FileNotFoundError(f"Template tidak ditemukan: {TEMPLATE_PATH}")

    # Siapkan direktori output
    out_dir = os.path.dirname(os.path.abspath(pdf_output_path))
    os.makedirs(out_dir, exist_ok=True)

    # Jalur DOCX sementara (ditulis ke direktori yg sama dengan PDF output)
    tmp_docx = pdf_output_path.replace(".pdf", "_tmp.docx")

    replacements = build_replacements(data)

    sig_paths = {
        "{%ttd_pemohon}":      normalize_sig_path(data.get("ttd_pemohon_path")),
        "{%ttd_koordinator}":  normalize_sig_path(data.get("ttd_koordinator_path")),
        "{%ttd_penanggung}":   normalize_sig_path(data.get("ttd_penanggung_path")),
        "{%ttd_agen}":         normalize_sig_path(data.get("ttd_agen_path")),
    }

    doc = docx.Document(TEMPLATE_PATH)

    # Ganti teks di paragraf dokumen utama (di luar tabel)
    for p in doc.paragraphs:
        replace_in_paragraph(p, replacements)

    # Proses tabel utama
    table = doc.tables[0]
    for row in table.rows:
        seen_cells = set()
        for cell in row.cells:
            if cell in seen_cells:
                continue
            seen_cells.add(cell)
            for p in cell.paragraphs:
                # Cek apakah paragraf ini adalah placeholder gambar TTD
                matched_sig_key = None
                for sig_key in SIG_KEYS:
                    if sig_key in p.text:
                        matched_sig_key = sig_key
                        break

                if matched_sig_key:
                    img_path = sig_paths.get(matched_sig_key)
                    # Bersihkan paragraf
                    for run in p.runs:
                        run.text = ""
                    p.paragraph_format.space_before = Pt(2)
                    p.paragraph_format.space_after = Pt(2)
                    if img_path and os.path.exists(img_path):
                        # Sisipkan gambar tanda tangan
                        try:
                            run = p.add_run()
                            run.add_picture(img_path, width=Inches(1.2))
                        except Exception:
                            # Fallback jika gambar tidak valid
                            p.add_run(" ")
                    else:
                        # Ruang kosong untuk TTD manual / belum ada
                        p.add_run(" ")
                else:
                    replace_in_paragraph(p, replacements)

    # Pastikan tidak ada pageBreakBefore paksaan sehingga dokumen mengalir natural sesuai template
    p_first_in_row18 = table.rows[18].cells[0].paragraphs[0]
    pPr = p_first_in_row18._p.get_or_add_pPr()
    for existing in pPr.findall(qn("w:pageBreakBefore")):
        pPr.remove(existing)

    doc.save(tmp_docx)

    # Konversi DOCX ke PDF via Microsoft Word COM
    docx2pdf.convert(tmp_docx, pdf_output_path)

    # Hapus file DOCX sementara
    try:
        os.remove(tmp_docx)
    except OSError:
        pass

    return pdf_output_path


def main():
    if len(sys.argv) < 3:
        print("Usage: generate_fr015_pdf.py <data_json_path> <output_pdf_path>", file=sys.stderr)
        sys.exit(1)

    json_path   = sys.argv[1]
    output_path = sys.argv[2]

    if not os.path.exists(json_path):
        print(f"ERROR: JSON data tidak ditemukan: {json_path}", file=sys.stderr)
        sys.exit(1)

    with open(json_path, encoding="utf-8") as f:
        data = json.load(f)

    try:
        result = generate_pdf(data, output_path)
        # Output path PDF ke stdout agar Laravel bisa membacanya
        print(result)
        sys.exit(0)
    except Exception as e:
        print(f"ERROR: {e}", file=sys.stderr)
        sys.exit(1)


if __name__ == "__main__":
    main()
